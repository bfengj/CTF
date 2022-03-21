FROM php:7.4.28-fpm-buster

LABEL Maintainer="yxxx"
ENV REFRESHED_AT 2022-03-14
ENV LANG C.UTF-8

RUN sed -i 's/http:\/\/security.debian.org/http:\/\/mirrors.163.com/g' /etc/apt/sources.list
RUN sed -i 's/http:\/\/deb.debian.org/http:\/\/mirrors.163.com/g' /etc/apt/sources.list
RUN apt upgrade -y && \
    apt update -y && \
    apt install nginx -y

ENV DEBIAN_FRONTEND noninteractive



COPY index.php /var/www/html
COPY default.conf /etc/nginx/sites-available/default
COPY flag /flag

EXPOSE 80

CMD php-fpm -D && nginx -g 'daemon off;'