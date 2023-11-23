from flask import Flask, request, redirect, url_for
import requests

app = Flask(__name__)

black_list = ['bash', 'rm', 'whoami', 'wget', 'dnslog']

@app.route('/', defaults={'path': ''}, methods=['GET', 'POST'])
@app.route('/<path:path>', methods=['GET', 'POST'])
def catch_all(path):
    if request.method == 'POST':
        return "Method Not Allowed"
    elif request.method == 'GET':
        redirect_url = request.args.get('redirect_url')
        if redirect_url:
            for black in black_list:
                if black in redirect_url:
                    return 'Attack!!!', 403
            response = requests.get(redirect_url, verify=False)
            return 'redirect_url:'+redirect_url+'<br/><br/>code:'+str(response.status_code)+'<br/><br/>response:'+response.text

        else:
            return "Not see your redirect_url param"

if __name__ == '__main__':
    app.run(debug=False,host="0.0.0.0", port=5000)
