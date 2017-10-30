FROM phpdockerio/php7-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.0-mysql php7.0-intl php7.0-phpdbg php7.0-xdebug php7.0-bcmath php7.0-mbstring \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR "/var/www/dictionary"

CMD composer install
CMD mkdir var/jwt
CMD openssl genrsa -out var/jwt/private.pem -aes256 -passout pass:$JWT_PASSPHRASE 4096
CMD openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
CMD php bin/console cache:clear
CMD /usr/bin/php-fpm
