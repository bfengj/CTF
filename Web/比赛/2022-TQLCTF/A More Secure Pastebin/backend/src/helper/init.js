const User = require("../models/User");
const Pastes = require("../models/Pastes");
const uuid = require("uuid").v4;
const fs = require("fs");

function getFlag() {
    return fs.readFileSync("../flag").toString();
}

module.exports = {
    initdb: () => {
        // add Admin
        User.findOneAndUpdate(
            {
                username: "admin",
            },
            {
                $setOnInsert: {
                    password: process.env.ADMIN_PASS || "admin",
                },
            },
            {
                upsert: true,
                new: true,
            }
        )
            .then(console.log("[+] Admin added"))
            .catch((err) => console.log(err));

        // add flags
        Pastes.findOneAndUpdate(
            {
                username: "admin",
                title: "secretpaste",
            },
            {
                $setOnInsert: {
                    pasteid: uuid(),
                    content: `Hello, Admin. This is the flag. ${getFlag()}. Plz keep it secret.`,
                },
            },
            {
                upsert: true,
                new: true,
            }
        )
            .then(console.log("[+] Flag added"))
            .catch((err) => console.log(err));

        Pastes.findOneAndUpdate(
            {
                username: "admin",
                title: "yet another secret paste",
            },
            {
                $setOnInsert: {
                    pasteid: uuid(),
                    content: `Add a flag for test. ${getFlag()}.`,
                },
            },
            {
                upsert: true,
                new: true,
            }
        )
            .then(console.log("[+] Flag added"))
            .catch((err) => console.log(err));

        Pastes.findOneAndUpdate(
            {
                username: "admin",
                title: "Admin admin",
            },
            {
                $setOnInsert: {
                    pasteid: uuid(),
                    content: `This is cool. ${getFlag()}`,
                },
            },
            {
                upsert: true,
                new: true,
            }
        )
            .then(console.log("[+] Flag added"))
            .catch((err) => console.log(err));
        Pastes.findOneAndUpdate(
            {
                username: "admin",
                title: "GML YS TQL",
            },
            {
                $setOnInsert: {
                    pasteid: uuid(),
                    content: `GML YS kill the game. ${getFlag()}`,
                },
            },
            {
                upsert: true,
                new: true,
            }
        )
            .then(console.log("[+] Flag added"))
            .catch((err) => console.log(err));
        Pastes.findOneAndUpdate(
            {
                username: "admin",
                title: "Do you know GYS?",
            },
            {
                $setOnInsert: {
                    pasteid: uuid(),
                    content: `He is a god. ${getFlag()}`,
                },
            },
            {
                upsert: true,
                new: true,
            }
        )
            .then(console.log("[+] Flag added"))
            .catch((err) => console.log(err));
    },
};
