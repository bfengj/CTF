const SECRET_COOKIE = process.env.SECRET_COOKIE || "admin";

const ensureAdmin = (req, res, next) => {
    if (req.session.username === "admin") {
        return next();
    } else {
        console.log(new Date() + ": Not admin");
        return res.status(403).send("You are not admin");
    }
};

module.exports = {
    ensureAdmin,
};
