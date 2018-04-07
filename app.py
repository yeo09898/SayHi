from flask import Flask, request, redirect, url_for, render_template, send_file, send_from_directory, session, make_response

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('index.php')

@app.route('/DB.php')
def DB():
    return render_template('classes/DB.php')

@app.route('/create-account.php')
def create_account():
    return render_template('create-account.php')

if __name__ == "__main__":
    app.run(host='127.0.0.1',debug=True) 