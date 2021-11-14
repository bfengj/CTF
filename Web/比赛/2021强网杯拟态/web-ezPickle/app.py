from flask import Flask, request, session, render_template_string, url_for,redirect
import pickle
import io
import sys
import base64
import random
import subprocess
from config import notadmin

app = Flask(__name__)

class RestrictedUnpickler(pickle.Unpickler):
    def find_class(self, module, name):
        if module in ['config'] and "__" not in name:
            return getattr(sys.modules[module], name)
        raise pickle.UnpicklingError("'%s.%s' not allowed" % (module, name))


def restricted_loads(s):
    """Helper function analogous to pickle.loads()."""
    return RestrictedUnpickler(io.BytesIO(s)).load()

@app.route('/')
def index():
    info = request.args.get('name', '')
    if info is not '':
        x = base64.b64decode(info)
        User = restricted_loads(x)
    return render_template_string('Hello')


if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, port=5000)