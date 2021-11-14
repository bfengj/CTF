const express = require("express");
const path = require("path");
const fs = require("fs");
const notevil = require("./notevil"); // patched something...
const crypto = require("crypto");
const cookieSession = require("cookie-session");
const safeEval = require("./notevil");

const app = express();
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(cookieSession({
  name: 'session',
  keys: [Math.random().toString(16)],
}));

//flag in root directory but name is randomized

const utils = {
  async md5(s) {
    return new Promise((resolve, reject) => {
      resolve(crypto.createHash("md5").update(s).digest("hex"));
    });
  },
  async readFile(n) {
    return new Promise((resolve, reject) => {
      fs.readFile(n, (err, data) => {
        if (err) {
          reject(err);
        } else {
          resolve(data);
        }
      });
    });
  },
}

const template = fs.readFileSync("./static/index.html").toString();

function render(s) {
  return template.replace("{{res}}", s.join('<br/>'));
}

app.use("/", async (req, res) => {
  const e = req.body.e;
  const his = req.session.his || [];

  if (e) {
    try {
      
      const ret = (await notevil(e, utils)).toString();
      his.unshift(`${e} = ${ret}`);
      if (his.length > 10) {
        his.pop();
      }
    } catch (error) {
      console.log(error);
      // his.add(`${e} = wrong?`);
    }
    req.session.his = his;
  }

  res.send(render(his));
});

app.use((err, res) => {
  console.log(err);
  res.redirect('/');
});

module.exports = app
app.listen(process.env.PORT || 3000);
