(function(q, Va) {
    function D(a) {
        var b = a.length,
            d = c.type(a);
        return c.isWindow(a) ? !1 : 1 === a.nodeType && b ? !0 : "array" === d || "function" !== d && (0 === b || "number" === typeof b && 0 < b && b - 1 in a)
    }

    function Wa(a) {
        var b = Xa[a] = {};
        c.each(a.match(L) || [], function(a, c) {
            b[c] = !0
        });
        return b
    }

    function U(a, b, d, e) {
        if (c.acceptData(a)) {
            var f = c.expando,
                g = "string" === typeof b,
                h = a.nodeType,
                k = h ? c.cache : a,
                l = h ? a[f] : a[f] && f;
            if (l && k[l] && (e || k[l].data) || !g || void 0 !== d) {
                l || (h ? a[f] = l = G.pop() || c.guid++ : l = f);
                k[l] || (k[l] = {}, h || (k[l].toJSON = c.noop));
                if ("object" === typeof b || "function" === typeof b) e ? k[l] = c.extend(k[l], b) : k[l].data = c.extend(k[l].data, b);
                a = k[l];
                e || (a.data || (a.data = {}), a = a.data);
                void 0 !== d && (a[c.camelCase(b)] = d);
                g ? (d = a[b], null == d && (d = a[c.camelCase(b)])) : d = a;
                return d
            }
        }
    }

    function Ya(a, b, d) {
        if (c.acceptData(a)) {
            var e, f, g, h = a.nodeType,
                k = h ? c.cache : a,
                l = h ? a[c.expando] : c.expando;
            if (k[l]) {
                if (b && (e = d ? k[l] : k[l].data)) {
                    c.isArray(b) ? b = b.concat(c.map(b, c.camelCase)) : b in e ? b = [b] : (b = c.camelCase(b), b = b in e ? [b] : b.split(" "));
                    f = 0;
                    for (g = b.length; f < g; f++) delete e[b[f]];
                    if (!(d ? xa : c.isEmptyObject)(e)) return
                }
                if (!d && (delete k[l].data, !xa(k[l]))) return;
                h ? c.cleanData([a], !0) : c.support.deleteExpando || k != k.window ? delete k[l] : k[l] = null
            }
        }
    }

    function Za(a, b, d) {
        if (void 0 === d && 1 === a.nodeType)
            if (d = "data-" + b.replace(Sb, "-$1").toLowerCase(), d = a.getAttribute(d), "string" === typeof d) {
                try {
                    d = "true" === d ? !0 : "false" === d ? !1 : "null" === d ? null : +d + "" === d ? +d : Tb.test(d) ? c.parseJSON(d) : d
                } catch (e) {}
                c.data(a, b, d)
            } else d = void 0;
        return d
    }

    function xa(a) {
        for (var b in a)
            if (("data" !== b || !c.isEmptyObject(a[b])) &&
                "toJSON" !== b) return !1;
        return !0
    }

    function Z() {
        return !0
    }

    function H() {
        return !1
    }

    function $a(a, b) {
        do a = a[b]; while (a && 1 !== a.nodeType);
        return a
    }

    function ab(a, b, d) {
        b = b || 0;
        if (c.isFunction(b)) return c.grep(a, function(a, c) {
            return !!b.call(a, c, a) === d
        });
        if (b.nodeType) return c.grep(a, function(a) {
            return a === b === d
        });
        if ("string" === typeof b) {
            var e = c.grep(a, function(a) {
                return 1 === a.nodeType
            });
            if (Ub.test(b)) return c.filter(b, e, !d);
            b = c.filter(b, e)
        }
        return c.grep(a, function(a) {
            return 0 <= c.inArray(a, b) === d
        })
    }

    function bb(a) {
        var b =
            cb.split("|");
        a = a.createDocumentFragment();
        if (a.createElement)
            for (; b.length;) a.createElement(b.pop());
        return a
    }

    function Vb(a, b) {
        return a.getElementsByTagName(b)[0] || a.appendChild(a.ownerDocument.createElement(b))
    }

    function db(a) {
        var b = a.getAttributeNode("type");
        a.type = (b && b.specified) + "/" + a.type;
        return a
    }

    function eb(a) {
        var b = Wb.exec(a.type);
        b ? a.type = b[1] : a.removeAttribute("type");
        return a
    }

    function ya(a, b) {
        for (var d, e = 0; null != (d = a[e]); e++) c._data(d, "globalEval", !b || c._data(b[e], "globalEval"))
    }

    function fb(a,
        b) {
        if (1 === b.nodeType && c.hasData(a)) {
            var d, e, f;
            e = c._data(a);
            var g = c._data(b, e),
                h = e.events;
            if (h)
                for (d in delete g.handle, g.events = {}, h)
                    for (e = 0, f = h[d].length; e < f; e++) c.event.add(b, d, h[d][e]);
            g.data && (g.data = c.extend({}, g.data))
        }
    }

    function z(a, b) {
        var d, e, f = 0,
            g = "undefined" !== typeof a.getElementsByTagName ? a.getElementsByTagName(b || "*") : "undefined" !== typeof a.querySelectorAll ? a.querySelectorAll(b || "*") : void 0;
        if (!g)
            for (g = [], d = a.childNodes || a; null != (e = d[f]); f++)!b || c.nodeName(e, b) ? g.push(e) : c.merge(g, z(e,
                b));
        return void 0 === b || b && c.nodeName(a, b) ? c.merge([a], g) : g
    }

    function Xb(a) {
        za.test(a.type) && (a.defaultChecked = a.checked)
    }

    function gb(a, b) {
        if (b in a) return b;
        for (var c = b.charAt(0).toUpperCase() + b.slice(1), e = b, f = hb.length; f--;)
            if (b = hb[f] + c, b in a) return b;
        return e
    }

    function ca(a, b) {
        a = b || a;
        return "none" === c.css(a, "display") || !c.contains(a.ownerDocument, a)
    }

    function ib(a, b) {
        for (var d, e = [], f = 0, g = a.length; f < g; f++) d = a[f], d.style && (e[f] = c._data(d, "olddisplay"), b ? (e[f] || "none" !== d.style.display || (d.style.display =
            ""), "" === d.style.display && ca(d) && (e[f] = c._data(d, "olddisplay", jb(d.nodeName)))) : e[f] || ca(d) || c._data(d, "olddisplay", c.css(d, "display")));
        for (f = 0; f < g; f++) d = a[f], !d.style || b && "none" !== d.style.display && "" !== d.style.display || (d.style.display = b ? e[f] || "" : "none");
        return a
    }

    function kb(a, b, c) {
        return (a = Yb.exec(b)) ? Math.max(0, a[1] - (c || 0)) + (a[2] || "px") : b
    }

    function lb(a, b, d, e, f) {
        b = d === (e ? "border" : "content") ? 4 : "width" === b ? 1 : 0;
        for (var g = 0; 4 > b; b += 2) "margin" === d && (g += c.css(a, d + V[b], !0, f)), e ? ("content" === d && (g -= c.css(a,
            "padding" + V[b], !0, f)), "margin" !== d && (g -= c.css(a, "border" + V[b] + "Width", !0, f))) : (g += c.css(a, "padding" + V[b], !0, f), "padding" !== d && (g += c.css(a, "border" + V[b] + "Width", !0, f)));
        return g
    }

    function mb(a, b, d) {
        var e = !0,
            f = "width" === b ? a.offsetWidth : a.offsetHeight,
            g = N(a),
            h = c.support.boxSizing && "border-box" === c.css(a, "boxSizing", !1, g);
        if (0 >= f || null == f) {
            f = O(a, b, g);
            if (0 > f || null == f) f = a.style[b];
            if (ha.test(f)) return f;
            e = h && (c.support.boxSizingReliable || f === a.style[b]);
            f = parseFloat(f) || 0
        }
        return f + lb(a, b, d || (h ? "border" :
            "content"), e, g) + "px"
    }

    function jb(a) {
        var b = p,
            d = nb[a];
        d || (d = ob(a, b), "none" !== d && d || (da = (da || c("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(b.documentElement), b = (da[0].contentWindow || da[0].contentDocument).document, b.write("<!doctype html><html><body>"), b.close(), d = ob(a, b), da.detach()), nb[a] = d);
        return d
    }

    function ob(a, b) {
        var d = c(b.createElement(a)).appendTo(b.body),
            e = c.css(d[0], "display");
        d.remove();
        return e
    }

    function Aa(a, b, d, e) {
        var f;
        if (c.isArray(b)) c.each(b,
            function(b, c) {
                d || Zb.test(a) ? e(a, c) : Aa(a + "[" + ("object" === typeof c ? b : "") + "]", c, d, e)
            });
        else if (d || "object" !== c.type(b)) e(a, b);
        else
            for (f in b) Aa(a + "[" + f + "]", b[f], d, e)
    }

    function pb(a) {
        return function(b, d) {
            "string" !== typeof b && (d = b, b = "*");
            var e, f = 0,
                g = b.toLowerCase().match(L) || [];
            if (c.isFunction(d))
                for (; e = g[f++];) "+" === e[0] ? (e = e.slice(1) || "*", (a[e] = a[e] || []).unshift(d)) : (a[e] = a[e] || []).push(d)
        }
    }

    function qb(a, b, d, e) {
        function f(k) {
            var l;
            g[k] = !0;
            c.each(a[k] || [], function(a, c) {
                var k = c(b, d, e);
                if ("string" === typeof k &&
                    !h && !g[k]) return b.dataTypes.unshift(k), f(k), !1;
                if (h) return !(l = k)
            });
            return l
        }
        var g = {},
            h = a === Ba;
        return f(b.dataTypes[0]) || !g["*"] && f("*")
    }

    function Ca(a, b) {
        var d, e, f = c.ajaxSettings.flatOptions || {};
        for (d in b) void 0 !== b[d] && ((f[d] ? a : e || (e = {}))[d] = b[d]);
        e && c.extend(!0, a, e);
        return a
    }

    function rb() {
        try {
            return new q.XMLHttpRequest
        } catch (a) {}
    }

    function sb() {
        setTimeout(function() {
            P = void 0
        });
        return P = c.now()
    }

    function $b(a, b) {
        c.each(b, function(b, c) {
            for (var f = (ea[b] || []).concat(ea["*"]), g = 0, h = f.length; g < h && !f[g].call(a,
                b, c); g++);
        })
    }

    function tb(a, b, d) {
        var e, f = 0,
            g = ia.length,
            h = c.Deferred().always(function() {
                delete k.elem
            }),
            k = function() {
                if (e) return !1;
                for (var b = P || sb(), b = Math.max(0, l.startTime + l.duration - b), c = 1 - (b / l.duration || 0), d = 0, f = l.tweens.length; d < f; d++) l.tweens[d].run(c);
                h.notifyWith(a, [l, c, b]);
                if (1 > c && f) return b;
                h.resolveWith(a, [l]);
                return !1
            },
            l = h.promise({
                elem: a,
                props: c.extend({}, b),
                opts: c.extend(!0, {
                    specialEasing: {}
                }, d),
                originalProperties: b,
                originalOptions: d,
                startTime: P || sb(),
                duration: d.duration,
                tweens: [],
                createTween: function(b,
                    d) {
                    var e = c.Tween(a, l.opts, b, d, l.opts.specialEasing[b] || l.opts.easing);
                    l.tweens.push(e);
                    return e
                },
                stop: function(b) {
                    var c = 0,
                        d = b ? l.tweens.length : 0;
                    if (e) return this;
                    for (e = !0; c < d; c++) l.tweens[c].run(1);
                    b ? h.resolveWith(a, [l, b]) : h.rejectWith(a, [l, b]);
                    return this
                }
            });
        d = l.props;
        for (ac(d, l.opts.specialEasing); f < g; f++)
            if (b = ia[f].call(l, a, d, l.opts)) return b;
        $b(l, d);
        c.isFunction(l.opts.start) && l.opts.start.call(a, l);
        c.fx.timer(c.extend(k, {
            elem: a,
            anim: l,
            queue: l.opts.queue
        }));
        return l.progress(l.opts.progress).done(l.opts.done,
            l.opts.complete).fail(l.opts.fail).always(l.opts.always)
    }

    function ac(a, b) {
        var d, e, f, g, h;
        for (d in a)
            if (e = c.camelCase(d), f = b[e], g = a[d], c.isArray(g) && (f = g[1], g = a[d] = g[0]), d !== e && (a[e] = g, delete a[d]), (h = c.cssHooks[e]) && "expand" in h)
                for (d in g = h.expand(g), delete a[e], g) d in a || (a[d] = g[d], b[d] = f);
            else b[e] = f
    }

    function v(a, b, c, e, f) {
        return new v.prototype.init(a, b, c, e, f)
    }

    function ja(a, b) {
        var c, e = {
                height: a
            },
            f = 0;
        for (b = b ? 1 : 0; 4 > f; f += 2 - b) c = V[f], e["margin" + c] = e["padding" + c] = a;
        b && (e.opacity = e.width = a);
        return e
    }

    function ub(a) {
        return c.isWindow(a) ?
            a : 9 === a.nodeType ? a.defaultView || a.parentWindow : !1
    }
    var vb, ka, p = q.document,
        bc = q.location,
        cc = q.jQuery,
        dc = q.$,
        la = {},
        G = [],
        wb = G.concat,
        Da = G.push,
        Q = G.slice,
        xb = G.indexOf,
        ec = la.toString,
        Ea = la.hasOwnProperty,
        Fa = "1.9.0".trim,
        c = function(a, b) {
            return new c.fn.init(a, b, vb)
        },
        ma = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        L = /\S+/g,
        fc = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
        gc = /^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/,
        yb = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        hc = /^[\],:{}\s]*$/,
        ic = /(?:^|:|,)(?:\s*\[)+/g,
        jc = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,
        kc = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,
        lc = /^-ms-/,
        mc = /-([\da-z])/gi,
        nc = function(a, b) {
            return b.toUpperCase()
        },
        sa = function() {
            p.addEventListener ? (p.removeEventListener("DOMContentLoaded", sa, !1), c.ready()) : "complete" === p.readyState && (p.detachEvent("onreadystatechange", sa), c.ready())
        };
    c.fn = c.prototype = {
        jquery: "1.9.0",
        constructor: c,
        init: function(a, b, d) {
            var e;
            if (!a) return this;
            if ("string" === typeof a) {
                e = "<" === a.charAt(0) && ">" === a.charAt(a.length - 1) && 3 <= a.length ? [null, a, null] :
                    gc.exec(a);
                if (!e || !e[1] && b) return !b || b.jquery ? (b || d).find(a) : this.constructor(b).find(a);
                if (e[1]) {
                    if (b = b instanceof c ? b[0] : b, c.merge(this, c.parseHTML(e[1], b && b.nodeType ? b.ownerDocument || b : p, !0)), yb.test(e[1]) && c.isPlainObject(b))
                        for (e in b)
                            if (c.isFunction(this[e])) this[e](b[e]);
                            else this.attr(e, b[e])
                } else {
                    if ((b = p.getElementById(e[2])) && b.parentNode) {
                        if (b.id !== e[2]) return d.find(a);
                        this.length = 1;
                        this[0] = b
                    }
                    this.context = p;
                    this.selector = a
                }
                return this
            }
            if (a.nodeType) return this.context = this[0] = a, this.length =
                1, this;
            if (c.isFunction(a)) return d.ready(a);
            void 0 !== a.selector && (this.selector = a.selector, this.context = a.context);
            return c.makeArray(a, this)
        },
        selector: "",
        length: 0,
        size: function() {
            return this.length
        },
        toArray: function() {
            return Q.call(this)
        },
        get: function(a) {
            return null == a ? this.toArray() : 0 > a ? this[this.length + a] : this[a]
        },
        pushStack: function(a) {
            a = c.merge(this.constructor(), a);
            a.prevObject = this;
            a.context = this.context;
            return a
        },
        each: function(a, b) {
            return c.each(this, a, b)
        },
        ready: function(a) {
            c.ready.promise().done(a);
            return this
        },
        slice: function() {
            return this.pushStack(Q.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        eq: function(a) {
            var b = this.length;
            a = +a + (0 > a ? b : 0);
            return this.pushStack(0 <= a && a < b ? [this[a]] : [])
        },
        map: function(a) {
            return this.pushStack(c.map(this, function(b, c) {
                return a.call(b, c, b)
            }))
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: Da,
        sort: [].sort,
        splice: [].splice
    };
    c.fn.init.prototype = c.fn;
    c.extend = c.fn.extend = function() {
        var a, b, d,
            e, f, g = arguments[0] || {},
            h = 1,
            k = arguments.length,
            l = !1;
        "boolean" === typeof g && (l = g, g = arguments[1] || {}, h = 2);
        "object" === typeof g || c.isFunction(g) || (g = {});
        k === h && (g = this, --h);
        for (; h < k; h++)
            if (null != (a = arguments[h]))
                for (b in a) d = g[b], e = a[b], g !== e && (l && e && (c.isPlainObject(e) || (f = c.isArray(e))) ? (f ? (f = !1, d = d && c.isArray(d) ? d : []) : d = d && c.isPlainObject(d) ? d : {}, g[b] = c.extend(l, d, e)) : void 0 !== e && (g[b] = e));
        return g
    };
    c.extend({
        noConflict: function(a) {
            q.$ === c && (q.$ = dc);
            a && q.jQuery === c && (q.jQuery = cc);
            return c
        },
        isReady: !1,
        readyWait: 1,
        holdReady: function(a) {
            a ? c.readyWait++ : c.ready(!0)
        },
        ready: function(a) {
            if (!0 === a ? !--c.readyWait : !c.isReady) {
                if (!p.body) return setTimeout(c.ready);
                c.isReady = !0;
                !0 !== a && 0 < --c.readyWait || (ka.resolveWith(p, [c]), c.fn.trigger && c(p).trigger("ready").off("ready"))
            }
        },
        isFunction: function(a) {
            return "function" === c.type(a)
        },
        isArray: Array.isArray || function(a) {
            return "array" === c.type(a)
        },
        isWindow: function(a) {
            return null != a && a == a.window
        },
        isNumeric: function(a) {
            return !isNaN(parseFloat(a)) && isFinite(a)
        },
        type: function(a) {
            return null ==
                a ? String(a) : "object" === typeof a || "function" === typeof a ? la[ec.call(a)] || "object" : typeof a
        },
        isPlainObject: function(a) {
            if (!a || "object" !== c.type(a) || a.nodeType || c.isWindow(a)) return !1;
            try {
                if (a.constructor && !Ea.call(a, "constructor") && !Ea.call(a.constructor.prototype, "isPrototypeOf")) return !1
            } catch (d) {
                return !1
            }
            for (var b in a);
            return void 0 === b || Ea.call(a, b)
        },
        isEmptyObject: function(a) {
            for (var b in a) return !1;
            return !0
        },
        error: function(a) {
            throw Error(a);
        },
        parseHTML: function(a, b, d) {
            if (!a || "string" !== typeof a) return null;
            "boolean" === typeof b && (d = b, b = !1);
            b = b || p;
            var e = yb.exec(a);
            d = !d && [];
            if (e) return [b.createElement(e[1])];
            e = c.buildFragment([a], b, d);
            d && c(d).remove();
            return c.merge([], e.childNodes)
        },
        parseJSON: function(a) {
            if (q.JSON && q.JSON.parse) return q.JSON.parse(a);
            if (null === a) return a;
            if ("string" === typeof a && (a = c.trim(a)) && hc.test(a.replace(jc, "@").replace(kc, "]").replace(ic, ""))) return (new Function("return " + a))();
            c.error("Invalid JSON: " + a)
        },
        parseXML: function(a) {
            var b, d;
            if (!a || "string" !== typeof a) return null;
            try {
                q.DOMParser ?
                    (d = new DOMParser, b = d.parseFromString(a, "text/xml")) : (b = new ActiveXObject("Microsoft.XMLDOM"), b.async = "false", b.loadXML(a))
            } catch (e) {
                b = void 0
            }
            b && b.documentElement && !b.getElementsByTagName("parsererror").length || c.error("Invalid XML: " + a);
            return b
        },
        noop: function() {},
        globalEval: function(a) {
            a && c.trim(a) && (q.execScript || function(a) {
                q.eval.call(q, a)
            })(a)
        },
        camelCase: function(a) {
            return a.replace(lc, "ms-").replace(mc, nc)
        },
        nodeName: function(a, b) {
            return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase()
        },
        each: function(a, b, c) {
            var e, f = 0,
                g = a.length;
            e = D(a);
            if (c)
                if (e)
                    for (; f < g && (e = b.apply(a[f], c), !1 !== e); f++);
                else
                    for (f in a) {
                        if (e = b.apply(a[f], c), !1 === e) break
                    } else if (e)
                        for (; f < g && (e = b.call(a[f], f, a[f]), !1 !== e); f++);
                    else
                        for (f in a)
                            if (e = b.call(a[f], f, a[f]), !1 === e) break;
            return a
        },
        trim: Fa && !Fa.call("\ufeff\u00a0") ? function(a) {
            return null == a ? "" : Fa.call(a)
        } : function(a) {
            return null == a ? "" : (a + "").replace(fc, "")
        },
        makeArray: function(a, b) {
            var d = b || [];
            null != a && (D(Object(a)) ? c.merge(d, "string" === typeof a ? [a] : a) : Da.call(d,
                a));
            return d
        },
        inArray: function(a, b, c) {
            var e;
            if (b) {
                if (xb) return xb.call(b, a, c);
                e = b.length;
                for (c = c ? 0 > c ? Math.max(0, e + c) : c : 0; c < e; c++)
                    if (c in b && b[c] === a) return c
            }
            return -1
        },
        merge: function(a, b) {
            var c = b.length,
                e = a.length,
                f = 0;
            if ("number" === typeof c)
                for (; f < c; f++) a[e++] = b[f];
            else
                for (; void 0 !== b[f];) a[e++] = b[f++];
            a.length = e;
            return a
        },
        grep: function(a, b, c) {
            var e, f = [],
                g = 0,
                h = a.length;
            for (c = !!c; g < h; g++) e = !!b(a[g], g), c !== e && f.push(a[g]);
            return f
        },
        map: function(a, b, c) {
            var e, f = 0,
                g = a.length,
                h = [];
            if (D(a))
                for (; f < g; f++) e =
                    b(a[f], f, c), null != e && (h[h.length] = e);
            else
                for (f in a) e = b(a[f], f, c), null != e && (h[h.length] = e);
            return wb.apply([], h)
        },
        guid: 1,
        proxy: function(a, b) {
            var d, e;
            "string" === typeof b && (d = a[b], b = a, a = d);
            if (c.isFunction(a)) return e = Q.call(arguments, 2), d = function() {
                return a.apply(b || this, e.concat(Q.call(arguments)))
            }, d.guid = a.guid = a.guid || c.guid++, d
        },
        access: function(a, b, d, e, f, g, h) {
            var k = 0,
                l = a.length,
                m = null == d;
            if ("object" === c.type(d))
                for (k in f = !0, d) c.access(a, b, k, d[k], !0, g, h);
            else if (void 0 !== e && (f = !0, c.isFunction(e) ||
                (h = !0), m && (h ? (b.call(a, e), b = null) : (m = b, b = function(a, b, d) {
                    return m.call(c(a), d)
                })), b))
                for (; k < l; k++) b(a[k], d, h ? e : e.call(a[k], k, b(a[k], d)));
            return f ? a : m ? b.call(a) : l ? b(a[0], d) : g
        },
        now: function() {
            return (new Date).getTime()
        }
    });
    c.ready.promise = function(a) {
        if (!ka)
            if (ka = c.Deferred(), "complete" === p.readyState) setTimeout(c.ready);
            else if (p.addEventListener) p.addEventListener("DOMContentLoaded", sa, !1), q.addEventListener("load", c.ready, !1);
        else {
            p.attachEvent("onreadystatechange", sa);
            q.attachEvent("onload", c.ready);
            var b = !1;
            try {
                b = null == q.frameElement && p.documentElement
            } catch (d) {}
            b && b.doScroll && function e() {
                if (!c.isReady) {
                    try {
                        b.doScroll("left")
                    } catch (a) {
                        return setTimeout(e, 50)
                    }
                    c.ready()
                }
            }()
        }
        return ka.promise(a)
    };
    c.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(a, b) {
        la["[object " + b + "]"] = b.toLowerCase()
    });
    vb = c(p);
    var Xa = {};
    c.Callbacks = function(a) {
        a = "string" === typeof a ? Xa[a] || Wa(a) : c.extend({}, a);
        var b, d, e, f, g, h, k = [],
            l = !a.once && [],
            m = function(c) {
                b = a.memory && c;
                d = !0;
                h = f || 0;
                f = 0;
                g = k.length;
                for (e = !0; k && h < g; h++)
                    if (!1 === k[h].apply(c[0], c[1]) && a.stopOnFalse) {
                        b = !1;
                        break
                    }
                e = !1;
                k && (l ? l.length && m(l.shift()) : b ? k = [] : r.disable())
            },
            r = {
                add: function() {
                    if (k) {
                        var d = k.length;
                        (function Ga(b) {
                            c.each(b, function(b, d) {
                                var e = c.type(d);
                                "function" === e ? a.unique && r.has(d) || k.push(d) : d && d.length && "string" !== e && Ga(d)
                            })
                        })(arguments);
                        e ? g = k.length : b && (f = d, m(b))
                    }
                    return this
                },
                remove: function() {
                    k && c.each(arguments, function(a, b) {
                        for (var d; - 1 < (d = c.inArray(b, k, d));) k.splice(d, 1), e && (d <= g && g--, d <= h && h--)
                    });
                    return this
                },
                has: function(a) {
                    return -1 < c.inArray(a, k)
                },
                empty: function() {
                    k = [];
                    return this
                },
                disable: function() {
                    k = l = b = void 0;
                    return this
                },
                disabled: function() {
                    return !k
                },
                lock: function() {
                    l = void 0;
                    b || r.disable();
                    return this
                },
                locked: function() {
                    return !l
                },
                fireWith: function(a, b) {
                    b = b || [];
                    b = [a, b.slice ? b.slice() : b];
                    !k || d && !l || (e ? l.push(b) : m(b));
                    return this
                },
                fire: function() {
                    r.fireWith(this, arguments);
                    return this
                },
                fired: function() {
                    return !!d
                }
            };
        return r
    };
    c.extend({
        Deferred: function(a) {
            var b = [
                    ["resolve", "done", c.Callbacks("once memory"),
                        "resolved"
                    ],
                    ["reject", "fail", c.Callbacks("once memory"), "rejected"],
                    ["notify", "progress", c.Callbacks("memory")]
                ],
                d = "pending",
                e = {
                    state: function() {
                        return d
                    },
                    always: function() {
                        f.done(arguments).fail(arguments);
                        return this
                    },
                    then: function() {
                        var a = arguments;
                        return c.Deferred(function(d) {
                            c.each(b, function(b, l) {
                                var m = l[0],
                                    r = c.isFunction(a[b]) && a[b];
                                f[l[1]](function() {
                                    var a = r && r.apply(this, arguments);
                                    if (a && c.isFunction(a.promise)) a.promise().done(d.resolve).fail(d.reject).progress(d.notify);
                                    else d[m + "With"](this ===
                                        e ? d.promise() : this, r ? [a] : arguments)
                                })
                            });
                            a = null
                        }).promise()
                    },
                    promise: function(a) {
                        return null != a ? c.extend(a, e) : e
                    }
                },
                f = {};
            e.pipe = e.then;
            c.each(b, function(a, c) {
                var k = c[2],
                    l = c[3];
                e[c[1]] = k.add;
                l && k.add(function() {
                    d = l
                }, b[a ^ 1][2].disable, b[2][2].lock);
                f[c[0]] = function() {
                    f[c[0] + "With"](this === f ? e : this, arguments);
                    return this
                };
                f[c[0] + "With"] = k.fireWith
            });
            e.promise(f);
            a && a.call(f, f);
            return f
        },
        when: function(a) {
            var b = 0,
                d = Q.call(arguments),
                e = d.length,
                f = 1 !== e || a && c.isFunction(a.promise) ? e : 0,
                g = 1 === f ? a : c.Deferred(),
                h = function(a, b, c) {
                    return function(d) {
                        b[a] = this;
                        c[a] = 1 < arguments.length ? Q.call(arguments) : d;
                        c === k ? g.notifyWith(b, c) : --f || g.resolveWith(b, c)
                    }
                },
                k, l, m;
            if (1 < e)
                for (k = Array(e), l = Array(e), m = Array(e); b < e; b++) d[b] && c.isFunction(d[b].promise) ? d[b].promise().done(h(b, m, d)).fail(g.reject).progress(h(b, l, k)) : --f;
            f || g.resolveWith(m, d);
            return g.promise()
        }
    });
    c.support = function() {
        var a, b, d, e, f, g, h, k = p.createElement("div");
        k.setAttribute("className", "t");
        k.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>";
        b = k.getElementsByTagName("*");
        d = k.getElementsByTagName("a")[0];
        if (!b || !d || !b.length) return {};
        e = p.createElement("select");
        f = e.appendChild(p.createElement("option"));
        b = k.getElementsByTagName("input")[0];
        d.style.cssText = "top:1px;float:left;opacity:.5";
        a = {
            getSetAttribute: "t" !== k.className,
            leadingWhitespace: 3 === k.firstChild.nodeType,
            tbody: !k.getElementsByTagName("tbody").length,
            htmlSerialize: !!k.getElementsByTagName("link").length,
            style: /top/.test(d.getAttribute("style")),
            hrefNormalized: "/a" === d.getAttribute("href"),
            opacity: /^0.5/.test(d.style.opacity),
            cssFloat: !!d.style.cssFloat,
            checkOn: !!b.value,
            optSelected: f.selected,
            enctype: !!p.createElement("form").enctype,
            html5Clone: "<:nav></:nav>" !== p.createElement("nav").cloneNode(!0).outerHTML,
            boxModel: "CSS1Compat" === p.compatMode,
            deleteExpando: !0,
            noCloneEvent: !0,
            inlineBlockNeedsLayout: !1,
            shrinkWrapBlocks: !1,
            reliableMarginRight: !0,
            boxSizingReliable: !0,
            pixelPosition: !1
        };
        b.checked = !0;
        a.noCloneChecked = b.cloneNode(!0).checked;
        e.disabled = !0;
        a.optDisabled = !f.disabled;
        try {
            delete k.test
        } catch (l) {
            a.deleteExpando = !1
        }
        b = p.createElement("input");
        b.setAttribute("value", "");
        a.input = "" === b.getAttribute("value");
        b.value = "t";
        b.setAttribute("type", "radio");
        a.radioValue = "t" === b.value;
        b.setAttribute("checked", "t");
        b.setAttribute("name", "t");
        d = p.createDocumentFragment();
        d.appendChild(b);
        a.appendChecked = b.checked;
        a.checkClone = d.cloneNode(!0).cloneNode(!0).lastChild.checked;
        k.attachEvent && (k.attachEvent("onclick", function() {
            a.noCloneEvent = !1
        }), k.cloneNode(!0).click());
        for (h in {
            submit: !0,
            change: !0,
            focusin: !0
        }) k.setAttribute(d =
            "on" + h, "t"), a[h + "Bubbles"] = d in q || !1 === k.attributes[d].expando;
        k.style.backgroundClip = "content-box";
        k.cloneNode(!0).style.backgroundClip = "";
        a.clearCloneStyle = "content-box" === k.style.backgroundClip;
        c(function() {
            var b, c, d = p.getElementsByTagName("body")[0];
            d && (b = p.createElement("div"), b.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", d.appendChild(b).appendChild(k), k.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", c = k.getElementsByTagName("td"),
                c[0].style.cssText = "padding:0;margin:0;border:0;display:none", g = 0 === c[0].offsetHeight, c[0].style.display = "", c[1].style.display = "none", a.reliableHiddenOffsets = g && 0 === c[0].offsetHeight, k.innerHTML = "", k.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;", a.boxSizing = 4 === k.offsetWidth, a.doesNotIncludeMarginInBodyOffset = 1 !== d.offsetTop, q.getComputedStyle && (a.pixelPosition = "1%" !==
                    (q.getComputedStyle(k, null) || {}).top, a.boxSizingReliable = "4px" === (q.getComputedStyle(k, null) || {
                        width: "4px"
                    }).width, c = k.appendChild(p.createElement("div")), c.style.cssText = k.style.cssText = "padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;", c.style.marginRight = c.style.width = "0", k.style.width = "1px", a.reliableMarginRight = !parseFloat((q.getComputedStyle(c, null) || {}).marginRight)), "undefined" !== typeof k.style.zoom && (k.innerHTML =
                    "", k.style.cssText = "padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;width:1px;padding:1px;display:inline;zoom:1", a.inlineBlockNeedsLayout = 3 === k.offsetWidth, k.style.display = "block", k.innerHTML = "<div></div>", k.firstChild.style.width = "5px", a.shrinkWrapBlocks = 3 !== k.offsetWidth, d.style.zoom = 1), d.removeChild(b), k = null)
        });
        b = e = d = f = d = b = null;
        return a
    }();
    var Tb = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/,
        Sb = /([A-Z])/g;
    c.extend({
        cache: {},
        expando: "jQuery" +
            ("1.9.0" + Math.random()).replace(/\D/g, ""),
        noData: {
            embed: !0,
            object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
            applet: !0
        },
        hasData: function(a) {
            a = a.nodeType ? c.cache[a[c.expando]] : a[c.expando];
            return !!a && !xa(a)
        },
        data: function(a, b, c) {
            return U(a, b, c, !1)
        },
        removeData: function(a, b) {
            return Ya(a, b, !1)
        },
        _data: function(a, b, c) {
            return U(a, b, c, !0)
        },
        _removeData: function(a, b) {
            return Ya(a, b, !0)
        },
        acceptData: function(a) {
            var b = a.nodeName && c.noData[a.nodeName.toLowerCase()];
            return !b || !0 !== b && a.getAttribute("classid") ===
                b
        }
    });
    c.fn.extend({
        data: function(a, b) {
            var d, e, f = this[0],
                g = 0,
                h = null;
            if (void 0 === a) {
                if (this.length && (h = c.data(f), 1 === f.nodeType && !c._data(f, "parsedAttrs"))) {
                    for (d = f.attributes; g < d.length; g++) e = d[g].name, e.indexOf("data-") || (e = c.camelCase(e.substring(5)), Za(f, e, h[e]));
                    c._data(f, "parsedAttrs", !0)
                }
                return h
            }
            return "object" === typeof a ? this.each(function() {
                c.data(this, a)
            }) : c.access(this, function(b) {
                    if (void 0 === b) return f ? Za(f, a, c.data(f, a)) : null;
                    this.each(function() {
                        c.data(this, a, b)
                    })
                }, null, b, 1 < arguments.length,
                null, !0)
        },
        removeData: function(a) {
            return this.each(function() {
                c.removeData(this, a)
            })
        }
    });
    c.extend({
        queue: function(a, b, d) {
            var e;
            if (a) return b = (b || "fx") + "queue", e = c._data(a, b), d && (!e || c.isArray(d) ? e = c._data(a, b, c.makeArray(d)) : e.push(d)), e || []
        },
        dequeue: function(a, b) {
            b = b || "fx";
            var d = c.queue(a, b),
                e = d.length,
                f = d.shift(),
                g = c._queueHooks(a, b),
                h = function() {
                    c.dequeue(a, b)
                };
            "inprogress" === f && (f = d.shift(), e--);
            if (g.cur = f) "fx" === b && d.unshift("inprogress"), delete g.stop, f.call(a, h, g);
            !e && g && g.empty.fire()
        },
        _queueHooks: function(a,
            b) {
            var d = b + "queueHooks";
            return c._data(a, d) || c._data(a, d, {
                empty: c.Callbacks("once memory").add(function() {
                    c._removeData(a, b + "queue");
                    c._removeData(a, d)
                })
            })
        }
    });
    c.fn.extend({
        queue: function(a, b) {
            var d = 2;
            "string" !== typeof a && (b = a, a = "fx", d--);
            return arguments.length < d ? c.queue(this[0], a) : void 0 === b ? this : this.each(function() {
                var d = c.queue(this, a, b);
                c._queueHooks(this, a);
                "fx" === a && "inprogress" !== d[0] && c.dequeue(this, a)
            })
        },
        dequeue: function(a) {
            return this.each(function() {
                c.dequeue(this, a)
            })
        },
        delay: function(a,
            b) {
            a = c.fx ? c.fx.speeds[a] || a : a;
            return this.queue(b || "fx", function(b, c) {
                var f = setTimeout(b, a);
                c.stop = function() {
                    clearTimeout(f)
                }
            })
        },
        clearQueue: function(a) {
            return this.queue(a || "fx", [])
        },
        promise: function(a, b) {
            var d, e = 1,
                f = c.Deferred(),
                g = this,
                h = this.length,
                k = function() {
                    --e || f.resolveWith(g, [g])
                };
            "string" !== typeof a && (b = a, a = void 0);
            for (a = a || "fx"; h--;)(d = c._data(g[h], a + "queueHooks")) && d.empty && (e++, d.empty.add(k));
            k();
            return f.promise(b)
        }
    });
    var W, zb, Ha = /[\t\r\n]/g,
        oc = /\r/g,
        pc = /^(?:input|select|textarea|button|object)$/i,
        qc = /^(?:a|area)$/i,
        Ab = /^(?:checked|selected|autofocus|autoplay|async|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped)$/i,
        Ia = /^(?:checked|selected)$/i,
        I = c.support.getSetAttribute,
        Ja = c.support.input;
    c.fn.extend({
        attr: function(a, b) {
            return c.access(this, c.attr, a, b, 1 < arguments.length)
        },
        removeAttr: function(a) {
            return this.each(function() {
                c.removeAttr(this, a)
            })
        },
        prop: function(a, b) {
            return c.access(this, c.prop, a, b, 1 < arguments.length)
        },
        removeProp: function(a) {
            a = c.propFix[a] || a;
            return this.each(function() {
                try {
                    this[a] =
                        void 0, delete this[a]
                } catch (b) {}
            })
        },
        addClass: function(a) {
            var b, d, e, f, g, h = 0,
                k = this.length;
            b = "string" === typeof a && a;
            if (c.isFunction(a)) return this.each(function(b) {
                c(this).addClass(a.call(this, b, this.className))
            });
            if (b)
                for (b = (a || "").match(L) || []; h < k; h++)
                    if (d = this[h], e = 1 === d.nodeType && (d.className ? (" " + d.className + " ").replace(Ha, " ") : " ")) {
                        for (g = 0; f = b[g++];) 0 > e.indexOf(" " + f + " ") && (e += f + " ");
                        d.className = c.trim(e)
                    }
            return this
        },
        removeClass: function(a) {
            var b, d, e, f, g, h = 0,
                k = this.length;
            b = 0 === arguments.length ||
                "string" === typeof a && a;
            if (c.isFunction(a)) return this.each(function(b) {
                c(this).removeClass(a.call(this, b, this.className))
            });
            if (b)
                for (b = (a || "").match(L) || []; h < k; h++)
                    if (d = this[h], e = 1 === d.nodeType && (d.className ? (" " + d.className + " ").replace(Ha, " ") : "")) {
                        for (g = 0; f = b[g++];)
                            for (; 0 <= e.indexOf(" " + f + " ");) e = e.replace(" " + f + " ", " ");
                        d.className = a ? c.trim(e) : ""
                    }
            return this
        },
        toggleClass: function(a, b) {
            var d = typeof a,
                e = "boolean" === typeof b;
            return c.isFunction(a) ? this.each(function(d) {
                c(this).toggleClass(a.call(this,
                    d, this.className, b), b)
            }) : this.each(function() {
                if ("string" === d)
                    for (var f, g = 0, h = c(this), k = b, l = a.match(L) || []; f = l[g++];) k = e ? k : !h.hasClass(f), h[k ? "addClass" : "removeClass"](f);
                else if ("undefined" === d || "boolean" === d) this.className && c._data(this, "__className__", this.className), this.className = this.className || !1 === a ? "" : c._data(this, "__className__") || ""
            })
        },
        hasClass: function(a) {
            a = " " + a + " ";
            for (var b = 0, c = this.length; b < c; b++)
                if (1 === this[b].nodeType && 0 <= (" " + this[b].className + " ").replace(Ha, " ").indexOf(a)) return !0;
            return !1
        },
        val: function(a) {
            var b, d, e, f = this[0];
            if (arguments.length) return e = c.isFunction(a), this.each(function(d) {
                var f = c(this);
                1 === this.nodeType && (d = e ? a.call(this, d, f.val()) : a, null == d ? d = "" : "number" === typeof d ? d += "" : c.isArray(d) && (d = c.map(d, function(a) {
                    return null == a ? "" : a + ""
                })), b = c.valHooks[this.type] || c.valHooks[this.nodeName.toLowerCase()], b && "set" in b && void 0 !== b.set(this, d, "value") || (this.value = d))
            });
            if (f) {
                if ((b = c.valHooks[f.type] || c.valHooks[f.nodeName.toLowerCase()]) && "get" in b && void 0 !== (d =
                    b.get(f, "value"))) return d;
                d = f.value;
                return "string" === typeof d ? d.replace(oc, "") : null == d ? "" : d
            }
        }
    });
    c.extend({
        valHooks: {
            option: {
                get: function(a) {
                    var b = a.attributes.value;
                    return !b || b.specified ? a.value : a.text
                }
            },
            select: {
                get: function(a) {
                    for (var b, d = a.options, e = a.selectedIndex, f = (a = "select-one" === a.type || 0 > e) ? null : [], g = a ? e + 1 : d.length, h = 0 > e ? g : a ? e : 0; h < g; h++)
                        if (b = d[h], !(!b.selected && h !== e || (c.support.optDisabled ? b.disabled : null !== b.getAttribute("disabled")) || b.parentNode.disabled && c.nodeName(b.parentNode, "optgroup"))) {
                            b =
                                c(b).val();
                            if (a) return b;
                            f.push(b)
                        }
                    return f
                },
                set: function(a, b) {
                    var d = c.makeArray(b);
                    c(a).find("option").each(function() {
                        this.selected = 0 <= c.inArray(c(this).val(), d)
                    });
                    d.length || (a.selectedIndex = -1);
                    return d
                }
            }
        },
        attr: function(a, b, d) {
            var e, f, g;
            g = a.nodeType;
            if (a && 3 !== g && 8 !== g && 2 !== g) {
                if ("undefined" === typeof a.getAttribute) return c.prop(a, b, d);
                if (g = 1 !== g || !c.isXMLDoc(a)) b = b.toLowerCase(), f = c.attrHooks[b] || (Ab.test(b) ? zb : W);
                if (void 0 !== d)
                    if (null === d) c.removeAttr(a, b);
                    else {
                        if (f && g && "set" in f && void 0 !== (e =
                            f.set(a, d, b))) return e;
                        a.setAttribute(b, d + "");
                        return d
                    } else {
                    if (f && g && "get" in f && null !== (e = f.get(a, b))) return e;
                    "undefined" !== typeof a.getAttribute && (e = a.getAttribute(b));
                    return null == e ? void 0 : e
                }
            }
        },
        removeAttr: function(a, b) {
            var d, e, f = 0,
                g = b && b.match(L);
            if (g && 1 === a.nodeType)
                for (; d = g[f++];) e = c.propFix[d] || d, Ab.test(d) ? !I && Ia.test(d) ? a[c.camelCase("default-" + d)] = a[e] = !1 : a[e] = !1 : c.attr(a, d, ""), a.removeAttribute(I ? d : e)
        },
        attrHooks: {
            type: {
                set: function(a, b) {
                    if (!c.support.radioValue && "radio" === b && c.nodeName(a,
                        "input")) {
                        var d = a.value;
                        a.setAttribute("type", b);
                        d && (a.value = d);
                        return b
                    }
                }
            }
        },
        propFix: {
            tabindex: "tabIndex",
            readonly: "readOnly",
            "for": "htmlFor",
            "class": "className",
            maxlength: "maxLength",
            cellspacing: "cellSpacing",
            cellpadding: "cellPadding",
            rowspan: "rowSpan",
            colspan: "colSpan",
            usemap: "useMap",
            frameborder: "frameBorder",
            contenteditable: "contentEditable"
        },
        prop: function(a, b, d) {
            var e, f, g;
            g = a.nodeType;
            if (a && 3 !== g && 8 !== g && 2 !== g) {
                if (g = 1 !== g || !c.isXMLDoc(a)) b = c.propFix[b] || b, f = c.propHooks[b];
                return void 0 !== d ? f &&
                    "set" in f && void 0 !== (e = f.set(a, d, b)) ? e : a[b] = d : f && "get" in f && null !== (e = f.get(a, b)) ? e : a[b]
            }
        },
        propHooks: {
            tabIndex: {
                get: function(a) {
                    var b = a.getAttributeNode("tabindex");
                    return b && b.specified ? parseInt(b.value, 10) : pc.test(a.nodeName) || qc.test(a.nodeName) && a.href ? 0 : void 0
                }
            }
        }
    });
    zb = {
        get: function(a, b) {
            var d = c.prop(a, b),
                e = "boolean" === typeof d && a.getAttribute(b);
            return (d = "boolean" === typeof d ? Ja && I ? null != e : Ia.test(b) ? a[c.camelCase("default-" + b)] : !!e : a.getAttributeNode(b)) && !1 !== d.value ? b.toLowerCase() : void 0
        },
        set: function(a, b, d) {
            !1 === b ? c.removeAttr(a, d) : Ja && I || !Ia.test(d) ? a.setAttribute(!I && c.propFix[d] || d, d) : a[c.camelCase("default-" + d)] = a[d] = !0;
            return d
        }
    };
    Ja && I || (c.attrHooks.value = {
        get: function(a, b) {
            var d = a.getAttributeNode(b);
            return c.nodeName(a, "input") ? a.defaultValue : d && d.specified ? d.value : void 0
        },
        set: function(a, b, d) {
            if (c.nodeName(a, "input")) a.defaultValue = b;
            else return W && W.set(a, b, d)
        }
    });
    I || (W = c.valHooks.button = {
        get: function(a, b) {
            var c = a.getAttributeNode(b);
            return c && ("id" === b || "name" === b || "coords" ===
                b ? "" !== c.value : c.specified) ? c.value : void 0
        },
        set: function(a, b, c) {
            var e = a.getAttributeNode(c);
            e || a.setAttributeNode(e = a.ownerDocument.createAttribute(c));
            e.value = b += "";
            return "value" === c || b === a.getAttribute(c) ? b : void 0
        }
    }, c.attrHooks.contenteditable = {
        get: W.get,
        set: function(a, b, c) {
            W.set(a, "" === b ? !1 : b, c)
        }
    }, c.each(["width", "height"], function(a, b) {
        c.attrHooks[b] = c.extend(c.attrHooks[b], {
            set: function(a, c) {
                if ("" === c) return a.setAttribute(b, "auto"), c
            }
        })
    }));
    c.support.hrefNormalized || (c.each(["href", "src", "width",
        "height"
    ], function(a, b) {
        c.attrHooks[b] = c.extend(c.attrHooks[b], {
            get: function(a) {
                a = a.getAttribute(b, 2);
                return null == a ? void 0 : a
            }
        })
    }), c.each(["href", "src"], function(a, b) {
        c.propHooks[b] = {
            get: function(a) {
                return a.getAttribute(b, 4)
            }
        }
    }));
    c.support.style || (c.attrHooks.style = {
        get: function(a) {
            return a.style.cssText || void 0
        },
        set: function(a, b) {
            return a.style.cssText = b + ""
        }
    });
    c.support.optSelected || (c.propHooks.selected = c.extend(c.propHooks.selected, {
        get: function(a) {
            if (a = a.parentNode) a.selectedIndex, a.parentNode &&
                a.parentNode.selectedIndex;
            return null
        }
    }));
    c.support.enctype || (c.propFix.enctype = "encoding");
    c.support.checkOn || c.each(["radio", "checkbox"], function() {
        c.valHooks[this] = {
            get: function(a) {
                return null === a.getAttribute("value") ? "on" : a.value
            }
        }
    });
    c.each(["radio", "checkbox"], function() {
        c.valHooks[this] = c.extend(c.valHooks[this], {
            set: function(a, b) {
                if (c.isArray(b)) return a.checked = 0 <= c.inArray(c(a).val(), b)
            }
        })
    });
    var Ka = /^(?:input|select|textarea)$/i,
        rc = /^key/,
        sc = /^(?:mouse|contextmenu)|click/,
        Bb = /^(?:focusinfocus|focusoutblur)$/,
        Cb = /^([^.]*)(?:\.(.+)|)$/;
    c.event = {
        global: {},
        add: function(a, b, d, e, f) {
            var g, h, k, l, m, r, n, t, p;
            if (m = 3 !== a.nodeType && 8 !== a.nodeType && c._data(a)) {
                d.handler && (g = d, d = g.handler, f = g.selector);
                d.guid || (d.guid = c.guid++);
                (l = m.events) || (l = m.events = {});
                (h = m.handle) || (h = m.handle = function(a) {
                    return "undefined" === typeof c || a && c.event.triggered === a.type ? void 0 : c.event.dispatch.apply(h.elem, arguments)
                }, h.elem = a);
                b = (b || "").match(L) || [""];
                for (m = b.length; m--;) k = Cb.exec(b[m]) || [], t = r = k[1], p = (k[2] || "").split(".").sort(), k =
                    c.event.special[t] || {}, t = (f ? k.delegateType : k.bindType) || t, k = c.event.special[t] || {}, r = c.extend({
                        type: t,
                        origType: r,
                        data: e,
                        handler: d,
                        guid: d.guid,
                        selector: f,
                        needsContext: f && c.expr.match.needsContext.test(f),
                        namespace: p.join(".")
                    }, g), (n = l[t]) || (n = l[t] = [], n.delegateCount = 0, k.setup && !1 !== k.setup.call(a, e, p, h) || (a.addEventListener ? a.addEventListener(t, h, !1) : a.attachEvent && a.attachEvent("on" + t, h))), k.add && (k.add.call(a, r), r.handler.guid || (r.handler.guid = d.guid)), f ? n.splice(n.delegateCount++, 0, r) : n.push(r),
                    c.event.global[t] = !0;
                a = null
            }
        },
        remove: function(a, b, d, e, f) {
            var g, h, k, l, m, r, n, t, p, q, z, v = c.hasData(a) && c._data(a);
            if (v && (l = v.events)) {
                b = (b || "").match(L) || [""];
                for (m = b.length; m--;)
                    if (k = Cb.exec(b[m]) || [], p = z = k[1], q = (k[2] || "").split(".").sort(), p) {
                        n = c.event.special[p] || {};
                        p = (e ? n.delegateType : n.bindType) || p;
                        t = l[p] || [];
                        k = k[2] && new RegExp("(^|\\.)" + q.join("\\.(?:.*\\.|)") + "(\\.|$)");
                        for (h = g = t.length; g--;) r = t[g], !f && z !== r.origType || d && d.guid !== r.guid || k && !k.test(r.namespace) || e && e !== r.selector && ("**" !== e || !r.selector) ||

                            (t.splice(g, 1), r.selector && t.delegateCount--, n.remove && n.remove.call(a, r));
                        h && !t.length && (n.teardown && !1 !== n.teardown.call(a, q, v.handle) || c.removeEvent(a, p, v.handle), delete l[p])
                    } else
                        for (p in l) c.event.remove(a, p + b[m], d, e, !0);
                c.isEmptyObject(l) && (delete v.handle, c._removeData(a, "events"))
            }
        },
        trigger: function(a, b, d, e) {
            var f, g, h, k, l, m, r = [d || p],
                n = a.type || a;
            m = a.namespace ? a.namespace.split(".") : [];
            g = f = d = d || p;
            if (3 !== d.nodeType && 8 !== d.nodeType && !Bb.test(n + c.event.triggered) && (0 <= n.indexOf(".") && (m = n.split("."),
                n = m.shift(), m.sort()), k = 0 > n.indexOf(":") && "on" + n, a = a[c.expando] ? a : new c.Event(n, "object" === typeof a && a), a.isTrigger = !0, a.namespace = m.join("."), a.namespace_re = a.namespace ? new RegExp("(^|\\.)" + m.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, a.result = void 0, a.target || (a.target = d), b = null == b ? [a] : c.makeArray(b, [a]), m = c.event.special[n] || {}, e || !m.trigger || !1 !== m.trigger.apply(d, b))) {
                if (!e && !m.noBubble && !c.isWindow(d)) {
                    h = m.delegateType || n;
                    Bb.test(h + n) || (g = g.parentNode);
                    for (; g; g = g.parentNode) r.push(g), f = g;
                    f === (d.ownerDocument ||
                        p) && r.push(f.defaultView || f.parentWindow || q)
                }
                for (f = 0;
                    (g = r[f++]) && !a.isPropagationStopped();) a.type = 1 < f ? h : m.bindType || n, (l = (c._data(g, "events") || {})[a.type] && c._data(g, "handle")) && l.apply(g, b), (l = k && g[k]) && c.acceptData(g) && l.apply && !1 === l.apply(g, b) && a.preventDefault();
                a.type = n;
                if (!(e || a.isDefaultPrevented() || m._default && !1 !== m._default.apply(d.ownerDocument, b) || "click" === n && c.nodeName(d, "a")) && c.acceptData(d) && k && d[n] && !c.isWindow(d)) {
                    (f = d[k]) && (d[k] = null);
                    c.event.triggered = n;
                    try {
                        d[n]()
                    } catch (t) {}
                    c.event.triggered =
                        void 0;
                    f && (d[k] = f)
                }
                return a.result
            }
        },
        dispatch: function(a) {
            a = c.event.fix(a);
            var b, d, e, f, g = [],
                h = Q.call(arguments);
            b = (c._data(this, "events") || {})[a.type] || [];
            var k = c.event.special[a.type] || {};
            h[0] = a;
            a.delegateTarget = this;
            if (!k.preDispatch || !1 !== k.preDispatch.call(this, a)) {
                g = c.event.handlers.call(this, a, b);
                for (b = 0;
                    (f = g[b++]) && !a.isPropagationStopped();)
                    for (a.currentTarget = f.elem, d = 0;
                        (e = f.handlers[d++]) && !a.isImmediatePropagationStopped();)
                        if (!a.namespace_re || a.namespace_re.test(e.namespace)) a.handleObj =
                            e, a.data = e.data, e = ((c.event.special[e.origType] || {}).handle || e.handler).apply(f.elem, h), void 0 !== e && !1 === (a.result = e) && (a.preventDefault(), a.stopPropagation());
                k.postDispatch && k.postDispatch.call(this, a);
                return a.result
            }
        },
        handlers: function(a, b) {
            var d, e, f, g, h = [],
                k = b.delegateCount,
                l = a.target;
            if (k && l.nodeType && (!a.button || "click" !== a.type))
                for (; l != this; l = l.parentNode || this)
                    if (!0 !== l.disabled || "click" !== a.type) {
                        e = [];
                        for (d = 0; d < k; d++) g = b[d], f = g.selector + " ", void 0 === e[f] && (e[f] = g.needsContext ? 0 <= c(f, this).index(l) :
                            c.find(f, this, null, [l]).length), e[f] && e.push(g);
                        e.length && h.push({
                            elem: l,
                            handlers: e
                        })
                    }
            k < b.length && h.push({
                elem: this,
                handlers: b.slice(k)
            });
            return h
        },
        fix: function(a) {
            if (a[c.expando]) return a;
            var b, d, e = a,
                f = c.event.fixHooks[a.type] || {},
                g = f.props ? this.props.concat(f.props) : this.props;
            a = new c.Event(e);
            for (b = g.length; b--;) d = g[b], a[d] = e[d];
            a.target || (a.target = e.srcElement || p);
            3 === a.target.nodeType && (a.target = a.target.parentNode);
            a.metaKey = !!a.metaKey;
            return f.filter ? f.filter(a, e) : a
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: ["char", "charCode", "key", "keyCode"],
            filter: function(a, b) {
                null == a.which && (a.which = null != b.charCode ? b.charCode : b.keyCode);
                return a
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(a, b) {
                var c, e, f = b.button,
                    g = b.fromElement;
                null == a.pageX && null != b.clientX && (c = a.target.ownerDocument || p, e = c.documentElement, c = c.body, a.pageX = b.clientX + (e && e.scrollLeft || c && c.scrollLeft || 0) - (e && e.clientLeft ||
                    c && c.clientLeft || 0), a.pageY = b.clientY + (e && e.scrollTop || c && c.scrollTop || 0) - (e && e.clientTop || c && c.clientTop || 0));
                !a.relatedTarget && g && (a.relatedTarget = g === a.target ? b.toElement : g);
                a.which || void 0 === f || (a.which = f & 1 ? 1 : f & 2 ? 3 : f & 4 ? 2 : 0);
                return a
            }
        },
        special: {
            load: {
                noBubble: !0
            },
            click: {
                trigger: function() {
                    if (c.nodeName(this, "input") && "checkbox" === this.type && this.click) return this.click(), !1
                }
            },
            focus: {
                trigger: function() {
                    if (this !== p.activeElement && this.focus) try {
                        return this.focus(), !1
                    } catch (a) {}
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    if (this === p.activeElement && this.blur) return this.blur(), !1
                },
                delegateType: "focusout"
            },
            beforeunload: {
                postDispatch: function(a) {
                    void 0 !== a.result && (a.originalEvent.returnValue = a.result)
                }
            }
        },
        simulate: function(a, b, d, e) {
            a = c.extend(new c.Event, d, {
                type: a,
                isSimulated: !0,
                originalEvent: {}
            });
            e ? c.event.trigger(a, null, b) : c.event.dispatch.call(b, a);
            a.isDefaultPrevented() && d.preventDefault()
        }
    };
    c.removeEvent = p.removeEventListener ? function(a, b, c) {
        a.removeEventListener && a.removeEventListener(b,
            c, !1)
    } : function(a, b, c) {
        b = "on" + b;
        a.detachEvent && ("undefined" === typeof a[b] && (a[b] = null), a.detachEvent(b, c))
    };
    c.Event = function(a, b) {
        if (!(this instanceof c.Event)) return new c.Event(a, b);
        a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || !1 === a.returnValue || a.getPreventDefault && a.getPreventDefault() ? Z : H) : this.type = a;
        b && c.extend(this, b);
        this.timeStamp = a && a.timeStamp || c.now();
        this[c.expando] = !0
    };
    c.Event.prototype = {
        isDefaultPrevented: H,
        isPropagationStopped: H,
        isImmediatePropagationStopped: H,
        preventDefault: function() {
            var a = this.originalEvent;
            this.isDefaultPrevented = Z;
            a && (a.preventDefault ? a.preventDefault() : a.returnValue = !1)
        },
        stopPropagation: function() {
            var a = this.originalEvent;
            this.isPropagationStopped = Z;
            a && (a.stopPropagation && a.stopPropagation(), a.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = Z;
            this.stopPropagation()
        }
    };
    c.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    }, function(a, b) {
        c.event.special[a] = {
            delegateType: b,
            bindType: b,
            handle: function(a) {
                var e,
                    f = a.relatedTarget,
                    g = a.handleObj;
                if (!f || f !== this && !c.contains(this, f)) a.type = g.origType, e = g.handler.apply(this, arguments), a.type = b;
                return e
            }
        }
    });
    c.support.submitBubbles || (c.event.special.submit = {
        setup: function() {
            if (c.nodeName(this, "form")) return !1;
            c.event.add(this, "click._submit keypress._submit", function(a) {
                a = a.target;
                (a = c.nodeName(a, "input") || c.nodeName(a, "button") ? a.form : void 0) && !c._data(a, "submitBubbles") && (c.event.add(a, "submit._submit", function(a) {
                    a._submit_bubble = !0
                }), c._data(a, "submitBubbles", !0))
            })
        },
        postDispatch: function(a) {
            a._submit_bubble && (delete a._submit_bubble, this.parentNode && !a.isTrigger && c.event.simulate("submit", this.parentNode, a, !0))
        },
        teardown: function() {
            if (c.nodeName(this, "form")) return !1;
            c.event.remove(this, "._submit")
        }
    });
    c.support.changeBubbles || (c.event.special.change = {
        setup: function() {
            if (Ka.test(this.nodeName)) {
                if ("checkbox" === this.type || "radio" === this.type) c.event.add(this, "propertychange._change", function(a) {
                    "checked" === a.originalEvent.propertyName && (this._just_changed = !0)
                }), c.event.add(this, "click._change", function(a) {
                    this._just_changed && !a.isTrigger && (this._just_changed = !1);
                    c.event.simulate("change", this, a, !0)
                });
                return !1
            }
            c.event.add(this, "beforeactivate._change", function(a) {
                a = a.target;
                Ka.test(a.nodeName) && !c._data(a, "changeBubbles") && (c.event.add(a, "change._change", function(a) {
                    !this.parentNode || a.isSimulated || a.isTrigger || c.event.simulate("change", this.parentNode, a, !0)
                }), c._data(a, "changeBubbles", !0))
            })
        },
        handle: function(a) {
            var b = a.target;
            if (this !== b || a.isSimulated ||
                a.isTrigger || "radio" !== b.type && "checkbox" !== b.type) return a.handleObj.handler.apply(this, arguments)
        },
        teardown: function() {
            c.event.remove(this, "._change");
            return !Ka.test(this.nodeName)
        }
    });
    c.support.focusinBubbles || c.each({
        focus: "focusin",
        blur: "focusout"
    }, function(a, b) {
        var d = 0,
            e = function(a) {
                c.event.simulate(b, a.target, c.event.fix(a), !0)
            };
        c.event.special[b] = {
            setup: function() {
                0 === d++ && p.addEventListener(a, e, !0)
            },
            teardown: function() {
                0 === --d && p.removeEventListener(a, e, !0)
            }
        }
    });
    c.fn.extend({
        on: function(a,
            b, d, e, f) {
            var g, h;
            if ("object" === typeof a) {
                "string" !== typeof b && (d = d || b, b = void 0);
                for (h in a) this.on(h, b, d, a[h], f);
                return this
            }
            null == d && null == e ? (e = b, d = b = void 0) : null == e && ("string" === typeof b ? (e = d, d = void 0) : (e = d, d = b, b = void 0));
            if (!1 === e) e = H;
            else if (!e) return this;
            1 === f && (g = e, e = function(a) {
                c().off(a);
                return g.apply(this, arguments)
            }, e.guid = g.guid || (g.guid = c.guid++));
            return this.each(function() {
                c.event.add(this, a, e, d, b)
            })
        },
        one: function(a, b, c, e) {
            return this.on(a, b, c, e, 1)
        },
        off: function(a, b, d) {
            var e;
            if (a &&
                a.preventDefault && a.handleObj) return e = a.handleObj, c(a.delegateTarget).off(e.namespace ? e.origType + "." + e.namespace : e.origType, e.selector, e.handler), this;
            if ("object" === typeof a) {
                for (e in a) this.off(e, b, a[e]);
                return this
            }
            if (!1 === b || "function" === typeof b) d = b, b = void 0;
            !1 === d && (d = H);
            return this.each(function() {
                c.event.remove(this, a, d, b)
            })
        },
        bind: function(a, b, c) {
            return this.on(a, null, b, c)
        },
        unbind: function(a, b) {
            return this.off(a, null, b)
        },
        delegate: function(a, b, c, e) {
            return this.on(b, a, c, e)
        },
        undelegate: function(a,
            b, c) {
            return 1 === arguments.length ? this.off(a, "**") : this.off(b, a || "**", c)
        },
        trigger: function(a, b) {
            return this.each(function() {
                c.event.trigger(a, b, this)
            })
        },
        triggerHandler: function(a, b) {
            var d = this[0];
            if (d) return c.event.trigger(a, b, d, !0)
        },
        hover: function(a, b) {
            return this.mouseenter(a).mouseleave(b || a)
        }
    });
    c.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),
        function(a, b) {
            c.fn[b] = function(a, c) {
                return 0 < arguments.length ? this.on(b, null, a, c) : this.trigger(b)
            };
            rc.test(b) && (c.event.fixHooks[b] = c.event.keyHooks);
            sc.test(b) && (c.event.fixHooks[b] = c.event.mouseHooks)
        });
    (function(a, b) {
        function d(a) {
            return ha.test(a + "")
        }

        function e() {
            var a, b = [];
            return a = function(c, d) {
                b.push(c += " ") > w.cacheLength && delete a[b.shift()];
                return a[c] = d
            }
        }

        function f(a) {
            a[A] = !0;
            return a
        }

        function g(a) {
            var b = B.createElement("div");
            try {
                return a(b)
            } catch (c) {
                return !1
            } finally {}
        }

        function h(a, b, c,
            d) {
            var e, f, g, h, k;
            (b ? b.ownerDocument || b : F) !== B && na(b);
            b = b || B;
            c = c || [];
            if (!a || "string" !== typeof a) return c;
            if (1 !== (h = b.nodeType) && 9 !== h) return [];
            if (!J && !d) {
                if (e = ia.exec(a))
                    if (g = e[1])
                        if (9 === h)
                            if ((f = b.getElementById(g)) && f.parentNode) {
                                if (f.id === g) return c.push(f), c
                            } else return c;
                else {
                    if (b.ownerDocument && (f = b.ownerDocument.getElementById(g)) && oa(b, f) && f.id === g) return c.push(f), c
                } else {
                    if (e[2]) return G.apply(c, H.call(b.getElementsByTagName(a), 0)), c;
                    if ((g = e[3]) && C.getByClassName && b.getElementsByClassName) return G.apply(c,
                        H.call(b.getElementsByClassName(g), 0)), c
                } if (C.qsa && !y.test(a)) {
                    e = !0;
                    f = A;
                    g = b;
                    k = 9 === h && a;
                    if (1 === h && "object" !== b.nodeName.toLowerCase()) {
                        h = n(a);
                        (e = b.getAttribute("id")) ? f = e.replace(la, "\\$&"): b.setAttribute("id", f);
                        f = "[id='" + f + "'] ";
                        for (g = h.length; g--;) h[g] = f + p(h[g]);
                        g = Z.test(a) && b.parentNode || b;
                        k = h.join(",")
                    }
                    if (k) try {
                        return G.apply(c, H.call(g.querySelectorAll(k), 0)), c
                    } catch (l) {} finally {
                        e || b.removeAttribute("id")
                    }
                }
            }
            var m;
            a: {
                a = a.replace(D, "$1");
                f = n(a);
                if (!d && 1 === f.length) {
                    e = f[0] = f[0].slice(0);
                    if (2 < e.length &&
                        "ID" === (m = e[0]).type && 9 === b.nodeType && !J && w.relative[e[1].type]) {
                        b = w.find.ID(m.matches[0].replace(aa, ba), b)[0];
                        if (!b) {
                            m = c;
                            break a
                        }
                        a = a.slice(e.shift().value.length)
                    }
                    for (h = U.needsContext.test(a) ? -1 : e.length - 1; 0 <= h; h--) {
                        m = e[h];
                        if (w.relative[g = m.type]) break;
                        if (g = w.find[g])
                            if (d = g(m.matches[0].replace(aa, ba), Z.test(e[0].type) && b.parentNode || b)) {
                                e.splice(h, 1);
                                a = d.length && p(e);
                                if (!a) {
                                    G.apply(c, H.call(d, 0));
                                    m = c;
                                    break a
                                }
                                break
                            }
                    }
                }
                Ma(a, f)(d, b, J, c, Z.test(a));
                m = c
            }
            return m
        }

        function k(a, b) {
            for (var c = a && b && a.nextSibling; c; c =
                c.nextSibling)
                if (c === b) return -1;
            return a ? 1 : -1
        }

        function l(a) {
            return function(b) {
                return "input" === b.nodeName.toLowerCase() && b.type === a
            }
        }

        function m(a) {
            return function(b) {
                var c = b.nodeName.toLowerCase();
                return ("input" === c || "button" === c) && b.type === a
            }
        }

        function r(a) {
            return f(function(b) {
                b = +b;
                return f(function(c, d) {
                    for (var e, f = a([], c.length, b), g = f.length; g--;) c[e = f[g]] && (c[e] = !(d[e] = c[e]))
                })
            })
        }

        function n(a, b) {
            var c, d, e, f, g, k, l;
            if (g = O[a + " "]) return b ? 0 : g.slice(0);
            g = a;
            k = [];
            for (l = w.preFilter; g;) {
                if (!c || (d = ca.exec(g))) d &&
                    (g = g.slice(d[0].length) || g), k.push(e = []);
                c = !1;
                if (d = da.exec(g)) c = d.shift(), e.push({
                    value: c,
                    type: d[0].replace(D, " ")
                }), g = g.slice(c.length);
                for (f in w.filter)!(d = U[f].exec(g)) || l[f] && !(d = l[f](d)) || (c = d.shift(), e.push({
                    value: c,
                    type: f,
                    matches: d
                }), g = g.slice(c.length));
                if (!c) break
            }
            return b ? g.length : g ? h.error(a) : O(a, k).slice(0)
        }

        function p(a) {
            for (var b = 0, c = a.length, d = ""; b < c; b++) d += a[b].value;
            return d
        }

        function q(a, b, c) {
            var d = b.dir,
                e = c && "parentNode" === b.dir,
                f = V++;
            return b.first ? function(b, c, f) {
                for (; b = b[d];)
                    if (1 ===
                        b.nodeType || e) return a(b, c, f)
            } : function(b, c, g) {
                var h, k, La, l = R + " " + f;
                if (g)
                    for (; b = b[d];) {
                        if ((1 === b.nodeType || e) && a(b, c, g)) return !0
                    } else
                        for (; b = b[d];)
                            if (1 === b.nodeType || e)
                                if (La = b[A] || (b[A] = {}), (k = La[d]) && k[0] === l) {
                                    if (!0 === (h = k[1]) || h === u) return !0 === h
                                } else if (k = La[d] = [l], k[1] = a(b, c, g) || u, !0 === k[1]) return !0
            }
        }

        function v(a) {
            return 1 < a.length ? function(b, c, d) {
                for (var e = a.length; e--;)
                    if (!a[e](b, c, d)) return !1;
                return !0
            } : a[0]
        }

        function z(a, b, c, d, e) {
            for (var f, g = [], h = 0, k = a.length, l = null != b; h < k; h++)
                if (f = a[h])
                    if (!c ||
                        c(f, d, e)) g.push(f), l && b.push(h);
            return g
        }

        function K(a, b, c, d, e, g) {
            d && !d[A] && (d = K(d));
            e && !e[A] && (e = K(e, g));
            return f(function(f, g, k, l) {
                var m, n, r = [],
                    p = [],
                    w = g.length,
                    t;
                if (!(t = f)) {
                    t = b || "*";
                    for (var q = k.nodeType ? [k] : k, fa = [], u = 0, x = q.length; u < x; u++) h(t, q[u], fa);
                    t = fa
                }
                t = !a || !f && b ? t : z(t, r, a, k, l);
                q = c ? e || (f ? a : w || d) ? [] : g : t;
                c && c(t, q, k, l);
                if (d)
                    for (m = z(q, p), d(m, [], k, l), k = m.length; k--;)
                        if (n = m[k]) q[p[k]] = !(t[p[k]] = n);
                if (f) {
                    if (e || a) {
                        if (e) {
                            m = [];
                            for (k = q.length; k--;)(n = q[k]) && m.push(t[k] = n);
                            e(null, q = [], m, l)
                        }
                        for (k = q.length; k--;)(n =
                            q[k]) && -1 < (m = e ? P.call(f, n) : r[k]) && (f[m] = !(g[m] = n))
                    }
                } else q = z(q === g ? q.splice(w, q.length) : q), e ? e(null, g, q, l) : G.apply(g, q)
            })
        }

        function L(a) {
            var b, c, d, e = a.length,
                f = w.relative[a[0].type];
            c = f || w.relative[" "];
            for (var g = f ? 1 : 0, h = q(function(a) {
                return a === b
            }, c, !0), k = q(function(a) {
                return -1 < P.call(b, a)
            }, c, !0), l = [
                function(a, c, d) {
                    return !f && (d || c !== ta) || ((b = c).nodeType ? h(a, c, d) : k(a, c, d))
                }
            ]; g < e; g++)
                if (c = w.relative[a[g].type]) l = [q(v(l), c)];
                else {
                    c = w.filter[a[g].type].apply(null, a[g].matches);
                    if (c[A]) {
                        for (d = ++g; d < e &&
                            !w.relative[a[d].type]; d++);
                        return K(1 < g && v(l), 1 < g && p(a.slice(0, g - 1)).replace(D, "$1"), c, g < d && L(a.slice(g, d)), d < e && L(a = a.slice(d)), d < e && p(a))
                    }
                    l.push(c)
                }
            return v(l)
        }

        function S(a, b) {
            var c = 0,
                d = 0 < b.length,
                e = 0 < a.length,
                g = function(f, g, k, l, m) {
                    var n, r, p = [],
                        q = 0,
                        t = "0",
                        fa = f && [],
                        x = null != m,
                        y = ta,
                        Ga = f || e && w.find.TAG("*", m && g.parentNode || g),
                        ra = R += null == y ? 1 : Math.E;
                    x && (ta = g !== B && g, u = c);
                    for (; null != (m = Ga[t]); t++) {
                        if (e && m) {
                            for (n = 0; r = a[n]; n++)
                                if (r(m, g, k)) {
                                    l.push(m);
                                    break
                                }
                            x && (R = ra, u = ++c)
                        }
                        d && ((m = !r && m) && q--, f && fa.push(m))
                    }
                    q +=
                        t;
                    if (d && t !== q) {
                        for (n = 0; r = b[n]; n++) r(fa, p, g, k);
                        if (f) {
                            if (0 < q)
                                for (; t--;) fa[t] || p[t] || (p[t] = Y.call(l));
                            p = z(p)
                        }
                        G.apply(l, p);
                        x && !f && 0 < p.length && 1 < q + b.length && h.uniqueSort(l)
                    }
                    x && (R = ra, ta = y);
                    return fa
                };
            return d ? f(g) : g
        }

        function M() {}
        var pa, u, w, ua, ra, Ma, qa, ta, na, B, x, J, y, E, va, oa, Na, A = "sizzle" + -new Date,
            F = a.document,
            C = {},
            R = 0,
            V = 0,
            N = e(),
            O = e(),
            Q = e(),
            X = typeof b,
            I = [],
            Y = I.pop,
            G = I.push,
            H = I.slice,
            P = I.indexOf || function(a) {
                for (var b = 0, c = this.length; b < c; b++)
                    if (this[b] === a) return b;
                return -1
            },
            I = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+".replace("w",
                "w#"),
            W = "\\[[\\x20\\t\\r\\n\\f]*((?:\\\\.|[\\w-]|[^\\x00-\\xa0])+)[\\x20\\t\\r\\n\\f]*(?:([*^$|!~]?=)[\\x20\\t\\r\\n\\f]*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + I + ")|)|)[\\x20\\t\\r\\n\\f]*\\]",
            T = ":((?:\\\\.|[\\w-]|[^\\x00-\\xa0])+)(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + W.replace(3, 8) + ")*)|.*)\\)|)",
            D = /^[\x20\t\r\n\f]+|((?:^|[^\\])(?:\\.)*)[\x20\t\r\n\f]+$/g,
            ca = /^[\x20\t\r\n\f]*,[\x20\t\r\n\f]*/,
            da = /^[\x20\t\r\n\f]*([\x20\t\r\n\f>+~])[\x20\t\r\n\f]*/,
            ea = new RegExp(T),
            ga = new RegExp("^" +
                I + "$"),
            U = {
                ID: /^#((?:\\.|[\w-]|[^\x00-\xa0])+)/,
                CLASS: /^\.((?:\\.|[\w-]|[^\x00-\xa0])+)/,
                NAME: /^\[name=['"]?((?:\\.|[\w-]|[^\x00-\xa0])+)['"]?\]/,
                TAG: new RegExp("^(" + "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+".replace("w", "w*") + ")"),
                ATTR: new RegExp("^" + W),
                PSEUDO: new RegExp("^" + T),
                CHILD: /^:(only|first|last|nth|nth-last)-(child|of-type)(?:\([\x20\t\r\n\f]*(even|odd|(([+-]|)(\d*)n|)[\x20\t\r\n\f]*(?:([+-]|)[\x20\t\r\n\f]*(\d+)|))[\x20\t\r\n\f]*\)|)/i,
                needsContext: /^[\x20\t\r\n\f]*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\([\x20\t\r\n\f]*((?:-\d)?\d*)[\x20\t\r\n\f]*\)|)(?=[^-]|$)/i
            },
            Z = /[\x20\t\r\n\f]*[+~]/,
            ha = /\{\s*\[native code\]\s*\}/,
            ia = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
            ja = /^(?:input|select|textarea|button)$/i,
            ka = /^h\d$/i,
            la = /'|\\/g,
            ma = /\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,
            aa = /\\([\da-fA-F]{1,6}[\x20\t\r\n\f]?|.)/g,
            ba = function(a, b) {
                var c = "0x" + b - 65536;
                return c !== c ? b : 0 > c ? String.fromCharCode(c + 65536) : String.fromCharCode(c >> 10 | 55296, c & 1023 | 56320)
            };
        try {
            H.call(x.childNodes, 0)[0].nodeType
        } catch (Rc) {
            H = function(a) {
                for (var b, c = []; b = this[a]; a++) c.push(b);
                return c
            }
        }
        ra = h.isXML =
            function(a) {
                return (a = a && (a.ownerDocument || a).documentElement) ? "HTML" !== a.nodeName : !1
            };
        na = h.setDocument = function(a) {
            var c = a ? a.ownerDocument || a : F;
            if (c === B || 9 !== c.nodeType || !c.documentElement) return B;
            B = c;
            x = c.documentElement;
            J = ra(c);
            C.tagNameNoComments = g(function(a) {
                a.appendChild(c.createComment(""));
                return !a.getElementsByTagName("*").length
            });
            C.attributes = g(function(a) {
                a.innerHTML = "<select></select>";
                a = typeof a.lastChild.getAttribute("multiple");
                return "boolean" !== a && "string" !== a
            });
            C.getByClassName =
                g(function(a) {
                    a.innerHTML = "<div class='hidden e'></div><div class='hidden'></div>";
                    if (!a.getElementsByClassName || !a.getElementsByClassName("e").length) return !1;
                    a.lastChild.className = "e";
                    return 2 === a.getElementsByClassName("e").length
                });
            C.getByName = g(function(a) {
                a.id = A + 0;
                a.innerHTML = "<a name='" + A + "'></a><div name='" + A + "'></div>";
                x.insertBefore(a, x.firstChild);
                var b = c.getElementsByName && c.getElementsByName(A).length === 2 + c.getElementsByName(A + 0).length;
                C.getIdNotName = !c.getElementById(A);
                x.removeChild(a);
                return b
            });
            w.attrHandle = g(function(a) {
                a.innerHTML = "<a href='#'></a>";
                return a.firstChild && typeof a.firstChild.getAttribute !== X && "#" === a.firstChild.getAttribute("href")
            }) ? {} : {
                href: function(a) {
                    return a.getAttribute("href", 2)
                },
                type: function(a) {
                    return a.getAttribute("type")
                }
            };
            C.getIdNotName ? (w.find.ID = function(a, b) {
                if (typeof b.getElementById !== X && !J) {
                    var c = b.getElementById(a);
                    return c && c.parentNode ? [c] : []
                }
            }, w.filter.ID = function(a) {
                var b = a.replace(aa, ba);
                return function(a) {
                    return a.getAttribute("id") ===
                        b
                }
            }) : (w.find.ID = function(a, c) {
                if (typeof c.getElementById !== X && !J) {
                    var d = c.getElementById(a);
                    return d ? d.id === a || typeof d.getAttributeNode !== X && d.getAttributeNode("id").value === a ? [d] : b : []
                }
            }, w.filter.ID = function(a) {
                var b = a.replace(aa, ba);
                return function(a) {
                    return (a = typeof a.getAttributeNode !== X && a.getAttributeNode("id")) && a.value === b
                }
            });
            w.find.TAG = C.tagNameNoComments ? function(a, b) {
                if (typeof b.getElementsByTagName !== X) return b.getElementsByTagName(a)
            } : function(a, b) {
                var c, d = [],
                    e = 0,
                    f = b.getElementsByTagName(a);
                if ("*" === a) {
                    for (; c = f[e]; e++) 1 === c.nodeType && d.push(c);
                    return d
                }
                return f
            };
            w.find.NAME = C.getByName && function(a, b) {
                if (typeof b.getElementsByName !== X) return b.getElementsByName(name)
            };
            w.find.CLASS = C.getByClassName && function(a, b) {
                if (typeof b.getElementsByClassName !== X && !J) return b.getElementsByClassName(a)
            };
            E = [];
            y = [":focus"];
            if (C.qsa = d(c.querySelectorAll)) g(function(a) {
                a.innerHTML = "<select><option selected=''></option></select>";
                a.querySelectorAll("[selected]").length || y.push("\\[[\\x20\\t\\r\\n\\f]*(?:checked|disabled|ismap|multiple|readonly|selected|value)");
                a.querySelectorAll(":checked").length || y.push(":checked")
            }), g(function(a) {
                a.innerHTML = "<input type='hidden' i=''/>";
                a.querySelectorAll("[i^='']").length && y.push("[*^$]=[\\x20\\t\\r\\n\\f]*(?:\"\"|'')");
                a.querySelectorAll(":enabled").length || y.push(":enabled", ":disabled");
                a.querySelectorAll("*,:x");
                y.push(",.*:")
            });
            (C.matchesSelector = d(va = x.matchesSelector || x.mozMatchesSelector || x.webkitMatchesSelector || x.oMatchesSelector || x.msMatchesSelector)) && g(function(a) {
                C.disconnectedMatch = va.call(a, "div");
                va.call(a,
                    "[s!='']:x");
                E.push("!=", T)
            });
            y = new RegExp(y.join("|"));
            E = new RegExp(E.join("|"));
            oa = d(x.contains) || x.compareDocumentPosition ? function(a, b) {
                var c = 9 === a.nodeType ? a.documentElement : a,
                    d = b && b.parentNode;
                return a === d || !!(d && 1 === d.nodeType && (c.contains ? c.contains(d) : a.compareDocumentPosition && a.compareDocumentPosition(d) & 16))
            } : function(a, b) {
                if (b)
                    for (; b = b.parentNode;)
                        if (b === a) return !0;
                return !1
            };
            Na = x.compareDocumentPosition ? function(a, b) {
                var d;
                return a === b ? (qa = !0, 0) : (d = b.compareDocumentPosition && a.compareDocumentPosition &&
                    a.compareDocumentPosition(b)) ? d & 1 || a.parentNode && 11 === a.parentNode.nodeType ? a === c || oa(F, a) ? -1 : b === c || oa(F, b) ? 1 : 0 : d & 4 ? -1 : 1 : a.compareDocumentPosition ? -1 : 1
            } : function(a, b) {
                var d, e = 0;
                d = a.parentNode;
                var f = b.parentNode,
                    g = [a],
                    h = [b];
                if (a === b) return qa = !0, 0;
                if (a.sourceIndex && b.sourceIndex) return (~b.sourceIndex || -2147483648) - (oa(F, a) && ~a.sourceIndex || -2147483648);
                if (!d || !f) return a === c ? -1 : b === c ? 1 : d ? -1 : f ? 1 : 0;
                if (d === f) return k(a, b);
                for (d = a; d = d.parentNode;) g.unshift(d);
                for (d = b; d = d.parentNode;) h.unshift(d);
                for (; g[e] ===
                    h[e];) e++;
                return e ? k(g[e], h[e]) : g[e] === F ? -1 : h[e] === F ? 1 : 0
            };
            qa = !1;
            [0, 0].sort(Na);
            C.detectDuplicates = qa;
            return B
        };
        h.matches = function(a, b) {
            return h(a, null, null, b)
        };
        h.matchesSelector = function(a, b) {
            (a.ownerDocument || a) !== B && na(a);
            b = b.replace(ma, "='$1']");
            if (!(!C.matchesSelector || J || E && E.test(b) || y.test(b))) try {
                var c = va.call(a, b);
                if (c || C.disconnectedMatch || a.document && 11 !== a.document.nodeType) return c
            } catch (d) {}
            return 0 < h(b, B, null, [a]).length
        };
        h.contains = function(a, b) {
            (a.ownerDocument || a) !== B && na(a);
            return oa(a,
                b)
        };
        h.attr = function(a, b) {
            var c;
            (a.ownerDocument || a) !== B && na(a);
            J || (b = b.toLowerCase());
            return (c = w.attrHandle[b]) ? c(a) : J || C.attributes ? a.getAttribute(b) : ((c = a.getAttributeNode(b)) || a.getAttribute(b)) && !0 === a[b] ? b : c && c.specified ? c.value : null
        };
        h.error = function(a) {
            throw Error("Syntax error, unrecognized expression: " + a);
        };
        h.uniqueSort = function(a) {
            var b, c = [],
                d = 1,
                e = 0;
            qa = !C.detectDuplicates;
            a.sort(Na);
            if (qa) {
                for (; b = a[d]; d++) b === a[d - 1] && (e = c.push(d));
                for (; e--;) a.splice(c[e], 1)
            }
            return a
        };
        ua = h.getText = function(a) {
            var b,
                c = "",
                d = 0;
            b = a.nodeType;
            if (!b)
                for (; b = a[d]; d++) c += ua(b);
            else if (1 === b || 9 === b || 11 === b) {
                if ("string" === typeof a.textContent) return a.textContent;
                for (a = a.firstChild; a; a = a.nextSibling) c += ua(a)
            } else if (3 === b || 4 === b) return a.nodeValue;
            return c
        };
        w = h.selectors = {
            cacheLength: 50,
            createPseudo: f,
            match: U,
            find: {},
            relative: {
                ">": {
                    dir: "parentNode",
                    first: !0
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: !0
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(a) {
                    a[1] = a[1].replace(aa, ba);
                    a[3] = (a[4] || a[5] || "").replace(aa,
                        ba);
                    "~=" === a[2] && (a[3] = " " + a[3] + " ");
                    return a.slice(0, 4)
                },
                CHILD: function(a) {
                    a[1] = a[1].toLowerCase();
                    "nth" === a[1].slice(0, 3) ? (a[3] || h.error(a[0]), a[4] = +(a[4] ? a[5] + (a[6] || 1) : 2 * ("even" === a[3] || "odd" === a[3])), a[5] = +(a[7] + a[8] || "odd" === a[3])) : a[3] && h.error(a[0]);
                    return a
                },
                PSEUDO: function(a) {
                    var b, c = !a[5] && a[2];
                    if (U.CHILD.test(a[0])) return null;
                    a[4] ? a[2] = a[4] : c && ea.test(c) && (b = n(c, !0)) && (b = c.indexOf(")", c.length - b) - c.length) && (a[0] = a[0].slice(0, b), a[2] = c.slice(0, b));
                    return a.slice(0, 3)
                }
            },
            filter: {
                TAG: function(a) {
                    if ("*" ===
                        a) return function() {
                        return !0
                    };
                    a = a.replace(aa, ba).toLowerCase();
                    return function(b) {
                        return b.nodeName && b.nodeName.toLowerCase() === a
                    }
                },
                CLASS: function(a) {
                    var b = N[a + " "];
                    return b || (b = new RegExp("(^|[\\x20\\t\\r\\n\\f])" + a + "([\\x20\\t\\r\\n\\f]|$)")) && N(a, function(a) {
                        return b.test(a.className || typeof a.getAttribute !== X && a.getAttribute("class") || "")
                    })
                },
                ATTR: function(a, b, c) {
                    return function(d) {
                        d = h.attr(d, a);
                        if (null == d) return "!=" === b;
                        if (!b) return !0;
                        d += "";
                        return "=" === b ? d === c : "!=" === b ? d !== c : "^=" === b ? c && 0 === d.indexOf(c) :
                            "*=" === b ? c && -1 < d.indexOf(c) : "$=" === b ? c && d.substr(d.length - c.length) === c : "~=" === b ? -1 < (" " + d + " ").indexOf(c) : "|=" === b ? d === c || d.substr(0, c.length + 1) === c + "-" : !1
                    }
                },
                CHILD: function(a, b, c, d, e) {
                    var f = "nth" !== a.slice(0, 3),
                        g = "last" !== a.slice(-4),
                        h = "of-type" === b;
                    return 1 === d && 0 === e ? function(a) {
                        return !!a.parentNode
                    } : function(b, c, k) {
                        var l, m, n, r, p;
                        c = f !== g ? "nextSibling" : "previousSibling";
                        var q = b.parentNode,
                            t = h && b.nodeName.toLowerCase();
                        k = !k && !h;
                        if (q) {
                            if (f) {
                                for (; c;) {
                                    for (m = b; m = m[c];)
                                        if (h ? m.nodeName.toLowerCase() ===
                                            t : 1 === m.nodeType) return !1;
                                    p = c = "only" === a && !p && "nextSibling"
                                }
                                return !0
                            }
                            p = [g ? q.firstChild : q.lastChild];
                            if (g && k)
                                for (k = q[A] || (q[A] = {}), l = k[a] || [], r = l[0] === R && l[1], n = l[0] === R && l[2], m = r && q.childNodes[r]; m = ++r && m && m[c] || (n = r = 0) || p.pop();) {
                                    if (1 === m.nodeType && ++n && m === b) {
                                        k[a] = [R, r, n];
                                        break
                                    }
                                } else if (k && (l = (b[A] || (b[A] = {}))[a]) && l[0] === R) n = l[1];
                                else
                                    for (;
                                        (m = ++r && m && m[c] || (n = r = 0) || p.pop()) && ((h ? m.nodeName.toLowerCase() !== t : 1 !== m.nodeType) || !++n || (k && ((m[A] || (m[A] = {}))[a] = [R, n]), m !== b)););
                            n -= e;
                            return n === d || 0 ===
                                n % d && 0 <= n / d
                        }
                    }
                },
                PSEUDO: function(a, b) {
                    var c, d = w.pseudos[a] || w.setFilters[a.toLowerCase()] || h.error("unsupported pseudo: " + a);
                    return d[A] ? d(b) : 1 < d.length ? (c = [a, a, "", b], w.setFilters.hasOwnProperty(a.toLowerCase()) ? f(function(a, c) {
                        for (var e, f = d(a, b), g = f.length; g--;) e = P.call(a, f[g]), a[e] = !(c[e] = f[g])
                    }) : function(a) {
                        return d(a, 0, c)
                    }) : d
                }
            },
            pseudos: {
                not: f(function(a) {
                    var b = [],
                        c = [],
                        d = Ma(a.replace(D, "$1"));
                    return d[A] ? f(function(a, b, c, e) {
                        e = d(a, null, e, []);
                        for (var f = a.length; f--;)
                            if (c = e[f]) a[f] = !(b[f] = c)
                    }) : function(a,
                        e, f) {
                        b[0] = a;
                        d(b, null, f, c);
                        return !c.pop()
                    }
                }),
                has: f(function(a) {
                    return function(b) {
                        return 0 < h(a, b).length
                    }
                }),
                contains: f(function(a) {
                    return function(b) {
                        return -1 < (b.textContent || b.innerText || ua(b)).indexOf(a)
                    }
                }),
                lang: f(function(a) {
                    ga.test(a || "") || h.error("unsupported lang: " + a);
                    a = a.replace(aa, ba).toLowerCase();
                    return function(b) {
                        var c;
                        do
                            if (c = J ? b.getAttribute("xml:lang") || b.getAttribute("lang") : b.lang) return c = c.toLowerCase(), c === a || 0 === c.indexOf(a + "-");
                        while ((b = b.parentNode) && 1 === b.nodeType);
                        return !1
                    }
                }),
                target: function(b) {
                    var c = a.location && a.location.hash;
                    return c && c.slice(1) === b.id
                },
                root: function(a) {
                    return a === x
                },
                focus: function(a) {
                    return a === B.activeElement && (!B.hasFocus || B.hasFocus()) && !!(a.type || a.href || ~a.tabIndex)
                },
                enabled: function(a) {
                    return !1 === a.disabled
                },
                disabled: function(a) {
                    return !0 === a.disabled
                },
                checked: function(a) {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && !!a.checked || "option" === b && !!a.selected
                },
                selected: function(a) {
                    a.parentNode && a.parentNode.selectedIndex;
                    return !0 === a.selected
                },
                empty: function(a) {
                    for (a = a.firstChild; a; a = a.nextSibling)
                        if ("@" < a.nodeName || 3 === a.nodeType || 4 === a.nodeType) return !1;
                    return !0
                },
                parent: function(a) {
                    return !w.pseudos.empty(a)
                },
                header: function(a) {
                    return ka.test(a.nodeName)
                },
                input: function(a) {
                    return ja.test(a.nodeName)
                },
                button: function(a) {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && "button" === a.type || "button" === b
                },
                text: function(a) {
                    var b;
                    return "input" === a.nodeName.toLowerCase() && "text" === a.type && (null == (b = a.getAttribute("type")) || b.toLowerCase() === a.type)
                },
                first: r(function() {
                    return [0]
                }),
                last: r(function(a, b) {
                    return [b - 1]
                }),
                eq: r(function(a, b, c) {
                    return [0 > c ? c + b : c]
                }),
                even: r(function(a, b) {
                    for (var c = 0; c < b; c += 2) a.push(c);
                    return a
                }),
                odd: r(function(a, b) {
                    for (var c = 1; c < b; c += 2) a.push(c);
                    return a
                }),
                lt: r(function(a, b, c) {
                    for (b = 0 > c ? c + b : c; 0 <= --b;) a.push(b);
                    return a
                }),
                gt: r(function(a, b, c) {
                    for (c = 0 > c ? c + b : c; ++c < b;) a.push(c);
                    return a
                })
            }
        };
        for (pa in {
            radio: !0,
            checkbox: !0,
            file: !0,
            password: !0,
            image: !0
        }) w.pseudos[pa] = l(pa);
        for (pa in {
            submit: !0,
            reset: !0
        }) w.pseudos[pa] = m(pa);
        Ma = h.compile =
            function(a, b) {
                var c, d = [],
                    e = [],
                    f = Q[a + " "];
                if (!f) {
                    b || (b = n(a));
                    for (c = b.length; c--;) f = L(b[c]), f[A] ? d.push(f) : e.push(f);
                    f = Q(a, S(e, d))
                }
                return f
            };
        w.pseudos.nth = w.pseudos.eq;
        w.filters = M.prototype = w.pseudos;
        w.setFilters = new M;
        na();
        h.attr = c.attr;
        c.find = h;
        c.expr = h.selectors;
        c.expr[":"] = c.expr.pseudos;
        c.unique = h.uniqueSort;
        c.text = h.getText;
        c.isXMLDoc = h.isXML;
        c.contains = h.contains
    })(q);
    var tc = /Until$/,
        uc = /^(?:parents|prev(?:Until|All))/,
        Ub = /^.[^:#\[\.,]*$/,
        Db = c.expr.match.needsContext,
        vc = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };
    c.fn.extend({
        find: function(a) {
            var b, d, e;
            if ("string" !== typeof a) return e = this, this.pushStack(c(a).filter(function() {
                for (b = 0; b < e.length; b++)
                    if (c.contains(e[b], this)) return !0
            }));
            d = [];
            for (b = 0; b < this.length; b++) c.find(a, this[b], d);
            d = this.pushStack(c.unique(d));
            d.selector = (this.selector ? this.selector + " " : "") + a;
            return d
        },
        has: function(a) {
            var b, d = c(a, this),
                e = d.length;
            return this.filter(function() {
                for (b = 0; b < e; b++)
                    if (c.contains(this, d[b])) return !0
            })
        },
        not: function(a) {
            return this.pushStack(ab(this,
                a, !1))
        },
        filter: function(a) {
            return this.pushStack(ab(this, a, !0))
        },
        is: function(a) {
            return !!a && ("string" === typeof a ? Db.test(a) ? 0 <= c(a, this.context).index(this[0]) : 0 < c.filter(a, this).length : 0 < this.filter(a).length)
        },
        closest: function(a, b) {
            for (var d, e = 0, f = this.length, g = [], h = Db.test(a) || "string" !== typeof a ? c(a, b || this.context) : 0; e < f; e++)
                for (d = this[e]; d && d.ownerDocument && d !== b && 11 !== d.nodeType;) {
                    if (h ? -1 < h.index(d) : c.find.matchesSelector(d, a)) {
                        g.push(d);
                        break
                    }
                    d = d.parentNode
                }
            return this.pushStack(1 < g.length ?
                c.unique(g) : g)
        },
        index: function(a) {
            return a ? "string" === typeof a ? c.inArray(this[0], c(a)) : c.inArray(a.jquery ? a[0] : a, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function(a, b) {
            var d = "string" === typeof a ? c(a, b) : c.makeArray(a && a.nodeType ? [a] : a),
                d = c.merge(this.get(), d);
            return this.pushStack(c.unique(d))
        },
        addBack: function(a) {
            return this.add(null == a ? this.prevObject : this.prevObject.filter(a))
        }
    });
    c.fn.andSelf = c.fn.addBack;
    c.each({
        parent: function(a) {
            return (a = a.parentNode) && 11 !== a.nodeType ?
                a : null
        },
        parents: function(a) {
            return c.dir(a, "parentNode")
        },
        parentsUntil: function(a, b, d) {
            return c.dir(a, "parentNode", d)
        },
        next: function(a) {
            return $a(a, "nextSibling")
        },
        prev: function(a) {
            return $a(a, "previousSibling")
        },
        nextAll: function(a) {
            return c.dir(a, "nextSibling")
        },
        prevAll: function(a) {
            return c.dir(a, "previousSibling")
        },
        nextUntil: function(a, b, d) {
            return c.dir(a, "nextSibling", d)
        },
        prevUntil: function(a, b, d) {
            return c.dir(a, "previousSibling", d)
        },
        siblings: function(a) {
            return c.sibling((a.parentNode || {}).firstChild,
                a)
        },
        children: function(a) {
            return c.sibling(a.firstChild)
        },
        contents: function(a) {
            return c.nodeName(a, "iframe") ? a.contentDocument || a.contentWindow.document : c.merge([], a.childNodes)
        }
    }, function(a, b) {
        c.fn[a] = function(d, e) {
            var f = c.map(this, b, d);
            tc.test(a) || (e = d);
            e && "string" === typeof e && (f = c.filter(e, f));
            f = 1 < this.length && !vc[a] ? c.unique(f) : f;
            1 < this.length && uc.test(a) && (f = f.reverse());
            return this.pushStack(f)
        }
    });
    c.extend({
        filter: function(a, b, d) {
            d && (a = ":not(" + a + ")");
            return 1 === b.length ? c.find.matchesSelector(b[0],
                a) ? [b[0]] : [] : c.find.matches(a, b)
        },
        dir: function(a, b, d) {
            var e = [];
            for (a = a[b]; a && 9 !== a.nodeType && (void 0 === d || 1 !== a.nodeType || !c(a).is(d));) 1 === a.nodeType && e.push(a), a = a[b];
            return e
        },
        sibling: function(a, b) {
            for (var c = []; a; a = a.nextSibling) 1 === a.nodeType && a !== b && c.push(a);
            return c
        }
    });
    var cb = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        wc = / jQuery\d+="(?:null|\d+)"/g,
        Eb = new RegExp("<(?:" + cb + ")[\\s/>]",
            "i"),
        Oa = /^\s+/,
        Fb = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        Gb = /<([\w:]+)/,
        Hb = /<tbody/i,
        xc = /<|&#?\w+;/,
        yc = /<(?:script|style|link)/i,
        za = /^(?:checkbox|radio)$/i,
        zc = /checked\s*(?:[^=]|=\s*.checked.)/i,
        Ib = /^$|\/(?:java|ecma)script/i,
        Wb = /^true\/(.*)/,
        Ac = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
        K = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            area: [1, "<map>", "</map>"],
            param: [1, "<object>", "</object>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: c.support.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
        },
        Pa = bb(p).appendChild(p.createElement("div"));
    K.optgroup = K.option;
    K.tbody = K.tfoot = K.colgroup = K.caption = K.thead;
    K.th = K.td;
    c.fn.extend({
        text: function(a) {
            return c.access(this, function(a) {
                    return void 0 === a ? c.text(this) : this.empty().append((this[0] && this[0].ownerDocument || p).createTextNode(a))
                },
                null, a, arguments.length)
        },
        wrapAll: function(a) {
            if (c.isFunction(a)) return this.each(function(b) {
                c(this).wrapAll(a.call(this, b))
            });
            if (this[0]) {
                var b = c(a, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && b.insertBefore(this[0]);
                b.map(function() {
                    for (var a = this; a.firstChild && 1 === a.firstChild.nodeType;) a = a.firstChild;
                    return a
                }).append(this)
            }
            return this
        },
        wrapInner: function(a) {
            return c.isFunction(a) ? this.each(function(b) {
                c(this).wrapInner(a.call(this, b))
            }) : this.each(function() {
                var b = c(this),
                    d = b.contents();
                d.length ? d.wrapAll(a) : b.append(a)
            })
        },
        wrap: function(a) {
            var b = c.isFunction(a);
            return this.each(function(d) {
                c(this).wrapAll(b ? a.call(this, d) : a)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                c.nodeName(this, "body") || c(this).replaceWith(this.childNodes)
            }).end()
        },
        append: function() {
            return this.domManip(arguments, !0, function(a) {
                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || this.appendChild(a)
            })
        },
        prepend: function() {
            return this.domManip(arguments, !0, function(a) {
                1 !== this.nodeType && 11 !==
                    this.nodeType && 9 !== this.nodeType || this.insertBefore(a, this.firstChild)
            })
        },
        before: function() {
            return this.domManip(arguments, !1, function(a) {
                this.parentNode && this.parentNode.insertBefore(a, this)
            })
        },
        after: function() {
            return this.domManip(arguments, !1, function(a) {
                this.parentNode && this.parentNode.insertBefore(a, this.nextSibling)
            })
        },
        remove: function(a, b) {
            for (var d, e = 0; null != (d = this[e]); e++)
                if (!a || 0 < c.filter(a, [d]).length) b || 1 !== d.nodeType || c.cleanData(z(d)), d.parentNode && (b && c.contains(d.ownerDocument, d) &&
                    ya(z(d, "script")), d.parentNode.removeChild(d));
            return this
        },
        empty: function() {
            for (var a, b = 0; null != (a = this[b]); b++) {
                for (1 === a.nodeType && c.cleanData(z(a, !1)); a.firstChild;) a.removeChild(a.firstChild);
                a.options && c.nodeName(a, "select") && (a.options.length = 0)
            }
            return this
        },
        clone: function(a, b) {
            a = null == a ? !1 : a;
            b = null == b ? a : b;
            return this.map(function() {
                return c.clone(this, a, b)
            })
        },
        html: function(a) {
            return c.access(this, function(a) {
                var d = this[0] || {},
                    e = 0,
                    f = this.length;
                if (void 0 === a) return 1 === d.nodeType ? d.innerHTML.replace(wc,
                    "") : void 0;
                if (!("string" !== typeof a || yc.test(a) || !c.support.htmlSerialize && Eb.test(a) || !c.support.leadingWhitespace && Oa.test(a) || K[(Gb.exec(a) || ["", ""])[1].toLowerCase()])) {
                    a = a.replace(Fb, "<$1></$2>");
                    try {
                        for (; e < f; e++) d = this[e] || {}, 1 === d.nodeType && (c.cleanData(z(d, !1)), d.innerHTML = a);
                        d = 0
                    } catch (g) {}
                }
                d && this.empty().append(a)
            }, null, a, arguments.length)
        },
        replaceWith: function(a) {
            c.isFunction(a) || "string" === typeof a || (a = c(a).not(this).detach());
            return this.domManip([a], !0, function(a) {
                var d = this.nextSibling,
                    e = this.parentNode;
                if (e && 1 === this.nodeType || 11 === this.nodeType) c(this).remove(), d ? d.parentNode.insertBefore(a, d) : e.appendChild(a)
            })
        },
        detach: function(a) {
            return this.remove(a, !0)
        },
        domManip: function(a, b, d) {
            a = wb.apply([], a);
            var e, f, g, h, k = 0,
                l = this.length,
                m = this,
                r = l - 1,
                n = a[0],
                p = c.isFunction(n);
            if (p || !(1 >= l || "string" !== typeof n || c.support.checkClone) && zc.test(n)) return this.each(function(c) {
                var e = m.eq(c);
                p && (a[0] = n.call(this, c, b ? e.html() : void 0));
                e.domManip(a, b, d)
            });
            if (l && (e = c.buildFragment(a, this[0].ownerDocument, !1, this), f = e.firstChild, 1 === e.childNodes.length && (e = f), f)) {
                b = b && c.nodeName(f, "tr");
                f = c.map(z(e, "script"), db);
                for (g = f.length; k < l; k++) h = e, k !== r && (h = c.clone(h, !0, !0), g && c.merge(f, z(h, "script"))), d.call(b && c.nodeName(this[k], "table") ? Vb(this[k], "tbody") : this[k], h, k);
                if (g)
                    for (e = f[f.length - 1].ownerDocument, c.map(f, eb), k = 0; k < g; k++) h = f[k], Ib.test(h.type || "") && !c._data(h, "globalEval") && c.contains(e, h) && (h.src ? c.ajax({
                        url: h.src,
                        type: "GET",
                        dataType: "script",
                        async: !1,
                        global: !1,
                        "throws": !0
                    }) : c.globalEval((h.text ||
                        h.textContent || h.innerHTML || "").replace(Ac, "")));
                e = f = null
            }
            return this
        }
    });
    c.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(a, b) {
        c.fn[a] = function(a) {
            for (var e = 0, f = [], g = c(a), h = g.length - 1; e <= h; e++) a = e === h ? this : this.clone(!0), c(g[e])[b](a), Da.apply(f, a.get());
            return this.pushStack(f)
        }
    });
    c.extend({
        clone: function(a, b, d) {
            var e, f, g, h, k, l = c.contains(a.ownerDocument, a);
            c.support.html5Clone || c.isXMLDoc(a) || !Eb.test("<" + a.nodeName + ">") ?
                k = a.cloneNode(!0) : (Pa.innerHTML = a.outerHTML, Pa.removeChild(k = Pa.firstChild));
            if (!(c.support.noCloneEvent && c.support.noCloneChecked || 1 !== a.nodeType && 11 !== a.nodeType || c.isXMLDoc(a)))
                for (e = z(k), f = z(a), h = 0; null != (g = f[h]); ++h)
                    if (e[h]) {
                        var m = e[h],
                            r = void 0,
                            n = void 0,
                            p = void 0;
                        if (1 === m.nodeType) {
                            r = m.nodeName.toLowerCase();
                            if (!c.support.noCloneEvent && m[c.expando]) {
                                n = c._data(m);
                                for (p in n.events) c.removeEvent(m, p, n.handle);
                                m.removeAttribute(c.expando)
                            }
                            if ("script" === r && m.text !== g.text) db(m).text = g.text, eb(m);
                            else if ("object" === r) m.parentNode && (m.outerHTML = g.outerHTML), c.support.html5Clone && g.innerHTML && !c.trim(m.innerHTML) && (m.innerHTML = g.innerHTML);
                            else if ("input" === r && za.test(g.type)) m.defaultChecked = m.checked = g.checked, m.value !== g.value && (m.value = g.value);
                            else if ("option" === r) m.defaultSelected = m.selected = g.defaultSelected;
                            else if ("input" === r || "textarea" === r) m.defaultValue = g.defaultValue
                        }
                    }
            if (b)
                if (d)
                    for (f = f || z(a), e = e || z(k), h = 0; null != (g = f[h]); h++) fb(g, e[h]);
                else fb(a, k);
            e = z(k, "script");
            0 < e.length && ya(e, !l && z(a, "script"));
            return k
        },
        buildFragment: function(a, b, d, e) {
            for (var f, g, h, k, l, m, r = a.length, n = bb(b), p = [], q = 0; q < r; q++)
                if ((f = a[q]) || 0 === f)
                    if ("object" === c.type(f)) c.merge(p, f.nodeType ? [f] : f);
                    else if (xc.test(f)) {
                h = h || n.appendChild(b.createElement("div"));
                g = (Gb.exec(f) || ["", ""])[1].toLowerCase();
                k = K[g] || K._default;
                h.innerHTML = k[1] + f.replace(Fb, "<$1></$2>") + k[2];
                for (m = k[0]; m--;) h = h.lastChild;
                !c.support.leadingWhitespace && Oa.test(f) && p.push(b.createTextNode(Oa.exec(f)[0]));
                if (!c.support.tbody)
                    for (m = (f = "table" !==
                        g || Hb.test(f) ? "<table>" !== k[1] || Hb.test(f) ? 0 : h : h.firstChild) && f.childNodes.length; m--;) c.nodeName(l = f.childNodes[m], "tbody") && !l.childNodes.length && f.removeChild(l);
                c.merge(p, h.childNodes);
                for (h.textContent = ""; h.firstChild;) h.removeChild(h.firstChild);
                h = n.lastChild
            } else p.push(b.createTextNode(f));
            h && n.removeChild(h);
            c.support.appendChecked || c.grep(z(p, "input"), Xb);
            for (q = 0; f = p[q++];)
                if (!e || -1 === c.inArray(f, e))
                    if (a = c.contains(f.ownerDocument, f), h = z(n.appendChild(f), "script"), a && ya(h), d)
                        for (m = 0; f = h[m++];) Ib.test(f.type ||
                            "") && d.push(f);
            return n
        },
        cleanData: function(a, b) {
            for (var d, e, f, g, h = 0, k = c.expando, l = c.cache, m = c.support.deleteExpando, p = c.event.special; null != (f = a[h]); h++)
                if (b || c.acceptData(f))
                    if (d = (e = f[k]) && l[e]) {
                        if (d.events)
                            for (g in d.events) p[g] ? c.event.remove(f, g) : c.removeEvent(f, g, d.handle);
                        l[e] && (delete l[e], m ? delete f[k] : "undefined" !== typeof f.removeAttribute ? f.removeAttribute(k) : f[k] = null, G.push(e))
                    }
        }
    });
    var O, N, da, Qa = /alpha\([^)]*\)/i,
        Bc = /opacity\s*=\s*([^)]*)/,
        Cc = /^(top|right|bottom|left)$/,
        Dc = /^(none|table(?!-c[ea]).+)/,
        Jb = /^margin/,
        Yb = new RegExp("^(" + ma + ")(.*)$", "i"),
        ha = new RegExp("^(" + ma + ")(?!px)[a-z%]+$", "i"),
        Ec = new RegExp("^([+-])=(" + ma + ")", "i"),
        nb = {
            BODY: "block"
        },
        Fc = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        Kb = {
            letterSpacing: 0,
            fontWeight: 400
        },
        V = ["Top", "Right", "Bottom", "Left"],
        hb = ["Webkit", "O", "Moz", "ms"];
    c.fn.extend({
        css: function(a, b) {
            return c.access(this, function(a, b, f) {
                var g, h = {},
                    k = 0;
                if (c.isArray(b)) {
                    f = N(a);
                    for (g = b.length; k < g; k++) h[b[k]] = c.css(a, b[k], !1, f);
                    return h
                }
                return void 0 !== f ? c.style(a,
                    b, f) : c.css(a, b)
            }, a, b, 1 < arguments.length)
        },
        show: function() {
            return ib(this, !0)
        },
        hide: function() {
            return ib(this)
        },
        toggle: function(a) {
            var b = "boolean" === typeof a;
            return this.each(function() {
                (b ? a : ca(this)) ? c(this).show(): c(this).hide()
            })
        }
    });
    c.extend({
        cssHooks: {
            opacity: {
                get: function(a, b) {
                    if (b) {
                        var c = O(a, "opacity");
                        return "" === c ? "1" : c
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            "float": c.support.cssFloat ? "cssFloat" : "styleFloat"
        },
        style: function(a, b, d, e) {
            if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style) {
                var f, g, h, k = c.camelCase(b),
                    l = a.style;
                b = c.cssProps[k] || (c.cssProps[k] = gb(l, k));
                h = c.cssHooks[b] || c.cssHooks[k];
                if (void 0 !== d) {
                    if (g = typeof d, "string" === g && (f = Ec.exec(d)) && (d = (f[1] + 1) * f[2] + parseFloat(c.css(a, b)), g = "number"), !(null == d || "number" === g && isNaN(d) || ("number" !== g || c.cssNumber[k] || (d += "px"), c.support.clearCloneStyle || "" !== d || 0 !== b.indexOf("background") || (l[b] = "inherit"), h && "set" in h && void 0 === (d = h.set(a, d, e))))) try {
                        l[b] = d
                    } catch (m) {}
                } else return h &&
                    "get" in h && void 0 !== (f = h.get(a, !1, e)) ? f : l[b]
            }
        },
        css: function(a, b, d, e) {
            var f, g;
            g = c.camelCase(b);
            b = c.cssProps[g] || (c.cssProps[g] = gb(a.style, g));
            (g = c.cssHooks[b] || c.cssHooks[g]) && "get" in g && (f = g.get(a, !0, d));
            void 0 === f && (f = O(a, b, e));
            "normal" === f && b in Kb && (f = Kb[b]);
            return d ? (a = parseFloat(f), !0 === d || c.isNumeric(a) ? a || 0 : f) : f
        },
        swap: function(a, b, c, e) {
            var f, g = {};
            for (f in b) g[f] = a.style[f], a.style[f] = b[f];
            c = c.apply(a, e || []);
            for (f in b) a.style[f] = g[f];
            return c
        }
    });
    q.getComputedStyle ? (N = function(a) {
        return q.getComputedStyle(a,
            null)
    }, O = function(a, b, d) {
        var e, f = (d = d || N(a)) ? d.getPropertyValue(b) || d[b] : void 0,
            g = a.style;
        d && ("" !== f || c.contains(a.ownerDocument, a) || (f = c.style(a, b)), ha.test(f) && Jb.test(b) && (a = g.width, b = g.minWidth, e = g.maxWidth, g.minWidth = g.maxWidth = g.width = f, f = d.width, g.width = a, g.minWidth = b, g.maxWidth = e));
        return f
    }) : p.documentElement.currentStyle && (N = function(a) {
        return a.currentStyle
    }, O = function(a, b, c) {
        var e, f, g = (c = c || N(a)) ? c[b] : void 0,
            h = a.style;
        null == g && h && h[b] && (g = h[b]);
        if (ha.test(g) && !Cc.test(b)) {
            c = h.left;
            if (f =
                (e = a.runtimeStyle) && e.left) e.left = a.currentStyle.left;
            h.left = "fontSize" === b ? "1em" : g;
            g = h.pixelLeft + "px";
            h.left = c;
            f && (e.left = f)
        }
        return "" === g ? "auto" : g
    });
    c.each(["height", "width"], function(a, b) {
        c.cssHooks[b] = {
            get: function(a, e, f) {
                if (e) return 0 === a.offsetWidth && Dc.test(c.css(a, "display")) ? c.swap(a, Fc, function() {
                    return mb(a, b, f)
                }) : mb(a, b, f)
            },
            set: function(a, e, f) {
                var g = f && N(a);
                return kb(a, e, f ? lb(a, b, f, c.support.boxSizing && "border-box" === c.css(a, "boxSizing", !1, g), g) : 0)
            }
        }
    });
    c.support.opacity || (c.cssHooks.opacity = {
        get: function(a, b) {
            return Bc.test((b && a.currentStyle ? a.currentStyle.filter : a.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : b ? "1" : ""
        },
        set: function(a, b) {
            var d = a.style,
                e = a.currentStyle,
                f = c.isNumeric(b) ? "alpha(opacity=" + 100 * b + ")" : "",
                g = e && e.filter || d.filter || "";
            d.zoom = 1;
            if ((1 <= b || "" === b) && "" === c.trim(g.replace(Qa, "")) && d.removeAttribute && (d.removeAttribute("filter"), "" === b || e && !e.filter)) return;
            d.filter = Qa.test(g) ? g.replace(Qa, f) : g + " " + f
        }
    });
    c(function() {
        c.support.reliableMarginRight || (c.cssHooks.marginRight = {
            get: function(a, b) {
                if (b) return c.swap(a, {
                    display: "inline-block"
                }, O, [a, "marginRight"])
            }
        });
        !c.support.pixelPosition && c.fn.position && c.each(["top", "left"], function(a, b) {
            c.cssHooks[b] = {
                get: function(a, e) {
                    if (e) return e = O(a, b), ha.test(e) ? c(a).position()[b] + "px" : e
                }
            }
        })
    });
    c.expr && c.expr.filters && (c.expr.filters.hidden = function(a) {
        return 0 === a.offsetWidth && 0 === a.offsetHeight || !c.support.reliableHiddenOffsets && "none" === (a.style && a.style.display || c.css(a, "display"))
    }, c.expr.filters.visible = function(a) {
        return !c.expr.filters.hidden(a)
    });
    c.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function(a, b) {
        c.cssHooks[a + b] = {
            expand: function(c) {
                var e = 0,
                    f = {};
                for (c = "string" === typeof c ? c.split(" ") : [c]; 4 > e; e++) f[a + V[e] + b] = c[e] || c[e - 2] || c[0];
                return f
            }
        };
        Jb.test(a) || (c.cssHooks[a + b].set = kb)
    });
    var Gc = /%20/g,
        Zb = /\[\]$/,
        Lb = /\r?\n/g,
        Hc = /^(?:submit|button|image|reset)$/i,
        Ic = /^(?:input|select|textarea|keygen)/i;
    c.fn.extend({
        serialize: function() {
            return c.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var a = c.prop(this, "elements");
                return a ? c.makeArray(a) : this
            }).filter(function() {
                var a = this.type;
                return this.name && !c(this).is(":disabled") && Ic.test(this.nodeName) && !Hc.test(a) && (this.checked || !za.test(a))
            }).map(function(a, b) {
                var d = c(this).val();
                return null == d ? null : c.isArray(d) ? c.map(d, function(a) {
                    return {
                        name: b.name,
                        value: a.replace(Lb, "\r\n")
                    }
                }) : {
                    name: b.name,
                    value: d.replace(Lb, "\r\n")
                }
            }).get()
        }
    });
    c.param = function(a, b) {
        var d, e = [],
            f = function(a, b) {
                b = c.isFunction(b) ? b() : null == b ? "" : b;
                e[e.length] = encodeURIComponent(a) + "=" + encodeURIComponent(b)
            };
        void 0 === b && (b = c.ajaxSettings && c.ajaxSettings.traditional);
        if (c.isArray(a) || a.jquery && !c.isPlainObject(a)) c.each(a, function() {
            f(this.name, this.value)
        });
        else
            for (d in a) Aa(d, a[d], b, f);
        return e.join("&").replace(Gc, "+")
    };
    var Y, S, Ra = c.now(),
        Sa = /\?/,
        Jc = /#.*$/,
        Mb = /([?&])_=[^&]*/,
        Kc = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg,
        Lc = /^(?:GET|HEAD)$/,
        Mc = /^\/\//,
        Nb = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,
        Ob = c.fn.load,
        Pb = {},
        Ba = {},
        Qb = "*/".concat("*");
    try {
        S = bc.href
    } catch (a) {
        S = p.createElement("a"), S.href = "", S = S.href
    }
    Y =
        Nb.exec(S.toLowerCase()) || [];
    c.fn.load = function(a, b, d) {
        if ("string" !== typeof a && Ob) return Ob.apply(this, arguments);
        var e, f, g, h = this,
            k = a.indexOf(" ");
        0 <= k && (e = a.slice(k, a.length), a = a.slice(0, k));
        c.isFunction(b) ? (d = b, b = void 0) : b && "object" === typeof b && (f = "POST");
        0 < h.length && c.ajax({
            url: a,
            type: f,
            dataType: "html",
            data: b
        }).done(function(a) {
            g = arguments;
            h.html(e ? c("<div>").append(c.parseHTML(a)).find(e) : a)
        }).complete(d && function(a, b) {
            h.each(d, g || [a.responseText, b, a])
        });
        return this
    };
    c.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),
        function(a, b) {
            c.fn[b] = function(a) {
                return this.on(b, a)
            }
        });
    c.each(["get", "post"], function(a, b) {
        c[b] = function(a, e, f, g) {
            c.isFunction(e) && (g = g || f, f = e, e = void 0);
            return c.ajax({
                url: a,
                type: b,
                dataType: g,
                data: e,
                success: f
            })
        }
    });
    c.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: S,
            type: "GET",
            isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(Y[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Qb,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText"
            },
            converters: {
                "* text": q.String,
                "text html": !0,
                "text json": c.parseJSON,
                "text xml": c.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(a, b) {
            return b ? Ca(Ca(a, c.ajaxSettings), b) : Ca(c.ajaxSettings, a)
        },
        ajaxPrefilter: pb(Pb),
        ajaxTransport: pb(Ba),
        ajax: function(a, b) {
            function d(a, b, d, h) {
                var l, p, r, B, x = b;
                if (2 !== M) {
                    M = 2;
                    k && clearTimeout(k);
                    e = void 0;
                    g = h || "";
                    u.readyState = 0 < a ? 4 : 0;
                    if (d) {
                        B = n;
                        h = u;
                        var J, y, E, G, H = B.contents,
                            D = B.dataTypes,
                            A = B.responseFields;
                        for (y in A) y in d && (h[A[y]] = d[y]);
                        for (;
                            "*" === D[0];) D.shift(), void 0 === J && (J = B.mimeType || h.getResponseHeader("Content-Type"));
                        if (J)
                            for (y in H)
                                if (H[y] && H[y].test(J)) {
                                    D.unshift(y);
                                    break
                                }
                        if (D[0] in d) E = D[0];
                        else {
                            for (y in d) {
                                if (!D[0] || B.converters[y + " " + D[0]]) {
                                    E = y;
                                    break
                                }
                                G || (G = y)
                            }
                            E = E || G
                        }
                        E ? (E !== D[0] && D.unshift(E), B = d[E]) : B = void 0
                    }
                    if (200 <= a && 300 > a || 304 === a)
                        if (n.ifModified && ((d = u.getResponseHeader("Last-Modified")) &&
                            (c.lastModified[f] = d), (d = u.getResponseHeader("etag")) && (c.etag[f] = d)), 304 === a) l = !0, x = "notmodified";
                        else {
                            a: {
                                p = n;
                                r = B;
                                var F, C, x = {};
                                J = 0;
                                y = p.dataTypes.slice();
                                E = y[0];
                                p.dataFilter && (r = p.dataFilter(r, p.dataType));
                                if (y[1])
                                    for (F in p.converters) x[F.toLowerCase()] = p.converters[F];
                                for (; d = y[++J];)
                                    if ("*" !== d) {
                                        if ("*" !== E && E !== d) {
                                            F = x[E + " " + d] || x["* " + d];
                                            if (!F)
                                                for (C in x)
                                                    if (l = C.split(" "), l[1] === d && (F = x[E + " " + l[0]] || x["* " + l[0]])) {
                                                        !0 === F ? F = x[C] : !0 !== x[C] && (d = l[0], y.splice(J--, 0, d));
                                                        break
                                                    }
                                            if (!0 !== F)
                                                if (F && p["throws"]) r =
                                                    F(r);
                                                else try {
                                                    r = F(r)
                                                } catch (L) {
                                                    l = {
                                                        state: "parsererror",
                                                        error: F ? L : "No conversion from " + E + " to " + d
                                                    };
                                                    break a
                                                }
                                        }
                                        E = d
                                    }
                                l = {
                                    state: "success",
                                    data: r
                                }
                            }
                            x = l.state;
                            p = l.data;
                            r = l.error;
                            l = !r
                        } else if (r = x, a || !x) x = "error", 0 > a && (a = 0);
                    u.status = a;
                    u.statusText = (b || x) + "";
                    l ? z.resolveWith(q, [p, x, u]) : z.rejectWith(q, [u, x, r]);
                    u.statusCode(I);
                    I = void 0;
                    m && v.trigger(l ? "ajaxSuccess" : "ajaxError", [u, n, l ? p : r]);
                    K.fireWith(q, [u, x]);
                    m && (v.trigger("ajaxComplete", [u, n]), --c.active || c.event.trigger("ajaxStop"))
                }
            }
            "object" === typeof a && (b = a, a = void 0);
            b = b || {};
            var e, f, g, h, k, l, m, p, n = c.ajaxSetup({}, b),
                q = n.context || n,
                v = n.context && (q.nodeType || q.jquery) ? c(q) : c.event,
                z = c.Deferred(),
                K = c.Callbacks("once memory"),
                I = n.statusCode || {},
                G = {},
                H = {},
                M = 0,
                D = "canceled",
                u = {
                    readyState: 0,
                    getResponseHeader: function(a) {
                        var b;
                        if (2 === M) {
                            if (!h)
                                for (h = {}; b = Kc.exec(g);) h[b[1].toLowerCase()] = b[2];
                            b = h[a.toLowerCase()]
                        }
                        return null == b ? null : b
                    },
                    getAllResponseHeaders: function() {
                        return 2 === M ? g : null
                    },
                    setRequestHeader: function(a, b) {
                        var c = a.toLowerCase();
                        M || (a = H[c] = H[c] || a, G[a] = b);
                        return this
                    },
                    overrideMimeType: function(a) {
                        M || (n.mimeType = a);
                        return this
                    },
                    statusCode: function(a) {
                        var b;
                        if (a)
                            if (2 > M)
                                for (b in a) I[b] = [I[b], a[b]];
                            else u.always(a[u.status]);
                        return this
                    },
                    abort: function(a) {
                        a = a || D;
                        e && e.abort(a);
                        d(0, a);
                        return this
                    }
                };
            z.promise(u).complete = K.add;
            u.success = u.done;
            u.error = u.fail;
            n.url = ((a || n.url || S) + "").replace(Jc, "").replace(Mc, Y[1] + "//");
            n.type = b.method || b.type || n.method || n.type;
            n.dataTypes = c.trim(n.dataType || "*").toLowerCase().match(L) || [""];
            null == n.crossDomain && (l = Nb.exec(n.url.toLowerCase()),
                n.crossDomain = !(!l || l[1] === Y[1] && l[2] === Y[2] && (l[3] || ("http:" === l[1] ? 80 : 443)) == (Y[3] || ("http:" === Y[1] ? 80 : 443))));
            n.data && n.processData && "string" !== typeof n.data && (n.data = c.param(n.data, n.traditional));
            qb(Pb, n, b, u);
            if (2 === M) return u;
            (m = n.global) && 0 === c.active++ && c.event.trigger("ajaxStart");
            n.type = n.type.toUpperCase();
            n.hasContent = !Lc.test(n.type);
            f = n.url;
            n.hasContent || (n.data && (f = n.url += (Sa.test(f) ? "&" : "?") + n.data, delete n.data), !1 === n.cache && (n.url = Mb.test(f) ? f.replace(Mb, "$1_=" + Ra++) : f + (Sa.test(f) ?
                "&" : "?") + "_=" + Ra++));
            n.ifModified && (c.lastModified[f] && u.setRequestHeader("If-Modified-Since", c.lastModified[f]), c.etag[f] && u.setRequestHeader("If-None-Match", c.etag[f]));
            (n.data && n.hasContent && !1 !== n.contentType || b.contentType) && u.setRequestHeader("Content-Type", n.contentType);
            u.setRequestHeader("Accept", n.dataTypes[0] && n.accepts[n.dataTypes[0]] ? n.accepts[n.dataTypes[0]] + ("*" !== n.dataTypes[0] ? ", " + Qb + "; q=0.01" : "") : n.accepts["*"]);
            for (p in n.headers) u.setRequestHeader(p, n.headers[p]);
            if (n.beforeSend &&
                (!1 === n.beforeSend.call(q, u, n) || 2 === M)) return u.abort();
            D = "abort";
            for (p in {
                success: 1,
                error: 1,
                complete: 1
            }) u[p](n[p]);
            if (e = qb(Ba, n, b, u)) {
                u.readyState = 1;
                m && v.trigger("ajaxSend", [u, n]);
                n.async && 0 < n.timeout && (k = setTimeout(function() {
                    u.abort("timeout")
                }, n.timeout));
                try {
                    M = 1, e.send(G, d)
                } catch (w) {
                    if (2 > M) d(-1, w);
                    else throw w;
                }
            } else d(-1, "No Transport");
            return u
        },
        getScript: function(a, b) {
            return c.get(a, void 0, b, "script")
        },
        getJSON: function(a, b, d) {
            return c.get(a, b, d, "json")
        }
    });
    c.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(a) {
                c.globalEval(a);
                return a
            }
        }
    });
    c.ajaxPrefilter("script", function(a) {
        void 0 === a.cache && (a.cache = !1);
        a.crossDomain && (a.type = "GET", a.global = !1)
    });
    c.ajaxTransport("script", function(a) {
        if (a.crossDomain) {
            var b, d = p.head || c("head")[0] || p.documentElement;
            return {
                send: function(c, f) {
                    b = p.createElement("script");
                    b.async = !0;
                    a.scriptCharset && (b.charset = a.scriptCharset);
                    b.src = a.url;
                    b.onload = b.onreadystatechange = function(a, c) {
                        if (c || !b.readyState ||
                            /loaded|complete/.test(b.readyState)) b.onload = b.onreadystatechange = null, b.parentNode && b.parentNode.removeChild(b), b = null, c || f(200, "success")
                    };
                    d.insertBefore(b, d.firstChild)
                },
                abort: function() {
                    if (b) b.onload(void 0, !0)
                }
            }
        }
    });
    var Rb = [],
        Ta = /(=)\?(?=&|$)|\?\?/;
    c.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var a = Rb.pop() || c.expando + "_" + Ra++;
            this[a] = !0;
            return a
        }
    });
    c.ajaxPrefilter("json jsonp", function(a, b, d) {
        var e, f, g, h = !1 !== a.jsonp && (Ta.test(a.url) ? "url" : "string" === typeof a.data && !(a.contentType ||
            "").indexOf("application/x-www-form-urlencoded") && Ta.test(a.data) && "data");
        if (h || "jsonp" === a.dataTypes[0]) return e = a.jsonpCallback = c.isFunction(a.jsonpCallback) ? a.jsonpCallback() : a.jsonpCallback, h ? a[h] = a[h].replace(Ta, "$1" + e) : !1 !== a.jsonp && (a.url += (Sa.test(a.url) ? "&" : "?") + a.jsonp + "=" + e), a.converters["script json"] = function() {
            g || c.error(e + " was not called");
            return g[0]
        }, a.dataTypes[0] = "json", f = q[e], q[e] = function() {
            g = arguments
        }, d.always(function() {
            q[e] = f;
            a[e] && (a.jsonpCallback = b.jsonpCallback, Rb.push(e));
            g && c.isFunction(f) && f(g[0]);
            g = f = void 0
        }), "script"
    });
    var T, ga, Nc = 0,
        Ua = q.ActiveXObject && function() {
            for (var a in T) T[a](void 0, !0)
        };
    c.ajaxSettings.xhr = q.ActiveXObject ? function() {
        var a;
        if (!(a = !this.isLocal && rb())) a: {
            try {
                a = new q.ActiveXObject("Microsoft.XMLHTTP");
                break a
            } catch (b) {}
            a = void 0
        }
        return a
    } : rb;
    ga = c.ajaxSettings.xhr();
    c.support.cors = !!ga && "withCredentials" in ga;
    (ga = c.support.ajax = !!ga) && c.ajaxTransport(function(a) {
        if (!a.crossDomain || c.support.cors) {
            var b;
            return {
                send: function(d, e) {
                    var f, g, h = a.xhr();
                    a.username ? h.open(a.type, a.url, a.async, a.username, a.password) : h.open(a.type, a.url, a.async);
                    if (a.xhrFields)
                        for (g in a.xhrFields) h[g] = a.xhrFields[g];
                    a.mimeType && h.overrideMimeType && h.overrideMimeType(a.mimeType);
                    a.crossDomain || d["X-Requested-With"] || (d["X-Requested-With"] = "XMLHttpRequest");
                    try {
                        for (g in d) h.setRequestHeader(g, d[g])
                    } catch (k) {}
                    h.send(a.hasContent && a.data || null);
                    b = function(d, g) {
                        var m, p, n, q, v;
                        try {
                            if (b && (g || 4 === h.readyState))
                                if (b = void 0, f && (h.onreadystatechange = c.noop, Ua && delete T[f]), g) 4 !==
                                    h.readyState && h.abort();
                                else {
                                    q = {};
                                    m = h.status;
                                    v = h.responseXML;
                                    n = h.getAllResponseHeaders();
                                    v && v.documentElement && (q.xml = v);
                                    "string" === typeof h.responseText && (q.text = h.responseText);
                                    try {
                                        p = h.statusText
                                    } catch (z) {
                                        p = ""
                                    }
                                    m || !a.isLocal || a.crossDomain ? 1223 === m && (m = 204) : m = q.text ? 200 : 404
                                }
                        } catch (z) {
                            g || e(-1, z)
                        }
                        q && e(m, p, q, n)
                    };
                    a.async ? 4 === h.readyState ? setTimeout(b) : (f = ++Nc, Ua && (T || (T = {}, c(q).unload(Ua)), T[f] = b), h.onreadystatechange = b) : b()
                },
                abort: function() {
                    b && b(void 0, !0)
                }
            }
        }
    });
    var P, wa, Oc = /^(?:toggle|show|hide)$/,
        Pc = new RegExp("^(?:([+-])=|)(" + ma + ")([a-z%]*)$", "i"),
        Qc = /queueHooks$/,
        ia = [
            function(a, b, d) {
                var e, f, g, h, k, l, m = this,
                    p = a.style,
                    n = {},
                    q = [],
                    v = a.nodeType && ca(a);
                d.queue || (k = c._queueHooks(a, "fx"), null == k.unqueued && (k.unqueued = 0, l = k.empty.fire, k.empty.fire = function() {
                    k.unqueued || l()
                }), k.unqueued++, m.always(function() {
                    m.always(function() {
                        k.unqueued--;
                        c.queue(a, "fx").length || k.empty.fire()
                    })
                }));
                1 === a.nodeType && ("height" in b || "width" in b) && (d.overflow = [p.overflow, p.overflowX, p.overflowY], "inline" === c.css(a, "display") &&
                    "none" === c.css(a, "float") && (c.support.inlineBlockNeedsLayout && "inline" !== jb(a.nodeName) ? p.zoom = 1 : p.display = "inline-block"));
                d.overflow && (p.overflow = "hidden", c.support.shrinkWrapBlocks || m.done(function() {
                    p.overflow = d.overflow[0];
                    p.overflowX = d.overflow[1];
                    p.overflowY = d.overflow[2]
                }));
                for (e in b) g = b[e], Oc.exec(g) && (delete b[e], f = f || "toggle" === g, g !== (v ? "hide" : "show") && q.push(e));
                if (b = q.length)
                    for (g = c._data(a, "fxshow") || c._data(a, "fxshow", {}), ("hidden" in g) && (v = g.hidden), f && (g.hidden = !v), v ? c(a).show() :
                        m.done(function() {
                            c(a).hide()
                        }), m.done(function() {
                            var b;
                            c._removeData(a, "fxshow");
                            for (b in n) c.style(a, b, n[b])
                        }), e = 0; e < b; e++) f = q[e], h = m.createTween(f, v ? g[f] : 0), n[f] = g[f] || c.style(a, f), f in g || (g[f] = h.start, v && (h.end = h.start, h.start = "width" === f || "height" === f ? 1 : 0))
            }
        ],
        ea = {
            "*": [
                function(a, b) {
                    var d, e, f = this.createTween(a, b),
                        g = Pc.exec(b),
                        h = f.cur(),
                        k = +h || 0,
                        l = 1,
                        m = 20;
                    if (g) {
                        d = +g[2];
                        e = g[3] || (c.cssNumber[a] ? "" : "px");
                        if ("px" !== e && k) {
                            k = c.css(f.elem, a, !0) || d || 1;
                            do l = l || ".5", k /= l, c.style(f.elem, a, k + e); while (l !==
                                (l = f.cur() / h) && 1 !== l && --m)
                        }
                        f.unit = e;
                        f.start = k;
                        f.end = g[1] ? k + (g[1] + 1) * d : d
                    }
                    return f
                }
            ]
        };
    c.Animation = c.extend(tb, {
        tweener: function(a, b) {
            c.isFunction(a) ? (b = a, a = ["*"]) : a = a.split(" ");
            for (var d, e = 0, f = a.length; e < f; e++) d = a[e], ea[d] = ea[d] || [], ea[d].unshift(b)
        },
        prefilter: function(a, b) {
            b ? ia.unshift(a) : ia.push(a)
        }
    });
    c.Tween = v;
    v.prototype = {
        constructor: v,
        init: function(a, b, d, e, f, g) {
            this.elem = a;
            this.prop = d;
            this.easing = f || "swing";
            this.options = b;
            this.start = this.now = this.cur();
            this.end = e;
            this.unit = g || (c.cssNumber[d] ?
                "" : "px")
        },
        cur: function() {
            var a = v.propHooks[this.prop];
            return a && a.get ? a.get(this) : v.propHooks._default.get(this)
        },
        run: function(a) {
            var b, d = v.propHooks[this.prop];
            this.pos = this.options.duration ? b = c.easing[this.easing](a, this.options.duration * a, 0, 1, this.options.duration) : b = a;
            this.now = (this.end - this.start) * b + this.start;
            this.options.step && this.options.step.call(this.elem, this.now, this);
            d && d.set ? d.set(this) : v.propHooks._default.set(this);
            return this
        }
    };
    v.prototype.init.prototype = v.prototype;
    v.propHooks = {
        _default: {
            get: function(a) {
                return null == a.elem[a.prop] || a.elem.style && null != a.elem.style[a.prop] ? (a = c.css(a.elem, a.prop, "auto")) && "auto" !== a ? a : 0 : a.elem[a.prop]
            },
            set: function(a) {
                if (c.fx.step[a.prop]) c.fx.step[a.prop](a);
                else a.elem.style && (null != a.elem.style[c.cssProps[a.prop]] || c.cssHooks[a.prop]) ? c.style(a.elem, a.prop, a.now + a.unit) : a.elem[a.prop] = a.now
            }
        }
    };
    v.propHooks.scrollTop = v.propHooks.scrollLeft = {
        set: function(a) {
            a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now)
        }
    };
    c.each(["toggle", "show",
        "hide"
    ], function(a, b) {
        var d = c.fn[b];
        c.fn[b] = function(a, c, g) {
            return null == a || "boolean" === typeof a ? d.apply(this, arguments) : this.animate(ja(b, !0), a, c, g)
        }
    });
    c.fn.extend({
        fadeTo: function(a, b, c, e) {
            return this.filter(ca).css("opacity", 0).show().end().animate({
                opacity: b
            }, a, c, e)
        },
        animate: function(a, b, d, e) {
            var f = c.isEmptyObject(a),
                g = c.speed(b, d, e),
                h = function() {
                    var b = tb(this, c.extend({}, a), g);
                    h.finish = function() {
                        b.stop(!0)
                    };
                    (f || c._data(this, "finish")) && b.stop(!0)
                };
            h.finish = h;
            return f || !1 === g.queue ? this.each(h) :
                this.queue(g.queue, h)
        },
        stop: function(a, b, d) {
            var e = function(a) {
                var b = a.stop;
                delete a.stop;
                b(d)
            };
            "string" !== typeof a && (d = b, b = a, a = void 0);
            b && !1 !== a && this.queue(a || "fx", []);
            return this.each(function() {
                var b = !0,
                    g = null != a && a + "queueHooks",
                    h = c.timers,
                    k = c._data(this);
                if (g) k[g] && k[g].stop && e(k[g]);
                else
                    for (g in k) k[g] && k[g].stop && Qc.test(g) && e(k[g]);
                for (g = h.length; g--;) h[g].elem !== this || null != a && h[g].queue !== a || (h[g].anim.stop(d), b = !1, h.splice(g, 1));
                !b && d || c.dequeue(this, a)
            })
        },
        finish: function(a) {
            !1 !== a && (a =
                a || "fx");
            return this.each(function() {
                var b, d = c._data(this),
                    e = d[a + "queue"];
                b = d[a + "queueHooks"];
                var f = c.timers,
                    g = e ? e.length : 0;
                d.finish = !0;
                c.queue(this, a, []);
                b && b.cur && b.cur.finish && b.cur.finish.call(this);
                for (b = f.length; b--;) f[b].elem === this && f[b].queue === a && (f[b].anim.stop(!0), f.splice(b, 1));
                for (b = 0; b < g; b++) e[b] && e[b].finish && e[b].finish.call(this);
                delete d.finish
            })
        }
    });
    c.each({
            slideDown: ja("show"),
            slideUp: ja("hide"),
            slideToggle: ja("toggle"),
            fadeIn: {
                opacity: "show"
            },
            fadeOut: {
                opacity: "hide"
            },
            fadeToggle: {
                opacity: "toggle"
            }
        },
        function(a, b) {
            c.fn[a] = function(a, c, f) {
                return this.animate(b, a, c, f)
            }
        });
    c.speed = function(a, b, d) {
        var e = a && "object" === typeof a ? c.extend({}, a) : {
            complete: d || !d && b || c.isFunction(a) && a,
            duration: a,
            easing: d && b || b && !c.isFunction(b) && b
        };
        e.duration = c.fx.off ? 0 : "number" === typeof e.duration ? e.duration : e.duration in c.fx.speeds ? c.fx.speeds[e.duration] : c.fx.speeds._default;
        if (null == e.queue || !0 === e.queue) e.queue = "fx";
        e.old = e.complete;
        e.complete = function() {
            c.isFunction(e.old) && e.old.call(this);
            e.queue && c.dequeue(this,
                e.queue)
        };
        return e
    };
    c.easing = {
        linear: function(a) {
            return a
        },
        swing: function(a) {
            return .5 - Math.cos(a * Math.PI) / 2
        }
    };
    c.timers = [];
    c.fx = v.prototype.init;
    c.fx.tick = function() {
        var a, b = c.timers,
            d = 0;
        for (P = c.now(); d < b.length; d++) a = b[d], a() || b[d] !== a || b.splice(d--, 1);
        b.length || c.fx.stop();
        P = void 0
    };
    c.fx.timer = function(a) {
        a() && c.timers.push(a) && c.fx.start()
    };
    c.fx.interval = 13;
    c.fx.start = function() {
        wa || (wa = setInterval(c.fx.tick, c.fx.interval))
    };
    c.fx.stop = function() {
        clearInterval(wa);
        wa = null
    };
    c.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    };
    c.fx.step = {};
    c.expr && c.expr.filters && (c.expr.filters.animated = function(a) {
        return c.grep(c.timers, function(b) {
            return a === b.elem
        }).length
    });
    c.fn.offset = function(a) {
        if (arguments.length) return void 0 === a ? this : this.each(function(b) {
            c.offset.setOffset(this, a, b)
        });
        var b, d, e = {
                top: 0,
                left: 0
            },
            f = (d = this[0]) && d.ownerDocument;
        if (f) {
            b = f.documentElement;
            if (!c.contains(b, d)) return e;
            "undefined" !== typeof d.getBoundingClientRect && (e = d.getBoundingClientRect());
            d = ub(f);
            return {
                top: e.top + (d.pageYOffset ||
                    b.scrollTop) - (b.clientTop || 0),
                left: e.left + (d.pageXOffset || b.scrollLeft) - (b.clientLeft || 0)
            }
        }
    };
    c.offset = {
        setOffset: function(a, b, d) {
            var e = c.css(a, "position");
            "static" === e && (a.style.position = "relative");
            var f = c(a),
                g = f.offset(),
                h = c.css(a, "top"),
                k = c.css(a, "left"),
                l = {},
                m = {};
            ("absolute" === e || "fixed" === e) && -1 < c.inArray("auto", [h, k]) ? (m = f.position(), e = m.top, k = m.left) : (e = parseFloat(h) || 0, k = parseFloat(k) || 0);
            c.isFunction(b) && (b = b.call(a, d, g));
            null != b.top && (l.top = b.top - g.top + e);
            null != b.left && (l.left = b.left - g.left +
                k);
            "using" in b ? b.using.call(a, l) : f.css(l)
        }
    };
    c.fn.extend({
        position: function() {
            if (this[0]) {
                var a, b, d = {
                        top: 0,
                        left: 0
                    },
                    e = this[0];
                "fixed" === c.css(e, "position") ? b = e.getBoundingClientRect() : (a = this.offsetParent(), b = this.offset(), c.nodeName(a[0], "html") || (d = a.offset()), d.top += c.css(a[0], "borderTopWidth", !0), d.left += c.css(a[0], "borderLeftWidth", !0));
                return {
                    top: b.top - d.top - c.css(e, "marginTop", !0),
                    left: b.left - d.left - c.css(e, "marginLeft", !0)
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var a = this.offsetParent ||
                    p.documentElement; a && !c.nodeName(a, "html") && "static" === c.css(a, "position");) a = a.offsetParent;
                return a || p.documentElement
            })
        }
    });
    c.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function(a, b) {
        var d = /Y/.test(b);
        c.fn[a] = function(e) {
            return c.access(this, function(a, e, h) {
                var k = ub(a);
                if (void 0 === h) return k ? b in k ? k[b] : k.document.documentElement[e] : a[e];
                k ? k.scrollTo(d ? c(k).scrollLeft() : h, d ? h : c(k).scrollTop()) : a[e] = h
            }, a, e, arguments.length, null)
        }
    });
    c.each({
        Height: "height",
        Width: "width"
    }, function(a, b) {
        c.each({
            padding: "inner" +
                a,
            content: b,
            "": "outer" + a
        }, function(d, e) {
            c.fn[e] = function(e, g) {
                var h = arguments.length && (d || "boolean" !== typeof e),
                    k = d || (!0 === e || !0 === g ? "margin" : "border");
                return c.access(this, function(b, d, e) {
                    return c.isWindow(b) ? b.document.documentElement["client" + a] : 9 === b.nodeType ? (d = b.documentElement, Math.max(b.body["scroll" + a], d["scroll" + a], b.body["offset" + a], d["offset" + a], d["client" + a])) : void 0 === e ? c.css(b, d, k) : c.style(b, d, e, k)
                }, b, h ? e : void 0, h, null)
            }
        })
    });
    q.jQuery = q.$ = c;
    "function" === typeof define && define.amd && define.amd.jQuery &&
        define("jquery", [], function() {
            return c
        })
})(window);
$(function() {
    var q = new Victor("container", "output"),
        Va = [
            ["#002c4a", "#005584"],
            ["#35ac03", "#3f4303"],
            ["#ac0908", "#cd5726"],
            ["#18bbff", "#00486b"]
        ];
    $(".color li").each(function(D, Wa) {
        var U = Va[D];
        $(this).mouseover(function() {
            q(U).set()
        })
    })
});
if (self != top) {
    window.top.location.replace(self.location)
}
var obj = window.location.href;
obj = obj.toLowerCase();
obj = obj.substr(7);
if (obj.indexOf("www.") == 0) {
    obj = obj.substr(4)
}

function CheckSearchForm() {
    if (document.getElementById("searchform").stitle.value == "") {
        alert("请输入查询内容;");
        return false
    }
    return true
}
$(function() {
    var a = new Victor("container", "output"),
        b = [
            ["#002c4a", "#005584"],
            ["#35ac03", "#3f4303"],
            ["#ac0908", "#cd5726"],
            ["#18bbff", "#00486b"]
        ];
    $(".color li").each(function(c, e) {
        var d = b[c];
        $(this).mouseover(function() {
            a(d).set()
        })
    })
});