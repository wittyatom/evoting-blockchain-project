import requests
import datetime
import socket
import time

from requests import Session
from requests.auth import HTTPBasicAuth
from suds.client import Client
from suds.transport.http import HttpAuthenticated
import logging
from ecdsa import VerifyingKey,SECP256k1
import hashlib
import json

logging.basicConfig(level=logging.DEBUG)
logging.getLogger('suds.client').setLevel(logging.DEBUG)
logging.getLogger('suds.transport').setLevel(logging.DEBUG)
logging.getLogger('suds.xsd.schema').setLevel(logging.DEBUG)
logging.getLogger('suds.wsdl').setLevel(logging.DEBUG)
from flask import Flask, request, render_template
from flask_cors import CORS, cross_origin
from xml.etree import ElementTree
import re
app = Flask(__name__)
app.debug = True
@app.route("/voting", methods=['GET', 'POST', 'OPTIONS'])
@cross_origin(origin='*')

def voting():
    if request.method == "POST":
         data=request.values.get("bnm")
         print(data)
         verification(data)

def verification(data):
    j=json.loads(data)

    message =j['Message']
    print(message)

    public_key=j['public_key']

    public_key=public_key[2:]
    sig=j['signature']


    x=bytes.fromhex(public_key)

    y=message.encode('utf-8')


    vk = VerifyingKey.from_string(x,curve=SECP256k1)
    ans=vk.verify(bytes.fromhex(sig),y,hashfunc=hashlib.sha256)
    print(ans)
    if ans== True:
        broadcast(data)


def broadcast(data):
    print("main ghusa")
    UDP_IP = "172.20.10.15"
    UDP_PORT = 12345
    MESSAGE = data
    MESSAGE=MESSAGE.encode('utf-8')
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    print(sock)
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
    sock.sendto(MESSAGE, (UDP_IP, UDP_PORT))
    time.sleep(1)





if __name__ == '__main__':
   app.run(host='172.20.10.4',port=23451)
