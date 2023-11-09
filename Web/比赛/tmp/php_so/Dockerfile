FROM nevergone/php-apache:7.4.3-4ubuntu2.19--2023-07-18
COPY start.sh /
COPY waf.so 	/usr/lib/php/20190902/waf.so
ENV key 2ffe4e77325d9a7152f7086ea7aa5114
COPY index.php /var/www/html/index.php
COPY apache2.tar /etc/apache2/
RUN cd /etc/apache2&&tar -xvf apache2.tar&&rm /etc/apache2/apache2.tar&&rm /var/www/html/index.html
COPY php.ini /etc/php/7.4/apache2/php.ini
ENTRYPOINT ["/start.sh"]
CMD ["/start.sh"]