# Base image: PHP 8.2 with Apache already set up
FROM php:8.2-apache

# Install the mysqli extension so the site can talk to MySQL
RUN docker-php-ext-install mysqli

# Enable Apache's mod_rewrite (harmless even if unused, common requirement)
RUN a2enmod rewrite

# Copy the website source into Apache's web root
COPY app/ /var/www/html/

# Make sure Apache can read the files
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
