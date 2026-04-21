FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Copy semua file
COPY . .

# Pastikan folder uploads ada & bisa ditulis
RUN mkdir -p uploads \
    && chmod -R 777 uploads

# Expose port Railway
EXPOSE 8080

# Jalankan PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]