FROM node:16

COPY ./sources.list /etc/apt/sources.list
RUN apt update
COPY src /app
COPY ./flag /flag

# install dependencies
RUN ["mkdir", "/install"]
ADD ["./src/package.json", "/install"]
WORKDIR /install
RUN npm --registry https://registry.npm.taobao.org install -y yarn
# RUN npm config set strict-ssl false && npm install -y yarn 
# RUN yarn config set "strict-ssl" false --global
RUN yarn --registry https://registry.npm.taobao.org && yarn global add pm2 --registry https://registry.npm.taobao.org
# RUN npm install -g nodemon
ENV NODE_PATH=/install/node_modules

WORKDIR /app

USER 1000

CMD npm run $NPM_RUN_SCRIPT
