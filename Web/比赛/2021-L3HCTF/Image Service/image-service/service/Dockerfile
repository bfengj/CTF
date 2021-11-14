FROM alpine
WORKDIR /opt/app
COPY . .
RUN chmod +x ./main
RUN mkdir upload
COPY ./start.sh /start.sh

ENTRYPOINT [ "sh", "/start.sh" ]