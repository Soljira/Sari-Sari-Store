# Use PHP with Apache
FROM php:8.2-apache

# Install extensions needed for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (often needed for PHP apps)
RUN a2enmod rewrite

# Copy project files into container
COPY . /var/www/html/

# Fix permissions so Apache can read them
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
