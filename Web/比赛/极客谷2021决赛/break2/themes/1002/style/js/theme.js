$('body').vegas({
    overlay: 'img/overlay.png',
    slides: [{
        src: 'img/banner3.jpg'
    }, {
        src: 'img/banner2.jpg'
    }, {
        src: 'img/banner1.jpg'
    }, ],
});
$(window).load(function() {
    $(".page-loader").fadeOut("slow")
});
new WOW().init();

if (self != top) {
    window.top.location.replace(self.location)
}
var obj = window.location.href;
obj = obj.toLowerCase();
obj = obj.substr(7);
if (obj.indexOf("www.") == 0) {
    obj = obj.substr(4)
}
