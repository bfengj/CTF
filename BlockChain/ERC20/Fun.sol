//SPDX-License-Identifier: UNLICENSED

pragma solidity ^0.8.0;

import "./ERC20.sol";
contract Fun is ERC20{
    address public owner;
    constructor() payable  {
        owner = msg.sender;
    }
    modifier onlyOwner(){
        require(msg.sender == owner);
        _;
    }
    function mint(address _account, uint _value) public onlyOwner{
        _mint(_account, _value);
    }
    function getMoney() public {
        uint value = uint(keccak256(abi.encodePacked(block.timestamp))) % 10;
        _mint(msg.sender, value);
    }
}
