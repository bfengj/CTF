# SMTP

25端口

枚举用户名：

```shell
use auxiliary/scanner/smtp/smtp_enum
setg RHOSTS  10.10.10.179
set USER_FILE /Users/feng/many-ctf/my-fuzz-wordlist/username/xato-net-10-million-usernames.txt
exploit
```

