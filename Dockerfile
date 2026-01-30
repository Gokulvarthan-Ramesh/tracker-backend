FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ✅ Copy only composer files first (cache-friendly)
COPY composer.json composer.lock ./

# ✅ Install dependencies ONCE
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ✅ Copy the rest of the project
COPY . .

# Fix permissions (optional but recommended)
RUN chown -R www-data:www-data /app

# Expose port (optional, Render ignores this but fine)
EXPOSE 8000

# ✅ Runtime only — no DB mutations here
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t public"]
