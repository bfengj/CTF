const { hashSync, compareSync, genSaltSync } = require('bcrypt');
const { URL } = require('url');

class Utils {

    static hashPassword(password) {
        return hashSync(password, genSaltSync());
    }

    static checkPassword(password, encrypted) {
        return compareSync(password, encrypted);
    }

    static checkSignIn(req, res, next) {
        if (!req.session.userId) {
            return res.redirect('/signin')
        }
        next();
    }

    static signIn(req, user) {
        req.session.userId = user.userId;
    }

    static signOut(req, next) {
        req.session.destroy(next)
    }

    static checkBucket(url) {
        try {
            url = new URL(url);
        } catch (err) {
            return false;
        }
        if (url.protocol != "http:" && url.protocol != "https:") return false;
        if (url.href.includes('oss-cn-beijing.ichunqiu.com') === false) return false;
        return true;
    }

    static async sleep (ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    }
}



module.exports = Utils;
