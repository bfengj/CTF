/**
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (xh829.com)
*	Date:2016-01-01
*/
var nwjs={
	init:function(){
		this.nw = nwjsgui;
		if(!this.nw)return;
		this.fs  = require('fs');
		this.win = nwjsgui.Window.get();
	},
	serverdata:function(str){
		
	},
	createtray:function(tls, lx){
		if(!this.nw)return;
		var icon = 'images/logo.png';
		if(lx==0)icon='images/logo_hui.png';
		var tray = new nwjsgui.Tray({ title:tls, icon: icon});
		tray.tooltip = tls;
		var menu = new nwjsgui.Menu();
		menu.append(new nwjsgui.MenuItem({label: '打开窗口',click:function(){
			nwjs.winshow();
		}}));
		this.closebool = false;
		menu.append(new nwjsgui.MenuItem({label: '退出',click:function(){
			nwjs.closebool = true;
			try{bodyunload();js.onunload();}catch(e){}
			nw.App.quit();
		}}));
		
		tray.menu 	= menu;
		
		tray.on('click',function(){
			nwjs.winshow();
		});
		
		this.tray = tray;
		
		this.win.removeAllListeners('close');
		this.win.on('close',function(){
			if(nwjs.closebool){
				try{bodyunload();js.onunload();}catch(e){}
				nw.App.quit();
				//nw.Window.get().close(true);
			}else{
				nwjs.win.hide();
			}
		});
		
		if(lx==0)return;
		var kjj=js.getoption('kuaijj','Q');
		this.addShortcut(kjj);
		this.addfile();
		var llq = navigator.userAgent.toLowerCase();
		try{if(llq.indexOf('windows nt 5')<0)this.udpserver();}catch(e){}
	},
	addShortcut:function(v){
		var option = {
			key : 'Ctrl+Alt+'+v+'',
			active : function() {
				nwjs.changewinhide();
			}
		};
		this.shortcut = new nwjsgui.Shortcut(option);
		nwjsgui.App.unregisterGlobalHotKey(this.shortcut);
		nwjsgui.App.registerGlobalHotKey(this.shortcut);
	},
	changekuai:function(o1){
		var val=o1.value;
		this.addShortcut(val);
		js.setoption('kuaijj',val);
	},
	removetray:function(){
		if(!this.nw)return;
		if(this.tray)this.tray.remove();
		this.win.removeAllListeners('close');
		if(this.shortcut)nwjsgui.App.unregisterGlobalHotKey(this.shortcut);
		this.closeserver();
		this.tray = false;
		this.shortcut = false;
	},
	changewinhide:function(){
		if(windowfocus){
			this.win.hide();
		}else{
			this.winshow();
		}
	},
	runcmd:function(cmd){
		if(!this.nw)return;
		if(!this.execcmd)this.execcmd= require('child_process').exec;
		this.execcmd(cmd);
	},
	openurl:function(url){
		this.runcmd(''+this.getpath()+'/images/start.bat '+url+'');
	},
	editoffice:function(cstr){
		this.runcmd(''+this.getpath()+'/images/rockoffice.exe '+cstr+'');
	},
	winshow:function(){
		if(!this.nw){
			window.focus();
			return;
		}
		this.win.show();
		this.win.focus();
	},
	jumpicon:function(oi,bo){
		if(!this.tray)return;
		clearTimeout(this.jumptime);
		var s=this.changeicon(this.wdshu,true);
		if(oi==1)s='images/logo_none.png';
		this.tray.icon = s;
		oi = (oi==1)?0:1;
		if(!bo)this.jumptime=setTimeout('nwjs.jumpicon('+oi+')',500);
		if(bo)this.changeicon(this.wdshu);
	},
	jumpclear:function(){
		this.jumpicon(0,true);
	},
	wdshu:0,
	changeicon:function(oi,lx){
		if(!this.tray)return;
		var s='images/logo.png';
		if(oi>0){
			s='images/logo_new.png';
		}
		this.wdshu = oi;
		if(lx)return s;
		if(!lx)this.tray.icon = s;
	},
	writeFile:function(path, str){
		if(!this.nw)return;
		if(!this.fs)this.fs = require('fs');
		var oatg = this.getpath();
		this.fs.writeFile(''+oatg+'/'+path+'', str,function(err){
			if(err){
				js.msg('msg','error:'+err+'');
			};
		});
	},
	getpath:function(){
		if(!this.pathobj)this.pathobj = require('path');
		var oatg = this.pathobj.dirname(process.execPath);
		oatg	 = oatg.replace(/\\/g, '/');
		return oatg;
		var peiz= nwjsgui.App.manifest;
		if(peiz.localpath)return peiz.localpath;
		var url = peiz.main;
		var las = url.lastIndexOf('\\');
		var oatg = url.substr(0, las);
		if(oatg.substr(0,5)=='file:')oatg=oatg.substr(7)
		return oatg;
	},
	addfile:function(){
		return;
		js.ajaxss('down','file',function(ret){
			var fs = require("fs");
			fs.writeFile('rock.php', jm.base64decode(ret.filecont),  function(err) {
				alert(err);
			});
		});
	},
	banben:function(o1){
		o1.innerHTML='已是最新';
	},
	getipmac:function(){
		var json={ip:'','mac':''};
		if(!this.nw)return json;
		var os = require('os');
		var network = os.networkInterfaces();
		for(var a in network){
			for(var i = 0; i < network[a].length; i++) {
				var json = network[a][i];
				if(json.family == 'IPv4') {
					json.ip = json.address
					break;
				}
			}
			break;
		}
		return json;
	},
	closeserver:function(){
		if(!this.server)return;
		if(this.socketobj)this.socketobj.destroy();
		this.server.close();
		this.server=false;
	},
	socketobj:false,
	udpserver:function(funarr){
		if(!this.nw)return;
		var http 	= require('http');
		this.server = http.createServer(function(req, res){
			var url = req.url.toString(),bstr='ok';
			if(url.indexOf('?')>-1){
				try{
				var urla= url.split('?'),batr= urla[urla.length-1],i,bas1,bst='',bas={},k,v;
				var batra = batr.split('&');
				for(i=0;i<batra.length;i++){
					bas1  = batra[i].split('=');
					k 	  = bas1[0]; v = bas1[1]; if(!v)v='';
					if(v.indexOf('base64')==0)v=jm.base64decode(v.substr(6));
					bas[k]= v;
				}
				var barr = nwjs.serverdata(bas);
				if(typeof(barr)=='object')bas = js.apply(bas, barr);
				for(k in bas)bst+=',"'+k+'":"'+bas[k]+'"';
				if(bst!='')bst=bst.substr(1);
				bstr= '{'+bst+'}';
				if(typeof(barr)=='string')bstr = barr;
				if(bas.callback)bstr=''+bas.callback+'({'+bst+'})';
				}catch(e){}
			}
			res.writeHead(200,{'Content-Type':'text/html;charset=utf-8'});res.write(bstr);res.end();
			nwjs.socketobj.destroy();
			nwjs.socketobj=false;
		});
		this.server.on('connection',function(socket){
			nwjs.socketobj = socket;
		});
		this.server.listen(2829,'127.0.0.1',function(){});
	},
	downfile:function(params){
		var cans 	= js.apply({url:'',savefile:'',onsuccess:function(){},onjindu:function(){},onerror:function(){}},params);
		var http 	= require('http');
		http.get(cans.url, function(res) { 
			if(res.statusCode != 200){
				cans.onerror('not found');
				return;
			}
			var filesize = res.headers['content-length'];
			if(!filesize)filesize = res.headers['accept-length'];
			filesize	= parseFloat(filesize);
			res.setEncoding('binary');
			var str = '';
			res.on('data',function(s){
				str+=s;
				var jd = Math.round(100*str.length/filesize);
				cans.onjindu(jd, filesize*jd*0.01);
			}).on('end', function(){
				nwjs.fs.writeFile(cans.savefile, str, 'binary', function(err){
					cans.onsuccess();
				});
			});
		}).on('error', function(e) {
			cans.onerror('error');
		});
	},
	createdir:function(path){
		var a1 = path.split('/'),spth='';
		for(var i=0;i<a1.length-1;i++){
			spth+=''+a1[i]+'/';
			if(!this.fs.existsSync(spth))this.fs.mkdirSync(spth);
		}
	},
	filetobase64:function(path){
		var data = this.fs.readFileSync(path);  
		data = new Buffer(data).toString('base64');
		//this.fs.writeFileSync(path, data);
		return data;
	}
};