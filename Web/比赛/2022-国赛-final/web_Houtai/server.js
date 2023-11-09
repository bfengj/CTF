const fs = require('fs');
const express = require('express');
const bodyParser = require('body-parser');
const session = require('express-session');
const randomize = require('randomatic');
const mysql = require('mysql');
const mysqlConfig = require("./config/mysql");
const ejs = require('ejs');
const child_process=require('child_process');
const axios = require('axios');
const url = require('url');

const pool = mysql.createPool(mysqlConfig)
const app = express();
const port = process.env.PORT || 80;


const loginCheck = (data) => {
	const blacklists = [`"`, `\\`, `|`, `&`, `+`, `-`, `*`, `/`, `^`];
	const blackwords = [`select`, `drop`, `insert`, `update`, `delete`, `like`, `order`, `truncate`, `create`, `reg`, `sub`, `left`, `right`, `mid`, `if`, `log`, `pro`, `func`, `history`, `file`, `plugin`, `role`, `collation`, `event`];

	if (typeof data !== "string") return false;

	let flag = true;

	blacklists.concat(blackwords).forEach((blackword) => {
		if (data.indexOf(blackword) !== -1) return (flag = false);
	});

	return flag;
};

const urlCheck = (data) => {
	if (typeof data !== "string") return false;

		const host_waf = new RegExp("[A-z]+")
		if (host_waf.test(url.parse(data).host)) return false;
	    const blacklists = [`flag`,`|`,`&`,'`',`"`, `'`, `^`, `~`, `.`, `\\`, `|`, `;`, `>`, `<`, `[`, `]`, `,`, `-`, `_`, `!`, `*`, `(`, `)`, `\``, `{`, `}`];

		//const blacklists = [`"`, `'`, `^`, `~`, `.`, `\\`, `|`, `;`, `>`, `<`, `[`, `]`, `,`, `-`, `_`, `!`, `*`, `(`, `)`, `\``, `{`, `}`];

		let flag = true;

		blacklists.forEach((blackword) => {
			if (data.indexOf(blackword) !== -1) {
				console.log(blackword)
				return (flag = false);

			}
		});

		return flag;
}
const auth = (req, res, next) => {
	if (!req.session.login || !req.session.userid) {
		res.redirect(302, "/login");
	}
	else
	{
		next();
	}
};

const query = (sql, values) => {
    return new Promise((resolve, reject) => {
      pool.getConnection((err, connection) => {
        if (err) {
          reject(err)
        } else {
          connection.query(sql, values, (err, rows) => {
            if (err) {
              reject(err)
            } else {
              resolve(rows)
            }
            connection.release()
          })
        }
      })
    })
}

app.use(bodyParser.urlencoded({extended: true})).use(bodyParser.json());
app.use(express.static((__dirname+'/static/')));
app.use(session({
    name: 'session',
    secret: randomize('aA0', 16),
    resave: false,
    saveUninitialized: false
}));

app.set('json escape',true);
app.set('view engine', 'ejs');
app.set('views', './views');

app.get("/login", (req, res, next) => {
	res.render('login');
});

app.post("/login", async (req, res, next) => {
	let username = req.body.username;
	let password = req.body.password;

	if (loginCheck(username) && loginCheck(password)) {
		let sql = `select * from users where username = "{{username}}" and password = "{{password}}"`;
		let dataList;

		console.log((sql.replace("{{username}}", username)).replace("{{password}}", password));

		try {
			dataList = await query(sql.replace("{{username}}", username).replace("{{password}}", password));
		} catch(err) {
			res.send("<script>alert('Username or password error!'); location.replace('/login');</script>");
			return;
		} 
		

		if (dataList.length === 0 || 
			password !== dataList[0].password || 
			username !== dataList[0].username) {
			res.send("<script>alert('Username or password error!'); location.replace('/login');</script>");
			return;
		}
		console.log(dataList);
		req.session.userid = dataList[0].Id;
		req.session.login = true;
		req.session.isadmin = dataList[0].isadmin;
		res.redirect(302, "/");
		return;
	}
	else {
		res.send("<script>alert('Hacked!'); location.replace('/login');</script>");
		return;
	}
});


app.get("/", auth, (req, res, next) => {
	res.render('index', {message: ""});
});

app.post("/", auth, async (req, res, next) => {
	let request_url = req.body.url;

	if (!req.session.isadmin) {
		res.send("<script>alert('You are not admin!'); location.replace('/');</script>");
		return;
	}

	if (!urlCheck(request_url)) {
		res.send("<script>alert('Hacker!'); location.replace('/');</script>");
		return;
	}

	try {
		let response = await axios.get(request_url);
		let log = `echo '${url.parse(request_url).href}' >> /tmp/access.log`;
		console.log(log);
		child_process.exec(log);

		res.render('index', {message: response.data});
	} catch(error) {
		console.log(error);
		let log = `echo '${error}' >> /tmp/error.log`;
		console.log(log);
		child_process.exec(log);
		res.render('index', {message: error});
	}
});

app.get("/logout", (req, res, next) => {
	req.session.userid = null;
	req.session.login = false;
	req.session.auth = false;
	res.redirect(302, '/login');
});

app.listen(port, () => {
	console.log(`Start to listening ${port}...`);
});