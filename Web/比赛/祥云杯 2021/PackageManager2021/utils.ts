import * as crypto from "crypto";
import { hashSync, genSaltSync } from 'bcrypt';
import { Request, Response, NextFunction } from 'express';

const md5 = (input: string) => {
  return crypto.createHash('md5').update(input).digest('hex');
}

const checkmd5Regex = (token: string) => {
  return /([a-f\d]{32}|[A-F\d]{32})/.exec(token);
}

const genPackageId = (userId: string) => {
  return md5(hashSync(userId, genSaltSync()));
}

const checkLogin = (req: Request, res: Response, next: NextFunction) => {
  if (!req.session.userId) {
    return res.redirect('/login')
  }
  next()
}

const checkAuth = (req: Request, res: Response, next: NextFunction) => {
  if (!req.session.AccessGranted) {
    return res.render("submit", { error: 'Only auth user can submit packages' })
  }
  next()
}

export {
  md5,
  genPackageId,
  checkmd5Regex,
  checkLogin,
  checkAuth
}

