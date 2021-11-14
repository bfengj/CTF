var express = require('express');
var path = require("path");
var fs = require("fs");
var router = express.Router();
var author = require("../common/author")
var Art = require('../model/Art');

router.get('/',author.un,function(req, res, next) {
    var dir = fs.readdirSync("/tmp")
    var ID_ary = []
    for (let i in dir) {
        if(dir[i].slice(-3) == ".md"){
            ID_ary.push(dir[i].slice(0,-3))
        }
    }if(req.session.user==undefined){
        res.render('tag',{username:null,ID_Ary:ID_ary});
    }else {
        res.render('tag', {ID_Ary:ID_ary});
    }
});

router.get("/:id",author.un,function(req, res, next){
    var id = req.params.id;
    var dir = fs.readdirSync("/tmp")
    var ID_ary = []
    for (let i in dir) {
        if(dir[i].slice(-3) == ".md"){
            ID_ary.push(dir[i].slice(0,-3))
        }
    }
    Art.find(function(err,data){
        if(req.session.user==undefined) {
            res.render('tags', {arts: data,username: null,ID_Ary:ID_ary,ID:id});
        }else {
            res.render('tags', {arts: data,ID_Ary:ID_ary,ID:id});
        }
    })
})
module.exports = router;



