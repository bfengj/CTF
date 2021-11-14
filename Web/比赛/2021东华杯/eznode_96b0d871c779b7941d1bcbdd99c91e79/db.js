const mysql = require('mysql');

config = {
    host: 'localhost',
    user: 'root',
    password: 'root',
    database: 'test'
}
const select = (sql) => {
    this.connection = mysql.createConnection(config);
    return new Promise(async (resolve, reject) => {
        this.connection.query(sql, function (err, result, fields) {
            if (err) {
                return reject(err)
            };
            resolve(result[0]);
        })
    })

}


const close = () => {
    return new Promise((resolve, reject) => {
        this.connection.end(err => {
            if (err)
                return reject(err);
            resolve();
        });
    });
}

const check = (word) => {
    try {
        let flag = false
        const blacklist = ['\\', '\^', ')','(', '\"', '\'', '*', ')', ' '];
        blacklist.forEach(ele => {
            if (word.indexOf(ele) != -1) {
                flag = true
                return false;
            }
        })
        if (flag == true) {
            return false;
        }
        return word;
    }
    catch (error) {
        console.log(error);
    }

}

module.exports = {
    select,
    close,
    check
}
