import random
a=[False for i in range(1000000)]
payload = "01234"
count = 1
while True:
    n = random.choice("0123456789")
    now = payload[-4:] + n
    if not a[int(now)]:
        payload+=n
        count+=1
    if count %100 == 0 :
        print(f"进度:{count}/99999,payload 长度：{len(payload)}")
        with open("payload.txt","w") as f:
            f.write(payload)
    if count == 99999:
        print("完成")
        break
