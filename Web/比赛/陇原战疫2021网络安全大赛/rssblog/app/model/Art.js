var mongoose = require('mongoose');

var artSchema = mongoose.Schema({
    title: String,
    au: String,
    txt: String,
    username:String,
    createtime:{
        default:new Date(),
        type:Date
    }
});

var art = mongoose.model('Art', artSchema);

module.exports=art;