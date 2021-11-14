var mongoose = require('mongoose');


var userSchema = mongoose.Schema({
    name: String,
    pwd: Number,
    createtime:{
        default:new Date(),
        type:Date
    }
});

var user = mongoose.model('User', userSchema);

module.exports=user;