//SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.0;

import "./ERC20Interface.sol";

contract ERC20 is ERC20Interface{
    string public _name = "Feng";
    string public _symbol = "FENG";
    uint8 public _decimals = 18;  // 18 是建议的默认值
    uint256 public _totalSupply;
    
    mapping (address => uint256) public _balanceOf;
    mapping (address => mapping (address => uint256)) _allowance;
    
    
    event Transfer(address indexed _from, address indexed _to, uint256 _value);
    event Approval(address indexed _owner, address indexed _spender, uint256 _value);
    
    
    function name() public view virtual override returns (string memory){
        return _name;
    }
    function symbol() public view virtual override returns (string memory){
        return _symbol;
    }
    function decimals() public view virtual override returns (uint8 ){
        return _decimals;
    }
    function totalSupply() public view virtual override returns (uint256 ){
        return _totalSupply;
    }
    function balanceOf(address _owner) public  view virtual override returns (uint256 balance){
        balance = _balanceOf[_owner];
    }
    function _transfer(address _from, address _to, uint256 _value) internal virtual {
        require(_from != address(0));
        require(_to != address(0));
        require(_balanceOf[_from] >= _value);
        _balanceOf[msg.sender]-=_value;
        _balanceOf[_to]+=_value;
        emit Transfer(_from, _to, _value);
    }
    function _approve(address _owner, address _spender, uint256 _value) internal virtual {
        require(_owner != address(0));
        require(_spender != address(0)); 
        _allowance[_owner][_spender] = _value;
        emit Approval(_owner, _spender, _value);
    }
    function transfer(address _to, uint256 _value) public virtual override returns (bool success){
        _transfer(msg.sender, _to, _value);
        success = true;
    }
    function transferFrom(address _from, address _to, uint256 _value) public virtual override returns (bool success){
        _transfer(_from, _to, _value);
        uint256 allowanceNum = _allowance[_from][_to];
        require(allowanceNum >= _value);
        _approve(_from, _to, allowanceNum - _value);
        success = true;
    }
    function approve(address _spender, uint256 _value) public virtual override returns (bool success){
        _approve(msg.sender, _spender, _value);
        success = true;
    }
    function allowance(address _owner, address _spender) public view virtual override returns (uint256 remaining){
        return _allowance[_owner][_spender];
    }
    function _mint(address _account, uint256 _value) internal virtual{
        require(_account != address(0));
        _totalSupply += _value;
        _balanceOf[_account] += _value;
        emit Transfer(address(0), _account, _value);
    }
    
}
