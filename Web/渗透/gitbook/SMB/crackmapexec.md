# crackmapexec

不支持mac arm，放到了docker里。

```shell
crackmapexec smb 10.10.10.192 -u "" -p "" --shares

#多用户名和密码时测试是否可连
./cme-linux smb 10.10.10.179 -u user.txt -p pass.txt --continue-on-success

```

