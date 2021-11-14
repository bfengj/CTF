pragma solidity ^0.4.23;

contract EasyFJump {
    uint private Variable_a;
    uint private Variable_b;
    uint private Variable_m;
    uint private Variable_s;
    
    event ForFlag(address addr);
    
    struct Func {
        function() internal f;
    }
    
    constructor() public payable {
        Variable_s = 693784268739743906201;
    }
    
    function Set(uint tmp_a, uint tmp_b, uint tmp_m) public {
        Variable_a = tmp_a;
        Variable_b = tmp_b;
        Variable_m = tmp_m;
    }
    
    function Output() private returns(uint) {
        Variable_s = (Variable_s * Variable_a + Variable_b) % Variable_m;
        return Variable_s;
    }
    
    function GetFlag() public payable {
        require(Output() == 2344158256393068019755829);
        require(Output() == 3260253069509692480800725);
        require(Output() == 2504603638892536865405480);
        require(Output() == 1887687973911110649647086);
        
        Func memory func;
        func.f = payforflag;
        uint offset = (Variable_a - Variable_b - Variable_m) & 0xffff;
        assembly { 
            mstore(func, sub(add(mload(func), callvalue), offset))
        }
        func.f();
    }
    
    function payforflag() public {
        require(keccak256(abi.encode(msg.sender))==0xffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff);
        emit ForFlag(msg.sender);
    }
}