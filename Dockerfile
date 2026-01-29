FROM php:8.2-cli

#install system deps
RUN apt-get update && apt-get install-y\git unzip libzip-dev zip

# install composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY ..

RUN composer -install --no-dev --optimize-autoloader

EXPOSE 10000

CMD php -S 0.0.0.0:10000 -t public