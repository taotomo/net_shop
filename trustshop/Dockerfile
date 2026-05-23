FROM php:8.0-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y git unzip libzip-dev curl && \
    docker-php-ext-configure zip && \
    docker-php-ext-install pdo_mysql zip

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the application files to the container
COPY . /var/www/html

# Install Composer
COPY --from=composer:2.5.1 /usr/bin/composer /usr/bin/composer


# Install Laravel dependencies
RUN composer install

# Set the document root to public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Change the owner and permissions of the storage and bootstrap/cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache