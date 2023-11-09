import os
import re
import yaml
import time
import socket
import subprocess
from hashlib import md5
from flask import Flask, render_template, make_response, send_file, request, redirect, session

app = Flask(__name__)
app.config['SECRET_KEY'] = socket.gethostname()

def response(content, status):
    resp = make_response(content, status)
    return resp


@app.before_request
def is_login():
    if request.path == "/upload":
        if session.get('user') != "Administrator":
            return f"<script>alert('Access Denied');window.location.href='/'</script>"
        else:
            return None


@app.route('/', methods=['GET'])
def main():
    if not session.get('user'):
        session['user'] = 'Guest'
    try:
        return render_template('index.html')
    except:
        return response("Not Found.", 404)
    finally:
        try:
            updir = 'static/uploads/' + md5(request.remote_addr.encode()).hexdigest()
            if not session.get('updir'):
                session['updir'] = updir
            if not os.path.exists(updir):
                os.makedirs(updir)
        except:
            return response('Internal Server Error.', 500)


@app.route('/<path:file>', methods=['GET'])
def download(file):
    if session.get('updir'):
        basedir = session.get('updir')
        try:
            path = os.path.join(basedir, file).replace('../', '')
            if os.path.isfile(path):
                return send_file(path)
            else:
                return response("Not Found.", 404)
        except:
            return response("Failed.", 500)


@app.route('/upload', methods=['GET', 'POST'])
def upload():

    if request.method == 'GET':
        return redirect('/')

    if request.method == 'POST':
        uploadFile = request.files['file']
        filename = request.files['file'].filename

        if re.search(r"\.\.|/", filename, re.M|re.I) != None:
            return "<script>alert('Hacker!');window.location.href='/upload'</script>"
        
        filepath = f"{session.get('updir')}/{md5(filename.encode()).hexdigest()}.rar"
        if os.path.exists(filepath):
            return f"<script>alert('The {filename} file has been uploaded');window.location.href='/display?file={filename}'</script>"
        else:
            uploadFile.save(filepath)
        
        extractdir = f"{session.get('updir')}/{filename.split('.')[0]}"
        if not os.path.exists(extractdir):
            os.makedirs(extractdir)

        pStatus = subprocess.Popen(["/usr/bin/unrar", "x", "-o+", filepath, extractdir])
        t_beginning = time.time()  
        seconds_passed = 0
        timeout=60
        while True:  
            if pStatus.poll() is not None:  
                break  
            seconds_passed = time.time() - t_beginning  
            if timeout and seconds_passed > timeout:  
                pStatus.terminate()  
                raise TimeoutError(cmd, timeout)
            time.sleep(0.1)

        rarDatas = {'filename': filename, 'dirs': [], 'files': []}
        
        for dirpath, dirnames, filenames in os.walk(extractdir):
            relative_dirpath = dirpath.split(extractdir)[-1]
            rarDatas['dirs'].append(relative_dirpath)
            for file in filenames:
                rarDatas['files'].append(os.path.join(relative_dirpath, file).split('./')[-1])

        with open(f'fileinfo/{md5(filename.encode()).hexdigest()}.yaml', 'w') as f:
            f.write(yaml.dump(rarDatas))

        return redirect(f'/display?file={filename}')


@app.route('/display', methods=['GET'])
def display():

    filename = request.args.get('file')
    if not filename:
        return response("Not Found.", 404)

    if os.path.exists(f'fileinfo/{md5(filename.encode()).hexdigest()}.yaml'):
        with open(f'fileinfo/{md5(filename.encode()).hexdigest()}.yaml', 'r') as f:
            yamlDatas = f.read()
            if not re.search(r"apply|process|out|system|exec|tuple|flag|\(|\)|\{|\}", yamlDatas, re.M|re.I):
                rarDatas = yaml.load(yamlDatas.strip().strip(b'\x00'.decode()))
                if rarDatas:
                    return render_template('result.html', filename=filename, path=filename.split('.')[0], files=rarDatas['files'])
                else:
                    return response('Internal Server Error.', 500)
            else:
                return response('Forbidden.', 403)
    else:
        return response("Not Found.", 404)


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8888)