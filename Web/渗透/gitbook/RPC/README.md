# RPC

135端口。

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

