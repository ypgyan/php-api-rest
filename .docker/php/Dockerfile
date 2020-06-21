FROM php:7.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    zip \
    libzip-dev \
    unzip \
    git \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip bcmath
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www

COPY --chown=www:www . /var/www

COPY ./local.ini /usr/local/etc/php/conf.d/local.ini

USER www

EXPOSE 9000
CMD ["php-fpm"]