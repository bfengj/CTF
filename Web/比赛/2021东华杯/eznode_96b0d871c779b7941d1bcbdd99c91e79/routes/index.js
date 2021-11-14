const express = require('express');
const fs = require('fs');
const { select, close, check } = require('../db');
const multer  = require('multer');
const storage = multer.diskStorage({
	destination: function (req, file, cb) {
	  cb(null, './upload_tmp')
	},
	filename: function (req, file, cb) {
	  cb(null,  Date.now()+'.jpg')
	}
  })
  
const upload = multer({ storage: storage })
const router = express.Router();

const checkLogin = (req, res, next) => {
	if (req.signedCookies.token)
		next()
	else
		res.render('error', { error: 'plz login first' })
}

router.get('/', function (req, res, next) {
	res.render('index')
});


router.post('/', async function (req, res, next) {
	let username = req.body.username;
	let password = req.body.password;
	if (check(username) && check(password)) {
		let sql = `select * from users where username='${username}' and password = '${password}'`;
		const result = await select(sql)
			.then(close())
			.catch(err => { console.log(err); });
		// console.log(result);
		if(result){
			if (result.username == username && password == result.password) {
				res.cookie('token', result, { signed: true });
				res.send("yes");
			} else {
				res.send("username or password error")
			}
		} else{
			res.send('no')
		}
	} else {
		res.send("Fak OFF HACKER");
	}
});

router.get('/admin', checkLogin, function (req, res, next) {
	res.render('admin', { "name": "admin" })
});


router.post('/admin', checkLogin, function (req, res, next) {
	var name = req.body.name ? req.body.name : "admin";
	res.render('admin', name)
});

// 还未上线..., checkLogin
router.post('/upload', checkLogin, upload.any(), function (req, res, next) {

	fs.readFile(req.files[0].path, function (err, data) {  
			if (err) {
				console.log(err);
			} else {
				response = {
					message: 'File uploaded successfully',
					filename: req.files[0].path
				};
			res.end(JSON.stringify(response));
		}
	});
})


module.exports = router;
