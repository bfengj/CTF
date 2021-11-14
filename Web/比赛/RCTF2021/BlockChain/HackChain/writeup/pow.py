from pwn import *
import os,re,hashlib,random,string
from Crypto.Util.number import bytes_to_long,long_to_bytes

#context.log_level=True
context.terminal = ["deepin-terminal","-x","sh","-c"]

remote_addr=['127.0.0.1', 10001]
p=remote(remote_addr[0],remote_addr[1])

ru = lambda x : p.recvuntil(x)
rud = lambda x : p.recvuntil(x ,drop=True)
sn = lambda x : p.send(x)
rl = lambda   : p.recvline()
sl = lambda x : p.sendline(x)
rv = lambda x : p.recv(x)
sa = lambda a,b : p.sendafter(a,b)
sla = lambda a,b : p.sendlineafter(a,b)
pi = lambda : p.interactive()

def dbg(b =""):
    gdb.attach(io , b)
    raw_input()

def lg(s, addr):
    log.info('\033[1;31;40m %s --> 0x%x \033[0m' % (s,addr))

def raddr(a=6):
    if(a==6):
        return u64(rv(a).ljust(8,'\x00'))
    else:
        return u64(rl().strip('\n').ljust(8,'\x00'))

def passpow():

    rud('[+] Welcome!\n')
    s = rl().decode('utf-8')
    print(s)
    prefix = re.split('\(', s)[1][:8]
    print(prefix)
    difficulty = 20
    print(difficulty)
    while 1:
        answer=''.join(random.choice(string.ascii_letters + string.digits) for i in range(8))
        bits=bin(int(hashlib.sha256((prefix+answer).encode()).hexdigest(),16))[2:]
        if bits.endswith('0'*difficulty):
            print(answer)
            sl(answer)
            return

if __name__ == '__main__':
    passpow()
    #sl('1')
    p.recv()
    pi()
