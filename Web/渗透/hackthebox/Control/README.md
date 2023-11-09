# README

## 信息收集

### nmap

```shell
sudo nmap -p-  --min-rate 10000 10.10.10.167
Password:
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-27 14:29 CST
Nmap scan report for 10.10.10.167
Host is up (0.35s latency).
Not shown: 65530 filtered tcp ports (no-response)
PORT      STATE SERVICE
80/tcp    open  http
135/tcp   open  msrpc
3306/tcp  open  mysql
49666/tcp open  unknown
49667/tcp open  unknown

Nmap done: 1 IP address (1 host up) scanned in 14.39 seconds

```





```shell
sudo nmap -p 80,135,3306 -sC -sV 10.10.10.167
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-27 14:30 CST
Nmap scan report for 10.10.10.167
Host is up (0.32s latency).

PORT     STATE SERVICE VERSION
80/tcp   open  http    Microsoft IIS httpd 10.0
| http-methods:
|_  Potentially risky methods: TRACE
|_http-server-header: Microsoft-IIS/10.0
|_http-title: Fidelity
135/tcp  open  msrpc   Microsoft Windows RPC
3306/tcp open  mysql?
Service Info: OS: Windows; CPE: cpe:/o:microsoft:windows

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 91.00 seconds
```

