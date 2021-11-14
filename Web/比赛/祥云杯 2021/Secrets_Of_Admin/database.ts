import * as sqlite3 from 'sqlite3';

let db = new sqlite3.Database('./database.db', (err) => {
    if (err) {
        console.log(err.message)
    } else {
        console.log("Successfully Connected!");
        db.exec(`
        DROP TABLE IF EXISTS users;

	CREATE TABLE IF NOT EXISTS users (
            id         INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            username   VARCHAR(255) NOT NULL,
            password   VARCHAR(255) NOT NULL
        );

	INSERT INTO users (id, username, password) VALUES (1, 'admin','e365655e013ce7fdbdbf8f27b418c8fe6dc9354dc4c0328fa02b0ea547659645');

	DROP TABLE IF EXISTS files;

	CREATE TABLE IF NOT EXISTS files (
            username   VARCHAR(255) NOT NULL,
            filename   VARCHAR(255) NOT NULL UNIQUE,
            checksum   VARCHAR(255) NOT NULL
        );

	INSERT INTO files (username, filename, checksum) VALUES ('superuser','flag','be5a14a8e504a66979f6938338b0662c');`);
        console.log('Init Finished!')
    }
});

export default class DB {
    static Login(username: string, password: string): Promise<any> {
        return new Promise((resolve, reject) => {
            db.get(`SELECT * FROM users WHERE username = ? AND password = ?`, username, password, (err , result ) => {
                if (err) return reject(err);
                resolve(result !== undefined);
            })
        })
    }
    
    static getFile(username: string, checksum: string): Promise<any> {
        return new Promise((resolve, reject) => {
            db.get(`SELECT filename FROM files WHERE username = ? AND checksum = ?`, username, checksum, (err , result ) => {
                if (err) return reject(err);
                resolve(result ? result['filename'] : null);
            })
        })
    }

    static listFile(username: string): Promise<any> {
        return new Promise((resolve, reject) => {
            db.all(`SELECT filename, checksum FROM files WHERE username = ? ORDER BY filename`, username, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            })
        })
    }

    static Create(username: string, filename: string, checksum: string): Promise<any> {
       return new Promise((resolve, reject) => {
            try {
                let query = `INSERT INTO files(username, filename, checksum) VALUES('${username}', '${filename}', '${checksum}');`;
                resolve(db.run(query));
            } catch (err) {
                reject(err);
            }
       })
    }
}

