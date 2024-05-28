#!/bin/bash

podman build -t budget-buddy-php .

podman stop budget-buddy-server

podman rm budget-buddy-server

podman run -d -p 8080:8080 --name budget-buddy-server budget-buddy-php

echo "Deployment complete. The application is running on port 8080."
