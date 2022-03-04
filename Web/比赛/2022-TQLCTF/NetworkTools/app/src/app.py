from flask import Flask, request, send_from_directory,session
from flask_session import Session
from io import BytesIO
import re
import os
import ftplib
from hashlib import md5

app = Flask(__name__)
app.config['SECRET_KEY'] = os.urandom(32)
app.config['SESSION_TYPE'] = 'filesystem'  
sess = Session()
sess.init_app(app)

def exec_command(cmd, addr):
    result = ''
    if re.match(r'^[a-zA-Z0-9.:-]+$', addr) != None:
        with os.popen(cmd % (addr)) as readObj:
            result = readObj.read()
    else:
        result = 'Invalid Address!'
    return result

@app.route("/")
def index():
    if not session.get('token'):
        token = md5(os.urandom(32)).hexdigest()[:8]
        session['token'] = token
    return send_from_directory('', 'index.html')

@app.route("/ping", methods=['POST'])
def ping():
    addr = request.form.get('addr', '')
    if addr == '':
        return 'Parameter "addr" Empty!'
    return exec_command("ping -c 3 -W 1 %s 2>&1", addr)

@app.route("/traceroute", methods=['POST'])
def traceroute():
    addr = request.form.get('addr', '')
    if addr == '':
        return 'Parameter "addr" Empty!'
    return exec_command("traceroute -q 1 -w 1 -n %s 2>&1", addr)

@app.route("/ftpcheck")
def ftpcheck():
    if not session.get('token'):
        return redirect("/")
    domain = session.get('token') + ".ftp.testsweb.xyz"
    file = 'robots.txt'
    fp = BytesIO()
    try:
        with ftplib.FTP(domain) as ftp:
            ftp.login("admin","admin")
            ftp.retrbinary('RETR ' + file, fp.write)
    except ftplib.all_errors as e:
        return 'FTP {} Check Error: {}'.format(domain,str(e))
    fp.seek(0)
    try:
        with ftplib.FTP(domain) as ftp:
            ftp.login("admin","admin")
            ftp.storbinary('STOR ' + file, fp)
    except ftplib.all_errors as e:
        return 'FTP {} Check Error: {}'.format(domain,str(e))
    fp.close()
    return 'FTP {} Check Success.'.format(domain)

@app.route("/shellcheck", methods=['POST'])
def shellcheck():
    if request.remote_addr != '127.0.0.1':
        return 'Localhost only'
    shell = request.form.get('shell', '')
    if shell == '':
        return 'Parameter "shell" Empty!'
    return str(os.system(shell))

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=8080)
