# UTF-8 Overlong Encoding

## 分析

UTF-8可以将unicode码表里的所有字符，用某种计算方式转换成长度是1到4位字节的字符，规则如下：
![image-20240306183237913](UTF-8%20Overlong%20Encoding.assets/image-20240306183237913.png)

常见的UTF-8都是一个Byte的，但是也可以转换成其他的长度。

例如大写字母T(`0b01010100`)，转换成两个字节就是分成`xxxxx`和`xxxxxx`这两组，即`00001`（不足长度就补0）和`010100`，然后变成`11000001`和`10010100`。

```java
        int b1 = 0b11000001;
        int b2 = 0b10010100;
        System.out.println((char) (((b1 & 0x1F) << 6) | ((b2 & 0x3F) << 0)));
        //T
```







在Java反序列化的时候，因为类名序列化后是明文，在反序列化的时候会进行utf-8的解析：

```java
       private long readUTFSpan(StringBuilder sbuf, long utflen)
            throws IOException
        {
            int cpos = 0;
            int start = pos;
            int avail = Math.min(end - pos, CHAR_BUF_SIZE);
            // stop short of last char unless all of utf bytes in buffer
            int stop = pos + ((utflen > avail) ? avail - 2 : (int) utflen);
            boolean outOfBounds = false;

            try {
                while (pos < stop) {
                    int b1, b2, b3;
                    b1 = buf[pos++] & 0xFF;
                    switch (b1 >> 4) {
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:   // 1 byte format: 0xxxxxxx
                            cbuf[cpos++] = (char) b1;
                            break;

                        case 12:
                        case 13:  // 2 byte format: 110xxxxx 10xxxxxx
                            b2 = buf[pos++];
                            if ((b2 & 0xC0) != 0x80) {
                                throw new UTFDataFormatException();
                            }
                            cbuf[cpos++] = (char) (((b1 & 0x1F) << 6) |
                                                   ((b2 & 0x3F) << 0));
                            break;

                        case 14:  // 3 byte format: 1110xxxx 10xxxxxx 10xxxxxx
                            b3 = buf[pos + 1];
                            b2 = buf[pos + 0];
                            pos += 2;
                            if ((b2 & 0xC0) != 0x80 || (b3 & 0xC0) != 0x80) {
                                throw new UTFDataFormatException();
                            }
                            cbuf[cpos++] = (char) (((b1 & 0x0F) << 12) |
                                                   ((b2 & 0x3F) << 6) |
                                                   ((b3 & 0x3F) << 0));
                            break;

                        default:  // 10xx xxxx, 1111 xxxx
                            throw new UTFDataFormatException();
                    }
                }
            } catch (ArrayIndexOutOfBoundsException ex) {
                outOfBounds = true;
            } finally {
                if (outOfBounds || (pos - start) > utflen) {
                    /*
                     * Fix for 4450867: if a malformed utf char causes the
                     * conversion loop to scan past the expected end of the utf
                     * string, only consume the expected number of utf bytes.
                     */
                    pos = start + (int) utflen;
                    throw new UTFDataFormatException();
                }
            }

            sbuf.append(cbuf, 0, cpos);
            return pos - start;
        }
```

因此就可以利用这种姿势来绕过对于类名的waf（还需要修改序列化后字符流中表示长度的那部分）



这种类型的攻击也就是UTF-8 Overlong Encoding。

在一些场景就可以尝试用`%C0%AE`替代`.`



## POC

转换脚本脚本：

```python
def convert_int(i: int) -> bytes:
    b1 = ((i >> 6) & 0b11111) | 0b11000000
    b2 = (i & 0b111111) | 0b10000000
    return bytes([b1, b2])


def convert_str(s: str) -> bytes:
    bs = b''
    for ch in s.encode():
        bs += convert_int(ch)

    return bs


if __name__ == '__main__':
    print(convert_str('.')) # b'\xc0\xae'
    print(convert_str('org.example.Evil')) # b'\xc1\xaf\xc1\xb2\xc1\xa7\xc0\xae\xc1\xa5\xc1\xb8\xc1\xa1\xc1\xad\xc1\xb0\xc1\xac\xc1\xa5\xc0\xae\xc1\x85\xc1\xb6\xc1\xa9\xc1\xac'
```



## References

[UTF-8 Overlong Encoding导致的安全问题 | 离别歌](https://www.leavesongs.com/PENETRATION/utf-8-overlong-encoding.html)

