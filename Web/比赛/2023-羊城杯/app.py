import uuid
from flask import *
from werkzeug.utils import *
app = Flask(__name__)
app.config['SECRET_KEY'] =str(uuid.uuid4()).replace("-","*")+"Boogipopisweak"
@app.route('/')
def index():
    name=request.args.get("name","name")
    m1sery=[request.args.get("m1sery","Doctor.Boogipop")]
    if(session.get("name")=="Dr.Boog1pop"):
        blacklist=re.findall("/ba|sh|\\\\|\[|]|#|system|'|\"/", name, re.IGNORECASE)
        if blacklist:
            return "bad hacker no way"
        exec(f'for [{name}] in [{m1sery}]:print("strange?")')
    else:
        session['name'] = "Doctor"
    return render_template("index.html",name=session.get("name"))
@app.route('/read')
def read():
        file = request.args.get('file')
        fileblacklist=re.findall("/flag|fl|ag/",file, re.IGNORECASE)
        if fileblacklist:
            return "bad hacker!"
        start=request.args.get("start","0")
        end=request.args.get("end","0")
        if start=="0" and end=="0":
            return open(file,"rb").read()
        else:
            start,end=int(start),int(end)
            f=open(file,"rb")
            f.seek(start)
            data=f.read(end)
            return data
@app.route("/<path:path>")
def render_page(path):
    print(os.path.pardir)
    print(path)
    if not os.path.exists("templates/" + path):
        return "not found", 404
    return render_template(path)
if __name__=='__main__':
    app.run(
        debug=False,
        host="0.0.0.0"
    )
    print(app.config['SECRET_KEY'])
