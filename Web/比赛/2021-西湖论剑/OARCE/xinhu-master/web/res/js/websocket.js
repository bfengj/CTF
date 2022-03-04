/**
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (xh829.com)
*	Date:2016-01-01
*/

function websocketClass(a){var b=this;this.wsobj=!1,this.wshost="",this.onopen=function(){},this.onmessage=function(){},this.onclose=function(){},this.onerror=function(){},this.reimfrom="rockdemo",this.adminid="1",this.sendname="1",this._init=function(){if(a)for(var c in a)this[c]=a[c];"undefined"==typeof WebSocket?(WEB_SOCKET_SWF_LOCATION="",WEB_SOCKET_DEBUG=!0,$.getScript("res/js/swfobject.js",function(){b._contect()})):this._contect()},this._contect=function(){this.wsobj=new WebSocket(this.wshost),this.wsobj.onopen=function(a){b._onopen(a)},this.wsobj.onmessage=function(a){b._onmessage(a)},this.wsobj.onclose=function(a){b._onclose(a)},this.wsobj.onerror=function(a){b._onerror(a)}},this.connect=function(){this._contect()},this._onopen=function(a){this.onopen(this,a),this.send({atype:"connect"})},this._onmessage=function(a){var b=a.data;this.onmessage(b,this)},this._onclose=function(a){this.onclose(this,a)},this._onerror=function(a){this.onerror(this,a)},this.send=function(a){var b=this.objecttostr(a);return this.wsobj.send(b)},this.objecttostr=function(a){var b,d,c="",e=js.apply({from:this.reimfrom,adminid:this.adminid,atype:"send",sendname:this.sendname},a);for(b in e)d=e[b],c+=',"'+b+'":"'+d+'"';return""!=c&&(c=c.substr(1)),"{"+c+"}"},this._init()}