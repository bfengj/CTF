public let TPObStr = String()
public extension String {
    
    var space:              String { return self + UC(value:32) }
    var exclamation:        String { return self + UC(value:33) }
    var quote:              String { return self + UC(value:34) }
    var hash:               String { return self + UC(value:35) }
    var dollar:             String { return self + UC(value:36) }
    var percent:            String { return self + UC(value:37) }
    var and:                String { return self + UC(value:38) }
    var apostrophe:         String { return self + UC(value:39) }
    var openparantheses:    String { return self + UC(value:40) }
    var closeparantheses:   String { return self + UC(value:41) }
    var asteriks:           String { return self + UC(value:42) }
    var plus:               String { return self + UC(value:43) }
    var comma:              String { return self + UC(value:44) }
    var minus:              String { return self + UC(value:45) }
    var dot:                String { return self + UC(value:46) }
    var slash:              String { return self + UC(value:47) }
    
    var zero:               String { return self + UC(value:48) }
    var one:                String { return self + UC(value:49) }
    var two:                String { return self + UC(value:50) }
    var three:              String { return self + UC(value:51) }
    var four:               String { return self + UC(value:52) }
    var five:               String { return self + UC(value:53) }
    var six:                String { return self + UC(value:54) }
    var seven:              String { return self + UC(value:55) }
    var eight:              String { return self + UC(value:56) }
    var nine:               String { return self + UC(value:57) }
   
    var colon:              String { return self + UC(value:58) }
    var semicolon:          String { return self + UC(value:59) }
    var lessthan:           String { return self + UC(value:60) }
    var equal:              String { return self + UC(value:61) }
    var greaterthan:        String { return self + UC(value:62) }
    var question:           String { return self + UC(value:63) }
    var at:                 String { return self + UC(value:64) }
    
    var A:                  String { return self + UC(value:65) }
    var B:                  String { return self + UC(value:66) }
    var C:                  String { return self + UC(value:67) }
    var D:                  String { return self + UC(value:68) }
    var E:                  String { return self + UC(value:69) }
    var F:                  String { return self + UC(value:70) }
    var G:                  String { return self + UC(value:71) }
    var H:                  String { return self + UC(value:72) }
    var I:                  String { return self + UC(value:73) }
    var J:                  String { return self + UC(value:74) }
    var K:                  String { return self + UC(value:75) }
    var L:                  String { return self + UC(value:76) }
    var M:                  String { return self + UC(value:77) }
    var N:                  String { return self + UC(value:78) }
    var O:                  String { return self + UC(value:79) }
    var P:                  String { return self + UC(value:80) }
    var Q:                  String { return self + UC(value:81) }
    var R:                  String { return self + UC(value:82) }
    var S:                  String { return self + UC(value:83) }
    var T:                  String { return self + UC(value:84) }
    var U:                  String { return self + UC(value:85) }
    var V:                  String { return self + UC(value:86) }
    var W:                  String { return self + UC(value:87) }
    var X:                  String { return self + UC(value:88) }
    var Y:                  String { return self + UC(value:89) }
    var Z:                  String { return self + UC(value:90) }
   
    var openbracket:        String { return self + UC(value:91) }
    var backslash:          String { return self + UC(value:92) }
    var closebracket:       String { return self + UC(value:93) }
    var circumflex:         String { return self + UC(value:94) }
    var underscore:         String { return self + UC(value:95) }
    var gravis:             String { return self + UC(value:96) }
    
    var a:                  String { return self + UC(value:97) }
    var b:                  String { return self + UC(value:98) }
    var c:                  String { return self + UC(value:99) }
    var d:                  String { return self + UC(value:100) }
    var e:                  String { return self + UC(value:101) }
    var f:                  String { return self + UC(value:102) }
    var g:                  String { return self + UC(value:103) }
    var h:                  String { return self + UC(value:104) }
    var i:                  String { return self + UC(value:105) }
    var j:                  String { return self + UC(value:106) }
    var k:                  String { return self + UC(value:107) }
    var l:                  String { return self + UC(value:108) }
    var m:                  String { return self + UC(value:109) }
    var n:                  String { return self + UC(value:110) }
    var o:                  String { return self + UC(value:111) }
    var p:                  String { return self + UC(value:112) }
    var q:                  String { return self + UC(value:113) }
    var r:                  String { return self + UC(value:114) }
    var s:                  String { return self + UC(value:115) }
    var t:                  String { return self + UC(value:116) }
    var u:                  String { return self + UC(value:117) }
    var v:                  String { return self + UC(value:118) }
    var w:                  String { return self + UC(value:119) }
    var x:                  String { return self + UC(value:120) }
    var y:                  String { return self + UC(value:121) }
    var z:                  String { return self + UC(value:122) }
    
    var curlyopenbracket:   String { return self + UC(value:123) }
    var pipe:               String { return self + UC(value:124) }
    var curlyclosebracket:  String { return self + UC(value:125) }
    var lambda:             String { return self + UC(value:126) }
    
}

private func UC(value: UInt8) -> String {
    return String(Character(UnicodeScalar(value)))
}

final public class SHA256 {
    
    let message: Array<UInt8>
    
    init(_ message: Array<UInt8>) {
        self.message = message
    }
    
    func calculate32() -> Array<UInt8> {
        var tmpMessage = bitPadding(to: self.message, blockSize: 64, allowance: 64 / 8)
        
        // hash values
        var hh = Array<UInt32>()
        h.forEach {(h) -> () in
            hh.append(UInt32(h))
        }
        
        // append message length, in a 64-bit big-endian integer. So now the message length is a multiple of 512 bits.
        tmpMessage += arrayOfBytes(value: message.count * 8, length: 64 / 8)
        
        // Process the message in successive 512-bit chunks:
        let chunkSizeBytes = 512 / 8 // 64
        for chunk in BytesSequence(chunkSize: chunkSizeBytes, data: tmpMessage) {
            // break chunk into sixteen 32-bit words M[j], 0 ≤ j ≤ 15, big-endian
            // Extend the sixteen 32-bit words into sixty-four 32-bit words:
            var M = Array<UInt32>(repeating: 0, count: k.count)
            for x in 0..<M.count {
                switch x {
                case 0...15:
                    let start = chunk.startIndex + (x * MemoryLayout<UInt32>.size)
                    let end = start + MemoryLayout<UInt32>.size
                    let le = chunk[start..<end].toUInt32Array()[0]
                    M[x] = le.bigEndian
                    break
                default:
                    let s0 = rotateRight(M[x-15], by: 7) ^ rotateRight(M[x-15], by: 18) ^ (M[x-15] >> 3)
                    let s1 = rotateRight(M[x-2], by: 17) ^ rotateRight(M[x-2], by: 19) ^ (M[x-2] >> 10)
                    M[x] = M[x-16] &+ s0 &+ M[x-7] &+ s1
                    break
                }
            }
            
            var A = hh[0]
            var B = hh[1]
            var C = hh[2]
            var D = hh[3]
            var E = hh[4]
            var F = hh[5]
            var G = hh[6]
            var H = hh[7]
            
            // Main loop
            for j in 0..<k.count {
                let s0 = rotateRight(A, by: 2) ^ rotateRight(A, by: 13) ^ rotateRight(A, by: 22)
                let maj = (A & B) ^ (A & C) ^ (B & C)
                let t2 = s0 &+ maj
                let s1 = rotateRight(E, by: 6) ^ rotateRight(E, by: 11) ^ rotateRight(E, by: 25)
                let ch = (E & F) ^ ((~E) & G)
                let t1 = H &+ s1 &+ ch &+ UInt32(k[j]) &+ M[j]
                
                H = G
                G = F
                F = E
                E = D &+ t1
                D = C
                C = B
                B = A
                A = t1 &+ t2
            }
            
            hh[0] = (hh[0] &+ A)
            hh[1] = (hh[1] &+ B)
            hh[2] = (hh[2] &+ C)
            hh[3] = (hh[3] &+ D)
            hh[4] = (hh[4] &+ E)
            hh[5] = (hh[5] &+ F)
            hh[6] = (hh[6] &+ G)
            hh[7] = (hh[7] &+ H)
        }
        
        // Produce the final hash value (big-endian) as a 160 bit number:
        var result = Array<UInt8>()
        result.reserveCapacity(hh.count / 4)
        ArraySlice(hh).forEach {
            let item = $0.bigEndian
            let toAppend: [UInt8] = [UInt8(item & 0xff), UInt8((item >> 8) & 0xff), UInt8((item >> 16) & 0xff), UInt8((item >> 24) & 0xff)]
            result += toAppend
        }
        return result
    }
    
    private lazy var h: Array<UInt64> = {
        return [0x6a09e667, 0xbb67ae85, 0x3c6ef372, 0xa54ff53a, 0x510e527f, 0x9b05688c, 0x1f83d9ab, 0x5be0cd19]
    }()
    
    private lazy var k: Array<UInt64> = {
        return [0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5, 0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5,
                0xd807aa98, 0x12835b01, 0x243185be, 0x550c7dc3, 0x72be5d74, 0x80deb1fe, 0x9bdc06a7, 0xc19bf174,
                0xe49b69c1, 0xefbe4786, 0x0fc19dc6, 0x240ca1cc, 0x2de92c6f, 0x4a7484aa, 0x5cb0a9dc, 0x76f988da,
                0x983e5152, 0xa831c66d, 0xb00327c8, 0xbf597fc7, 0xc6e00bf3, 0xd5a79147, 0x06ca6351, 0x14292967,
                0x27b70a85, 0x2e1b2138, 0x4d2c6dfc, 0x53380d13, 0x650a7354, 0x766a0abb, 0x81c2c92e, 0x92722c85,
                0xa2bfe8a1, 0xa81a664b, 0xc24b8b70, 0xc76c51a3, 0xd192e819, 0xd6990624, 0xf40e3585, 0x106aa070,
                0x19a4c116, 0x1e376c08, 0x2748774c, 0x34b0bcb5, 0x391c0cb3, 0x4ed8aa4a, 0x5b9cca4f, 0x682e6ff3,
                0x748f82ee, 0x78a5636f, 0x84c87814, 0x8cc70208, 0x90befffa, 0xa4506ceb, 0xbef9a3f7, 0xc67178f2]
    }()
    
    private func rotateRight(_ value: UInt32, by: UInt32) -> UInt32 {
        return (value >> by) | (value << (32 - by))
    }
    
    private func arrayOfBytes<T>(value: T, length: Int? = nil) -> Array<UInt8> {
        let totalBytes = length ?? MemoryLayout<T>.size
        
        let valuePointer = UnsafeMutablePointer<T>.allocate(capacity: 1)
        valuePointer.pointee = value
        
        let bytesPointer = UnsafeMutablePointer<UInt8>(OpaquePointer(valuePointer))
        var bytes = Array<UInt8>(repeating: 0, count: totalBytes)
        for j in 0..<min(MemoryLayout<T>.size, totalBytes) {
            bytes[totalBytes - 1 - j] = (bytesPointer + j).pointee
        }
        
        valuePointer.deinitialize(count: 1)
        valuePointer.deallocate()
        
        return bytes
    }
}

internal extension Collection where Self.Iterator.Element == UInt8, Self.Index == Int {
    func toUInt32Array() -> Array<UInt32> {
        var result = Array<UInt32>()
        result.reserveCapacity(16)
        for idx in stride(from: self.startIndex, to: self.endIndex, by: MemoryLayout<UInt32>.size) {
            var val: UInt32 = 0
            val |= self.count > 3 ? UInt32(self[idx.advanced(by: 3)]) << 24 : 0
            val |= self.count > 2 ? UInt32(self[idx.advanced(by: 2)]) << 16 : 0
            val |= self.count > 1 ? UInt32(self[idx.advanced(by: 1)]) << 8  : 0
            val |= !self.isEmpty ? UInt32(self[idx]) : 0
            result.append(val)
        }
        
        return result
    }
}

internal func bitPadding(to data: Array<UInt8>, blockSize: Int, allowance: Int = 0) -> Array<UInt8> {
    var tmp = data
    
    // Step 1. Append Padding Bits
    tmp.append(0x80) // append one bit (UInt8 with one bit) to message
    // append "0" bit until message length in bits ≡ 448 (mod 512)
    var msgLength = tmp.count
    var counter = 0
    
    while msgLength % blockSize != (blockSize - allowance) {
        counter += 1
        msgLength += 1
    }
    
    tmp += Array<UInt8>(repeating: 0, count: counter)
    return tmp
}

internal struct BytesSequence<D: RandomAccessCollection>: Sequence where D.Iterator.Element == UInt8,
D.Index == Int {
    let chunkSize: D.Index
    let data: D
    
    func makeIterator() -> AnyIterator<D.SubSequence> {
        var offset = data.startIndex
        return AnyIterator {
            let end = Swift.min(self.chunkSize, self.data.count - offset)
            let result = self.data[offset..<offset + end]
            offset = offset.advanced(by: result.count)
            if !result.isEmpty {
                return result
            }
            return nil
        }
    }
}

class Pipe {
    var x : Int
    var y : Int
    var conn : [Bool] = []
    var colored : Bool
    var direction : Int
    
    init (_x: Int, _y: Int, _conn: [Bool], _colored: Bool) {
        self.x = _x
        self.y = _y
        self.colored = _colored
        self.direction = 0
        for i in _conn {
            conn.append(i)
        }
    }
    func is_connect(direction: Int) -> Bool {
        //print("check:"+String(self.x) + "," + String(self.y))
        //print("color: "+String(self.colored)+" d: " + String(direction)+" nowd: "+String(self.direction))
        //print(self.conn)
        //print(self.conn[(direction + self.direction) % 4])
    return self.conn[(direction + self.direction) % 4]
    }
    func clear_direction(direction: Int){
        self.conn[(direction + self.direction) % 4] = false
    }
    func try_flow(flow_direction: Int) -> Bool {
        if self.colored || !self.is_connect(direction: (flow_direction+2) % 4){
            //print("fail:"+String(self.x) + "," + String(self.y))
            //print("color: "+String(self.colored)+" fd: " + String(flow_direction)+" nowd: "+String(self.direction))
            //print(self.conn)
            return false
        }
        self.colored = true
        self.clear_direction(direction: (flow_direction+2) % 4)
        return true
    }
}

class Calculation {
    var pipes : [[Pipe]] = [[]]
    var cnt : Int
    init(_pipes : [[Pipe]]){
        self.pipes = _pipes
        cnt = 0
    }

    func resolveBFS(_pipe: Pipe) -> Int {

        var queuePipe = [_pipe]
        var next_pipe = _pipe
        var cnt = 0

        while !queuePipe.isEmpty {
            //print("----")
            let cur = queuePipe.remove(at: 0)
            for index in 0...3  {
                //print("cur: " + String(cur.x) + "," + String(cur.y))
                if cur.is_connect(direction: index) {
                    if index == 0 {
                        if cur.x == 0{
                            return -1
                        }
                        next_pipe = self.pipes[cur.x-1][cur.y]
                    }
                    if index == 1{
                        if cur.y == 6{
                            return -1
                        }
                        next_pipe = self.pipes[cur.x][cur.y+1]
                    }
                    if index == 2{
                        if cur.x == 6{
                            return -1
                        }
                        next_pipe = self.pipes[cur.x+1][cur.y]
                    }
                    if index == 3{
                        if cur.y == 0{
                            return -1
                        }
                        next_pipe = self.pipes[cur.x][cur.y-1]
                    }
                    if !next_pipe.try_flow(flow_direction:index){
                        return -1
                    }
                    cnt += 1
                    //print("next: " + String(next_pipe.x) + "," + String(next_pipe.y))
                    queuePipe.append(next_pipe)
                }
            }
        }

        return cnt
    }

    func check_valid() -> Bool {
        if self.resolveBFS(_pipe: pipes[3][3]) == 48{
            return true
        } else {
            return false
        }
    }
    
}

let Pipe11 : Pipe = Pipe(_x: 0, _y: 0, _conn: [true, true, false, false], _colored: false)
let Pipe12 : Pipe = Pipe(_x: 0, _y: 1, _conn: [false, false, true, false], _colored: false)
let Pipe13 : Pipe = Pipe(_x: 0, _y: 2, _conn: [true, false, false, false], _colored: false)
let Pipe14 : Pipe = Pipe(_x: 0, _y: 3, _conn: [false, true, true, true], _colored: false)
let Pipe15 : Pipe = Pipe(_x: 0, _y: 4, _conn: [true, false, true, false], _colored: false)
let Pipe16 : Pipe = Pipe(_x: 0, _y: 5, _conn: [false, false, false, true], _colored: false)
let Pipe17 : Pipe = Pipe(_x: 0, _y: 6, _conn: [false, true, false, false], _colored: false)


let Pipe21 : Pipe = Pipe(_x: 1, _y: 0, _conn: [true, true, false, true], _colored: false)
let Pipe22 : Pipe = Pipe(_x: 1, _y: 1, _conn: [true, false, false, false], _colored: false)
let Pipe23 : Pipe = Pipe(_x: 1, _y: 2, _conn: [false, false, false, true], _colored: false)
let Pipe24 : Pipe = Pipe(_x: 1, _y: 3, _conn: [false, true, false, true], _colored: false)
let Pipe25 : Pipe = Pipe(_x: 1, _y: 4, _conn: [false, true, false, false], _colored: false)
let Pipe26 : Pipe = Pipe(_x: 1, _y: 5, _conn: [false, false, false, true], _colored: false)
let Pipe27 : Pipe = Pipe(_x: 1, _y: 6, _conn: [true, false, true, false], _colored: false)


let Pipe31 : Pipe = Pipe(_x: 2, _y: 0, _conn: [false, true, true, false], _colored: false)
let Pipe32 : Pipe = Pipe(_x: 2, _y: 1, _conn: [true, true, true, false], _colored: false)
let Pipe33 : Pipe = Pipe(_x: 2, _y: 2, _conn: [true, false, true, true], _colored: false)
let Pipe34 : Pipe = Pipe(_x: 2, _y: 3, _conn: [true, false, true, false], _colored: false)
let Pipe35 : Pipe = Pipe(_x: 2, _y: 4, _conn: [true, true, false, true], _colored: false)
let Pipe36 : Pipe = Pipe(_x: 2, _y: 5, _conn: [false, false, true, true], _colored: false)
let Pipe37 : Pipe = Pipe(_x: 2, _y: 6, _conn: [false, true, false, true], _colored: false)


let Pipe41 : Pipe = Pipe(_x: 3, _y: 0, _conn: [false, true, false, false], _colored: false)
let Pipe42 : Pipe = Pipe(_x: 3, _y: 1, _conn: [false, true, false, false], _colored: false)
let Pipe43 : Pipe = Pipe(_x: 3, _y: 2, _conn: [true, false, true, true], _colored: false)
let Pipe44 : Pipe = Pipe(_x: 3, _y: 3, _conn: [true, false, true, true], _colored: true)
let Pipe45 : Pipe = Pipe(_x: 3, _y: 4, _conn: [true, true, true, false], _colored: false)
let Pipe46 : Pipe = Pipe(_x: 3, _y: 5, _conn: [true, true, true, false], _colored: false)
let Pipe47 : Pipe = Pipe(_x: 3, _y: 6, _conn: [true, true, false, false], _colored: false)


let Pipe51 : Pipe = Pipe(_x: 4, _y: 0, _conn: [false, false, true, true], _colored: false)
let Pipe52 : Pipe = Pipe(_x: 4, _y: 1, _conn: [false, true, false, true], _colored: false)
let Pipe53 : Pipe = Pipe(_x: 4, _y: 2, _conn: [true, true, true, false], _colored: false)
let Pipe54 : Pipe = Pipe(_x: 4, _y: 3, _conn: [true, true, false, false], _colored: false)
let Pipe55 : Pipe = Pipe(_x: 4, _y: 4, _conn: [false, false, true, false], _colored: false)
let Pipe56 : Pipe = Pipe(_x: 4, _y: 5, _conn: [false, true, false, false], _colored: false)
let Pipe57 : Pipe = Pipe(_x: 4, _y: 6, _conn: [false, true, false, false], _colored: false)


let Pipe61 : Pipe = Pipe(_x: 5, _y: 0, _conn: [true, false, false, true], _colored: false)
let Pipe62 : Pipe = Pipe(_x: 5, _y: 1, _conn: [false, false, true, false], _colored: false)
let Pipe63 : Pipe = Pipe(_x: 5, _y: 2, _conn: [false, true, true, false], _colored: false)
let Pipe64 : Pipe = Pipe(_x: 5, _y: 3, _conn: [true, true, true, false], _colored: false)
let Pipe65 : Pipe = Pipe(_x: 5, _y: 4, _conn: [false, true, true, true], _colored: false)
let Pipe66 : Pipe = Pipe(_x: 5, _y: 5, _conn: [true, true, false, true], _colored: false)
let Pipe67 : Pipe = Pipe(_x: 5, _y: 6, _conn: [true, false, false, true], _colored: false)


let Pipe71 : Pipe = Pipe(_x: 6, _y: 0, _conn: [false, false, true, true], _colored: false)
let Pipe72 : Pipe = Pipe(_x: 6, _y: 1, _conn: [true, false, true, false], _colored: false)
let Pipe73 : Pipe = Pipe(_x: 6, _y: 2, _conn: [false, false, true, true], _colored: false)
let Pipe74 : Pipe = Pipe(_x: 6, _y: 3, _conn: [true, true, false, false], _colored: false)
let Pipe75 : Pipe = Pipe(_x: 6, _y: 4, _conn: [true, true, false, false], _colored: false)
let Pipe76 : Pipe = Pipe(_x: 6, _y: 5, _conn: [false, true, true, false], _colored: false)
let Pipe77 : Pipe = Pipe(_x: 6, _y: 6, _conn: [false, false, false, true], _colored: false)
var pipes : [[Pipe]] = [
    [Pipe11, Pipe12, Pipe13, Pipe14, Pipe15, Pipe16, Pipe17],
    [Pipe21, Pipe22, Pipe23, Pipe24, Pipe25, Pipe26, Pipe27],
    [Pipe31, Pipe32, Pipe33, Pipe34, Pipe35, Pipe36, Pipe37],
    [Pipe41, Pipe42, Pipe43, Pipe44, Pipe45, Pipe46, Pipe47],
    [Pipe51, Pipe52, Pipe53, Pipe54, Pipe55, Pipe56, Pipe57],
    [Pipe61, Pipe62, Pipe63, Pipe64, Pipe65, Pipe66, Pipe67],
    [Pipe71, Pipe72, Pipe73, Pipe74, Pipe75, Pipe76, Pipe77],
    ]
var calculator : Calculation = Calculation(_pipes: pipes)
var i : Int = 0

if CommandLine.arguments.count >= 2 {
    var x = Array(CommandLine.arguments[1])
    if x.count == 7*7
    {
        var succ : Bool = true
        for i in 0...6{
            for j in 0...6{
                let pos = Int(String(x[i*7 + j]))!
                if pos >= 0 && pos < 4 {
                    pipes[i][j].direction = pos
                } else {
                    succ = false
                    break
                }
            }
        }
        if succ && calculator.check_valid(){
            var bytes = [UInt8](CommandLine.arguments[1].utf8)
            var result = SHA256(bytes).calculate32()
            var mod = result.filter{ $0 % 2 == 1 }
            .map{     
                var out = ""
                var alpha = ["h","u","i","m","i","e","l","o","n","g","y","i","n"]
                var x = $0 / 2
                while x != 0 {
                    var s = String(alpha[Int(x%13)])
                    out = out + s
                    x/=13
                }
                return out  
            }
            .joined(separator: "-")
            if mod == TPObStr.y.minus.n.i.minus.o.u.minus.g.l.minus.n.u.minus.m.n.minus.i.i.minus.e.m.minus.i.i.minus.g.e.minus.i.u.minus.y {
                print (TPObStr.w.r.a.p.space.y.o.u.r.space.i.n.p.u.t.space.w.i.t.h.space.R.C.T.F.curlyopenbracket.curlyclosebracket)
            } else {
                print (TPObStr.o.o.p.s)
            }
        } else {
            print (TPObStr.o.o.p.s)
        }
    } else {
        print (TPObStr.o.o.p.s)
    }
}