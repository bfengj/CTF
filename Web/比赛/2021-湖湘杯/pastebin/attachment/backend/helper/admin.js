const ensureAdmin = (req, res, next) => {
    const HTTP_X_AUTH = process.env.ADMIN_HEADER || "test-host";
    if(req.session.username === "admin" && req.headers['x-auth']){
        if (typeof req.headers['x-auth'] === 'string' &&  req.headers['x-auth'].trim() ===  HTTP_X_AUTH){
            return next()
        }
        return res.status(403).send('You are not admin')
    }
    else{
        return res.status(403).send('You are not admin')
    }
}

module.exports = {
    ensureAdmin,
}