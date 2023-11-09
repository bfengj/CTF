# smbclient

尝试Anonymous用户登陆：

```shell
smbclient -N -L //10.10.10.175/
```





```shell
smbclient //192.168.0.100/share -U "xx"
```

进入smb之后类似于ftp

```shell
命令 说明 
?或help [command] 提供关于帮助或某个命令的帮助 
![shell command]   执行所用的SHELL命令，或让用户进入 SHELL提示符 
cd [目录]  切换到服务器端的指定目录，如未指定，则 smbclient 返回当前本地目录 
lcd [目录]  切换到客户端指定的目录； 
dir 或ls   列出当前目录下的文件； 
exit 或quit    退出smbclient 
get file1  file2 从服务器上下载file1，并以文件名file2存在本地机上；如果不想改名，可以把file2省略 
mget file1 file2 file3  filen 从服务器上下载多个文件； 
md或mkdir 目录 在服务器上创建目录 
rd或rmdir 目录 删除服务器上的目录 
put file1 [file2] 向服务器上传一个文件file1,传到服务器上改名为file2； 
mput file1 file2 filen 向服务器上传多个文件 
```

文件太多的时候递归下载：

```shell
smb: \active.htb\> RECURSE ON
smb: \active.htb\> PROMPT OFF
smb: \active.htb\> mget *
```

