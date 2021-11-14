FROM php:7.4-fpm-alpine
COPY ./readflag.c /tmp/readflag.c
COPY ./flag /flag
RUN  sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories && \
    apk update && apk add build-base && \
    chmod 0600 /flag && \
    gcc /tmp/readflag.c -o /readflag  && chmod u+s /readflag 