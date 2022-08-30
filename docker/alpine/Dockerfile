FROM alpine:latest

LABEL maintainer="Natan Augusto"

ARG WWWGROUP

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN echo "@community http://nl.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories
RUN echo "@testing http://nl.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories

RUN apk update --no-cache

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apk add --no-cache --update shadow libcap readline curl bash supervisor
RUN apk add --no-cache --update \
    php81 \
    php81-dev \
    php81-common \
    php81-xdebug \
    php81-intl \
    php81-ldap \
    php81-redis \
    php81-pgsql \
    php81-sqlite3 \
    php81-gd \
    php81-xml \
    php81-zip \
    php81-bcmath \
    php81-dom \
    php81-soap \
    php81-curl \
    php81-phar \
    php81-imap \
    php81-mbstring \
    php81-tokenizer@community \
    php81-fileinfo@community \
    php81-xmlwriter@community \
    php81-pdo_mysql \
    php81-pecl-pcov@testing\
    php81-pecl-swoole \
    php81-pecl-memcached \
    php81-pecl-msgpack \
    php81-pecl-igbinary \
    && ln -s /usr/bin/php81 /usr/bin/php \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN apk add --no-cache --update nodejs npm
RUN apk add --no-cache --update mycli pgcli

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php81
RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
