## 搭建docker

`docker build -t "unistruct" .`


## 运行docker

`docker run -d -p "0.0.0.0:pub_port:9999" -h "unistruct" --name="unistruct" unistruct`


其中`pub_port`需要被替换成对选手开放的端口
