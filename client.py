import socket

TCP_IP = '192.168.0.167'
TCP_PORT = 9001
BUFFER_SIZE = 1024

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((TCP_IP, TCP_PORT))
#print('receiving data...')
data = s.recv(BUFFER_SIZE)
print s.getsockname()
print('data=%s', (data))
print('Successfully get the file')
s.close()
print('connection closed')

