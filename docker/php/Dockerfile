FROM php:7.2-fpm

# install composer
#RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
COPY --from=composer:1.10.15 /usr/bin/composer /usr/bin/composer
RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim \
    curl \
    gnupg \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

RUN echo 'alias ll="ls -l"' >> /root/.bashrc
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -

RUN apt-get install -y nodejs \
    && npm install npm@latest -g

WORKDIR /var/www/html
