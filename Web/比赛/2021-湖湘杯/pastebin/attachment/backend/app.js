const express = require('express')
const expressLayouts = require('express-ejs-layouts')
const mongoose = require('mongoose')
const uuid = require('uuid').v4
const flash = require('connect-flash')
const session = require('express-session')
const cookieParser = require('cookie-parser')


const BIND_ADDR = process.env.BIND_ADDR || '127.0.0.1';
const LPORT = process.env.LPORT || 3000
const SESSION_SECRET = process.env.SESSION_SECRET || uuid();

const app = express()

// DB config
const db = require('./helper/db').MongoURI;


mongoose.connect(db)
    .then(() => console.log('MongoDB Connected.'))
    .catch(err => console.log(err)) 


// EJS
app.use(expressLayouts)
app.set('view engine', 'ejs')


// body parser
app.use(express.urlencoded({ extended: false }))

// cookie parser
app.use(cookieParser())

// trust first proxy for secure cookies
app.set('trust proxy', 1)

// express session
app.use(session({
    secret: SESSION_SECRET,
    resave: false,
    saveUninitialized: true,
    name: 'ctf-session',
    cookie: {
        secure: true,
        httpOnly: true,
        sameSite: 'lax',
    },

}))

// connect flash
app.use(flash())
app.use((req, res, next) =>{
    res.locals.message = req.flash();
    next();
})

//TODO: Check the CSP if works normally
// csp 
app.use((req, res, next) => {
    res.header("Content-Security-Policy", "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net/; object-src 'none';")
    next();
})

// routes
app.use('/', require('./routes/index'))
app.use(express.static(__dirname + '/public'))
app.use('/user', require('./routes/user'))
app.use('/admin', require('./routes/admin'))


app.listen(LPORT, BIND_ADDR, console.log(`Started on http://${BIND_ADDR}:${LPORT}`))