FROM php:8.1-alpine AS build
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY ./reactphp/composer.json /app/
COPY ./reactphp/composer.lock /app/
RUN composer install --no-dev --no-autoloader --no-scripts
COPY ./reactphp /app
RUN composer install --no-dev --optimize-autoloader

FROM php:8.1-alpine
COPY --from=build /app /app
WORKDIR /app
EXPOSE 8080
CMD ["php", "index.php"]
