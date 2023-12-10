import os
import time
from pathlib import Path
from lib.codec import GSM
from dotenv import load_dotenv


import re
import threading
import serial
import requests

class smsServer:
    JOBS_PATH = os.getenv('JOBS_PATH')

    SERIAL_BUS = None
    SERIAL_PORT = os.getenv('SERIAL_PORT')
    SERIAL_BAUDRATE = os.getenv('SERIAL_BAUDRATE')
    SERIAL_PARITY_BITS = serial.PARITY_NONE
    SERIAL_STOP_BITS = serial.STOPBITS_ONE
    SERIAL_BYTESIZE = serial.EIGHTBITS
    SERIAL_TIMEOUT = 0.2

    CURRENT_REPLY = None

    TIMEOUT_CONCLUDE_UNRESPONSIVE = 20  # IN SECS

    LAST_STOP = 0

    MODEM_RESPONSIVE = True

    READER_THREAD = None

    RESTARTER_BUS = None
    RESTARTER_PORT = None
    RESTARTER_BAUDRATE = 9600

    SMS_MODE = 0

    def __init__(self):
        load_dotenv()

        self.SERIAL_PORT = os.getenv('SERIAL_PORT')
        self.SERIAL_BAUDRATE = os.getenv('SERIAL_BAUDRATE')
        self.JOBS_PATH = os.getenv('JOBS_PATH')
        self.begin_app_init()

    def begin_app_init(self):
        self.initialize_serial_bus()
        t = threading.Thread(target=self.start_queue())
        t.start()

    def start_queue(self):
        print('Started Queue..')
        while True:
            paths = sorted(Path(self.JOBS_PATH).iterdir(), key=os.path.getmtime)
            for path in paths:
                myvars = {}
                with open(path) as myfile:
                    for line in myfile:
                        name, var = line.partition("=")[::2]
                        myvars[name.strip()] = var.rstrip()
                print("file - " + repr(path))
                data = self.send_sms(params=myvars)
                if data == 'OK':
                    webhook_url = myvars['WEBHOOK']
                    t = threading.Thread(target=self.send_to_webhook(url=webhook_url, data=data))
                    t.start()
                    os.remove(path)
                else:
                    print("ERROR: {}", format(str(os.path.basename(path))))

                # time.sleep(0.5)

    def send_to_webhook(self, url, data):
        try:
            requests.post(url, json={
                'data': data
            })
        except:
            pass

    def send_sms(self, params):
        command = 'AT+SMSEND="{}",3,"{}"'.format(params['PHONE_NUMBER'], params['MESSAGE'])
        command = bytes(command, 'ascii')
        command += b'\x0d\x0a'
        self.SERIAL_BUS.write(command)
        response = self.SERIAL_BUS.readall()
        result = re.search('\r\n\r\n(.*)\r\n', response.decode('ascii'))
        print(response)
        if result is not None:
            return str(result.group(1))

        return 'ERROR'

    def initialize_serial_bus(self):
        print(self.SERIAL_PORT)
        print(self.SERIAL_BAUDRATE)
        try:
            self.SERIAL_BUS = serial.Serial(
                port=self.SERIAL_PORT,
                baudrate=self.SERIAL_BAUDRATE,
                parity=self.SERIAL_PARITY_BITS,
                stopbits=self.SERIAL_STOP_BITS,
                bytesize=self.SERIAL_BYTESIZE,
                xonxoff=True,
                timeout=self.SERIAL_TIMEOUT
            )

            if self.SERIAL_BUS.isOpen() == False:
                self.SERIAL_BUS.open()

            self.MODEM_RESPONSIVE = True

            print("Connected to serial device")
        except:
            print("Serial Port Unavailable Retrying...")
            time.sleep(2)
            self.initialize_serial_bus()


if __name__ == "__main__":
    smsServer()
