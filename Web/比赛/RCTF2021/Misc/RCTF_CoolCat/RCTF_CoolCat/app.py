from flask import Flask, render_template, request, send_from_directory,redirect
import hashlib
import random
import os
from PIL import Image
import base64


def ACM(img, p, q, m):
    counter = 0

    if img.mode == "P":
        img = img.convert("RGB")

    assert img.size[0] == img.size[1]

    while counter < m:
        dim = width, height = img.size

        with Image.new(img.mode, dim) as canvas:
            for x in range(width):
                for y in range(height):
                    nx = (x + y * p) % width
                    ny = (x * q + y * (p * q + 1)) % height

                    canvas.putpixel((nx, ny), img.getpixel((x, y)))

        img = canvas
        counter += 1

    return canvas


app = Flask(__name__)


@app.route('/getImage', methods=['POST', 'GET'])
def getImage():
    basepath = os.path.dirname(__file__)  # 当前文件所在路径
    ip = request.remote_addr
    filename = hashlib.md5(ip.encode()).hexdigest()
    path = os.path.join(basepath, 'static/images/{}.jpg'.format(filename))
    with Image.open('flag.jpg') as im:
        im = im.resize((600,600))
        ACM(im,66,66,random.randint(1,5)).save(path)
    return redirect('/static/images/{}'.format(filename)+'.jpg')


@app.route('/', methods=['POST', 'GET'])
def welcome():
    return render_template("index.html")

@app.route('/upload', methods=['POST', 'GET'])
def upload():
    if request.method == 'POST':
        f = request.files['file']
        im = Image.open(f)
        ip = request.remote_addr
        filename = hashlib.md5(ip.encode()).hexdigest()
        basepath = os.path.dirname(__file__)
        path = os.path.join(basepath, 'static/images/{}.jpg'.format(filename))
        im = im.resize((600,600))
        ACM(im,66,66,random.randint(1,5)).save(path)
        return redirect('/static/images/{}'.format(filename)+'.jpg')
    return render_template('upload.html')



if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
