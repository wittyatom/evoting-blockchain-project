from flask import Flask, request, render_template
from flask_cors import CORS, cross_origin
from xml.etree import ElementTree                     
import re
import paramiko
from contextlib import contextmanager
import subprocess
@cross_origin(origin='*')
app = Flask(__name__)
app.debug = True
@app.route("/aadhar", methods=['GET', 'POST', 'OPTIONS'])
@cross_origin(origin='*')








def aadhar():

	if request.method == "POST":
		data=request.values.get("aadhar")
		return "aadhar="+data
		
	



			
if __name__ == '__main__':                            
   app.run(host='localhost',port=13456) 