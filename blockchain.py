import time
from kafka import KafkaProducer, KafkaConsumer
from pymongo import MongoClient
import json

client = MongoClient('localhost:27017')
if not client.blockchain:
	db = client.blockchain
	db = db.unverified 

class Blockchain(object):

	def __init__(self, **kwargs):
	

	def unverified_to_mongo(self):
		consumer = KafkaConsumer('unverified_queue')
		for msg in consumer:
			 _data = json.loads(msg)
			 _data.update({'verification_count': 0})
			 db.unverified.insert_one(_data)

	def client_to_unverifed_queue(self, message):
		producer = KafkaProducer(bootstrap_servers='localhost:9092')
		producer.send('unverifed_queue', message)
