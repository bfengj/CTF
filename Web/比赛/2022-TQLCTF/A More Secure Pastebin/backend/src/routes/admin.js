const express = require("express");
const router = express.Router();
const ensureAdmin = require("../helper/admin").ensureAdmin;
const Pastes = require("../models/Pastes");
const User = require("../models/User");
const escapeStringRegexp = require("escape-string-regexp");

router.get("/search", ensureAdmin, (req, res) => {
    return res.render("admin/search");
});

router.get("/searchword", ensureAdmin, async (req, res) => {
    let { word } = req.query;

    if (word) {
        const searchRgx = new RegExp(escapeStringRegexp(word), "gi");
        // No time to implemente the pagination. So only show 5 results first.
        let paste = await Pastes.find({
            content: searchRgx,
        })
            .sort({ date: "asc" })
            .limit(5);
        if (paste && paste.length > 0) {
            let data = [];
            console.time("test");
            await Promise.all(
                paste.map(async (p) => {
                    let user = await User.findOne({ username: p.username });
                    data.push({
                        pasteid: p.pasteid,
                        title: p.title,
                        content: p.content,
                        date: p.date,
                        username: user.username,
                        website: user.website,
                    });
                })
            );
            console.timeEnd("test");
            return res.json({ status: "success", data: data });
        } else {
            return res.json({ status: "fail", data: [] });
        }
    } else {
        return res.json({ status: "fail", data: [] });
    }
});

module.exports = router;
