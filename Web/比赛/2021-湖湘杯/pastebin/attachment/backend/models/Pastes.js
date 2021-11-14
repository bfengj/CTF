const mongoose = require('mongoose')

const Pastesschema = new mongoose.Schema({
    username: {
        type: String,
        required: true
    },
    content: {
        type: String,
        required: true
    },
    hash: {
        type: String,
        required: true
    },
    date: {
        type: String,
        default: Date.now()
    }

})

const Pastes = mongoose.model('Pastes', Pastesschema)
module.exports = Pastes
