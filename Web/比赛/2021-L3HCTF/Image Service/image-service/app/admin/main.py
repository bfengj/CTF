import requests
import time
import os

os.chdir(os.path.dirname(os.path.abspath(__file__)))

username = 'admin'
password = os.environ['ADMIN_PASSWORD']

def alert(message):
    print(f'!!!!!!{message}!!!!!!')
    exit(1)

sess = requests.Session()
while True:
    try:
        r = sess.post('http://localhost:8000/user/login', data={"username": username, "password": password})
        if r.status_code in [200, 403]:
            break
    except Exception as e:
        print("Server not started")
    time.sleep(1)

def admin_bot():
    r = sess.post('http://localhost:8000/user/register', data={"username": username, "password": password})
    r = sess.post('http://localhost:8000/user/login', data={"username": username, "password": password})
    assert(r.status_code == 200)

    r = sess.get('http://localhost:8000/image/list')
    assert(r.status_code == 200)
    if r.json():
        print("Admin already registered")
        exit(0)

    r = sess.post('http://localhost:8000/image/upload', files={"image": open("./flag1.png", "rb")})
    assert(r.status_code == 200)
    r = sess.get('http://localhost:8000/image/list')
    assert(r.status_code == 200)
    flag1 = r.json()[0]['uuid']
    r = sess.post('http://localhost:8000/share/new', data={"uuid": flag1, "public": 1})
    assert(r.status_code == 200)
    print(f"flag1: {r.text}")

    r = sess.post('http://localhost:8000/image/upload', files={"image": open("./flag2.png", "rb")})
    assert(r.status_code == 200)
    r = sess.get('http://localhost:8000/image/list')
    assert(r.status_code == 200)
    flag2 = r.json()[0]['uuid']
    r = sess.post('http://localhost:8000/share/new', data={"uuid": flag2, "y1": 200, "x1": 200, "text": "secret", "textsize": 50, "blur": 20, "public": 1})
    assert(r.status_code == 200)
    print(f"flag2: {r.text}")

try:
    print("Registering admin")
    admin_bot()
    print("Admin registered")
except Exception as e:
    from traceback import print_exc
    print_exc()
    alert(f'Admin bot failed: {e}')

