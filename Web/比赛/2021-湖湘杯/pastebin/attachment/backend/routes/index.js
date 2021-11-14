const express = require("express");
const router = express.Router();
const ensureAuthenticated = require("../helper/auth").ensureAuthenticated;
const bot = require("../helper/bot");
const uuid = require("uuid").v4;
const Pastes = require("../models/Pastes");
const User = require('../models/User')
const crypto = require("crypto");
const HOST = process.env.HOST || "localhost";
const PORT = process.env.PORT || "443";
const CHALLURL = "https://" + HOST + ":" + PORT;

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
	let { content } = req.body;
	let username = req.session.username;
	if (!content || typeof content !== "string") {
		req.flash("danger", "No content?");
		return res.redirect("/");
	}
	content = Buffer.from(content).toString("base64");

	if (username === "admin") {
		req.flash("danger", "Admin can not add new links.");
	} else {
		const newPaste = new Pastes({
			username: username,
			content: content,
			hash: uuid(),
		});
		newPaste.save().catch(() => {
			req.flash("danger", "Could not save!");
		});
	}
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

	if (username === "admin") {
		return res.render("paste", {
			pasteid: pasteid,
		});
	}

	let item = await Pastes.findOne({ username: username, hash: pasteid });

	if (!item) {
		return res.send({ msg: "Could not find such a paste id" });
	} else {
		return res.render("paste", {
			pasteid: pasteid,
		});
	}
});

router.get("/paste/:pasteid", ensureAuthenticated, async (req, res) => {
	let pasteid = req.params.pasteid;
	let username = req.session.username;

	let regex =
		/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i;
	let match = regex.exec(pasteid);
	if (!match) {
		return res.status(403).send({ error: "Your paste id is wrong" });
	}

	if (username === "admin") {
		let item = await Pastes.findOne({ hash: pasteid });
		res.set("Content-Type", "text/plain");
		return res.send(item.content);
	}

	let item = await Pastes.findOne({ username: username, hash: pasteid });

	if (!item) {
		return res.status(403).send({ msg: "Could not find such a paste id" });
	} else {
		res.set("Content-Type", "text/plain");
		return res.send(item.content);
	}
});

router.get("/report", ensureAuthenticated, (req, res) => {
	function randomInteger(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
	let ranNum = randomInteger(100000, 10000000);
	let md5 = crypto.createHash("md5");
	let captcha = md5.update(ranNum.toString()).digest("hex").substr(0, 7);
	req.session.captcha = captcha;
	return res.render("report", { captcha: captcha });
});

router.post("/report", ensureAuthenticated, async (req, res) => {
	let { captcha, url } = req.body;
	let username = req.session.username;
	let md5 = crypto.createHash("md5");

	let user = await User.findOne({ username: username });
	if (user.remarks) {
		res.status(403).send("You already have a flag now.");
	}

	if (
		typeof url !== "string" ||
		url.startsWith(CHALLURL + "/paste") === false
	) {
		return res.status(403).send({ error: "Your url is wrong" });
	}
	if (
		md5.update(captcha).digest("hex").substr(0, 7) === req.session.captcha
	) {
		bot.visit(url);
		return res.send({ msg: "Your report has been sent" });
	} else {
		return res.status(403).send({ error: "Your captcha is wrong" });
	}
});

module.exports = router;
