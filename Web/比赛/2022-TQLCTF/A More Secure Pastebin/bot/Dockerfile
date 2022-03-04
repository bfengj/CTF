FROM ubuntu:20.04

# dir
COPY src /app
WORKDIR /app

# install pkgs
ENV DEBIAN_FRONTEND=noninteractive
# RUN sed -i "s/http:\/\/archive.ubuntu.com/http:\/\/mirrors.tuna.tsinghua.edu.cn/g" /etc/apt/sources.list\
COPY sources.list /etc/apt/sources.list
RUN apt-get update \
	&& apt-get install -y man ca-certificates curl wget ca-certificates fonts-liberation libappindicator3-1 libasound2 libatk-bridge2.0-0 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libglib2.0-0 libgtk-3-0 libnspr4 libnss3 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 lsb-release xdg-utils net-tools iproute2 inetutils-ping tcpdump
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && apt-get install -y nodejs

# release
RUN npm --registry https://registry.npm.taobao.org install -g yarn 
RUN yarn --registry https://registry.npm.taobao.org && yarn global add pm2 --registry https://registry.npm.taobao.org

# set
USER root
COPY ./init.sh /init.sh
CMD ["bash", "/init.sh"]

# docker build -t bot . && docker run -it --rm -p 4000:4000 --name bot bot
# tc qdisc add dev eth0 root netem delay 100ms