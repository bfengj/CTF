Buffertostr = function Buftostr(buf) {
    var jsonBuffer = JSON.parse(JSON.stringify(buf))
    var data = jsonBuffer.data
    var newData = []
    var str = new String()
    data.forEach(function(elem){
        newData.push(elem.toString(16))
    })
    newData.forEach(function(elem){
        str += elem.toString()
    })
    return str
}

module.exports = Buffertostr