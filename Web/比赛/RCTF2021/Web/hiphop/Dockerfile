FROM hhvm/hhvm
RUN apt-get update && apt-get install -y nginx sudo
COPY start.sh /
COPY flag /flag
COPY readflag /readflag
COPY index.php /var/www/html
RUN chmod -R 0755 /var/www/html && \
    mkdir /var/www/html/sandbox && \
    chmod 1777 /var/www/html/sandbox && \
    chmod 0700 /start.sh && \
    chown root:root /flag && chmod 0600 /flag && \
    chmod u+s /readflag && chmod +x /readflag
CMD ["/start.sh"]
