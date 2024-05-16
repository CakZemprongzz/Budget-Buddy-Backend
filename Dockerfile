# Use the official PHP image as a base
FROM php:8.1-apache

# Set the working directory
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . /var/www/html

# Expose port 80
EXPOSE 8080

# Start the Apache server
CMD ["apache2-foreground"]
