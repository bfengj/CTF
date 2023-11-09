#!/bin/sh

cp server.js /app/server.js
ps -ef | grep node | grep -v grep | awk '{print $2}' | xargs kill -9 
cd /app && nohup node server.js  >> /opt/aa.log 2>&1 &


