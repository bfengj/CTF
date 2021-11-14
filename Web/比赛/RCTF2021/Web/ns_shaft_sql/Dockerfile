FROM php:8.0.10-apache


RUN docker-php-ext-install mysqli; \
    docker-php-ext-enable mysqli

WORKDIR /var/www/html

COPY ./www/ /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]