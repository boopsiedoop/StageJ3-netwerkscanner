# Gebruik een Python-basisimage
FROM python

# Installeer Nmap en Scapy
RUN apt-get update && apt-get install -y --no-install-recommends nmap && apt-get clean
RUN pip install scapy

# Copy the current directory contents into the container at /app
COPY . /app

# Stel het werkingsdirectory in
WORKDIR /app

# Voer het Python-script uit wanneer de container wordt gestart
CMD ["python", "netwerkscan.py"]

