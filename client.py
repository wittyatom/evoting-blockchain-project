import threading, logging, time
import multiprocessing

from kafka import KafkaConsumer, KafkaProducer

import socket
host = '192.168.37.135'
port = 12345

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
s.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
s.bind((host, port))


"""
while True:
    try:
	logging.basicConfig(
        format='Transaction received by blockchain network node-client',
        level=logging.INFO
	)
        message = s.recv(8192)
        print "Got data: %s" % repr(message)
    except KeyboardInterrupt:
        break

"""

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
                logging.basicConfig(
        	format='Transaction received by blockchain network node-client',
        	level=logging.INFO
        	)
        	message = s.recv(8192)
		
               #print "Got data: %s" % repr(message)
    	    except KeyboardInterrupt:
       		 break
	
	    else:
		 producer = KafkaProducer(bootstrap_servers='localhost:9092')
		
        #	while not self.stop_event.is_set():
           	 producer.send('unverified_queue', message)
                 time.sleep(1)
		 producer.close()


def main():
    tasks = [
        Producer(),
       # Consumer()
    ]

    for t in tasks:
        t.start()

    #time.sleep(10)

   # for task in tasks:
    #    task.stop()

    #for task in tasks:
     #   task.join()


if __name__ == "__main__":
    logging.basicConfig(
        format='Inserting into "Unverified Queue"',
        level=logging.INFO
        )
    main()

