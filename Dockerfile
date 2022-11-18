FROM php:8 AS base

RUN apt-get update && apt-get install -y git wget
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash; \
  apt-get install symfony-cli

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer --install-dir=/usr/local/bin
RUN mkdir /.composer
RUN chmod -Rf 777 /.composer

RUN mkdir /app
WORKDIR /app

COPY .docker/php-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD symfony server:start
