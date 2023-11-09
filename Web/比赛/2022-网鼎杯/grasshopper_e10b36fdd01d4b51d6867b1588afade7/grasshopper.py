from Crypto.Util.number import *
from random import randrange
from grassfield import flag

p = getPrime(16)

k = [randrange(1,p) for i in range(5)]

for i in range(len(flag)):
    grasshopper = flag[i]
    for j in range(5):
        k[j] = grasshopper = grasshopper * k[j] % p
    print('Grasshopper#'+str(i).zfill(2)+':'+hex(grasshopper)[2:].zfill(4))
