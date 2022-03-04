FROM phpswoole/swoole:4.7-php7.4-alpine

RUN mkdir /etc/[Forbidden]
COPY update_file/flag /etc/[Forbidden]/flag
RUN chmod -R 444 /etc/[Forbidden]/flag

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories &&\
    apk update && apk add --no-cache unzip &&\
    docker-php-ext-install mysqli pdo_mysql > /dev/null

RUN composer config -g repo.packagist composer "https://mirrors.aliyun.com/composer/" &&\
    composer create-project imiphp/project-http /app && cd /app &&\
    composer require imiphp/imi-swoole:~2.0.0 &&\
    composer remove imiphp/imi-workerman &&\
    composer remove imiphp/imi-fpm &&\
    composer remove --dev swoole/ide-helper

WORKDIR /app

COPY update_file/config config
COPY update_file/config.php ApiServer/config/config.php
COPY update_file/IndexController.php ApiServer/Controller/IndexController.php

RUN chmod -R 755 /app &&\
    chmod -R 777 /app/.runtime &&\
    rm -rf .runtime/*

EXPOSE 8080
USER www-data
CMD ["vendor/bin/imi-swoole", "swoole/start"]