const express = require("express");
const router = express.Router();
const ensureAuthenticated = require("../helper/auth").ensureAuthenticated;
const uuid = require("uuid").v4;
const Pastes = require("../models/Pastes");

router.get("/", ensureAuthenticated, async (req, res) => {
    let username = req.session.username;

    let query = { username };
    let items = await Pastes.find(query).sort({ date: -1 });

    if (items.length) {
        return res.render("index", {
            items: items,
            username: username,
        });
    } else {
        return res.render("index", {
            items: [],
            username: username,
        });
    }
});

router.post("/add", ensureAuthenticated, (req, res) => {
    let { title, content } = req.body;
    let username = req.session.username;
    if (!content || typeof content !== "string") {
        req.flash("danger", "No content?");
        return res.redirect("/");
    }

    const newPaste = new Pastes({
        username: username,
        title: title,
        content: content,
        pasteid: uuid(),
    });
    newPaste
        .save()
        .then(() => {
            req.flash("success", "Paste created!");
        })
        .catch(() => {
            req.flash("danger", "Could not save!");
        });

    return res.redirect(301, "/");
});

router.get("/paste/:pasteid/view", ensureAuthenticated, async (req, res) => {
    let pasteid = req.params.pasteid;
    let username = req.session.username;

    let regex =
        /^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i;
    let match = regex.exec(pasteid);
    if (!match) {
        return res.send(403, { error: "Your paste id is wrong" });
    }

    let item = await Pastes.findOne({ username: username, pasteid: pasteid });

    if (!item) {
        return res.send({ msg: "Could not find such a paste id" });
    } else {
        return res.render("paste", {
            title: item.title,
            content: item.content,
        });
    }
});

module.exports = router;
