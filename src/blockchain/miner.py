from pymongo import MongoClient
import json, time
import logging
import hashlib
import re
import random
import datetime
from cast_block import *

client = MongoClient('localhost',27017)

db = client.blockchain

MAX_LIMIT = 3

def check_mining_pool():
    _rows = db.mining.count()
    if _rows >= 3:
      count = 0
      _data = []
      max_limit = MAX_LIMIT
      _entries = db.mining.find()
      for entry in _entries:
        if count < max_limit:
          del entry['_id']
          del entry['index']
          _data.append(entry)
          count = count+1
      return _data
    return False



class Block(object):
    def __init__(self, index, timestamp,data, previous_hash,current_hash,nonce):
        self.index = index
        self.timestamp = timestamp
        self.data = data
        self.previous_hash = previous_hash
        self.current_hash=current_hash
        self.nonce=nonce

    def calculateHash(self,index,timestamp,previous_hash,nonce,difficulty=1):
        Hashingstring=(str(index)+str(timestamp)+str(previous_hash)+str(nonce)+str(difficulty)).encode('utf-8')
        Hashedvalue=hashlib.sha256()
        Hashedvalue.update(Hashingstring)
        Hashedans=Hashedvalue.hexdigest()
        return Hashedans

    def proofofwork(self,index,timestamp,previous_hash,nonce):
        while True:
            Hash = self.calculateHash(index,timestamp,previous_hash,nonce)
            initial_nonce = nonce
            first_character = Hash[0]
            print(first_character)
            if first_character == '0':
                return [Hash, nonce]
            else:
                nonce = nonce+1
                if nonce >= 1000:
                    nonce = nonce%1000
                if nonce == initial_nonce:
                    print("block could not be created as no matching hash is available")
                    return False

    #@staticmethod
    def generateNextBlock(self,blockData):
         nextIndex = int(self.index) + 1
         nextTimestamp = int(time.time())
         previous_hash = self.current_hash
         nonce = self.rand()
         nextHash = self.proofofwork(nextIndex,nextTimestamp,previous_hash,nonce)
         if nextHash:
             return Block(nextIndex,nextTimestamp,blockData,previous_hash,nextHash[0], nextHash[1])
         else:
             print("block making error")

    def rand(self):
        y= random.randrange(0,1001)
        return y



offset = 0

while True:

    #mining_pool_transactions = check_mining_pool()
    _rows = db.mining.count()
    _data = []

    if _rows >= 3:
        count = 0
        max_limit = MAX_LIMIT
        #leave = db.mining.find({'index': offset})
        #for i in leave:
            #offset = i['index']
        _entries = db.mining.find().skip(offset)
        for entry in _entries:
            if count < max_limit:
                del entry['_id']
                del entry['index']
                _data.append(entry)
                count = count+1
        offset = offset+3

    if _data:
        row = db.chain.find()
        if row.count() <=0:
            previous_block = Block(0, '0', "This is the genesis block :", "Null", "ccc55c8dfa0efe8b309f77a692234f773c02ee41844a5a74a3226d1139803d84", '0')
            db.chain.insert(previous_block.__dict__)

        else:
            last_block_index = row.count()-1
            last_block = db.chain.find({'index' : last_block_index})
            for block in last_block:
                kwargs = {}
                kwargs['index'] = block['index']
                kwargs['nonce'] = block['nonce']
                kwargs['timestamp'] = block['timestamp']
                kwargs['current_hash'] = block['current_hash']
                kwargs['previous_hash'] = block['previous_hash']
                kwargs['data'] = block['data']
            previous_block = Block(kwargs['index'], kwargs['timestamp'], kwargs['data'], kwargs['previous_hash'], kwargs['current_hash'], kwargs['nonce'])

        new_block = previous_block.generateNextBlock(_data)
        broadcast(json.dumps(new_block.__dict__))
        db.chain.insert(new_block.__dict__)
        break
