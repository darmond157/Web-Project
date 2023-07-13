FROM php:8.1-fpm-alpine

RUN set -ex \
	&& apk --no-cache add postgresql-libs postgresql-dev \
	&& docker-php-ext-install pgsql pdo_pgsql \
	&& apk del postgresql-dev

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer* ./

COPY artisan .

RUN composer install --no-scripts --no-autoloader --ansi --no-interaction

COPY . .

RUN composer dump-autoload -o \
    && chown -R :www-data ./app \
    && chmod -R 775 ./storage ./bootstrap/cache

EXPOSE 80

#COPY .env.example .env

ENTRYPOINT ["sh","-c","php artisan serve --host=0.0.0.0 --port=80"]
#ENTRYPOINT ["sh","-c","php artisan serve --host=0.0.0.0"]
