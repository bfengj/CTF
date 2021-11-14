var express = require('express');
var router = express.Router();
var RSS = require('rss');
var author = require("../common/author")
var request = require('request');
router.get('/',author.un,function(req, res, next) {
    if(req.session.user==undefined){
        res.render('rss',{username:null});
    }else{
        res.render('rss',);
    }
});


router.get("/:id",author.un,function(req, res, next) {
    const link = `http://127.0.0.1:3000/${req.params.id}`;
    const options = {
        'method': 'GET',
        'url': link,
        'headers': {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36",
        }
    };
    request(options, function (error, response, body) {
        if (error) throw new Error(error);
        try {
            const data = Function(
                body.match(/var passage = \{.*};/gm)[0]
                + 'let json_data=JSON.parse(JSON.stringify(passage));'
                + 'return json_data;'
            )();
            var feed = new RSS(data);
            var xml = feed.xml();
            res.contentType('application/xml');
            res.send(xml)
        }catch (e) {
            res.render('err');
        }
    })
})
module.exports = router;