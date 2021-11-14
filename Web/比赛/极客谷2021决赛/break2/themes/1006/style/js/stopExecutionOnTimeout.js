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
"use strict";
"object" != typeof window.CP && (window.CP = {}), window.CP.PenTimer = {
    programNoLongerBeingMonitored: !1,
    timeOfFirstCallToShouldStopLoop: 0,
    _loopExits: {},
    _loopTimers: {},
    START_MONITORING_AFTER: 2e3,
    STOP_ALL_MONITORING_TIMEOUT: 5e3,
    MAX_TIME_IN_LOOP_WO_EXIT: 2200,
    exitedLoop: function(o) {
        this._loopExits[o] = !0
    },
    shouldStopLoop: function(o) {
        if (this.programKilledSoStopMonitoring) return !0;
        if (this.programNoLongerBeingMonitored) return !1;
        if (this._loopExits[o]) return !1;
        var t = this._getTime();
        if (0 === this.timeOfFirstCallToShouldStopLoop) return this.timeOfFirstCallToShouldStopLoop = t, !1;
        var i = t - this.timeOfFirstCallToShouldStopLoop;
        if (i < this.START_MONITORING_AFTER) return !1;
        if (i > this.STOP_ALL_MONITORING_TIMEOUT) return this.programNoLongerBeingMonitored = !0, !1;
        try {
            this._checkOnInfiniteLoop(o, t)
        } catch (n) {
            return this._sendErrorMessageToEditor(), this.programKilledSoStopMonitoring = !0, !0
        }
        return !1
    },
    _sendErrorMessageToEditor: function() {
        try {
            if (this._shouldPostMessage()) {
                var o = {
                    action: "infinite-loop",
                    line: this._findAroundLineNumber()
                };
                parent.postMessage(JSON.stringify(o), "*")
            } else this._throwAnErrorToStopPen()
        } catch (t) {
            this._throwAnErrorToStopPen()
        }
    },
    _shouldPostMessage: function() {
        return document.location.href.match(/boomerang/)
    },
    _throwAnErrorToStopPen: function() {
        throw "We found an infinite loop in your Pen. We've stopped the Pen from running. Please correct it or contact support@codepen.io."
    },
    _findAroundLineNumber: function() {
        var o = new Error,
            t = 0;
        if (o.stack) {
            var i = o.stack.match(/boomerang\S+:(\d+):\d+/);
            i && (t = i[1])
        }
        return t
    },
    _checkOnInfiniteLoop: function(o, t) {
        if (!this._loopTimers[o]) return this._loopTimers[o] = t, !1;
        var i = t - this._loopTimers[o];
        if (i > this.MAX_TIME_IN_LOOP_WO_EXIT) throw "Infinite Loop found on loop: " + o
    },
    _getTime: function() {
        return +new Date
    }
}, window.CP.shouldStopExecution = function(o) {
    return window.CP.PenTimer.shouldStopLoop(o)
}, window.CP.exitedLoop = function(o) {
    window.CP.PenTimer.exitedLoop(o)
};
$(document).ready(function() {
    var stars = 800;
    var $stars = $('.stars');
    var r = 800;
    for (var i = 0; i < stars; i++) {
        if (window.CP.shouldStopExecution(1)) {
            break;
        }
        var $star = $('<div/>').addClass('star');
        $stars.append($star);
    }
    window.CP.exitedLoop(1);
    $('.star').each(function() {
        var cur = $(this);
        var s = 0.2 + Math.random() * 1;
        var curR = r + Math.random() * 300;
        cur.css({
            transformOrigin: '0 0 ' + curR + 'px',
            transform: ' translate3d(0,0,-' + curR + 'px) rotateY(' + Math.random() * 360 + 'deg) rotateX(' + Math.random() * -50 + 'deg) scale(' + s + ',' + s + ')'
        });
    });
});