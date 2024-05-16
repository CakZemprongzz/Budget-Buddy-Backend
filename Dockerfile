# Use the official PHP image as a base
FROM php:8.1-apache

# Set the working directory
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . /var/www/html

# Expose port 8080
EXPOSE 8080

# Change the listening port in Apache configuration
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Start the Apache server
CMD ["apache2-foreground"]

