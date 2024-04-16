"""
Prototype netwerk scanner Mycys platform

Author: Daniëlle van der Tuin
Version: 0.5

Dit script is een eenvoudige netwerkscan die gebruik maakt van: 
- nmap voor het ondekken van hosts, mac, hostname, Os, services en de versies hiervan 
- scapy voor het ontdekken van open poorten door middel van multithreading.
"""

import nmap
import sys
import socket
import random
import concurrent.futures
from scapy.all import IP, TCP, sr, ICMP
from datetime import datetime
import os

def write_results_to_file(results):
    """Schrijf de resultaten naar een tekstbestand met datum en tijd als bestandsnaam.

    Parameters
    ----------
    results : str
        De resultaten die naar het bestand moeten worden geschreven."""
    # Bepaal het pad naar de map voor de scanresultaten
    result_directory = os.path.join(os.path.dirname(os.path.abspath(__file__)), "Scan_resultaten")

    # Controleer of de map bestaat, zo niet, maak deze dan aan
    if not os.path.exists(result_directory):
        os.makedirs(result_directory)

    now = datetime.now()
    timestamp = now.strftime("%d-%m-%Y__%H-%M")
    
    # Bepaal het volledige pad naar het bestand met de scanresultaten
    filename = os.path.join(result_directory, f"scan_results_{timestamp}.txt")
    
    with open(filename, "w") as file:
        file.write(results)
    



def get_hostname(ip_address):
    """Retrieve the hostname associated with the provided IP address.

    Parameters
    ----------
    ip_address : str
        The IP address for which the hostname is to be retrieved.

    Returns
    -------
    str
        The hostname associated with the provided IP address, or "Unknown" if not found."""
    try:
        return socket.gethostbyaddr(ip_address)[0]
    except socket.herror:
        return "Unknown"
    
def get_open_ports(host, timeout=1):
    """Function to scan a host for open ports and return a list of open ports.

    Parameters
    ----------
    host : str
        The IP address or hostname of the host to be scanned.
    timeout : float, optional
        The number of seconds to wait for a response before timing out (default is 1).

    Returns
    -------
    list
        A list of integers representing the numbers of all open ports on the specified host. """
    open_ports = []

    with open("constants/ports.txt", "r") as file:
        ports = file.read()

    port_list = []

    for p in ports.split(","):
        if "-" in p:
            start, end = p.split("-")
            port_list.extend(range(int(start), int(end)+1))
        else:
            port_list.append(int(p))
            
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

def scan_port(host, dst_port, timeout=1):
    """Function to scan a single port on a host.

    Parameters
    ----------
    host : str
        The IP address or hostname of the host to be scanned.
    dst_port : int
        The number of the port to be scanned.
    timeout : float, optional
        The number of seconds to wait for a response before timing out (default is 1).

    Returns
    -------
    int or None
        The number of the port if it is open, or None if it is closed or could not be determined."""
    src_port = random.randint(1025, 65534)
    ans, _ = sr(IP(dst=host) / TCP(sport=src_port, dport=dst_port, flags="S"), timeout=timeout, verbose=0)

    for _, resp in ans:
        if resp.haslayer(TCP):
            if resp.getlayer(TCP).flags == 0x12:
                sr(IP(dst=host) / TCP(sport=src_port, dport=dst_port, flags='R'), timeout=timeout, verbose=0)
                return dst_port
        elif resp.haslayer(ICMP):
            if int(resp.getlayer(ICMP).type) == 3 and int(
                resp.getlayer(ICMP).code
            ) in {1, 2, 3, 9, 10, 13}:
                return None
    return None

def scan_network(target):
    """Function to perform a network scan using Nmap and retrieve information about open ports, services, OS, and hostname for each host.

    Parameters
    ----------
    target : str
        The target IP range or host to be scanned.

    Returns
    -------
    None"""
    try:
        # Set a timeout value for the scan (in seconds)
        timeout_seconds = 120
        
        # Initialize Nmap PortScanner object
        nm = nmap.PortScanner()

        # Perform the scan with timeout
        nm.scan(hosts=target, arguments='-sV -O', timeout=timeout_seconds )

        #string om de resultaten in op te slaan
        results = ""

        for host in nm.all_hosts():
            if 'addresses' in nm[host]:
                ip_address = host
                mac_address = nm[host]['addresses'].get('mac', 'N/A')
                
                # Get hostname using socket
                hostname = get_hostname(ip_address)
                
                # Extract all open ports
                open_ports = get_open_ports(host)
                
                # Extract service names and port numbers for open ports, including versions
                services_with_ports_and_versions = [f"{nm[host]['tcp'][port]['name']}({port}) - Version: {nm[host]['tcp'][port]['version']}" if nm[host]['tcp'][port]['version'] else f"{nm[host]['tcp'][port]['name']}({port}) - No version detected" for port in open_ports]

                # Extract OS information
                detected_os = nm[host]['osmatch'][0]['name'] if 'osmatch' in nm[host] and nm[host]['osmatch'] else "Unknown"

                # Print information line by line
                print("Hostname:", hostname)
                print("IP:", ip_address)
                print("MAC:", mac_address)
                print("OS:", detected_os)
                print("Open Ports/Services:", ', '.join(services_with_ports_and_versions) if services_with_ports_and_versions else "All closed")
                print()
                
                # Voeg de resultaten toe aan de resultatenstring
                results += "Hostname: " + hostname + "\n"
                results += "IP: " + ip_address + "\n"
                results += "MAC: " + mac_address + "\n"
                results += "OS: " + detected_os + "\n"
                results += "Open Ports/Services: " + ', '.join(services_with_ports_and_versions) if services_with_ports_and_versions else "All closed"
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