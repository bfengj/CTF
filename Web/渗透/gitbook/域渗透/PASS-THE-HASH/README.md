# Pass The Hash

PTH（哈希传递攻击）

PTE （PaSS Tbe Hash， 哈希传遊政击）是内网横向移动的一种方式。由于 NTLM 认证过程和kerberos认证过程默认都是使用用户密码的 NITLMA Hasb 来进行加密，因此当获取到了用户密码的入NTLM Hash 而没有解出明文时，可以利用该NTLM Hash进行 PTH，对内网其他机器进行 Hash的碰撞，碰撞到使用相同密码的的机器，然后通过135或 413 端口横向移动到使用该密码的其他机器上。

