# Gebruik een Python-basisimage
FROM python:3.9-slim

# Installeer nmap en scapy
RUN apt-get update && apt-get install -y nmap && pip install scapy

# Kopieer het Python-script naar de container
COPY network_scan.py /app/network_scan.py

# Stel het werkingsdirectory in
WORKDIR /app

# Voer het Python-script uit wanneer de container wordt gestart
CMD ["python", "networkscan.py"]