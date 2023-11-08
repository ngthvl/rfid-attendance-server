import serial

ser = serial.Serial('COM10', 460800, timeout=5)
ser.write(b'AT+CMGF=1\r')
response = ser.readline()
ser.write(b'AT+CMGS="+639457201016"\r')
ser.write(b'This is a test message.\r')
ser.write(bytes([26]))
response = ser.readline()
ser.close()

print(f"The response to the SMS command is: {response}")