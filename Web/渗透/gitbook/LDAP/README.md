# LDAP

389端口



ldapsearch工具的参数：

```
Option	说明
-H	ldapuri，格式为ldap://机器名或者IP:端口号，不能与-h和-p同时使用
-h	LDAP服务器IP或者可解析的hostname，与-p可结合使用，不能与-H同时使用
-p	LDAP服务器端口号，与-h可结合使用，不能与-H同时使用
-x	使用简单认证方式
-D	所绑定的服务器的DN
-w	绑定DN的密码，与-W二者选一
-W	不输入密码，会交互式的提示用户输入密码，与-w二者选一
-f	指定输入条件，在RFC 4515中有更详细的说明
-c	出错后忽略当前错误继续执行，缺省情况下遇到错误即终止
-n	模拟操作但并不实际执行，用于验证，常与-v一同使用进行问题定位
-v	显示详细信息
-d	显示debug信息，可设定级别
-s	指定搜索范围, 可选值：base|one|sub|children
```



用户枚举：

```shell
ldapsearch -x -b "DC=htb,DC=local"  -s base -h 10.10.10.161

#-s base会少很多信息
ldapsearch -x -b "DC=htb,DC=local"  -h 10.10.10.161

#最好保存起来，因为信息太多终端显示不完
ldapsearch -x -b "dc=cascade,dc=local"   -h 10.10.10.182 > /Users/feng/github/CTF/Web/渗透/hackthebox/Cascade/ldapsearchresult.txt

#查询用户相关
ldapsearch -x -h 10.10.10.182  -b "dc=cascade,dc=local" '(objectClass=person)'
```



```shell
nmap --script "ldap*" -p 389 10.10.10.175
```

