import os
import time
from pathlib import Path
from lib.codec import GSM
import threading

import serial
import requests


class smsServer:
    JOBS_PATH = "jobs"

    SERIAL_BUS = None
    SERIAL_PORT = "COM10"
    SERIAL_BAUDRATE = 230400
    SERIAL_PARITY_BITS = serial.PARITY_NONE
    SERIAL_STOP_BITS = serial.STOPBITS_ONE
    SERIAL_BYTESIZE = serial.EIGHTBITS
    SERIAL_TIMEOUT = 10

    CURRENT_REPLY = None

    TIMEOUT_CONCLUDE_UNRESPONSIVE = 20  # IN SECS

    LAST_STOP = 0

    MODEM_RESPONSIVE = True

    READER_THREAD = None

    RESTARTER_BUS = None
    RESTARTER_PORT = "COM9"
    RESTARTER_BAUDRATE = 9600

    SMS_MODE = 0

    def __init__(self):
        self.begin_app_init()
        t = threading.Thread(target=self.initialize_restarter_serial_bus())
        t.start()

    def begin_app_init(self):
        self.initialize_serial_bus()
        self.READER_THREAD = threading.Thread(target=self.initialize_serial_continous_reader)
        self.READER_THREAD.start()
        self.send_at_command(command='AT')

        # set sms mode
        res = self.send_at_command(command='AT+CMGF=0')

        if res == 'ERROR':
            res = self.send_at_command(command='AT+CMGF=1')

        if res == 'ERROR':
            print('Unable to initialize sms mode... exiting app..')
            exit(0)
        else:
            self.SMS_MODE = 1

        print('Started with sms mode = {}'.format(self.SMS_MODE))

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
                webhook_url = myvars['WEBHOOK']
                t = threading.Thread(target=self.send_to_webhook(url=webhook_url, data=data))
                t.start()
                os.remove(path)

    def send_to_webhook(self, url, data):
        try:
            requests.post(url, json={
                'data': data
            })
        except:
            pass

    def send_sms(self, params):
        result = 'Not Sent'
        if self.SMS_MODE == 0:
            rep = self.send_at_command(
                command='AT+CSCA={}'.format(params['PHONE_NUMBER'])
            )
            ln = len(params['MESSAGE'])
            rep = self.send_at_command('AT+CMGS={}'.format(ln))
            if rep == '>':
                result = self.send_at_command(
                    command=GSM.encode(params['MESSAGE']),
                    terminator=bytes([26])
                )
        elif self.SMS_MODE == 1:
            rep = self.send_at_command(
                command='AT+CMGS={}'.format(params['PHONE_NUMBER'])
            )
            if rep == '>':
                result = self.send_at_command(
                    command=params['MESSAGE'].replace(' ', '_'),
                    terminator=bytes([26])
                )

        return result

    def send_at_command(self, command, terminator=b'\r'):
        print(command)
        self.CURRENT_REPLY = None
        self.SERIAL_BUS.write(command.encode() + terminator)
        while self.CURRENT_REPLY is None and self.MODEM_RESPONSIVE:
            self.LAST_STOP += 0.1
            if self.LAST_STOP >= self.TIMEOUT_CONCLUDE_UNRESPONSIVE:
                self.initialize_restart_modem()
                return ''
            time.sleep(0.1)
        self.LAST_STOP = 0
        reply = self.CURRENT_REPLY
        self.CURRENT_REPLY = None
        print(reply)
        return reply

    def initialize_restart_modem(self):
        print('Modem Unresponsive, shutting down...')
        self.MODEM_RESPONSIVE = False
        self.LAST_STOP = 0
        self.SERIAL_BUS.close()
        self.SERIAL_BUS = None
        self.begin_app_init()
        if self.RESTARTER_BUS is not None:
            self.RESTARTER_BUS.write(b'RSTD')

    def initialize_serial_continous_reader(self):
        try:
            print('Reader Started')
            while self.MODEM_RESPONSIVE:
                if self.SERIAL_BUS.in_waiting > 0:
                    self.CURRENT_REPLY = None
                    self.CURRENT_REPLY = self.SERIAL_BUS.readline().decode().strip()

            print('Reader Stopped')
        except:
            print('Reader encountered an error')
            self.initialize_restart_modem()

    def initialize_restarter_serial_bus(self):
        try:
            self.RESTARTER_BUS = serial.Serial(
                port=self.RESTARTER_PORT,  # port
                baudrate=self.RESTARTER_BAUDRATE,
                parity=self.SERIAL_PARITY_BITS,
                stopbits=self.SERIAL_STOP_BITS,
                bytesize=self.SERIAL_BYTESIZE,
            )

            if self.RESTARTER_BUS.isOpen() == False:
                self.RESTARTER_BUS.open()

            self.RESTARTER_BUS.write('ats')
            print("Connected to restarter device")
        except:
            time.sleep(10)
            self.initialize_restarter_serial_bus()

    def initialize_serial_bus(self):
        try:
            self.SERIAL_BUS = serial.Serial(
                port=self.SERIAL_PORT,  # port
                baudrate=self.SERIAL_BAUDRATE,
                parity=self.SERIAL_PARITY_BITS,
                stopbits=self.SERIAL_STOP_BITS,
                bytesize=self.SERIAL_BYTESIZE,
                timeout=self.SERIAL_TIMEOUT,
                xonxoff=True,
                rtscts=False,
                dsrdtr=False,
            )

            if self.SERIAL_BUS.isOpen() == False:
                self.SERIAL_BUS.open()

            self.MODEM_RESPONSIVE = True
            print("Connected to serial device")
        except:
            print("Serial Port Unavailable Retrying...")
            time.sleep(10)
            self.initialize_serial_bus()


if __name__ == "__main__":
    smsServer()
