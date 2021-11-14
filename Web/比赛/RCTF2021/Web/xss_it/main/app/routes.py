# coding=utf-8

from flask import Flask, request, jsonify, Response, render_template

from app import app

@app.route("/")
def index():
    return app.send_static_file('index.html')
