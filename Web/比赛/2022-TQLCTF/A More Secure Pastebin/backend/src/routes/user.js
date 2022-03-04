const express = require("express");
const ensureAuthenticated = require("../helper/auth").ensureAuthenticated;
const router = express.Router();
const User = require("../models/User");
const Pastes = require("../models/Pastes");
const escapeStringRegexp = require("escape-string-regexp");
const init = require("../helper/init");

init.initdb();

// login page
router.get("/login", async (req, res) => {
    return res.render("login");
});

//logout page
router.get("/logout", (req, res) => {
    req.session.username = "";
    req.flash("success", "Logged out successfully.");
    return res.redirect("/user/login");
});

// register
router.get("/register", (req, res) => {
    return res.render("register");
});

// register post
router.post("/register", async (req, res) => {
    let { username, password, password2 } = req.body;
    let errors = [];
    // check it
    if (!username || !password || !password2) {
        errors.push({ msg: "Please fill in all fields." });
    }
    if (password !== password2) {
        errors.push({ msg: "Password do not match." });
    }
    if (password.length < 4) {
        errors.push({ msg: "Password must be at least 4 characters." });
    }
    if (username.length < 4) {
        errors.push({ msg: "Username must be at least 4 characters." });
    }

    let user = await User.findOne({ username: username });
    if (user) {
        errors.push({ msg: "Username is already taken." });
    }

    if (errors.length) {
        return res.render("register", {
            errors,
            username,
            password,
            password2,
        });
    } else {
        const newUser = new User({
            username,
            password,
        });
        newUser
            .save()
            .then((user) => {
                req.flash("success", "You are now registered.");
                return res.redirect("/user/login");
            })
            .catch((err) => {
                req.flash("success", "DB error LOL.");
                console.log(err);
                return res.redirect("/user/login");
            });
    }
});

// login post
router.post("/login", (req, res) => {
    let { username, password } = req.body;
    User.findOne({ username: username }).then((user) => {
        if (!user) {
            return res.render("login", {
                errors: [{ msg: "Username does not exit." }],
                username,
                password,
            });
        } else if (user.password !== password) {
            return res.render("login", {
                errors: [{ msg: "Password or Username not correct." }],
                username,
                password,
            });
        } else {
            if (user.username === "admin") {
                console.log(new Date().toLocaleString() + ": admin logged in.");
            }
            req.session.username = user.username;
            return res.redirect("/");
        }
    });
});

router.get("/profile", ensureAuthenticated, (req, res) => {
    let username = req.session.username;
    User.findOne({ username: username }).then((user) => {
        return res.render("profile", { website: user.website });
    });
});

router.post("/profile", ensureAuthenticated, (req, res) => {
    let { website } = req.body;
    let username = req.session.username;
    User.findOneAndUpdate(
        { username: username },
        { website: website },
        { new: true },
        (err, doc) => {
            if (err) {
                req.flash("danger", "DB error LOL.");
            } else {
                req.flash("success", "Profile updated.");
            }
            return res.redirect("/user/profile");
        }
    );
});

router.get("/search", ensureAuthenticated, (req, res) => {
    return res.render("search");
});

router.get("/searchword", ensureAuthenticated, async (req, res) => {
    let { word } = req.query;
    let username = req.session.username;

    if (word) {
        const searchRgx = new RegExp(escapeStringRegexp(word), "gi");
        let paste = await Pastes.find({
            content: searchRgx,
            username: username,
        });
        if (paste && paste.length > 0) {
            let data = [];
            paste.forEach((p) => {
                data.push({
                    pasteid: p.pasteid,
                    title: p.title,
                    content: p.content,
                    date: p.date,
                });
            });
            return res.json({ status: "success", data: data });
        } else {
            return res.json({ status: "fail", data: [] });
        }
    } else {
        return res.json({ status: "fail", data: [] });
    }
});

module.exports = router;
