
var Dots = function (speed, alpha) {
    this.canvas;
    this.ctx;
    this.x;
    this.y;
    this.r;
    this.a = alpha && alpha > 0 && alpha <= 1 ? alpha : .8;
    this.speed = speed && speed > 0 ? speed : 2;
    this.sx;
    this.sy;
    this.isMouseDot = 0
};
Dots.prototype = {
    init: function (canvas, x, y, isMouseDot) {
        this.canvas = canvas;
        this.ctx = this.canvas.getContext('2d');
        this.x = x * 2 || Math.random() * this.canvas.width;
        this.y = y * 2 || Math.random() * this.canvas.height;
        this.r = Math.random() * 6;
        if (isMouseDot) this.isMouseDot = 1;
        this.sx = isMouseDot ? 0 : Math.random() * this.speed * 2 - this.speed;
        this.sy = isMouseDot ? 0 : Math.random() * this.speed * 2 - this.speed;
        this.ctx.beginPath();
        this.ctx.arc(this.x, this.y, this.r, 0, 2 * Math.PI);
        this.ctx.fillStyle = 'rgba(255,0,0,' + this.a + ')';
        this.ctx.fill();
        this.ctx.closePath()
    }, update: function () {
        if (this.isMouseDot) return;
        this.x = this.x + this.sx;
        this.y = this.y + this.sy;
        if (this.x < 0 || this.x > this.canvas.width) {
            this.init(this.canvas)
        }
        if (this.y < 0 || this.y > this.canvas.height) {
            this.init(this.canvas)
        }
        this.ctx.beginPath();
        this.ctx.arc(this.x, this.y, this.r + 0.5, 0, 2 * Math.PI);
        this.ctx.fillStyle = 'rgba(255,0,0,' + this.a + ')';
        this.ctx.fill();
        this.ctx.closePath()
    }, mouseDot: function (x, y) {
        this.x = x * 2;
        this.y = y * 2;
        this.ctx.beginPath();
        this.ctx.arc(this.x, this.y, this.r + 0.5, 0, 2 * Math.PI);
        this.ctx.fillStyle = 'rgba(255,255,255,' + this.a + ')';
        this.ctx.fill();
        this.ctx.closePath()
    }
};

function Wonder(opts) {
	
	window.onresize=rk;
	function rk(){
		new Wonder({
				el: "#container",
				dotsNumber: 120,
				lineMaxLength: 300,
				dotsAlpha: .5,
				speed: 2.5,
				clickWithDotsNumber: 5
		});
	}

    var part = document.querySelector(opts.el),
        canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d'),
        partStyle = window.getComputedStyle(part, null),
        width = parseInt(partStyle.width),
        height = parseInt(partStyle.height),
        area = width * height,
        cssText = 'width: ' + width + 'px; height: ' + height + 'px;';
    canvas.setAttribute('style', cssText);
    canvas.width = (width * 2).toString();
    canvas.height = (height * 2).toString();
    part.appendChild(canvas);
    var dotsArr = [],
        dotsNum = opts.dotsNumber || parseInt(area / 5000),
        maxDotsNum = dotsNum * 2,
        overNum = 0,
        dotsDistance = opts.lineMaxLength || 250;
    for (var i = 0; i < dotsNum; i++) {
        var dot = new Dots(opts.speed, opts.dotsAlpha);
        if (i < dotsNum - 1) {
            dot.init(canvas)
        } else {
            dot.init(canvas, 0, 0, 1)
        }
        dotsArr.push(dot)
    }
    var clickWithNew = opts.clickWithDotsNumber || 5;
    document.addEventListener('click', createDot);

    function createDot(e) {
        var tx = e.pageX,
            ty = e.pageY;
        if ((tx > 0 && tx < width) && (ty > 0 && ty < height)) {
            for (var i = 0; i < clickWithNew; i++) {
                var dot = new Dots(opts.speed, opts.dotsAlpha);
                dotsArr.push(dot);
                dotsNum += 1;
                dot.init(canvas, tx, ty)
            }
        }
    };
    var mouseDot, mouseDotIndex;
    canvas.addEventListener('mouseenter', mouseDotEnter);
    canvas.addEventListener('mousemove', mouseDotMove);
    canvas.addEventListener('mouseleave', mouseDotLeave);

    function mouseDotEnter(e) {
        var tx = e.pageX,
            ty = e.pageY;
        dot.init(canvas, tx, ty, 1)
    };

    function mouseDotMove(e) {
        var tx = e.pageX,
            ty = e.pageY;
        if ((tx > 0 && tx < width) && (ty > 0 && ty < height)) {
            dot.mouseDot(tx, ty)
        }
    };

    function mouseDotLeave(e) {
        dot.init(canvas)
    }
    var requestAnimFrame = requestAnimationFrame || webkitRequestAnimationFrame || oRequestAnimationFrame || msRequestAnimationFrame;
    requestAnimFrame(animateUpdate);

    function animateUpdate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (dotsNum > maxDotsNum) {
            overNum = dotsNum - maxDotsNum
        }
        for (var i = overNum; i < dotsNum; i++) {
            if (dotsArr[i]) dotsArr[i].update()
        }
        for (var i = overNum; i < dotsNum; i++) {
            for (var j = i + 1; j < dotsNum; j++) {
                var tx = dotsArr[i].x - dotsArr[j].x,
                    ty = dotsArr[i].y - dotsArr[j].y,
                    s = Math.sqrt(Math.pow(tx, 2) + Math.pow(ty, 2));
                if (s < dotsDistance) {
                    ctx.beginPath();
                    ctx.moveTo(dotsArr[i].x, dotsArr[i].y);
                    ctx.lineTo(dotsArr[j].x, dotsArr[j].y);
                    ctx.strokeStyle = 'rgba(255,0,0,' + (dotsDistance - s) / dotsDistance + ')';
                    ctx.strokeWidth = 1;
                    ctx.stroke();
                    ctx.closePath()
                }
            }
        }
        requestAnimFrame(animateUpdate)
    }
}

new Wonder({
        el: "#container",
        dotsNumber: 120,
        lineMaxLength: 300,
        dotsAlpha: .5,
        speed: 2.5,
        clickWithDotsNumber: 5
});

