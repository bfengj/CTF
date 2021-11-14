import * as express from 'express';
import { Package, User, Report } from '../schema';
import * as createError from 'http-errors';
import { checkAuth, checkmd5Regex, genPackageId } from '../utils';

const router = express.Router();

router.get('/', async (_, res) => {
	const user = await User.findOne({ username: 'testuser' });
	const packs = await Package.find({ user_id: user.id });
	return res.render('packages', {
		packs: packs,
		message: `TOP packages will be shown here :)  So don't hesitate to create your own!`,
	});
});

router.get('/list', async (req, res, next) => {
	const packs = await Package.find({ user_id: req.session.userId });
	if (packs.length == 0) {
		return res.redirect('/packages');
	}
	let { search } = req.query;
	if (search) {
		try {
			let description = search;
			let name = search;
			if (typeof description === 'string') {
				description = { description };
			}
			if (typeof name === 'string') {
				name = { name };
			}
			const packs = await Package.find({
				user_id: req.session.userId,
				$or: [name, description],
			});
			if (packs.length == 0) {
				return next(createError(404));
			}
			return res.render('packages', { packs });
		} catch (err) {
			return next(createError(500))
		}
	}
	return res.render('packages', { packs });
});

router.get('/add', (_, res) => res.render('add'));

router.post('/add', async (req, res) => {
	let { name, description, version } = req.body;
	if (name && description && version) {
		if (
			name == '' ||
			typeof name !== 'string' ||
			description == '' ||
			typeof description !== 'string' ||
			version == '' ||
			typeof version !== 'string'
		) {
			return res.render('add', { error: 'Parameters error' });
		}
		try {
			const pack_id = genPackageId(req.session.userId);
			const new_pack = new Package({
				user_id: req.session.userId,
				pack_id: pack_id,
				name: name,
				description: description,
				version: version,
			});
			await new_pack.save();
			return res.redirect(`/packages/${pack_id}`);
		} catch (err) {
			return res.render('add', { error: 'Failed adding the package' });
		}
	} else {
		return res.render('add', { error: 'Parameters cannot be blank' });
	}
});

router.get('/:id/edit', async (req, res, next) => {
	const pack = await Package.findOne({
		user_id: req.session.userId,
		pack_id: req.params.id,
	});
	if (!pack) {
		return next(createError(404));
	}
	return res.render('edit', { package: pack });
});

router.post('/:id/edit', async (req, res) => {
	let { name, description, version } = req.body;
	if (name && description && version) {
		if (
			name == '' ||
			typeof name !== 'string' ||
			description == '' ||
			typeof description !== 'string' ||
			version == '' ||
			typeof version !== 'string'
		) {
			return res.render('edit', {
				error: 'Parameters error',
				package: {
					pack_id: req.params.id,
					name: name,
					description: description,
					version: version,
				},
			});
		}
		try {
			await Package.updateOne(
				{
					user_id: req.session.userId,
					pack_id: req.params.id,
				},
				{
					name: name,
					description: description,
					version: version,
				}
			);
			return res.redirect(`/packages/${req.params.id}`);
		} catch (err) {
			return res.render('edit', {
				error: 'Failed editing the package',
				package: {
					pack_id: req.params.id,
					name: name,
					description: description,
					version: version,
				},
			});
		}
	} else {
		return res.render('edit', {
			error: 'Parameters can not be blank',
			package: {
				pack_id: req.params.id,
				name: name,
				description: description,
				version: version,
			},
		});
	}
});

router.get('/:id/delete', async (req, res) => {
	try {
		await Package.deleteOne({
			user_id: req.session.userId,
			pack_id: req.params.id,
		});
	} catch (err) {
		return res.render('packages', { error: 'Failed deleting the package' });
	}
	res.redirect('/packages/list');
});

router.get('/submit', checkAuth, (_, res) => res.render('submit'));

router.post('/submit', checkAuth, async (req, res) => {
	let { pack_id } = req.body;
	if (!checkmd5Regex(pack_id)) {
		return res.render('submit', {
			error: 'Package id must be valid md5 string',
		});
	}
	try {
		const report = new Report({ pack_id: pack_id });
		await report.save();
		return res.render('submit', { message: 'Package successfully submitted' });
	} catch (err) {
		return res.render('submit', { error: 'Already submit your package' });
	}
});

router.get('/:id', async (req, res, next) => {
	try {
		const admin = await User.findOne({ username: 'admin', isAdmin: true });
		if (req.session.userId === admin.id) {
			const pack = await Package.findOne({ pack_id: req.params.id });
			if (pack) {
				return res.render('pack', { package: pack });
			} else {
				next(createError(404));
			}
		}
		const pack = await Package.findOne({
			user_id: req.session.userId,
			pack_id: req.params.id,
		});
		if (pack) {
			return res.render('pack', { package: pack });
		} else {
			next(createError(404));
		}
	} catch (err) {
		next(createError(404));
	}
});

export default router;
