FROM php:8.2-apache

# Copy semua file ke server
COPY . /var/www/html/

# Aktifkan mod rewrite (optional)
RUN a2enmod rewrite

# Permission folder uploads
RUN chmod -R 777 /var/www/html/uploads