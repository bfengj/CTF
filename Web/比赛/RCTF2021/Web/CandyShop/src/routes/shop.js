const express = require('express')
const db = require('../db')
const pug = require('pug')

const router = express.Router()

const checkLogin = (req, res, next) => {
    if (req.signedCookies.token)
        next()
    else
        res.render('error', {error: 'You must login!'})
}

const checkActive = (req, res, next) => {
    if (req.signedCookies.token.active)
        next()
    else
        res.render('error', {error: 'Your account is not active!'})
}


router.get('/', checkLogin, async (req, res) => {
    let candies = await db.Candies.list()
    res.render('shop', {candies: candies})
})

router.get('/order', checkLogin, checkActive, async (req, res) => {
    let {id} = req.query
    let candy = await db.Candies.find({id: id})
    res.render('order', {user_name: req.signedCookies.token.username, candy: candy})
})

router.post('/order', checkLogin, checkActive, async (req, res) => {
    let {user_name, candy_name, address} = req.body

    res.render('confirm', {
        user_name: user_name,
        candy_name: candy_name,
        address: pug.render(address)
    })
})

router.get('/thanks', checkLogin, checkActive, async (req, res) => {
    res.render('thanks')
} )

module.exports = router