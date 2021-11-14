FROM node:alpine

COPY src /app
COPY flag /flag

ENV PASSWORD fake_password_for_test2333

RUN cd /app && npm update -g && npm install && adduser meo -D

USER meo

CMD cd /app && node app.js