from kafka import KafkaProducer, KafkaConsumer
from pymongo import MongoClient
import json

client = MongoClient('localhost', 27017)

db  = client.blockchain


while True:
	consumer = KafkaConsumer('unverified_queue')
	for msg in consumer:
		_data = msg[value]
		_input = json.loads(_data)
		_input.update({'Verification_count': 0})
		db.unverified.insert_one(_input)

	




