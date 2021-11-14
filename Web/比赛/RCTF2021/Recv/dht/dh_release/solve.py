from hashlib import blake2b
from itertools import product
#import tqdm

tab = list(map(ord,'0123456789abcdef'))

def once(ch0,ch1,ch2):
    cur = [0]*64
    for i in range(tab.index(ch1)+1):
        for j in range(32,64):
            cur[j] = ch2
        for j in range(10000):
            cur = list(blake2b(bytes(cur)).digest())
    for j in range(tab.index(ch0)+1):
        cur = list(blake2b(bytes(cur)).digest())
    return cur[0]

'''
m = []
for i in range(256):
    m.append([])
for ch0,ch1,ch2 in tqdm.tqdm(product(tab, repeat=3)):
    res = once(ch0,ch1,ch2)
    m[res].append((ch0,ch1,ch2))
with open('mm','w') as f:
    f.write(repr(m))
exit()
'''
import ast
with open('mm') as f:
    m = ast.literal_eval(f.read())

'''
tt = []
tar = 'c64459bb76582a53'
#aa = []
for i in range(len(tar)):
    i1 = (i+1)%len(tar)
    i2 = (i+2)%len(tar)
    cnt = (tab.index(ord(tar[i1]))+1)*100000+tab.index(ord(tar[i]))+1
    tt.append((cnt,once(ord(tar[i]),ord(tar[i1]), ord(tar[i2]))))
    #aa.append((ord(tar[i]),ord(tar[i1]),ord(tar[i2])))
tt.sort(key=lambda x:x[0])
print(tt)
#print(aa)
'''

h = bytes.fromhex('89ce250390150407e1c3e377cc227b6d971588ddc613d0bde59845b0ccacbb0691c86348a5005736aa07e60def9f843570216a004e8ed764488bb0aa7632d67c')

dh = [110, 96, 118, 141, 127, 149, 145, 110, 150, 146, 194, 207, 197, 197, 235, 25]
prob = [None]*16

def search(idx, cnt, lastinc):
    if idx>=16:
        #print(prob)
        ans = []
        ans.extend(prob[0])

        used = [False]*16
        used[0] = True
        for i in range(15):
            for j in range(16):
                if not used[j] and ans[-2]==prob[j][0] and ans[-1]==prob[j][1]:
                    ans.append(prob[j][2])
                    used[j] = True
                    break
            if len(ans)!=i+4:
                break
        
        if len(ans)==18 and ans[0]==ans[16] and ans[1]==ans[17]:
            for i in range(16):
                tmp = bytes(ans[i:16]+ans[:i])
                if blake2b(tmp).digest() == h:
                    print('find',tmp)
                    exit()
        return
    for one in m[dh[idx]]:
        if one[1] >= cnt:
            prob[idx] = one[:]
            if one[1] == cnt:
                if idx-lastinc<4:
                    search(idx+1, one[1], lastinc)
            else:
                search(idx+1, one[1], idx)

search(0,0,0)
