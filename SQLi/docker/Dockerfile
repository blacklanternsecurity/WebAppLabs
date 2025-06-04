FROM php:7.4-apache

# Install MySQL extension and required tools
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable .htaccess and mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Add healthcheck
HEALTHCHECK --interval=5s --timeout=3s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Expose port 80
EXPOSE 80 