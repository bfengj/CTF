from web3 import Web3, WebsocketProvider
from solcx import compile_source
from Crypto.Cipher import AES
from Crypto.Util.Padding import pad, unpad
from base64 import b64encode, b64decode
import hashlib
import hmac
import os
import json
from random import *

# connect to node
w3 = Web3(Web3.HTTPProvider("http://ethereum:8545"))

# aes and hmac
def encrypt_then_mac(data, aes_key, hmac_key):

    cipher = AES.new(aes_key, AES.MODE_CBC)
    msg = cipher.iv + cipher.encrypt(pad(data, AES.block_size))
    sig = hmac.new(hmac_key, msg, hashlib.sha256).digest()
    token = b64encode(msg + sig).decode()

    return token

def validate_then_decrypt(token, aes_key, hmac_key):

    s = b64decode(token)
    msg, sig = s[:-32], s[-32:]
    assert sig == hmac.new(hmac_key, msg, hashlib.sha256).digest()
    iv, ct = msg[:16], msg[16:]
    cipher = AES.new(aes_key, AES.MODE_CBC, iv=iv)
    data = unpad(cipher.decrypt(ct), AES.block_size)

    return data

# solc
def compile_from_src(source):

    compiled_sol = compile_source(source)
    cont_if = compiled_sol['<stdin>:HackChain']

    return cont_if

# web3
def get_deploy_est_gas(cont_if, acct):

    return w3.eth.estimate_gas({'from':acct.address, 'data': cont_if['bin']})

def contract_deploy(acct, cont_if, value):

    instance = w3.eth.contract(
        abi=cont_if['abi'],
        bytecode=cont_if['bin']
    )
    construct_tx = instance.constructor().buildTransaction({
        'chainId': 8888,
        'from': acct.address,
        'nonce': w3.eth.getTransactionCount(acct.address),
        'value': w3.toWei(value, 'ether'),
        'gasPrice': w3.eth.gasPrice
    })

    signed_tx = acct.signTransaction(construct_tx)
    try:
        tx_hash = w3.eth.sendRawTransaction(signed_tx.rawTransaction)
    except Exception as err:
        return err, None
    return None, tx_hash

def contract_deploy2(acct, to, value, data):

    # s7 = '60806040526040516100823803806100828339818101604052602081101561002657600080fd5b81019080805164010000000081111561003e57600080fd5b8281019050602081018481111561005457600080fd5b815185600182028301116401000000008211171561007157600080fd5b5050929190505050805181602001f3fe000000000000000000000000000000000000000000000000000000000000002000000000000000000000000000000000000000000000000000000000000005476080604052600436106100345760003560e01c8063c180319114610039578063d4b839921461008a578063ec19a84f146100e1575b600080fd5b34801561004557600080fd5b506100886004803603602081101561005c57600080fd5b81019080803573ffffffffffffffffffffffffffffffffffffffff1690602001909291905050506100eb565b005b34801561009657600080fd5b5061009f61046f565b604051808273ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200191505060405180910390f35b6100e9610494565b005b600073ffffffffffffffffffffffffffffffffffffffff168173ffffffffffffffffffffffffffffffffffffffff16141561012557600080fd5b60006060823b91506040519050601f19601f602084010116810160405281815281600060208301853c60008090505b81518110156103205760f060f81b82828151811061016e57fe5b602001015160f81c60f81b7effffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff191614156101a657600080fd5b60f160f81b8282815181106101b757fe5b602001015160f81c60f81b7effffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff191614156101ef57600080fd5b60f260f81b82828151811061020057fe5b602001015160f81c60f81b7effffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff1916141561023857600080fd5b60f460f81b82828151811061024957fe5b602001015160f81c60f81b7effffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff1916141561028157600080fd5b60fa60f81b82828151811061029257fe5b602001015160f81c60f81b7effffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff191614156102ca57600080fd5b60ff60f81b8282815181106102db57fe5b602001015160f81c60f81b7effffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff1916141561031357600080fd5b8080600101915050610154565b508273ffffffffffffffffffffffffffffffffffffffff166040516024016040516020818303038152906040527fc5d24601000000000000000000000000000000000000000000000000000000007bffffffffffffffffffffffffffffffffffffffffffffffffffffffff19166020820180517bffffffffffffffffffffffffffffffffffffffffffffffffffffffff83818316178352505050506040518082805190602001908083835b602083106103ee57805182526020820191506020810190506020830392506103cb565b6001836020036101000a038019825116818451168082178552505050505050905001915050600060405180830381855af49150503d806000811461044e576040519150601f19603f3d011682016040523d82523d6000602084013e610453565b606091505b5050508273ffffffffffffffffffffffffffffffffffffffff16ff5b6000809054906101000a900473ffffffffffffffffffffffffffffffffffffffff1681565b6b033b2e3c9fd0803ce80000003410156104ad57600080fd5b7f89814845d4f005a4059f76ea572f39df73fbe3d1c9b20f12b3b03d09f999b9e233604051808273ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200191505060405180910390a156fea265627a7a72305820a26dab274274c7a744e79de9eeea6ffbda63ff298bc7e69f93a980384034067c64736f6c634300050a003200000000000000000000000000000000000000000000000000'

    txn = {
        'chainId': 8888,
        'from': Web3.toChecksumAddress(acct.address),
        'to': to,
        'gasPrice': w3.eth.gasPrice,
        'gas': 3000000,
        'nonce': w3.eth.getTransactionCount(Web3.toChecksumAddress(acct.address)),
        'value': Web3.toWei(0, 'ether'),
        'data': data,
    }
    signed_txn = w3.eth.account.signTransaction(txn, acct.privateKey)

    try:
        tx_hash = w3.eth.sendRawTransaction(signed_txn.rawTransaction)
    except Exception as err:
        return err, None
    return None, tx_hash

def contract_deploy_bydata(acct, value, data):

    txn = {
        'chainId': 8888,
        'from': Web3.toChecksumAddress(acct.address),
        # 'to': to,
        'gasPrice': w3.eth.gasPrice,
        'gas': 3000000,
        'nonce': w3.eth.getTransactionCount(Web3.toChecksumAddress(acct.address)),
        'value': Web3.toWei(0, 'ether'),
        'data': data,
    }
    signed_txn = w3.eth.account.signTransaction(txn, acct.privateKey)

    try:
        tx_hash = w3.eth.sendRawTransaction(signed_txn.rawTransaction)
    except Exception as err:
        return err, None

    return None, tx_hash

def get_cont_addr(tx_hash):

    tx_receipt = w3.eth.getTransactionReceipt(tx_hash)
    assert tx_receipt != None

    return tx_receipt['contractAddress']

def check_if_has_topic(addr, tx_hash, cont_if, topic):

    contract = w3.eth.contract(abi=cont_if['abi'])
    tx_receipt = w3.eth.getTransactionReceipt(tx_hash)
    logs = contract.events[topic]().processReceipt(tx_receipt)

    for d in logs:
        if d['address'] == addr:
            return True

    return False

def check_if_has_eth(addr):

    balance = w3.eth.getBalance(addr)
    if balance == 0:
        return True

    return False

def check_if_solved(cont_if, addr):

    contract = w3.eth.contract(abi = cont_if['abi'], address = addr)

    return contract.functions.solved().call() == True

# game account
def create_game_account():

    acct = w3.eth.account.create(os.urandom(32))

    return acct

def validate_game_account(data):

    addr, priv_key = data[:-32], data[-32:]
    acct = w3.eth.account.from_key(priv_key)
    assert acct.address.encode() == addr

    return acct
