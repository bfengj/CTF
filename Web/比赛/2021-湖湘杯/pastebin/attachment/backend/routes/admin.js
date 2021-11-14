const express = require("express");
const router = express.Router();
const ensureAdmin = require("../helper/admin").ensureAdmin;
const uuid = require("uuid").v4;
const Pastes = require("../models/Pastes");
const User = require("../models/User");

router.get("/paste/:pasteid", ensureAdmin, async (req, res) => {
	let pasteid = req.params.pasteid;

	let regex =
		/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i;
	let match = regex.exec(pasteid);
	if (!match) {
		return res.status(403).send({ error: "Your paste id is wrong" });
	}

	let query = {
		pasteid: pasteid,
	};
	let paste = await Pastes.findOne(query);
	let data = await Pastes.find({ username: paste.username })
		.sort({ date: -1 })
		.limit(10);

	return res.render("admin/paste", { data: data });
});

router.get("/paste/:pasteid/comment", ensureAdmin, async (req, res) => {
	let pasteid = req.params.pasteid;

	let regex =
		/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i;
	let match = regex.exec(pasteid);
	if (!match) {
		return res.status(403).send({ error: "Your paste id is wrong" });
	}

	let query = {
		pasteid: pasteid,
	};
	let paste = await Pastes.findOne(query);
	let user = await User.findOne({ username: paste.username });
	
	return res.render("admin/comment", { user: user });
});

router.post("/paste/:pasteid/comment", ensureAdmin, async (req, res) => {
	let pasteid = req.params.pasteid;
	let remarks = req.body.remarks;
	let _id = req.body.id;

	let regex =
		/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i;
	let match = regex.exec(pasteid);
	if (!match) {
		return res.status(403).send({ error: "Your paste id is wrong" });
	}

	let q = { _id: _id };
	let a = await User.findOne(q);

	let filter = { _id: _id };
	if( a.remarks.length > 256 ){
		return res.redirect("/admin/paste/" + pasteid);
	}
	let doc = await User.updateOne(filter, { remarks: remarks });
	return res.redirect("/admin/paste/" + pasteid);
});

module.exports = router;
