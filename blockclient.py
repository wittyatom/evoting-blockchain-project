

import socket
host = '172.20.10.15'
port = 54321

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
s.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
s.bind((host, port))

import threading, logging, time
import multiprocessing

from kafka import KafkaConsumer, KafkaProducer


class Producer(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.stop_event = threading.Event()

    def stop(self):
        self.stop_event.set()

    def run(self):
    	while True:
            try:
                message = s.recv(15000)
                print (message)
            except KeyboardInterrupt:
                break
            else:
                producer = KafkaProducer(bootstrap_servers='localhost:9092')
                producer.send('blockchain_unverified_queue', message)
                time.sleep(1)
                producer.close()


def main():
    tasks = [
        Producer(),

    ]

    for t in tasks:
        t.start()

if __name__ == "__main__":
    main()
