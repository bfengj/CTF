## 搭建docker

`docker build -t "catch_the_frog" .`


## 运行docker

`docker run -d -p "0.0.0.0:pub_port:9999" -h "catch_the_frog" --name="catch_the_frog" catch_the_frog`


其中`pub_port`需要被替换成对选手开放的端口
