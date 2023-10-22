import serial, csv, asyncio, random, string, os
from serial.tools import list_ports
import time

redisHost = 'redis'
redisPort = 6379
redisQueueName = 'python'

def main():
    initializeSerialBus()
#     initRedis()

    while True and ser:
        pass

def initializeSerialBus():
    serialBus = False
    ports = list_ports.comports()

    for port, desc, hwid in sorted(ports):
        try:
            serialBus = serial.Serial(
                port=port,
                baudrate=9600,
                parity=serial.PARITY_NONE,
                stopbits=serial.STOPBITS_ONE,
                bytesize=serial.EIGHTBITS,
            )
            print("Connected: {}: {} [{}]".format(port, desc, hwid))
            break
        except:
            print("Unavailable: {}: {} [{}]".format(port, desc, hwid))
            pass

    if not serialBus:
        print("Serial Port Not Found Retrying...")
        time.sleep(10)
        initializeSerialBus()

if __name__ == "__main__":
    main()
