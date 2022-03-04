import re
import os
import json
import base64
import hashlib
import pymysql
from flask import Flask, render_template, url_for, request, redirect, session

app = Flask(__name__)
app.config["SECRET_KEY"] = os.environ.get('SECRET_KEY')

conn = pymysql.connect(
    host = '127.0.0.1',
    user = 'ctfer',
    password = 'ctfer',
    database = 'ctf',
    charset = 'utf8'
)

cursor = conn.cursor()


def filter(str):
    blacklist = r"select|union|table|'|\"|\*|\\|#|order|information| |column|and|or|\||&|update|value|concat|from|where|,|" \
                r"database|insert|chr|mid|ascii|group|substr|left|exec|if|sleep|\(|\)|\.|like|regexp|join|truncate|outfile|" \
                r"lpad|rpad|-|mysql|limit"

    if re.search(blacklist, str, re.M|re.I) != None:
        return True


def isUserExist(username):
    sql = "select * from users where username = '%s'" % username
    try:
        cursor.execute(sql)
        results = cursor.fetchall()
        if results:
            return True
        else:
            return False
    except:
        pass


def checkUser(username, password):
    md5_password = hashlib.md5(password.encode(encoding='UTF-8')).hexdigest()
    sql = "select * from users where username = '%s' and password = '%s'" % (username, md5_password)
    try:
        cursor.execute(sql)
        results = cursor.fetchall()
        if results:
            if results[0][1] == username and results[0][2] == md5_password:
                return True
            else:
                return False
        else:
            return False
    except:
        pass


def createUser(username, password):
    md5_password = hashlib.md5(password.encode(encoding='UTF-8')).hexdigest()
    profiles = "Ordinary user, you only have ordinary authority"
    sql = "insert into users (username, password, profiles) values ('%s', '%s', '%s')" % (username, md5_password, profiles)
    try:
        cursor.execute(sql)
        conn.commit()
        return True
    except:
        conn.rollback()

    conn.close()


@app.route('/')
def index():
    if not session.get('islogin'):
        return redirect('/login')
    else:
        return redirect('/home')


@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'GET':
        return render_template("login.html")

    if request.method == 'POST':
        username = request.form.get('username')
        password = request.form.get('password')

        if filter(username) or filter(password):
            return "<script>alert('Illegal username or password');window.location.href='/login'</script>"

        if checkUser(username, password) == True:
            session['user'] = request.form.get('username')
            session['islogin'] = True
            return redirect('/home')
        else:
            return "<script>alert('Login Failed!');window.location.href='/login'</script>"


@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'GET':
        return render_template("register.html")

    if request.method == 'POST':
        username = request.form.get('username')
        password = request.form.get('password')

        if filter(username) or filter(password):
            return "<script>alert('Illegal username or password');window.location.href='/register'</script>"

        if isUserExist(username) == True:
            return "<script>alert('User already exists');window.location.href='/register'</script>"

        if createUser(username, password) == True:
            return redirect('/login')
        else:
            return "<script>alert('Register Failed!');window.location.href='/register'</script>"


@app.route('/home')
def home():
    profiles = ''
    images_datas = ''

    if not session.get('islogin'):
        return redirect('/login')
    else:
        username = session.get('user')
        image = session.get('pic')
        if image:
            image_data = open('./static/images/' + image, 'rb').read()
            images_datas = f"data:image/png;base64,{base64.b64encode(image_data).decode()}"

        blacklist = r"union|\"|\\|#|order| |and|&|update|value|,|insert|chr|mid|ascii|substr|left|exec|if|sleep|join|truncate|outfile|lpad|rpad|-|mysql|limit"

        if re.search(blacklist, username, re.M|re.I) != None:
            return "<script>alert('Hacker!');window.location.href='/login'</script>"

        sql = "select profiles from users where username = '%s'" % username
        try:
            cursor.execute(sql)
            results = cursor.fetchall()
            if results:
                profiles = results[0][0]
                session['profiles'] = profiles
            else:
                profiles = "Who are you? Why is there no information about you?"
        except:
            pass

        return render_template("home.html", user=username, profiles=profiles, images=images_datas)


@app.route('/images', methods=['POST'])
def images():
    if not session.get('islogin'):
        return redirect('/login')
    else:
        datas = request.get_data()
        json_datas = json.loads(datas)
        image = json_datas['pic']
        
        blacklist = r"cmdline|app|flag|start|cwd|bin|boot|fd|dev|home|lib|lib32|lib64|libx32|media|mnt|opt|root|run|sbin|srv|sys|tmp|usr|var"

        if re.search(blacklist, image, re.M|re.I) != None:
            return "<script>alert('Hacker!');window.location.href='/'</script>"
            
        session['pic'] = image
        image_data = open('./static/images/' + image, 'rb').read()
        images_datas = f"data:image/png;base64,{base64.b64encode(image_data).decode()}"

    return session['pic']

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8888)