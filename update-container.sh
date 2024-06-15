#!/bin/bash

git pull

podman stop budget-buddy-server

podman rm budget-buddy-server

podman rmi budget-buddy-php

podman build -t budget-buddy-php .

podman run -d -p 8080:8080 --name budget-buddy-server budget-buddy-php

echo "Deployment complete. The application is running on port 8080."