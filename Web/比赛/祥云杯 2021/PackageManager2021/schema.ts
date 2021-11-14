import *  as mongoose from "mongoose"
import * as dotenv from "dotenv";

dotenv.config();
const uri: string =  process.env.MONGO_URI


mongoose.connect(uri, { useUnifiedTopology: true, useNewUrlParser: true, useCreateIndex: true }, (err: any) => {
    if (err) {
        console.log(err.message);
    } else {
        console.log("Successfully Connected!");
    }
})

export interface IPackage extends mongoose.Document {
    user_id: string;
    pack_id: string;
    name: string;
    description: string;
    version: string;
}

export interface IUser extends mongoose.Document {
    username: string;
    password: string;
    isAdmin: boolean;
}

export interface IReport extends mongoose.Document {
    pack_id: string;
}


export const PackageSchema = new mongoose.Schema({
    user_id: { type: String, required: true },
    pack_id: { type: String, required: true },
    name: { type: String, required: true },
    description: { type: String, required: true },
    version: { type: String, required: true }
});
PackageSchema.index({ "name": 1 }, { unique: true })
PackageSchema.index({ "pack_id": 1 }, { unique: true })

export const UserSchema = new mongoose.Schema({
    username: { type: String, required: true },
    password: { type: String, required: true },
    isAdmin: { type: Boolean, required: true }
});
UserSchema.index({ "username": 1 }, { unique: true })

export const ReportSchema = new mongoose.Schema({
    pack_id: { type: String, required: true }
});

export const Package = mongoose.model<IPackage>("Package", PackageSchema);
export const User = mongoose.model<IUser>("User", UserSchema);
export const Report = mongoose.model<IReport>("Report", ReportSchema);
