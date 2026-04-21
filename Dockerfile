FROM php:8.2-apache

# Hapus kemungkinan MPM konflik (prefork biasanya default aman untuk PHP)
RUN a2dismod mpm_event || true
RUN a2enmod mpm_prefork

# Copy project
COPY . /var/www/html/

# Permission uploads
RUN chmod -R 777 /var/www/html/uploads