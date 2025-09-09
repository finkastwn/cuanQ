FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y libicu-dev zip unzip && \
    docker-php-ext-install intl mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files to the container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Set recommended permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80
