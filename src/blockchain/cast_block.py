import time

import socket


def broadcast(data):
    #print("main ghusa")
    UDP_IP = "172.20.10.15"
    UDP_PORT = 45321
    MESSAGE = data
    MESSAGE=MESSAGE.encode('utf-8')
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    print(sock)
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
    sock.sendto(MESSAGE, (UDP_IP, UDP_PORT))
    time.sleep(1)
