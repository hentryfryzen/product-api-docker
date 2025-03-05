# Use the official PHP FPM image
FROM php:8.2-fpm

# Install system dependencies and PostgreSQL extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql pgsql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Nginx
RUN apt-get update && apt-get install -y nginx

# Set the working directory
WORKDIR /var/www/html

# Copy Laravel app files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose ports
EXPOSE 80

# Start services
CMD service nginx start && php-fpm
