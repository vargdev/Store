FROM php:8.3-fpm-alpine

WORKDIR /app

RUN apk update && apk upgrade \
    && apk add --no-cache nginx bash \
    && apk add --no-cache libzip-dev curl postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql && docker-php-ext-enable pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename composer

EXPOSE 80

CMD ["php-fpm"]
