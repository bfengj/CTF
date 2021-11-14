const { Sequelize, Model, DataTypes } = require('sequelize');

class User extends Model { }
class Token extends Model { }

const sequelize = new Sequelize('mysql://root:root@localhost/crawler');

User.init({
    userId: { type: DataTypes.INTEGER, autoIncrement: true, primaryKey: true },
    username: { type: DataTypes.STRING(126), allowNull: false, primaryKey: true },
    password: { type: DataTypes.STRING(126), allowNull: false },
    affiliation: { type: DataTypes.STRING(126) },
    age: { type: DataTypes.INTEGER },
    bucket: { type: DataTypes.STRING(126) },
    personalBucket: { type: DataTypes.STRING(126) },
}, { sequelize, modelName: 'user' });


Token.init({
    token: { type: Sequelize.STRING(126), primaryKey: true },
    userId: Sequelize.INTEGER,
    valid: Sequelize.BOOLEAN,
}, { sequelize, modelName: 'token' });


module.exports = {
    User,
    Token,
    sequelize
}
