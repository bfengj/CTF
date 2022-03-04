var fs = require('fs')
var crypto = require('crypto')
var express = require("express");
var app = express();
app.use(express.static('public'));

var session = require('express-session');
var sessionStore = require('session-file-store')(session);
var random = require('randomatic')
app.use(session({
    name: "session",
    secret: random('Aa0', 40),
    store: new sessionStore("./sessions"),
    saveUninitialized: false,
    cookie: { maxAge: 10000 * 60 * 60}
}));

var cookieParser = require("cookie-parser");
app.use(cookieParser());

var bodyParser = require('body-parser');
app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());

var hbs = require('hbs');
var path = require('path');
app.set('views', path.join(__dirname, "views/"))
app.set('view engine', 'html');
app.engine('html', hbs.__express);

var users = {
    0 : {
        "name" : "admin",
        "weapon" : "trident",
        "type" : "admin",
        "privilege" : "render",
        "isAdmin" : true,
        "treasure" : {
            "flag" : "only the true explorer can truly see",
            "value" : {
                'place' : "Atlantis",
                'explore' : () => console.log("Trident could only wielded!!!"),
                'realflag' : require('./secret')
            }
        }
    }
}
var i = 1

app.use((req, res, next) => {
    if (req.session.userid && !users[req.session.userid]) {
        req.session.destroy()
        return res.end("session destroyed")
    }
    if (!req.session.userid) {
        users[i] = {
            "name" : "",
            "weapon" : "trident",
            "type" : "guest",
            "privilege" : "view"
        }
        req.session.userid = i++
    }
    if (req.ip === '127.0.0.1')
        users[req.session.userid].isAdmin = true
    if (users[req.session.userid].isAdmin)
        users[req.session.userid] = users[0]
    if (!users[req.session.userid].name && req.url !== '/') {
        return res.end("What's your name?")
    }
    next()
})

app.get('/', (req, res)=>{
    res.render('login')
})

app.post('/',(req, res)=>{
    if (req.body.name && typeof req.body.name == 'string'){
        users[req.session.userid].name = req.body.name
        res.send("<script>location.href='/user/index';</script>")
    }
    res.end('error')
})

app.get('/user/logout', (req, res)=> {
    req.session.destroy()
    res.redirect('/')
})

app.get('/user/index', (req, res)=> {
    res.render('index', users[req.session.userid])
})

app.post('/user/index', (req, res)=> {
    function check(s) {
        let deny = ['type', 'isAdmin', '__proto__']
        for (let i=0; i<s.length;i++)
            if ( deny.includes(s[i]) )
                return false
        return !deny.includes(s);
    }
    if ( check(req.body.key) && users[req.session.userid][req.body.key] ) {
        users[req.session.userid][req.body.key] = req.body.value
        return res.end('ok')
    }
    return res.end('sorry')
})

app.get('/user/render', (req, res) => {
    let tpl = crypto.createHash('md5').update(req.ip).digest('hex')
    let file = path.join(__dirname, 'views', `${tpl}.html`)
    if (!fs.existsSync(file)) {
        fs.writeFileSync(file, `<p>Hello {{name}}, you are {{type}}, and you can {{privilege}} template! </p>\n<p>Use your {{weapon}} to explore Atlantis by entering the Stargate!</p>`)
    }
    res.render(tpl, users[req.session.userid])
})
app.post('/user/render', (req, res)=> {
    if (!users[req.session.userid].isAdmin)
        return res.status(403).end("403 Forbidden")
    if (!req.body.code)
        return res.end("need param code")
    let deny = ['lookup', 'log', 'with', 'string', 'treasure', 'flag', 'if', 'level', '(', ')', '-', '@', '[']
    let code = req.body.code
    for (let i = 0; i<deny.length; i++) {
        code = code.replaceAll(deny[i], 'oh no')
    }
    let file = path.join(__dirname, "views", crypto.createHash('md5').update(req.ip).digest('hex') + '.html')
    fs.writeFileSync(file, code)
    return res.end(file)
})

app.listen(80, '0.0.0.0');