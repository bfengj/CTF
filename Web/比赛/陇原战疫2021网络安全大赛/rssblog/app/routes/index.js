var express = require('express');
var author = require("../common/author")


var router = express.Router();

/* GET home page. */
router.get('/',author.un,function(req, res, next) {
    if(req.session.user==undefined){
        res.render('index',{username:null});
    }else{
        res.render('index',);
    }
});

router.get('/session', function(req, res, next) {
    if(req.session.sss==undefined){
        req.session.sss=0
    }else{
        req.session.sss++
    }
    res.end(req.session.sss.toString());
});
module.exports = router;
