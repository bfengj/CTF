import *  as express from "express";
import { User } from "../schema";
import { checkmd5Regex } from "../utils";

const router = express.Router();

router.get('/', (_, res) => res.render('index'))

router.get('/login', (_, res) => res.render('login'))

router.post('/login', async (req, res) => {
	let { username, password } = req.body;
	if (username && password) {
		if (username == '' || typeof (username) !== "string" || password == '' || typeof (password) !== "string") {
			return res.render('login', { error: 'Parameters error' });
		}
		const user = await User.findOne({ "username": username })
		if (!user || !(user.password === password)) {
			return res.render('login', { error: 'Invalid username or password' });
		}
		req.session.userId = user.id
		res.redirect('/packages/list')
	} else {
		return res.render('login', { error: 'Parameters cannot be blank' });
	}
})

router.get('/register', (_, res) => res.render('register'))

router.post('/register', async (req, res) => {
	let { username, password, password2 } = req.body;
	if (username && password && password2) {
		if (username == '' || typeof (username) !== "string" || password == '' || typeof (password) !== "string" || password2 == '' || typeof (password2) !== "string") {
			return res.render('register', { error: 'Parameters error' });
		}
		if (password != password2) {
			return res.render('register', { error: 'Password do noy match' });
		}
		if (await User.findOne({ username: username })) {
			return res.render('register', { error: 'Username already taken' });
		}
		try {
			const user = new User({ "username": username, "password": password, "isAdmin": false })
			await user.save()
		} catch (err) {
			return res.render('register', { error: err });
		}
		res.redirect('/login');
	} else {
		return res.render('register', { error: 'Parameters cannot be blank' });
	}
})

router.get('/logout', (req, res) => {
	req.session.destroy(() => res.redirect('/'))
})


router.get('/auth', (_, res) => res.render('auth'))

router.post('/auth', async (req, res) => {
	let { token } = req.body;
	if (token !== '' && typeof (token) === 'string') {
		if (checkmd5Regex(token)) {
			try {
				let docs = await User.$where(`this.username == "admin" && hex_md5(this.password) == "${token.toString()}"`).exec()
				console.log(docs);
				if (docs.length == 1) {
					if (!(docs[0].isAdmin === true)) {
						return res.render('auth', { error: 'Failed to auth' })
					}
				} else {
					return res.render('auth', { error: 'No matching results' })
				}
			} catch (err) {
				return res.render('auth', { error: err })
			}
		} else {
			return res.render('auth', { error: 'Token must be valid md5 string' })
		}
	} else {
		return res.render('auth', { error: 'Parameters error' })
	}
	req.session.AccessGranted = true
	res.redirect('/packages/submit')
});


export default router;