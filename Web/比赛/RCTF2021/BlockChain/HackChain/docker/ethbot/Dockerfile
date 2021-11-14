FROM ubuntu:18.04

RUN sed -i "s/http:\/\/archive.ubuntu.com/http:\/\/mirrors.aliyun.com/g" /etc/apt/sources.list && \
    apt-get update && apt-get -y dist-upgrade && \
    apt-get install -y python3 python3-pip xinetd vim

RUN pip3 install pycryptodome web3 py-solc-x

RUN python3 -m solcx.install v0.4.23

RUN mkdir /root/ethbot

COPY ./ethbot /root/ethbot
COPY ./start.sh /start.sh
COPY ./ctf.xinetd /etc/xinetd.d/ctf
# COPY ./solc-v0.4.23 /root/.solcx/solc-v0.4.23

RUN chmod +x /start.sh

CMD ["/start.sh"]

EXPOSE 10001
