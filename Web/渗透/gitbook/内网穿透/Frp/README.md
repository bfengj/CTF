# Frp

服务端

```shell
[common]
# frp监听的端口，默认是7000，可以改成其他的
bind_port = 37000
# 授权码，请改成更复杂的
#token = 52010  # 这个token之后在客户端会用到

# frp管理后台端口，请按自己需求更改
dashboard_port = 37500
# frp管理后台用户名和密码，请改成自己的
dashboard_user = feng
dashboard_pwd = feng
enable_prometheus = true
```

客户端：

```shell
[common]
server_addr = 10.10.14.14
server_port = 37000

[windows]
type = tcp
#local_ip = 10.10.10.103
#local_port = 88
remote_port = 6000
plugin = socks5
```





```shell
./frps -c frps.ini
.\frpc.exe -c frpc.ini
```

代理需要用`proxychains4`，配置文件修改命令：`sudo vi /etc/proxychains.conf`

