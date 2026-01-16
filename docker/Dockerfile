FROM php:8.4-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite for pretty URLs
RUN a2enmod rewrite

# Install Composer CLI
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run apache server
CMD [ "apache2-foreground" ]
