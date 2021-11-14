
from flask import Flask, request,render_template,request,make_response
import redis
import time
import os
from utils.random import Upper_Lower_string
from main import Requests_On_Steroids
app = Flask(__name__)

# Make a connection of the queue and redis
r = redis.Redis(host='redis', port=6379)
#r.mset({"Croatia": "Zagreb", "Bahamas": "Nassau"})
#print(r.get("Bahamas"))
@app.route("/",methods=['GET','POST'])
def index():
    if request.method == 'POST':
        url = str(request.form.get('url'))
        resp = Requests_On_Steroids(url)
        return resp
    else:   
        resp = make_response(render_template('index.html'))
        if not request.cookies.get('userID'):
            user=Upper_Lower_string(32)
            r.mset({str(user+"_isAdmin"):"false"})
            resp.set_cookie('userID', user)
        else:
            user=request.cookies.get('userID')
            flag=r.get(str(user+"_isAdmin"))
            if flag == b"yes":
                resp.set_cookie('flag',str(os.environ['FLAG']))
            else:
                resp.set_cookie('flag', "NAAAN")
        return resp

if __name__ == "__main__":
    app.run('0.0.0.0')