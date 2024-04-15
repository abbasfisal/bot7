# Use the official PHP 8.2 FPM image
FROM php:8.2-fpm

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

# Set permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer dependencies
RUN composer install

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
