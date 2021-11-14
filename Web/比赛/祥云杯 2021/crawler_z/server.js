const express = require('express');
const logger = require('morgan');
const session = require('express-session');
const path = require('path');
const crypto = require('crypto');
const createError = require('http-errors');
const utils = require('./utils');
const indexRouter = require('./routes/index');
const userRouter = require('./routes/user');
const { sequelize } = require('./database');


const app = express();

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

app.use(logger('combined'));
app.use(express.urlencoded({ extended: false }));
app.use('/static', express.static(path.join(__dirname, 'static')));

app.use(session({
    secret: crypto.randomBytes(64).toString(),
    resave: false,
    saveUninitialized: false,
}))

app.use((req, res, next) => {
    res.locals.session = req.session;
    next();
});

app.use('/', indexRouter);
app.use('/user', utils.checkSignIn, userRouter);

app.use((_req, _res, next) => {
    next(createError(404));
})

app.use((err, _req, res, next) => {
    if (res.headersSent) {
        return next(err);
    }
    res.locals.message = err.message;
    res.locals.error =  err;
    res.locals.status = err.status || 500;
    res.status(err.status || 500);
    
    return res.render('error');
})


app.listen(8888, async () => {
    console.log('Listening at port 8888');
    for (;;) {
        try {
            await sequelize.authenticate();
            break;
        } catch(err) {
            console.log(err.message);
            await utils.sleep(1000);
        }
    }
    sequelize.sync();
});
