var createError = require('http-errors');
var express = require('express');
var path = require('path');
const cookiePaser = require('cookie-parser')
var logger = require('morgan');
const crypto = require('crypto')
const  hbs = require('hbs');

var indexRouter = require('./routes/index');

var app = express();

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'hbs');

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookiePaser(crypto.randomBytes(32).toString()))
app.use(express.static(path.join(__dirname, 'public')));

app.use('/', indexRouter);

app.use(function(req, res, next) {
  next(createError(404));
});

// error handler
app.use(function(err, req, res, next) {
  res.locals.message = err.message;
  res.locals.error = req.app.get('env') === 'development' ? err : {};
  res.status(err.status || 500);
  res.render('error');
});

process.on('uncaughtException', function (err) {
  console.log(err);
});

module.exports = app;
