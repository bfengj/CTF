#!/bin/bash

if [[ -f /flag.sh ]]; then
	source /flag.sh
fi
rm -rf /var/www/html/index.nginx-debian.html 
export FLAG=not_here
FLAG=not_here

nginx &
php-fpm
