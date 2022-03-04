const mongoose = require("mongoose");

const UserSchema = new mongoose.Schema({
    username: {
        type: String,
        required: true,
    },
    password: {
        type: String,
        required: true,
    },
    website: {
        type: String,
        required: false,
    },
    date: {
        type: String,
        default: Date.now(),
    },
});

const User = mongoose.model("User", UserSchema);
module.exports = User;
