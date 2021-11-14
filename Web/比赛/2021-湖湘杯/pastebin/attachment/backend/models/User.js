const mongoose = require('mongoose')

const UserSchema = new mongoose.Schema({
    username: {
        type: String,
        required: true
    },
    password: {
        type: String,
        required: true
    },
    remarks: {
        type: String,
        required: false
    },
    date: {
        type: String,
        default: Date.now()
    }
})

const User = mongoose.model('User', UserSchema)
module.exports = User