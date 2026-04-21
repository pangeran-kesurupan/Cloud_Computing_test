FROM php:8.2-apache

# Reset semua MPM
RUN a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2dismod mpm_prefork || true \
 && a2enmod mpm_prefork

# Copy project
COPY . /var/www/html/

# Set permission uploads
RUN chmod -R 777 /var/www/html/uploads

# Force Apache run
CMD ["apache2-foreground"]