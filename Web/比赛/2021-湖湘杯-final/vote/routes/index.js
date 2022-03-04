const path              = require('path');
const express           = require('express');
const pug               = require('pug');
const { unflatten }     = require('flat');
const router            = express.Router();

router.get('/', (req, res) => {
    return res.sendFile(path.resolve('views/index.html'));
});

router.post('/api/submit', (req, res) => {
    const { hero } = unflatten(req.body);

	if (hero.name.includes('奇亚纳') || hero.name.includes('锐雯') || hero.name.includes('卡蜜尔') || hero.name.includes('菲奥娜')) {
		return res.json({
			'response': pug.compile('You #{user}, thank for your vote!')({ user:'Guest' })
		});
	} else {
		return res.json({
			'response': 'Please provide us with correct name.'
		});
	}
});

module.exports = router;