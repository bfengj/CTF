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
    
    function payforflag() public {
        require(keccak256(abi.encode(msg.sender))==0xffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff);
        if(address(this).balance>1000){
            emit ForFlag(msg.sender);
        }else{
            emit Fail(msg.sender);
        }
    }
}