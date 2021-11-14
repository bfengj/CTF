import *  as mongoose from "mongoose"
import { Package, User } from "./schema";
import { genPackageId } from "./utils";


async function init_db() {
    try {
        await User.deleteMany({})
        console.log('User collection removed')
        let data = { "username": "admin", "password": process.env.ADMIN_PASSWORD, "isAdmin": true }
        const admin = new User(data)
        await admin.save()
        console.log('Successfully create user admin')

        data = { "username": "testuser", "password": process.env.TEST_PASSWORD, "isAdmin": false }
        const testuser = new User(data)
        await testuser.save()
        console.log('Successfully create user testuser')

        const flag = {
            "user_id": admin.id,
            "pack_id": genPackageId(admin.id),
            "name": "Flag is here",
            "description": process.env.FLAG,
            "version": "1.0.1"
        }

        const data1 = {
            "user_id": testuser.id,
            "pack_id": genPackageId(testuser.id),
            "name": "express",
            "description": "Fast, unopinionated, minimalist web framework for node.",
            "version": "4.17.1"
        }

        const data2 = {
            "user_id": testuser.id,
            "pack_id": genPackageId(testuser.id),
            "name": "koa",
            "description": "Next generation web framework for Node.js",
            "version": "2.13.1"
        }

        const data3 = {
            "user_id": testuser.id,
            "pack_id": genPackageId(testuser.id),
            "name": "react",
            "description": "React is a JavaScript library for building user interfaces.",
            "version": "17.0.1"
        }

        const data4 = {
            "user_id": testuser.id,
            "pack_id": genPackageId(testuser.id),
            "name": "typescript",
            "description": `TypeScript is a language for application-scale JavaScript. TypeScript adds optional types to JavaScript that support tools for large-scale JavaScript applications for any browser, for any host, on any OS. TypeScript compiles to readable, standards-based JavaScript. Try it out at the playground, and stay up to date via our blog and Twitter account.`,
            "version": "4.1.3"
        }

        const data5 = {
            "user_id": testuser.id,
            "pack_id": genPackageId(testuser.id),
            "name": "pug",
            "description": "Pug is a high-performance template engine heavily influenced by Haml and implemented with JavaScript for Node.js and browsers.",
            "version": "17.0.1"
        }

        const data6 = {
            "user_id": testuser.id,
            "pack_id": genPackageId(testuser.id),
            "name": "lodash",
            "description": "A modern JavaScript utility library delivering modularity, performance & extras.",
            "version": "4.17.20"
        }
        await Package.deleteMany({})
        console.log('Package collection removed')

        let pack = new Package(flag);
        await pack.save();
        pack = new Package(data1);
        await pack.save();
        pack = new Package(data2);
        await pack.save();
        pack = new Package(data3);
        await pack.save();
        pack = new Package(data4);
        await pack.save()
        pack = new Package(data5);
        await pack.save()
        pack = new Package(data6);
        await pack.save()
        console.log('Successfully create packages')


        await mongoose.disconnect()
        console.log('Init db Finished.')

    } catch (err) {
        console.log(err)
        console.log('Init db Failed.')
        await mongoose.disconnect()
    }
}


init_db()
