FROM debian:jessie

MAINTAINER "Hoang Viet Dzung" <dung.hvd@gmail.com>

WORKDIR /tmp

RUN apt-get update && \
    apt-get install -y wget

RUN echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list \
    && echo 'deb-src http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list \
    && wget https://www.dotdeb.org/dotdeb.gpg \
    && apt-key add dotdeb.gpg \
    && apt-get update

RUN apt-get install -y \
    php7.0-cli \
    php7.0-mcrypt \
    php7.0-mbstring \
    php7.0-xdebug \
    php7.0-xml \
    --no-install-recommends

RUN apt-get remove -y wget \
    && apt-get autoremove -y \
    && apt-get autoclean

COPY cli/php.ini                /etc/php/7.0/cli/php.ini
COPY mods-available/xdebug.ini  /etc/php/7.0/mods-available/xdebug.ini

VOLUME /var/www

WORKDIR /var/www

EXPOSE 9001

ENTRYPOINT ["/usr/sbin/php-fpm7.0", "-F"]