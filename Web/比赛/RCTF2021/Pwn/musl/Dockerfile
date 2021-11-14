FROM ubuntu:16.04

RUN sed -i "s/http:\/\/archive.ubuntu.com/http:\/\/mirrors.tuna.tsinghua.edu.cn/g" /etc/apt/sources.list && \
    apt-get update && apt-get -y dist-upgrade && \
    apt-get install -y lib32z1 xinetd && \
	apt-get install -y make && \
	apt-get install -y gcc && \
	apt-get install xinetd libseccomp-dev python -y

RUN useradd -m ctf

WORKDIR /home/ctf

COPY ./ctf.xinetd /etc/xinetd.d/ctf
COPY ./start.sh /start.sh
COPY ./bin/ /home/ctf/
COPY ./start.sh /home/ctf
COPY ./run.sh /home/ctf
COPY ./pow.py /home/ctf

RUN echo "Blocked by ctf_xinetd" > /etc/banner_fail && \
	chmod +x /start.sh && \
	chmod 774 /tmp && chmod -R 774 /var/tmp && chmod -R 774 /dev && chmod -R 774 /run && \
	chmod 1733 /tmp /var/tmp /dev/shm && chown -R root:ctf /home/ctf && chmod -R 750 /home/ctf

RUN mkdir /home/ctf/flag && mv /home/ctf/0_l78zflag /home/ctf/flag && chmod 740 /home/ctf/flag/0_l78zflag
RUN cd /home/ctf/musl-1.2.2 && ./configure && make && make install && rm -r /home/ctf/musl-1.2.2

CMD ["/start.sh"]

EXPOSE 9999