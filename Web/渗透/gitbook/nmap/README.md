# nmap

```shell
sudo nmap -sS -T4 172.31.1.68 -p0-1000 （也可以加-Pn： 禁用PING检测）
nmap 172.16.8.3-255
sudo nmap -sS -T4 -sV --script vulners 10.10.11.227

sudo nmap -p 445 --script=vuln 10.10.10.4

#似乎不全
sudo nmap -T4 -A -v 10.10.10.3
-A：一次扫描包含系统探测、版本探测、脚本扫描和跟踪扫描

#其他的形式
#first
sudo nmap -p- --min-rate 10000 10.10.10.175
#then
sudo nmap -p 53,80,88,135,139,389,445,464,593,636,5985,9389 -sC -sV  10.10.10.175

sudo proxychains4  nmap -p1-10000  -sT -Pn   192.168.20.26
```

