var express = require('express');
var User = require('../model/User');


var router = express.Router();

/* GET users listing. */
router.get('/',function(req, res, next) {
  res.send('respond with a resource');
});

router.get('/logup', function(req, res, next) {
  res.render('users/logup');
});
router.get('/login', function(req, res, next) {
  res.render('users/login');
});

router.post('/logup', function(req, res, next){
  var datas = req.body;
  User.find({name:datas.name},function(err,data){
    if(data.length==0){
      var user = new User({
        name:datas.name,
        pwd:datas.pwd
      })
      user.save(function(err,data){
        if(err) {
          res.json({status:-1,msg:"注册失败"})
        }else{
          res.json({status:1,msg:"注册成功"})
        }
      })
    }else{
      res.json({status:-1,msg:"用户名已存在"})
    }
  })
});

router.post("/login",function(req,res,next){
  var datas = req.body;
  console.log(datas)
  User.find({name:datas.name,pwd:datas.pwd},function(err,data){
    if(err) {
      res.json({status:-1,msg:"用户名或密码错误"})
    }else{
      if(data.length==0){
        res.json({status:-1,msg:"用户名不存在"})
      }else{
        req.session.user=data
        res.json({status:1,msg:"登陆成功"})
      }
      
    }
  })
  // if(err) res.redirect("/error.ejs")
  // res.redirect("/art/arts.ejs")
})


router.post("/Dout",function(req,res,next){
    req.session.destroy(function(err){
      if(err){
        res.json({state:-1})
      }else{
        res.json({state:1})
      }
  })})
module.exports = router;
