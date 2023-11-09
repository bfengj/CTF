FROM ctftraining/base_image_nginx_mysql_php_73

LABEL Author="Landasika"

COPY wordpress /var/www/html/
COPY username /username/
COPY password /password/

RUN sh -c 'mysqld_safe &' \
	&& sleep 5s \
    && mysqladmin -uroot password '123456' \
	&& mysql -e "CREATE DATABASE wordpress;" -uroot -p123456 \
    && chmod -R 777 /var/www/html/ \
    && rm -rf /tmp/* \
    && rm -rf /etc/apk
RUN mkdir /secretpath \
	&& touch /secretpath/secretname \
	&& echo 'flag=FLAG' > /secretpath/secretname

EXPOSE 80

VOLUME ["/var/log/nginx"]


ENTRYPOINT sed -i "s/FLAG/$FLAG/" /secretpath/secretname  && export FLAG=not_flag && FLAG=not_flag && /usr/local/bin/docker-php-entrypoint
