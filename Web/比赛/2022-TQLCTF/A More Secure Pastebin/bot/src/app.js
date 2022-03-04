const express = require("express");
const flash = require("connect-flash");
const session = require("express-session");
const crypto = require("crypto");
const bot = require("./bot");
const RedisStore = require("connect-redis")(session);
const redis = require("redis");

const BIND_ADDR = process.env.BIND_ADDR || "0.0.0.0";
const LPORT = process.env.BOTPORT || 4000;
const SESSION_SECRET = process.env.SESSION_SECRET || "tqlctf";
const REDIS_URI = "redis://" + (process.env.REDIS_HOST || "127.0.0.1:6379");
const REDIS_PASSWORD = process.env.REDIS_PASSWORD || "test";

const app = express();

// Redis config
const redisClient = redis.createClient({
    url: REDIS_URI,
    legacyMode: true,
    password: REDIS_PASSWORD,
});
redisClient
    .connect()
    .then(() => console.log("Redis Connected."))
    .catch((err) => console.log(err));

// body parser
app.use(express.urlencoded({ extended: false }));

app.set("view engine", "ejs");
app.use(express.static(__dirname + "/public"));

// express session
app.use(
    session({
        store: new RedisStore({ client: redisClient , prefix: "bot"}),
        secret: SESSION_SECRET,
        resave: false,
        saveUninitialized: true,
        name: "bot-session",
        cookie: {
            maxAge: 1000 * 60,
            httpOnly: true,
        },
    })
);

// connect flash
app.use(flash());
app.use((req, res, next) => {
    res.locals.message = req.flash();
    next();
});

function isValidHttpUrl(string) {
    let url;
    try {
        url = new URL(string);
    } catch (_) {
        return false;
    }

    return url.protocol === "http:" || url.protocol === "https:";
}

app.get("/", function (req, res) {
    function randomInteger(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    let ranNum = randomInteger(1000000, 100000000);
    console.log(ranNum);
    let md5 = crypto.createHash("md5");
    let captcha = md5.update(ranNum.toString()).digest("hex").substr(0, 6);
    req.session.captcha = captcha;
    return res.render("index", { captcha: captcha });
});

app.post("/", async (req, res) => {
    let { captcha, url } = req.body;
    let md5 = crypto.createHash("md5");
    if (md5.update(captcha).digest("hex").substr(0, 6) !== req.session.captcha)
        return res.status(403).send({ error: "Your captcha is wrong" });
    if (typeof url !== "string" || isValidHttpUrl(url) === false) {
        return res.status(403).send({ error: "Your url is wrong" });
    }
    res.send({ msg: "The bot will visit your url soon." });
    await bot.visit(url);
});

app.listen(
    LPORT,
    BIND_ADDR,
    console.log(`Started on http://${BIND_ADDR}:${LPORT}`)
);
