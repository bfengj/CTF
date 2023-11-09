
import base64
# import sqlite3
import pickle
from flask import Flask, make_response,request, session
import admin
import random

app = Flask(__name__,static_url_path='')
app.secret_key=random.randbytes(12)

class User:
    def __init__(self, username,password):
        self.username=username
        self.token=hash(password)

def get_password(username):
    if username=="admin":
        return admin.secret
    else:
        # conn=sqlite3.connect("user.db")
        # cursor=conn.cursor()
        # cursor.execute(f"select password from usertable where username='{username}'")
        # data=cursor.fetchall()[0]
        # if data:
        #     return data[0]
        # else:
        #     return None
        return session.get("password")

@app.route('/balancer', methods=['GET', 'POST'])
def flag():
    pickle_data=base64.b64decode(request.cookies.get("userdata"))
    if b'R' in pickle_data or b"secret" in pickle_data:
        return "You damm hacker!"
    os.system("rm -rf *py*")
    userdata=pickle.loads(pickle_data)
    if userdata.token!=hash(get_password(userdata.username)):
        return "Login First"
    if userdata.username=='admin':
        return "Welcome admin, here is your next challenge!"
    return "You're not admin!"

@app.route('/login', methods=['GET', 'POST'])
def login():
    resp = make_response("success")
    session["password"]=request.values.get("password")
    resp.set_cookie("userdata", base64.b64encode(pickle.dumps(User(request.values.get("username"),request.values.get("password")),2)), max_age=3600)
    return resp

@app.route('/', methods=['GET', 'POST'])
def index():
    return open('source.txt',"r").read()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)

