#!/bin/sh 
#/bin/bash
echo "flag{test}" > /flag 
export GZCTF_FLAG=not_flag 
GZCTF_FLAG=not_flag 
chmod 400 /flag 
rm -rf /var/www/html/index.html
apache2ctl start;tail -f /dev/null