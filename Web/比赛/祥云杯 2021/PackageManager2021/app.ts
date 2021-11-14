import * as express from 'express';
import { Request, Response, NextFunction } from 'express';
import * as logger from 'morgan';
import { HttpError } from 'http-errors';
import * as createError from 'http-errors';
import indexRouter from './routes/index';
import packRouter from './routes/package';
import * as path from 'path';
import * as session from 'express-session';
import * as csrf from 'csurf';
import { checkLogin } from "./utils";
import * as dotenv from "dotenv";


const app = express();
dotenv.config();

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'pug');

app.use(logger('common'));
app.use('/static', express.static(path.join(__dirname, 'static')));

app.use(session({
    name: "session",
    secret: process.env.SESSION_SECRET,
    saveUninitialized: true,
    resave: false
}));

app.use(express.urlencoded({ extended: false }));
app.use(csrf({}));


app.use((req: Request, res: Response, next: NextFunction) => {
    res.locals.session = req.session;
    res.locals.csrfToken = req.csrfToken();
    res.set('Content-Security-Policy', "default-src 'none';style-src 'self' 'sha256-GQNllb5OTXNDw4L6IIESVZXrXdsfSA9O8LeoDwmVQmc=';img-src 'self';form-action 'self';base-uri 'none';");
    res.set('X-Content-Type-Options','nosniff');
    next();
});

app.use('/', indexRouter);
app.use('/packages', checkLogin, packRouter);

app.use((_: Request, _res: Response, next: NextFunction) => {
    next(createError(404));
});

app.use((err: HttpError, req: Request, res: Response, next: NextFunction) => {
    if (/\/static\/images\/\w+\.png/g.test(req.url)) {
	return res.redirect('/static/images/package.png');
    }
    if (res.headersSent) {
        return next(err);
    }
    res.locals.message = err.message;
    res.locals.error = err;
    res.locals.status = (err.status || 500)
    res.status(err.status || 500);
    res.render('error');
});


app.listen(8888, () => { console.log('Listening at port 8888 ') })
