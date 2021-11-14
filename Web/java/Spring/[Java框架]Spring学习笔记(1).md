

# 前言

单纯的学习Spring的乱写的笔记，想写什么写什么，很乱，自己能看懂就行。



# 依赖

```xml
    <dependencies>
        <dependency>
            <groupId>org.springframework</groupId>
            <artifactId>spring-webmvc</artifactId>
            <version>5.3.9</version>
        </dependency>
    </dependencies>
```

光导入一个webmvc就可以额外导入所依赖的其他包，很方便。



# 简单的程序

`beans.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="http://www.springframework.org/schema/beans"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="http://www.springframework.org/schema/beans
        https://www.springframework.org/schema/beans/spring-beans.xsd">

    <bean id="hello1" class="com.feng.pojo.Hello">
        <property name="name" value="feng"></property>
    </bean>
    <bean id="userDaoImpl" class="com.feng.dao.UserDaoImpl"></bean>
    <bean id="userServiceImpl" class="com.feng.service.UserServiceImpl">
        <property name="userDao" ref="userDaoImpl"></property>
    </bean>

</beans>
```

id是对象名，class是类名，`name`是属性名，`value`是值，`ref`是`Spring`创建的对象。







 

```java

package com.feng.dao;

public interface UserDao {
    public void getUserInfo();
}

```

```java
package com.feng.dao;

public class UserDaoImpl implements UserDao{
    @Override
    public void getUserInfo() {
        System.out.println("获取用户信息");
    }
}

```



```java
package com.feng.service;

public interface UserService {
    public void getUserInfo();
}

```

```java
package com.feng.service;

import com.feng.dao.UserDao;

public class UserServiceImpl implements UserService{
    private UserDao userDao;

    public UserDao getUserDao() {
        return userDao;
    }

    public void setUserDao(UserDao userDao) {
        this.userDao = userDao;
    }

    @Override
    public void getUserInfo() {
        userDao.getUserInfo();
    }
}

```



```java
public class MyTest {
    public static void main(String[] args) {
        ApplicationContext context = new ClassPathXmlApplicationContext("beans.xml");
        UserServiceImpl userServiceImpl = (UserServiceImpl) context.getBean("userServiceImpl");
        userServiceImpl.getUserInfo();
    }
}
```

通过`ClassPathXmlApplicationContext`从CLASSPATH下加载配置元数据，拿到上下文对象，然后获取`Bean`，即可拿到`Spring`创建的对象。

所谓的`IOC`可以认为是，对象由`Spring`来创建，管理，装配。





# IOC创建对象的方式

```java
package com.feng.pojo;

public class User {
    private String name;

    public User(String name){
        this.name = name;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
    public void printName(){
        System.out.println(this.name);
    }
}

```



beans.xml里面注册bean得到的是调用无参构造。

想要调用有参构造有几种方式：

1. 构造函数参数索引。使用`index`属性来显式指定构造函数参数的索引

   ```java
       <bean id="user" class="com.feng.pojo.User">
           <constructor-arg index="0" value="feng"></constructor-arg>
       </bean>
   ```

   

2. 构造函数参数类型匹配。使用`type`属性显式指定了构造函数参数的类型，则容器可以使用简单类型的类型匹配。

   ```java
       <!--不建议使用 -->
       <bean id="user2" class="com.feng.pojo.User">
           <constructor-arg type="java.lang.String" value="feng"></constructor-arg>
       </bean>
   ```

   

3. 通过参数名

   ```java
       <bean id="user3" class="com.feng.pojo.User">
           <constructor-arg name="name" value="feng"></constructor-arg>
       </bean>
   ```



在配置文件加载的时候，容器中管理的对象就已经初始化了。

```java
    public User(String name){
        System.out.println("User被创建");
        this.name = name;
    }
```

```java
    public static void main(String[] args) {
        ApplicationContext context = new ClassPathXmlApplicationContext("beans.xml");
    }
```

```
D:\environment\jdk8u302-b08\bin\java.exe 
User被创建
User被创建
User被创建

Process finished with exit code 0
```



# 依赖注入DI

两种，一种就是前面提到的利用构造器注入，第二种就是`Setter`注入。重点是`Setter`注入。

- 依赖：bean对象的注入依赖于容器。
- 注入：bean对象中的所有属性，由容器来注入。



