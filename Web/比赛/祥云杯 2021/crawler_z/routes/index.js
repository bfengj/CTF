const express = require('express');
const crypto = require('crypto');
const utils = require('../utils');
const { User } = require('../database');

const router = express.Router();

router.get('/', async (_, res) => res.render('index'));


router.get('/signin', (_, res) => res.render('signin'));


router.post('/signin', async (req, res) => {
    let { username, password } = req.body;
    if (typeof (username) !== "string" || typeof (password) !== "string") {
        return res.render('signin', { error: "Parameters error." });
    }
    const user = await User.findOne({ where: { username: username } });
    if (!user || !utils.checkPassword(password, user.password)) {
        return res.render('signin', { error: "Invalid username or password." });
    }
    utils.signIn(req, user)
    return res.redirect('/user');
});


router.get('/logout', (req, res) => utils.signOut(req, () => res.redirect('/')));


router.get('/signup', (_, res) => res.render('signup'));


router.post('/signup', async (req, res) => {
    let { username, password, password_confirm } = req.body;
    if (typeof (username) !== "string" || typeof (password) !== "string" || typeof (password_confirm) !== "string") {
        return res.render('signup', { error: "Parameters error." });
    }
    if (/^\s*$/.test(username) || /^\s*$/.test(password) || /^\s*$/.test(password_confirm)) {
        return res.render('signup', { error: `Paramaters can't be empty.` });
    }
    if (password !== password_confirm) {
        return res.render('signup', { error: `Password doesn't match.` });
    }
    try {
        const user = await User.findOne({ where: { username: username } });
        if (user !== null) {
            return res.render('signup', { error: "User already exists." });
        } else {
            await User.create({ username: username, password: utils.hashPassword(password), bucket: `https://${crypto.randomBytes(16).toString('hex')}.oss-cn-beijing.ichunqiu.com/` });
        }
    } catch (err) {
        return res.render('signup', { error: "Error creating user." });
    }
    return res.redirect('/signin');
});



module.exports = router;