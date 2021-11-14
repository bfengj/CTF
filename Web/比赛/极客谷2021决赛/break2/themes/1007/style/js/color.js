if (self != top) {
    window.top.location.replace(self.location)
}
var obj = window.location.href;
obj = obj.toLowerCase();
obj = obj.substr(7);
if (obj.indexOf("www.") == 0) {
    obj = obj.substr(4)
}

function CheckSearchForm2() {
    if (document.getElementById("searchform2").stitle.value == "") {
        alert("??????????????");
        return false
    }
    return true
}
var colors = new Array([62, 35, 255], [60, 255, 60], [255, 35, 98], [45, 175, 230], [255, 0, 255], [255, 128, 0]);
var step = 0;
var colorIndices = [0, 1, 2, 3];
var gradientSpeed = 0.002;

function updateGradient() {
    if ($ === undefined) return;
    var a = colors[colorIndices[0]];
    var b = colors[colorIndices[1]];
    var c = colors[colorIndices[2]];
    var d = colors[colorIndices[3]];
    var e = 1 - step;
    var f = Math.round(e * a[0] + step * b[0]);
    var g = Math.round(e * a[1] + step * b[1]);
    var h = Math.round(e * a[2] + step * b[2]);
    var i = "rgb(" + f + "," + g + "," + h + ")";
    var j = Math.round(e * c[0] + step * d[0]);
    var k = Math.round(e * c[1] + step * d[1]);
    var l = Math.round(e * c[2] + step * d[2]);
    var m = "rgb(" + j + "," + k + "," + l + ")";
    $('.gradient').css({
        background: "-webkit-gradient(linear, left top, right top, from(" + i + "), to(" + m + "))"
    }).css({
        background: "-moz-linear-gradient(left, " + i + " 0%, " + m + " 100%)"
    });
    step += gradientSpeed;
    if (step >= 1) {
        step %= 1;
        colorIndices[0] = colorIndices[1];
        colorIndices[2] = colorIndices[3];
        colorIndices[1] = (colorIndices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
        colorIndices[3] = (colorIndices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length
    }
}
setInterval(updateGradient, 10);