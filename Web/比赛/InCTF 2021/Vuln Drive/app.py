import os
import requests
import socket
import random
from urllib.parse import unquote,urlparse
from flask import Flask, render_template, request,url_for,redirect,send_file,session
from werkzeug.utils import secure_filename
from form import Login
import uuid
from werkzeug.exceptions import RequestEntityTooLarge

app = Flask(__name__)

upload_folder=os.getenv('UPLOAD_FOLDER')
app.config['UPLOAD_FOLDER']=upload_folder
app.config['SECRET_KEY']=os.getenv('SECRET_KEY')
app.secret_key = os.getenv('KEY_SECRET')

if not os.path.isdir(upload_folder):
    os.mkdir(upload_folder)


def auth():
    if session.get('user') is None:
        return True
    elif not os.path.isdir(os.path.join(app.config['UPLOAD_FOLDER'],str(session['uid']))):
        return True
    else:
        return False



def url_validate(url):
    blacklist = ["::1", "::"]
    for i in blacklist:
        if(i in url):
            return "NO hacking this time ({- _ -})"
    y = urlparse(url)
    hostname = y.hostname
    try:
        ip = socket.gethostbyname(hostname)
    except:
        ip = ""
    print(url, hostname,ip)
    ips = ip.split('.')
    if ips[0] in ['127', '0']:
        return "NO hacking this time ({- _ -})"
    else:
        try:
            url = unquote(url)
            r = requests.get(url,allow_redirects = False)
            return r.text
        except:
            print(url, hostname)
            return "cannot get you url :)"




@app.route('/login',methods=['GET','POST'])
def login():
    form=Login()
    if not session.get('user') is None:
        return redirect('/logout')
    if request.method == 'POST':
        if form.username.data != "" and form.password.data != "":
            username=form.username.data
            uid = uuid.uuid4()
            session['user'] = username
            session['uid'] = uid
            os.mkdir(os.path.join(app.config['UPLOAD_FOLDER'],str(uid)))
            print(f"Created a folder {str(uid)} for user {username}",flush=True)
            return redirect(url_for('home'))
        else:
            err='enter a username and password to login'
            return render_template('login.html',title="login",form=form,err=err)

    return render_template('login.html',title="login",form=form)


@app.route('/logout',methods=['GET'])
def logout():
    if(session.get('user') is None):
        print("Cookies Deleted",flush=True)
    else:
        if os.path.isdir(os.path.join(app.config['UPLOAD_FOLDER'],str(session['uid']))):
            os.system(f"rm -rf {os.path.join(app.config['UPLOAD_FOLDER'],str(session['uid']))}")
        session.pop('user',None)
        session.pop('uid',None)
    return redirect(url_for('login'))


@app.route('/uploader', methods = ['GET', 'POST'])
def upload_file():
    if auth():
        return redirect('/logout')
    if request.method == 'POST':
        try:
            f = request.files['file']
            filename=secure_filename(f.filename)
            print("Trying",flush=True)
            f.save(os.path.join(app.config['UPLOAD_FOLDER'],str(session['uid']), filename))
            return redirect(url_for('home'))
        except:
            print("Excepting",flush=True)
            return render_template("index.html")





@app.route('/return-files')
def return_files_tut():
    if auth():
        return redirect('/logout')
    filename=request.args.get("f")
    if(filename==None):
        return "No filenames provided"
    print(filename)
    if '..' in filename:
        return "No hack"
    file_path = os.path.join(app.config['UPLOAD_FOLDER'],str(session['uid']),filename)
    if(not os.path.isfile(file_path)):
        return "No such file exists"
    return send_file(file_path, as_attachment=True, attachment_filename=filename)

@app.route("/dev_test",methods =["GET", "POST"])
def dev_test():
    if auth():
        return redirect('/logout')
    if request.method=="POST" and request.form.get("url")!="":
        url=request.form.get("url")
        return url_validate(url)
    return render_template("dev.html")

@app.route('/')
def home():
    if auth():
        return redirect('/logout')
    files=os.listdir(os.path.join(app.config['UPLOAD_FOLDER'],str(session['uid'])))
    if not files:
        return render_template('index.html',username=session["user"],error="You got nothing over here.")

    y=[]
    for x in files:
        b="".join([ random.choice("0123456789abcdef") for i in range(6)] )
        y.append([x,b])
    return render_template('index.html',files=y,username=session["user"])

@app.route('/source')
def source():
    return render_template('source.html',file=open(__file__).read())

if __name__ == "__main__":
    app.run(host='0.0.0.0',debug=False)