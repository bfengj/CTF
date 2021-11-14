const express = require('express');
const crypto = require('crypto');
const createError = require('http-errors');
const { Op } = require('sequelize');
const { User, Token } = require('../database');
const utils = require('../utils');
const Crawler = require('../crawler');

const router = express.Router();


router.get('/', async (req, res) => {
    const user = await User.findByPk(req.session.userId)
    return res.render('index', { username: user.username });
});


router.get('/profile', async (req, res) => {
    const user = await User.findByPk(req.session.userId);
    return res.render('user', { user });
});


router.post('/profile', async (req, res, next) => {
    let { affiliation, age, bucket } = req.body;
    const user = await User.findByPk(req.session.userId);
    if (!affiliation || !age || !bucket || typeof (age) !== "string" || typeof (bucket) !== "string" || typeof (affiliation) != "string") {
        return res.render('user', { user, error: "Parameters error or blank." });
    }
    if (!utils.checkBucket(bucket)) {
        return res.render('user', { user, error: "Invalid bucket url." });
    }
    let authToken;
    try {
        await User.update({
            affiliation,
            age,
            personalBucket: bucket
        }, {
            where: { userId: req.session.userId }
        });
        const token = crypto.randomBytes(32).toString('hex');
        authToken = token;
        await Token.create({ userId: req.session.userId, token, valid: true });
        await Token.update({
            valid: false,
        }, {
            where: {
                userId: req.session.userId,
                token: { [Op.not]: authToken }
            }
        });
    } catch (err) {
        next(createError(500));
    }
    if (/^https:\/\/[a-f0-9]{32}\.oss-cn-beijing\.ichunqiu\.com\/$/.exec(bucket)) {
        res.redirect(`/user/verify?token=${authToken}`)
    } else {
        // Well, admin won't do that actually XD. 
        return res.render('user', { user: user, message: "Admin will check if your bucket is qualified later." });
    }
});


router.get('/verify', async (req, res, next) => {
    let { token } = req.query;
    if (!token || typeof (token) !== "string") {
        return res.send("Parameters error");
    }
    let user = await User.findByPk(req.session.userId);
    const result = await Token.findOne({
        token,
        userId: req.session.userId,
        valid: true
    });
    console.log(result.valid)
    if (result) {
        try {
            await Token.update({
                valid: false
            }, {
                where: { userId: req.session.userId }
            });
            await User.update({
                bucket: user.personalBucket
            }, {
                where: { userId: req.session.userId }
            });
            user = await User.findByPk(req.session.userId);
            return res.render('user', { user, message: "Successfully update your bucket from personal bucket!" });
        } catch (err) {
            next(createError(500));
        }
    } else {
        user = await User.findByPk(req.session.userId);
        return res.render('user', { user, message: "Failed to update, check your token carefully" })
    }
})


// Not implemented yet
router.get('/bucket', async (req, res) => {
    const user = await User.findByPk(req.session.userId);
    if (/^https:\/\/[a-f0-9]{32}\.oss-cn-beijing\.ichunqiu\.com\/$/.exec(user.bucket)) {
        return res.json({ message: "Sorry but our remote oss server is under maintenance" });
    } else {
        // Should be a private site for Admin
        try {
            const page = new Crawler({
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36',
                referrer: 'https://www.ichunqiu.com/',
                waitDuration: '3s'
            });
            await page.goto(user.bucket);
            const html = page.htmlContent;
            const headers = page.headers;
            const cookies = page.cookies;
            await page.close();

            return res.json({ html, headers, cookies});
        } catch (err) {
            return res.json({ err: 'Error visiting your bucket. ' })
        }
    }
});



module.exports = router;
