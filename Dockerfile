FROM php:8.2-cli

WORKDIR /app
COPY . .

RUN chmod -R 777 uploads

CMD ["php", "-S", "0.0.0.0:8080"]