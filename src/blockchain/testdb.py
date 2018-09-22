import pymysql
conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='Aadhar database')
cursor = conn.cursor()


import socket, json
host = '172.20.10.15'
port = 45321

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
s.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
s.bind((host, port))



while True:
    message = s.recv(15000)
    _encoded_data = (message).decode('utf-8')
    #previous_block = json.loads(_encoded_data)

    new_data = json.loads(_encoded_data)

    transaction = new_data['data']
    #import pymysql
    #conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='Aadhar database')
    #cursor = conn.cursor()
    for msg in transaction:
        #import pdb
        #pdb.set_trace()
        candidate = msg['Candidate']
        constituency = msg['Constituency']
        party = msg['Party']

        sql = "Select Candidate,Party,Constituency from Central_Ledger where Candidate=%s and Party=%s and Constituency=%s"

        cursor.execute(sql,(candidate, party, constituency))
        results=cursor.fetchone()
        if results:
            sql="Update Central_Ledger set Votes=Votes+1 where Candidate=%sand Party=%s and Constituency=%s"
            cursor.execute(sql,(candidate, party, constituency))
            conn.commit()
            print("updated vote")
        else:
           sql="insert into Central_Ledger values(%s,%s,%s,%s)"
           cursor.execute(sql,(candidate,constituency,party,1))
           print("added a new query")
           conn.commit()

print(results)
conn.close()
