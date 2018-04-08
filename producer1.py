
from kafka import KafkaProducer
from kafka.errors import KafkaError

producer = KafkaProducer(bootstrap_servers=['localhost:9092'])
topic = "unverified_queue"

producer.send(topic, b'test message')

