from kafka import KafkaProducer, KafkaConsumer
from pymongo import MongoClient
import json, time
from ecdsa import VerifyingKey, SECP256k1
import hashlib
from cast import *
import logging

client = MongoClient('localhost', 27017)

db  = client.blockchain


def verification(data):
    json_data = json.loads(data)
    message = json_data['Message']
    public_key = json_data['public_key']
    public_key = public_key[2:]
    signature = json_data['signature']
    public_key_bytes = bytes.fromhex(public_key)
    message_encode = message.encode('utf-8')
    vk = VerifyingKey.from_string(public_key_bytes,curve=SECP256k1)
    ans=vk.verify(bytes.fromhex(signature),message_encode,hashfunc=hashlib.sha256)
    return ans


def insert_to_mining(data):
    _row = db.mining.find({'public_key': data['public_key']})
    index = db.mining.count()
    if _row.count() <= 0:
        _mining_transaction = {}
        _mining_transaction['Candidate'] = data['Candidate']
        _mining_transaction['Constituency'] = data['Constituency']
        _mining_transaction['Party'] = data['Party']
        _mining_transaction['public_key'] = data['public_key']
        _mining_transaction['index'] = index
        db.mining.insert(_mining_transaction)





while True:
    consumer = KafkaConsumer('unverified_queue')
    for message in consumer:
        #logging.debug("unverified_queue consumer received : {}".format(message))
        print(message)
        _data = message.value
        _encoded_data = (_data).decode('utf-8')
        _input = json.loads(_encoded_data)
        _search = _input['public_key']
        _search = str(_search)
        _row = db.unverified.find({'public_key': _search})
        for row in _row:
            verified = verification(json.dumps(_input))
            if verified:
                broadcast(json.dumps(_input))
                if not row.get('Message') :
                    del _input['signature']
                    _input['Verification_count'] = 1
                    _input['locked'] = 'True'
                    db.unverified.update_one({'public_key': _search}, {'$set' : _input })
                    time.sleep(3)
                    #checking locked to check that whether the new transaction is from the same user or the node
                elif not row.get('locked'):
                    count = row['Verification_count']
                    db.unverified.update_one(row, {'$set' : {'Verification_count': count+1 }})
                    if count+1 >= 2:
                        insert_to_mining(row)
                else:
                    logging.warning("User already voted!!")
            else:
                logging.warning("Transaction not Verified!!")
