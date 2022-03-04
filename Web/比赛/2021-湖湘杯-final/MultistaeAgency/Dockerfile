FROM golang:latest

RUN mkdir -p /code/logs

COPY . /code

WORKDIR /code

RUN go build -o bin/web web/main.go && \
    	go build -o bin/proxy proxy/main.go && \
    	go build -o bin/server server/main.go

RUN chmod -R 777 /code

RUN useradd web

ADD flag /flag

RUN chmod 400 /flag

ENTRYPOINT  "/code/start.sh"