const mongoose = require("mongoose");

const Pastesschema = new mongoose.Schema({
    pasteid: {
        type: String,
        required: true,
    },
    username: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    content: {
        type: String,
        required: true,
    },
    date: { type: Date, default: Date.now },
});

const Pastes = mongoose.model("Pastes", Pastesschema);
module.exports = Pastes;
