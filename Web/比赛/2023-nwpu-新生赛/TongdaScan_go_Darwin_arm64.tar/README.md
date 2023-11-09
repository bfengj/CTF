# TongdaScan_go

随手写的项目，持续更新中

![image](https://raw.githubusercontent.com/Fu5r0dah/Project_imgs/main/imgs/Snipaste_2023-02-14_16-51-02.png)

 ## 最新更新日期
  2023-05-27

 ## 支持检测
 ```
    通达OA v2014 get_contactlist.php 敏感信息泄漏
    通达OA v2017 video_file.php 任意文件下载
    通达OA v2017 action_upload.php 任意文件上传
    通达OA v2017 login_code.php 任意用户登录
    通达OA v11 login_code.php 任意用户登录
    通达OA v11.5 swfupload_new.php SQL注入
    通达OA v11.6 report_bi.func.php SQL注入
    通达OA v11.8 api.ali.php 任意文件上传漏洞
    通达OA v11.8 gateway.php 远程文件包含漏洞
    通达OA v11.6 print.php未授权删除auth.inc.php导致RCE
    通达OA v11.10 getdata 任意文件上传
 ```
 
 ## 漏洞检测
 使用scan参数时，不指定任何vulnID，则自动检测所有漏洞

 ```
 TongdaScan_go scan -u http://1.1.1.1
 ```
![image](https://raw.githubusercontent.com/Fu5r0dah/Project_imgs/main/imgs/Snipaste_2023-02-15_20-03-29.png)

 指定漏洞ID

 ```
 TongdaScan_go scan -u http://1.1.1.1 -i Td03
 ```
 ![image](https://raw.githubusercontent.com/Fu5r0dah/Project_imgs/main/imgs/Snipaste_2023-02-15_20-07-34.png)

 ## 漏洞利用
 ```
 TongdaScan_go exp -u http://1.1.1.1 -i Td03
 ```
![image](https://raw.githubusercontent.com/Fu5r0dah/Project_imgs/main/imgs/Snipaste_2023-02-21_21-20-39.png)

 ## 代理
 ```
 TongdaScan_go scan -u http://1.1.1.1 -i Td01 -s http://127.0.0.1:8080
 ```

 ## TODO
 收集所有通达OA的漏洞（进行中）
 指定文件上传

 ## 感谢下列项目及其作者

 - [seeyon-exploit](https://github.com/xfiftyone/seeyon-exploit) 致远OA漏洞检测
 - [WeaverScan](https://github.com/TD0U/WeaverScan) 泛微oa漏洞利用工具


 ## 免责声明
 如果您下载、安装、使用、修改本程序及相关代码，即表明您信任本程序。在使用本程序时造成对您自己或他人任何形式的损失和伤害，本人不承担任何责任。
 如您在使用本程序的过程中存在任何非法行为，您需自行承担相应后果，本人将不承担任何法律及连带责任。
 请您务必审慎阅读、充分理解各条款内容，特别是免除或者限制责任的条款，并选择接受或不接受。
 除非您已阅读并接受本协议所有条款，否则您无权下载、安装或使用本程序。您的下载、安装、使用等行为即视为您已阅读并同意上述协议的约束。
 Use at Your own risk.