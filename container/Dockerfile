# Основной образ с PHP и Composer
FROM php:8.2-fpm

ARG MPDECIMAL_VERSION=2.5.1

# Установка зависимостей
RUN apt-get update -yqq && apt-get upgrade -yqq

RUN set -xe \
  && apt-get update && apt-get install -y \
  build-essential \
  libtool\
  autoconf \
  g++ \
  make \
  gcc \
  pkg-config \
  libpq-dev \
  libonig-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  git \
  zip \
  curl \
  sudo \
  unzip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

RUN set -eux; \
  cd /tmp/; \
  curl -sSL -O https://www.bytereef.org/software/mpdecimal/releases/mpdecimal-${MPDECIMAL_VERSION}.tar.gz; \
  tar -xzf mpdecimal-${MPDECIMAL_VERSION}.tar.gz; \
  cd mpdecimal-${MPDECIMAL_VERSION}; \
  ./configure; \
  make; \
  make install \
  ;

RUN docker-php-ext-install pdo pdo_pgsql

# Очистка кеша
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* libmpdec

# Установка расширений
RUN docker-php-ext-install mbstring exif pcntl bcmath gd ctype
RUN pecl install decimal && docker-php-ext-enable decimal

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочего каталога в контейнере
WORKDIR /var/www

# Копирование текущего каталога в контейнер
COPY . .

# Копирование composer.lock и composer.json
#COPY composer.lock composer.json /var/www/

# Установка всех зависимостей
RUN #composer install

# Копирование всего проекта в рабочий каталог
COPY ../ /var/www

# Предоставление разрешений на папку
RUN chown -R www-data:www-data /var/www

# Запуск сервера

WORKDIR /var/www/
CMD ["php-fpm"]
CMD ["php", "/var/www/index.php"]

# Экспозиция порта
EXPOSE 9001
