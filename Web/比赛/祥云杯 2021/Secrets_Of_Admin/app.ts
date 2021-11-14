import * as express from 'express';
import {Request, Response, NextFunction} from 'express';
import {HttpError} from 'http-errors';
import * as cookieParser from 'cookie-parser';
import * as bodyParser from 'body-parser';
import * as path from 'path';
import * as logger from 'morgan';
import router from './routes/index';
import * as createError from 'http-errors';


const app = express();


app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'pug');

app.use(logger('dev'));
app.use('/static',express.static(path.join(__dirname,'static')));
app.use(cookieParser('💣🏁😓🐋🍓'));
app.use(bodyParser.urlencoded({extended: true}));

app.use('/',router);

app.use((_: Request, _res: Response, next: NextFunction) => {
    next(createError(404));
});

app.use((err: HttpError, _: Request, res: Response, next: NextFunction) => {
    if (res.headersSent) {
      return next(err);
    }
    res.locals.message = err.message;
    res.locals.error = err;
    res.locals.status = (err.status || 500)
    res.status(err.status || 500);
    res.render('error');
});

app.listen(8888, () => console.log('Listening at port 8888'));
