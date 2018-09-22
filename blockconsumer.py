from kafka import KafkaProducer, KafkaConsumer
from pymongo import MongoClient
import json
from ecdsa import VerifyingKey, SECP256k1
import hashlib
from cast import *
import logging

client = MongoClient('localhost', 27017)

db  = client.blockchain


def verification(data):
    j=json.loads(data)

    message =j['Message']

    public_key=j['public_key']

    public_key=public_key[2:]
    sig=j['signature']


    x=bytes.fromhex(public_key)

    y=message.encode('utf-8')


    vk = VerifyingKey.from_string(x,curve=SECP256k1)
    ans=vk.verify(bytes.fromhex(sig),y,hashfunc=hashlib.sha256)

    return ans




while True:
    consumer = KafkaConsumer('blockchain_unverified_queue')
    for msg in consumer:
        print(msg)
        #logging.debug("unverified_queue consumer received : {}".format(msg))
        _data = msg.value
        _encoded_data = (_data).decode('utf-8')
        _input = json.loads(_encoded_data)
        _search = _input['public_key']
        _search = str(_search)
        _row = db.unverified.find({'public_key': _search})
        for row in _row:
            verified = verification(json.dumps(_input))
            if not row.get('Message'):
                if verified:
                    del _input['signature']
                    _input['Verification_count'] = 1
                    db.unverified.update_one({'public_key': _search}, {'$set' : _input })
                    time.sleep(3)
                else:
                    logging.warning("Transaction not Verified!!")
            else:
                if verified:
                    count = row['Verification_count']
                    db.unverified.update_one(row, {'$set' : {'Verification_count': count+1 }})
                    if count+1 >= 2:
                        res = db.mining.find({'public_key': row['public_key']})
                        index = db.mining.count()
                        if res.count() <= 0:
                            _mining_transaction = {}
                            _mining_transaction['Candidate'] = row['Candidate']
                            _mining_transaction['Party'] = row['Party']
                            _mining_transaction['Constituency'] = row['Constituency']
                            _mining_transaction['public_key'] = row['public_key']
                            _mining_transaction['index'] = index
                            db.mining.insert(_mining_transaction)
                else:
                    logging.warning("Transaction not Verified!!")
