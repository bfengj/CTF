function renzheng(req, res, next){
    if(req.session.user){
        next();
      }else{
        res.redirect("/users/login")
      }
}

function un(req, res, next){
    if(req.session.user){
      res.locals.username=req.session.user[0].name;
    }
    next();
}
exports.renzheng=renzheng;
exports.un=un;