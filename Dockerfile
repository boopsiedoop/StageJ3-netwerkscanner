# Gebruik een Python-basisimage
FROM Python

# Copy the current directory contents into the container at /app
COPY . /app

# Stel het werkingsdirectory in
WORKDIR /app

# Installeer alle dependencies

CMD ["/bin/bash"]

