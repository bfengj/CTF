import * as express from 'express';
import { Request, Response, NextFunction } from 'express';
import * as createError from "http-errors";
import * as pdf from 'html-pdf';
import DB from '../database';
import * as fs from 'fs';
import * as path from 'path';
import * as crypto from 'crypto';
import { promisify } from 'util';
import { v4 as uuid  } from 'uuid';

const readFile = promisify(fs.readFile)

const getCheckSum = (filename: string): Promise<string> => {
    return new Promise((resolve, reject) => {
        const shasum = crypto.createHash('md5');
        try {
            const s = fs.createReadStream(path.join(__dirname , "../files/", filename));
            s.on('data', (data) => {
                shasum.update(data)
            })
            s.on('end', () => {
                return resolve(shasum.digest('hex'));
            })
        } catch (err) {
            reject(err)
        }
    })
}

const checkAuth = (req: Request, res:Response, next:NextFunction) => {
    let token = req.signedCookies['token']
    if (token && token["username"]) {
        if (token.username === 'superuser'){
            next(createError(404)) // superuser is disabled since you can't even find it in database :)
        }
        if (token.isAdmin === true) {
            next();
        }
        else {
            return res.redirect('/')
        }
    } else {
        next(createError(404));
    }
}


const router = express.Router();

router.get('/', (_, res) => res.render('index', { message: `Only admin's function is implemented. 😖 `}))

router.post('/', async (req, res) => {
    let { username, password } = req.body;
    if ( username && password) {
        if ( username == '' || typeof(username) !== "string" || password == '' || typeof(password) !== "string" ) {
            return res.render('index', { error: 'Parameters error 👻'});
        }
        let data = await DB.Login(username, password)
        if(!data) {
            return res.render('index', { error : 'You are not admin 😤'});
        }
        res.cookie('token', {
            username: username,
            isAdmin: true 
        }, { signed: true })
        res.redirect('/admin');
    } else {
        return res.render('index', { error : 'Parameters cannot be blank 😒'});
    }
})

router.get('/admin', checkAuth, async (req, res) => {
    let token = req.signedCookies['token'];
    try {
        const files = await DB.listFile(token.username);
        if (files) {
            res.cookie('token', {username: token.username, files: files, isAdmin: true }, { signed: true })
        }
    } catch (err) {
        return res.render('admin', { error: 'Something wrong ... 👻'})
    }
    return res.render('admin');
});

router.post('/admin', checkAuth, (req, res, next) => {
    let { content } = req.body;
    if ( content == '' || content.includes('<') || content.includes('>') || content.includes('/') || content.includes('script') || content.includes('on')){
        // even admin can't be trusted right ? :)  
        return res.render('admin', { error: 'Forbidden word 🤬'});
    } else {
        let template = `
        <html>
        <meta charset="utf8">
        <title>Create your own pdfs</title>
        <body>
        <h3>${content}</h3>
        </body>
        </html>
        `
        try {
            const filename = `${uuid()}.pdf`
            pdf.create(template, {
                "format": "Letter",
                "orientation": "portrait",
                "border": "0",
                "type": "pdf",
                "renderDelay": 3000,
                "timeout": 5000
            }).toFile(`./files/${filename}`, async (err, _) => {
                if (err) next(createError(500));
                const checksum = await getCheckSum(filename);
                await DB.Create('superuser', filename, checksum)
                return res.render('admin', { message : `Your pdf is successfully saved 🤑 You know how to download it right?`});
            });
        } catch (err) {
            return res.render('admin', { error : 'Failed to generate pdf 😥'})
        }
    }
});

// You can also add file logs here!
router.get('/api/files', async (req, res, next) => {
    if (req.socket.remoteAddress.replace(/^.*:/, '') != '127.0.0.1') {
        return next(createError(401));
    }
    let { username , filename, checksum } = req.query;
    if (typeof(username) == "string" && typeof(filename) == "string" && typeof(checksum) == "string") {
        try {
            await DB.Create(username, filename, checksum)
            return res.send('Done')
        } catch (err) {
            return res.send('Error!')
        }
    } else {
        return res.send('Parameters error')
    }
});

router.get('/api/files/:id', async (req, res) => {
    let token = req.signedCookies['token']
    if (token && token['username']) {
        if (token.username == 'superuser') {
            return res.send('Superuser is disabled now');   
        }
        try {
            let filename = await DB.getFile(token.username, req.params.id)
            if (fs.existsSync(path.join(__dirname , "../files/", filename))){
                return res.send(await readFile(path.join(__dirname , "../files/", filename)));
            } else {
                return res.send('No such file!');
            }
        } catch (err) {
            return res.send('Error!');
        }
    } else {
        return res.redirect('/');
    }
});

export default router;
