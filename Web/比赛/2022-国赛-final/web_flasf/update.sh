#!/bin/sh

cp /app.py /app/app.py
ps -ef | grep python | grep -v grep | awk '{print $2}' | xargs kill -9 
cd /app && nohup python app.py  >> /opt/app.log 2>&1 &