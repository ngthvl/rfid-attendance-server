FROM php:8.2-fpm as builder

ARG user
ARG uid

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN apt-get update \
  && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  zlib1g-dev \
  libpq-dev \
  libzip-dev \
  supervisor

#RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath gd

COPY . .
COPY ./.env.development ./.env

#COPY ./../.secrets/oauth-private.key ./storage/oauth-private.key
#COPY ./../.secrets/oauth-public.key ./storage/oauth-public.key
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer update
RUN composer install
#RUN php artisan migrate
RUN php artisan clear
RUN php artisan storage:link --quiet --no-interaction --force

COPY supervisord-dev.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000
CMD ["php-fpm"]
#CMD ["/usr/bin/supervisord"]
