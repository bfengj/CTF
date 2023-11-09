import tarfile
from flask import Flask, render_template, request, redirect
from hashlib import md5
import yaml
import os
import re


app = Flask(__name__)

def waf(s):
    flag = True
    blacklist = ['bytes','eval','map','frozenset','popen','tuple','exec','\\','object','listitems','subprocess','object','apply']
    for no in blacklist:
        if no.lower() in str(s).lower():
            flag= False
            print(no)
            break
    return flag
def extractFile(filepath, type):

    extractdir = filepath.split('.')[0]
    if not os.path.exists(extractdir):
        os.makedirs(extractdir)


    if type == 'tar':
        tf = tarfile.TarFile(filepath)
        tf.extractall(extractdir)
        return tf.getnames()

@app.route('/', methods=['GET'])
def main():
        fn = 'uploads/' + md5().hexdigest()
        if not os.path.exists(fn):
            os.makedirs(fn)
        return render_template('index.html')


@app.route('/upload', methods=['GET', 'POST'])
def upload():

    if request.method == 'GET':
        return redirect('/')

    if request.method == 'POST':
        upFile = request.files['file']
        print(upFile)
        if re.search(r"\.\.|/", upFile.filename, re.M|re.I) != None:
            return "<script>alert('Hacker!');window.location.href='/upload'</script>"

        savePath = f"uploads/{upFile.filename}"
        print(savePath)
        upFile.save(savePath)

        if tarfile.is_tarfile(savePath):
            zipDatas = extractFile(savePath, 'tar')
            return render_template('result.html', path=savePath, files=zipDatas)
        else:
            return f"<script>alert('{upFile.filename} upload successfully');history.back(-1);</script>"


@app.route('/src', methods=['GET'])
def src():
    if request.args:
        username = request.args.get('username')
        with open(f'config/{username}.yaml', 'rb') as f:
            Config = yaml.load(f.read())
            return render_template('admin.html', username="admin", message="success")
    else:
        return render_template('index.html')


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)