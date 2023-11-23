# README

```mssql

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
```

