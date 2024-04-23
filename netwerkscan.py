"""
Prototype netwerk scanner Mycys platform

Author: Daniëlle van der Tuin
Version: 0.7 geen error meer als de results string leeg is na een timeout van nmap en sneller maken

Dit script is een eenvoudige netwerkscan die gebruik maakt van: 
- nmap voor het ondekken van hosts, mac, Os, services en de versies hiervan 
- scapy voor het ontdekken van open poorten door middel van multithreading.
"""

import nmap
import socket
import concurrent.futures
from scapy.all import IP, TCP, sr, ICMP
from datetime import datetime
import os
import random
from ipaddress import IPv4Network

def write_results_to_file(results):
    """Write the results to a text file with a timestamp as the filename.

    Parameters:
    results (str): The results to be written to the file.
    """
    # Determine the directory path for the scan results
    result_directory = os.path.join(os.path.dirname(os.path.abspath(__file__)), "Scan_resultaten")
    # Check if the directory exists, if not, create it
    if not os.path.exists(result_directory):
        os.makedirs(result_directory)
    # Generate a timestamp for the filename
    timestamp = datetime.now().strftime("%d-%m-%Y__%H-%M")
    # Create the full path for the file
    filename = os.path.join(result_directory, f"scan_results_{timestamp}.txt")
    
    # Check if there are results to write
    if results:
        # Write the results to the file
        with open(filename, "w") as file:
            file.write(results)
    else:
        # If no results are available, write a message to indicate that
        print("No results to save.")

def get_active_hosts(target):
    """Scan for active hosts using Nmap.

    Parameters:
    target (str): The target IP range or subnet to be scanned.

    Returns:
    list: A list of active IP addresses.
    """
    nm = nmap.PortScanner()
    nm.scan(hosts=target, arguments='-sn')

    active_hosts = []
    for host in nm.all_hosts():
        if nm[host]['status']['state'] == 'up':
            active_hosts.append(host)

    return active_hosts
    
def get_hostname(ip_address):
    """Retrieve the hostname associated with the provided IP address.

    Parameters:
    ip_address (str): The IP address for which the hostname is to be retrieved.

    Returns:
    str: The hostname associated with the provided IP address, or "Unknown" if not found.
    """
    try:
        # The gethostbyaddr function returns a tuple, and we're interested in the first element, which is the hostname
        return socket.gethostbyaddr(ip_address)[0]
    except socket.herror:
        return "Unknown"

def scan_port(host, dst_port, timeout=1):
    """Scan a single port on a host.
    Parameters:

    host (str): The IP address or hostname of the host to be scanned.
    dst_port (int): The number of the port to be scanned.
    timeout (float): The number of seconds to wait for a response before timing out (default is 1).

    Returns:
    int or None: The number of the port if it is open, or None if it is closed or could not be determined.
    """
    # Generate a random source port number in the range of ephemeral ports (1025-65534)
    src_port = random.randint(1025, 65534)

    # Send a TCP SYN packet to the destination port and wait for a response
    ans, _ = sr(IP(dst=host) / TCP(sport=src_port, dport=dst_port, flags="S"), timeout=timeout, verbose=0)

    # Iterate through the response packets
    for _, resp in ans:
        if resp.haslayer(TCP):
            # If the response packet is a TCP packet
            if resp.getlayer(TCP).flags == 0x12:  # TCP SYN-ACK flag
                # Send a TCP RST packet to close the connection
                sr(IP(dst=host) / TCP(sport=src_port, dport=dst_port, flags='R'), timeout=timeout, verbose=0)
                return dst_port
        elif resp.haslayer(ICMP):
            # If the response packet is an ICMP packet (indicating a closed port)
            if int(resp.getlayer(ICMP).type) == 3 and int(resp.getlayer(ICMP).code) in {1, 2, 3, 9, 10, 13}:
                return None
    return None

def version_scan_with_vulns(host, port):
    """Perform a version scan on the specified host and port and retrieve vulnerability information.

    Parameters:
    host (str): The IP address or hostname of the host to be scanned.
    port (int): The number of the port to be scanned.

    Returns:
    str: Information about the service running on the port, including version and vulnerabilities if available.
    """
    # Initialize a new instance of the Nmap PortScanner
    nm = nmap.PortScanner()
    
    # Perform a version scan on the specified host and port
    nm.scan(hosts=host, ports=str(port), arguments='-sV --script vulners')

    # Extract service information from the scan results
    service_info = nm[host]['tcp'][port]
    
    # Extract service name and version
    service_name = service_info['name']
    service_version = service_info['version']
    
    # Extract vulnerability information
    vulnerabilities = service_info.get('script', {}).get('vulners', '')

    # Return information about the service, including version and vulnerabilities if available
    if service_version:
        return f"{service_name}({port}) - Version: {service_version}\nVulnerabilities:{vulnerabilities}"
    else:
        return f"{service_name}({port})"

def get_open_ports(host, timeout=1):
    """Scan a host for open ports and return a list of open ports.

    Parameters:
    host (str): The IP address or hostname of the host to be scanned.
    timeout (float): The number of seconds to wait for a response before timing out (default is 1).

    Returns:
    list: A list of integers representing the numbers of all open ports on the specified host.
    """
    # array voor de open poorten
    open_ports = []

    # open ports to scan file and put in array
    with open("constants/ports.txt", "r") as file:
        ports = file.read()
    port_list = []
    for p in ports.split(","):
        if "-" in p:
            start, end = p.split("-")
            port_list.extend(range(int(start), int(end)+1))
        else:
            port_list.append(int(p))

    # multithreadths port check if open if it is put it in the openports array
    with concurrent.futures.ThreadPoolExecutor() as executor:
        futures = {executor.submit(scan_port, host, dst_port, timeout): dst_port for dst_port in port_list}
        for future in concurrent.futures.as_completed(futures):
            dst_port = futures[future]
            try:
                result = future.result()
                if result is not None:
                    open_ports.append(result)
            except Exception as e:
                print(f"Exception occurred while scanning port {dst_port}: {e}")
    return open_ports     

def scan_network(target):
    """Perform a network scan using Nmap and retrieve information about open ports, services, OS, and hostname for each host.

    Parameters:
    target (str): The target IP range or host to be scanned.
    """
    try:
        # Get active hosts
        active_hosts = get_active_hosts(target)

        # Set a timeout value for the scan (in seconds)
        timeout_seconds = 120
        
        # Initialize string to store results
        results = ""
        
        for host in active_hosts:
            # Initialize Nmap PortScanner object
            nm = nmap.PortScanner()
            ip_address = host

            # Scan for OS detection
            nm.scan(hosts=host, arguments='-O', timeout=timeout_seconds)
            
            # Get hostname using socket
            hostname = get_hostname(host)
            
            # Extract all open ports
            open_ports = get_open_ports(host)
            
            # Extract service names and port numbers for open ports, including versions
            services_with_ports_and_versions = [version_scan_with_vulns(host, port) for port in open_ports]

            # Extract OS information
            detected_os = nm[host]['osmatch'][0]['name'] if 'osmatch' in nm[host] and nm[host]['osmatch'] else "Unknown"
            
            # Extract MAC address
            mac_address = nm[host]['addresses'].get('mac', 'N/A')

            # print alle resultaten in de terminal
            print("Hostname:", hostname)
            print("IP:", ip_address)
            print("MAC:", mac_address)
            print("OS:", detected_os)
            print("Open Vulnerable Ports/Services:", ', '.join(services_with_ports_and_versions) if services_with_ports_and_versions else "All closed")
            print()

            # schrijf alle resultaten naar de results array
            results += f"Hostname: {hostname}\n"
            results += f"IP: {ip_address}\n"
            results += f"MAC: {mac_address}\n"
            results += f"OS: {detected_os}\n"
            results += f"Open Vulnerable Ports/Services: {', '.join(services_with_ports_and_versions)}" if services_with_ports_and_versions else "All closed"
            results += "\n\n"
          
    except nmap.PortScannerError as e:
        if 'Timeout' in str(e):
            print("Scan timeout: The Nmap process took longer than the specified timeout to complete.")
        else:
            print(f"Nmap error occurred: {e}")
    except socket.gaierror as e:
        print(f"Socket error occurred: {e}")
    except Exception as e:
        print(f"An unexpected error occurred: {e}")
        sys.exit(1)

  # Schrijf de resultaten naar het bestand
    write_results_to_file(results)

if __name__ == "__main__":
    """Main function to start the network scan according to the provided subnet."""
    target = "192.168.1.0/24"
    scan_network(target)
