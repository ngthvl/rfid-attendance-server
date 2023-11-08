import asyncio
import os
import time
from pathlib import Path

import serial
import requests

jobs_path = "jobs"
serial_port = "COM10"

def main():
    serial_bus = initialize_serial_bus()

    if serial_bus and initialize_modem(serial_bus) == 'OK':
        print("Done Initializing modem...")
        start_executor(serial_bus)

    print("Unexpected Error Ended.")

def initialize_modem(serial_bus):
    serial_bus.reset_input_buffer()
    serial_bus.reset_output_buffer()

    response = send_at2_command(serial_bus, b'AT')
    print(response)

    ate0 = send_at2_command(serial_bus, b'ATE0')
    print(ate0)

    atcmgf = send_at2_command(serial_bus, b'AT+CMGF=1')
    print(atcmgf)

    return response

def start_executor(serial_bus):
    print("Started .")
    while True:
        paths = sorted(Path(jobs_path).iterdir(), key=os.path.getmtime)
        for path in paths:
            myvars = {}
            with open(path) as myfile:
                for line in myfile:
                    name, var = line.partition("=")[::2]
                    myvars[name.strip()] = var.rstrip()
            print("file - " + repr(path))
            data = serial_send_sms(serial_bus, myvars)
            webhook_url = myvars['WEBHOOK']
            asyncio.run(send_to_webhook(webhook_url, data))
            print("done - " + repr(path))
            os.remove(path)

def serial_send_sms(serial_bus, config):
    if (serial_bus.isOpen() == False):
        serial_bus.open()

    response = send_at2_command(serial_bus, b'AT+CMGS=' + config['PHONE_NUMBER'].encode())

    if response=='>':
        response = send_sms_at2(serial_bus, config['MESSAGE'].encode())

    serial_bus.close()
    return response

def send_at2_command(serial_bus, cmd):
    serial_bus.write(cmd)
    serial_bus.write(bytearray.fromhex('0D'))
    serial_bus.write(bytearray.fromhex('0A'))
    responded = False
    response = ''
    while not responded:
        data = serial_bus.readlines()
        for dt in data:
            stripped = dt.decode().strip()
            if stripped == 'OK' or stripped == 'ERROR' or stripped == '>':
                responded = True
                response = stripped
                pass
        time.sleep(0.1)
    return response

def send_sms_at2(serial_bus, message):
    serial_bus.write(message)
    serial_bus.write(bytes([26]))
    responded = False
    response = ''
    while not responded:
        data = serial_bus.readlines()
        for dt in data:
            stripped = dt.decode().strip()
            print(stripped)
            if stripped != '':
                responded = True
                response = stripped
                pass
            else:
                serial_bus.write(bytes([26]))
        time.sleep(0.1)
    return response

async def send_to_webhook(url, data):
    try:
        requests.post(url, json={
            'data': 'sent'
        })
    except:
        pass

def initialize_serial_bus():
    serial_bus = False

    try:
        serial_bus = serial.Serial(
            port=serial_port,  # port
            baudrate=230400,
            parity=serial.PARITY_NONE,
            stopbits=serial.STOPBITS_ONE,
            bytesize=serial.EIGHTBITS,
            timeout=0,
            xonxoff=True,
            rtscts=False,
            dsrdtr=False,
        )

        print("connected")
    except:
        print("Serial Port Unavailable Retrying...")
        time.sleep(10)
        initialize_serial_bus()

    return serial_bus


if __name__ == "__main__":
    main()
