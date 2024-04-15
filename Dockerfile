# Use the official PHP 8.2 FPM image
FROM php:8.2-fpm
USER root
# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . .

# Install Composer dependencies
RUN composer install

# Set permissions for Laravel storage and bootstrap directories
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html/storage

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
