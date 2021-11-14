#!/usr/bin/python3
import sys
import signal
import hashlib
import os
from Crypto.Util.number import bytes_to_long,long_to_bytes
from util import *
import random
import string
import time
from Crypto.Cipher import AES
from web3 import Web3, HTTPProvider
import time,json
from eth_abi import encode_single, encode_abi

# settings
AES_KEY = "6bdb046e10268c914db9d2bf4061bbd8"
HMAC_KEY = "25944d6bbd15dad4c378ad13937b034d297d435d45df716e1d568c0fa0d81d1a"

alarmsecs=60

flag="flag{C0ngr4tulate_t0_U_t0_h4ck_chain!!}"

# seed = "80238023-1b25-5ce8-913c-125890a78023"

workdir="/root/ethbot"

MENU = '''

We design a pretty easy contract game. Enjoy it!

1. Create a game account
2. Deploy a game contract
3. Request for flag

ps: Option 1, get an account which will be used to deploy the contract;

ps: Option 2, the robot will use the account to deploy the contract for the problem;

ps: Option 3, use this option to obtain the flag after the ForFlag(address addr) event is triggered;

You can finish this challenge in a lot of connections.

^_^ geth attach http://ip:8545

^_^ Get Testnet Ether from http://ip
'''
TOPIC = 'ForFlag'

class Unbuffered(object):
   def __init__(self, stream):
       self.stream = stream
   def write(self, data):
       self.stream.write(data)
       self.stream.flush()
   def __getattr__(self, attr):
       return getattr(self.stream, attr)
sys.stdout = Unbuffered(sys.stdout)
signal.alarm(alarmsecs)
os.chdir(workdir)

# get contract source and interface
with open('./eth.sol', 'r') as f:
    SRC_TEXT = f.read()
CONT_IF = compile_from_src(SRC_TEXT.replace('RN', str(int.from_bytes(os.urandom(16), 'little'))))

def generatepow(difficulty):
    prefix = ''.join(random.choice(string.ascii_letters + string.digits) for i in range(8))
    msg="sha256("+prefix+"+?).binary.endswith('"+"0"*difficulty+"')"
    return prefix,msg

def pow(prefix,difficulty,answer):
    hashresult=hashlib.sha256((prefix+answer).encode()).digest()
    bits=bin(int(hashlib.sha256((prefix+answer).encode()).hexdigest(),16))[2:]
    if bits.endswith("0"*difficulty):
        return True
    else:
        return False


print("[+] Welcome!")
prefix,msg=generatepow(20)
print("[+]",msg)
answer=input("[-] ?=")

if not pow(prefix,20,answer):
    print("[+] wrong proof")
    sys.exit(0)
print("[+] passed")

print(MENU)

choice=input("[-] input your choice: ")

if choice=='1':
    # create game account
    acct = create_game_account()
    print("[+] Your game account:{}".format(acct.address))

    # save account
    with open('account.txt', 'a') as f:
        f.write(acct.address)
        f.write('\n')
        f.write(''.join(['%02X' % x for x in acct.key]))
        f.write('\n\n')

    # generate token
    data = acct.address.encode() + acct.key
    AES_KEY = str.encode(AES_KEY)
    HMAC_KEY = str.encode(HMAC_KEY)
    token = encrypt_then_mac(data, AES_KEY, HMAC_KEY)
    print("[+] token: {}".format(token))

    est_gas = get_deploy_est_gas(CONT_IF, acct)
    print("[+] Deploy will cost {} gas".format(est_gas))
    print("[+] Make sure that you have enough ether to deploy!!!!!!")

elif choice=='2':
    token = input("[-] input your token: ")
    token=token.strip()
    AES_KEY = str.encode(AES_KEY)
    HMAC_KEY = str.encode(HMAC_KEY)
    data = validate_then_decrypt(token, AES_KEY, HMAC_KEY)
    if len(data) != 74:
        print("[+] wrong token")
        sys.exit(0)
    acct = validate_game_account(data)

    # deploy game contract
    source = SRC_TEXT.replace('RN', str(int.from_bytes(os.urandom(16), 'little')))
    cont_if = compile_from_src(source)

    err, tx_hash = contract_deploy(acct, CONT_IF, random.randint(1024,4095)*(1e-18))

    # check if got error when sending transaction
    if err:
        if err.args[0]['code'] == -32000:
            msg = 'Error: ' + err.args[0]['message'] + '\n'
            print("[+] {}".format(msg))
        sys.exit(0)

    # generate new token
    data = acct.address.encode() + acct.key + tx_hash
    new_token = encrypt_then_mac(data, AES_KEY, HMAC_KEY)
    print("[+] new token: {}".format(new_token))
    print("[+] Your goal is to emit ForFlag(address addr) event")
    print("[+] Transaction hash: {}".format(tx_hash.hex()))

elif choice=='3':
    new_token = input("[-] input your new token: ")
    new_token=new_token.strip()
    AES_KEY = str.encode(AES_KEY)
    HMAC_KEY = str.encode(HMAC_KEY)
    data = validate_then_decrypt(new_token, AES_KEY, HMAC_KEY)

    if len(data) != 106:
        print("[+] wrong token")
        sys.exit(0)

    data, tx_hash = data[:-32], data[-32:]
    acct = validate_game_account(data)
    addr = get_cont_addr(tx_hash)

    tx_hash = input("[-] input tx_hash that emitted {} event: ".format(TOPIC))
    tx_hash = tx_hash.strip()
    res = check_if_has_topic(addr, tx_hash, CONT_IF, TOPIC)

    if res:
        print("[+] flag:",flag)
        with open('solve.txt', 'a') as f:
            f.write(acct.address)
            f.write('\n')
            f.write(''.join(['%02X' % x for x in acct.key]))
            f.write('\n')
            f.write(tx_hash)
            f.write('\n\n')
    else:
        print("[+] sorry, it seems that you have not solved this~~~~")
else:
    print("[+] Invalid option")
    sys.exit(0)
