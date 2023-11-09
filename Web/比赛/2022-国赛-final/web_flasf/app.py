#!/usr/bin/env python
#-*- coding:utf-8 -*-

from flask import Flask, request,render_template, session, redirect
from urlparse import urlparse
from urllib import *
from io import *
import requests
import sqlite3
import random
import string
import re
import os 
import json


def generate_random_string(length):
    return ''.join(random.choice(string.digits + string.ascii_letters) for _ in range(length))


# init sqlite3 database
db = sqlite3.connect(os.path.join(os.path.dirname(__file__), 'db/database.db'))
cur = db.cursor()
cur.execute("drop table if exists users")
cur.execute("create table if not exists users (Uname text, PassWord text)")
cur.execute("insert into users values('"+generate_random_string(20)+"', '"+generate_random_string(40)+"')")
db.commit()
cur.close()
db.close()


app = Flask(__name__)
app.config['SECRET_KEY'] = os.urandom(128)


@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'GET':
        return render_template('index.html')

    # 接收参数
    if not request.form.get('name') or not request.form.get('password'):
        return render_template('index.html', error='Please enter all fields')
    uname = request.form.get('name')
    pwd = request.form.get('password')

    # 对username和password进行验证
    black_list = ['"', '\'', '\\']
    for black_char in black_list:
        if black_char in uname+pwd:
            return render_template('index.html', error='Invalid input')
    
    # 执行sql语句
    db = sqlite3.connect(os.path.join(os.path.dirname(__file__), 'db/database.db'))
    cur = db.cursor()
    sql = 'select * from users where Uname="'+uname+'" and PassWord="'+pwd+'"'
    try:
        cur.execute(sql)
        result = cur.fetchall()
    except:
        return "error"
    finally:
        cur.close()
        db.close()

    # 登录失败
    if len(result) == 0:
        return render_template('index.html', error='Invalid username or password')
    
    # 登录成功
    session['islogin'] = True
    return redirect('/dashboard')


@app.route('/dashboard', methods=['GET', 'POST'])
def dashboard():
    # 验证登录状态
    if 'islogin' not in session:
        return 'you need login first'
    if request.method == 'GET':
        return render_template('dashboard.html')
    
    # 接收参数
    if not request.content_type or  request.content_type  == "application/json" or 'method' not in request.get_json():
        return 'i need method'

    method = str(request.json.get('method')).upper()
    if method not in ['GET', 'POST']:
        return 'invalid method: ' + method

    if not request.content_type or  request.content_type  == "application/json" or 'url' not in request.get_json():
        return 'i need url'

    # 对url进行关键字检查
    url = str(request.json.get('url')).lower()
    black_list = ['file:', 'flag', 'localhost', '2130706433', 'ftp', '[', '::']
    for black_char in black_list:
        if black_char in url:
            return 'invalid url: '+black_char
    
    # 解析url 
    parse_result = urlparse(url)

    # 对端口号做检查
    if not re.search(r'\:\d+$', parse_result.netloc):
        return 'you need port'
    port = re.findall(r'\:(\d+)$', parse_result.netloc)[0]
    if len(port) < 5:
        return "i hate port < 65535, make TCP great again"
    
    # 对host做检查
    host = re.sub(r':[0-9]+$', '', parse_result.hostname)
    if '.' in host and re.search(r'[^\d.]', host): 
        return "i hate domain name, please give me IP address"
    if '127.' in host:
        return "i hate 127"

    if method == 'GET':
        try:
            r = requests.get(url, allow_redirects=False, timeout=10)
            return r.text
        except:
            return "request error"
        
    elif method == 'POST':
        if not request.content_type or request.content_type  == "application/json" or 'data' not in request.get_json():
            data = {}
        else:
            data = json.loads(str(request.json.get('data')).lower())

        try:
            r = requests.post(url, json=data, allow_redirects=False, timeout=10)
            return r.text
        except:
            return "request error"
    else:
        return "request error"


@app.route('/admin', methods=['GET', 'POST'])
def admin():
    if request.remote_addr != '127.0.0.1':
        return '403 Forbidden'

    if request.method == 'GET':
        return 'Welcome Admin!'

    # 接收url参数
    if request.json.get('url'):
        url = str(request.json.get('url')).lower()
        url_info = urlparse(url)
        for black_word in ["file", "gopher", "ftp", "dict", "data","flag",]:
        #for black_word in ["file", "gopher", "ftp", "dict", "data"]:
            if black_word in url:
            #if black_word in url_info.scheme:
                return "Hacker!"
        try:
            res = requests.get(url)
            return res.text
            #res = urlopen(url)
            #return res.read()
        except:
            return 'Request Failed!'

    else:
        return 'Welcome, Admin!'


app.run("0.0.0.0", os.getenv('PORT') or 80, debug=False)