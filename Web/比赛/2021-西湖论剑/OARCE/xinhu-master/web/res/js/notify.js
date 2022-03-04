/**
*	桌面通知插件(支持IE啊)
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (xh829.com)
*	Date:2016-01-01
*	var notify = notifyClass({
*		'sound':'声音文件地址','soundbo':true,'icon':'通知图标'
*	});
*	notify.showpopup('这是个通知？');
*	soundbo 声音提示
*	sound 声音文件地址
*/

function notifyClass(opts){
	var me 		= this;
	this.title 	= '系统提醒';
	this.icon  	= 'images/logo.png';
	this.notbool =true;
	this.lastmsg = '';
	this.sound 	 = '';
	this.sounderr= '';
	this.soundbo = true;
	this.showbool= false;
	this._init=function(){
		if(opts)for(var o1 in opts)this[o1]=opts[o1];
		var strsr = '';
		if(typeof(Notification)=='undefined'){
			this.notbool=false;
			strsr = '<bgsound id="notify_sound_audio" src="" hidden="true" autostart="false" loop="false">';
		}else{
			strsr = '<audio id="notify_sound_audio" src="web/res/sound/wu.mp3" autoplay="autoplay" hidden="true"></audio>';
		}
		if(this.sound)$('body').append(strsr);
	};
	this.setsound	= function(bo){
		this.soundbo=bo;
	};
	this.getsound	= function(){
		return this.soundbo;
	};
	this.opennotify = function(clsfun){
		if(!this.notbool)return false;
		if(!clsfun)clsfun=function(){};
		if(Notification.permission === 'granted')return false;
		if(Notification.permission !== 'denied'){
			Notification.requestPermission(function (permission){
				clsfun();
				if(!('permission' in Notification)) {
					Notification.permission = permission;
				}
				if(permission==='granted') {
					
				}
			});
		}
	};
	this.showpopup = function(msg,cans){
		this.lastmsg = msg;
		var can	= {body:msg,icon:this.icon,soundbo:this.soundbo,sound:this.sound,tag:'rockwebkitMeteoric',title:this.title,click:function(){}};
		if(cans)for(var oi in cans)can[oi]=cans[oi];
		var clsfun=can.click,title=can.title;
		if(this.showbool)this.show(can);
		if(!this.notbool){
			this._showpopupie(msg,clsfun,can);
			return;
		}else{
			var lx = this.getaccess();
			if(lx!='ok'){
				this.opennotify();
			}
		}
		var notification = false;
		if(nwjsgui){
			localStorage.setItem('xinhuoa_closelx','no');
			this.close();
			var url =NOWURL+'web/res/js/notification.html?'+Math.random()+'';
			localStorage.setItem('xinhuoa_notification', JSON.stringify({icon:can.icon,title:can.title,body:can.body}));
			var canss={"frame": false,title:"消息提醒","width": 350,resizable:false,'always_on_top':true,show:false,"height": 110,"show_in_taskbar":false}
			nw.Window.open(url,canss,function(wis){
				me.notification = wis;
				wis.on('close',function(){
					this.close(true);
				});
				wis.on('closed',function(){
					if(localStorage.getItem('xinhuoa_closelx')=='yes'){
						var salx=clsfun(can);
						if(!salx)nwjs.winshow();
					}
					me.notification=false;
				});
			});
		}else{
			var notification= new Notification(title, can);
			notification.onclick = function(){
				var salx=clsfun(can);
				if(!salx)nwjs.winshow();
				this.close();
			}
		}
		this.notification = notification;
		if(can.soundbo)this.playsound(can.sound);
	};
	this.close = function(){
		try{
		if(this.notification)this.notification.close(true);
		}catch(e){}
		this.notification = false;
	};
	this.playsound=function(src){
		if(!src)src=this.sound;
		var boa=document.getElementById('notify_sound_audio');
		if(boa){
			boa.src=src;
			if(boa.play)boa.play();
		}
	};
	this.playerrsound=function(src){
		if(!src)src=this.sounderr;
		if(src)this.playsound(src);
	};
	this.getaccess=function(){
		var lx = 'none';
		if(typeof(Notification)=='undefined'){
			lx='ok';
			return lx;
		}
		lx = Notification.permission;
		if(lx=='granted'){lx='ok';}else if(lx=='denied'){lx='jz';}else{lx='mr';}
		return lx;
	};
	this._showpopupie=function(msg, clsfun, can){
		if(typeof(createPopup)=='undefined')return;
		var x = window.screenLeft?window.screenLeft: window.screenX,
			y =	window.screenTop?window.screenTop: window.screenY; 
		var w = 310,h=80;
		var l = screen.width-x-w-10,
			t = screen.height-y-h-60;
		var p=window.createPopup();
		var pbody=p.document.body;
		pbody.style.backgroundColor='#f5f5f5';
		pbody.style.border= 'solid #cccccc 1px';
		msg   = msg.replace(/\n/gi,'<br>');
		var s = '<div style="cursor:pointer">';
		s+='<span id="createPopup_close" style="position:absolute;right:0px;top:0px;cursor:pointer;">×</span>';
		s+='<table id="createPopup_body"><tr valign="top">';
		s+='<td style="padding:5px"><img width="60px" src="'+can.icon+'" height="60px"><td>';
		s+='<td style="padding:0px 5px"><div style="font-size:14px;line-height:20px;padding-top:3px" align="left"><b>'+can.title+'</b></div><div style="font-size:12px;padding-top:3px;height:50px;overflow:hidden;">'+msg+'</div><td>';
		s+='</tr></table>';
		s+='</div>';
		pbody.innerHTML=s;
		p.show(l,t,w,h,document.body);
		p.document.getElementById('createPopup_close').onclick=function(){p.hide();};
		p.document.getElementById('createPopup_body').onclick=function(){
			var salx=clsfun(can);
			if(!salx)nwjs.winshow();
			p.hide();
		};
		if(can.soundbo)this.playsound(can.sound);
	};
	this.getnotifystr=function(ostr){
		var slx = '<font color="green">[已开启]</font>';
		var olx = this.getaccess();
		if(olx=='jz'){
			slx = '<font color="red">[已禁止]</font>,<a href="http://www.rockoa.com/view_notify.html" target="_blank">(去设置)</a>';
		}
		if(olx=='mr'){
			slx = '<font color="#ff6600">[未开启]</font>，<a onclick="'+ostr+'" href="javascript:;">[开启]</a>';
		}
		return slx;
	};
	
	
	
	//右边提示的
	this.show=function(cans){
		if(!cans)cans={};
		var can	= {body:'',icon:'images/todo.png',type:'info',right:'30px',top:'80px',closetime:0,soundbo:this.soundbo,sound:this.sound,title:this.title,click:false,rand:js.getrand()};
		if(cans)for(var oi in cans)can[oi]=cans[oi];
		var coarr = {
			'info':['#31708f', '#d9edf7','#bce8f1'],
			'success':['#3c763d', '#dff0d8','#d6e9c6'],
			'error':['#a94442', '#f2dede','#ebccd1'],
			'wait':['#8a6d3b', '#fcf8e3','#faebcc']
		};
		var cos = coarr[can.type],id = 'notify_show_'+can.rand+'';
		$('#'+id+'').remove();
		var wz  = this.showwei(can.right,can.top),mess=can.body
		mess	= mess.replace(/\n/gi, '<br>');
		var s = '<div id="'+id+'" temp="notifyshow" class="boxs" style="position:absolute;z-index:70;right:'+wz[0]+';top:'+wz[1]+';border:1px '+cos[2]+' solid; background:'+cos[1]+';color:'+cos[0]+';border-radius:5px">';
		if(can.closetime==0)s+='<div onclick="$(this).parent().fadeOut(function(){$(this).remove()})" style="position:absolute;right:3px;top:0px;cursor:pointer">×</div>';
		s+='<table style="margin:15px"><tr valign="top">';
		s+='	<td width="53px" align="left"><img style="width:40px;height:40px" src="'+can.icon+'"></td>';
		s+='	<td id="'+id+'_td" align="left"><div style="padding-bottom:3px;font-size:14px"><b>'+can.title+'</b></div><div>'+mess+'</div></td>';
		s+='</tr></table>';
		s+='</div>';
		$('body').append(s);
		if(can.closetime>0)setTimeout(function(){me.showclose(id)}, can.closetime*1000);
		if(typeof(can.click)=='function'){
			var clsfun=can.click;
			$('#'+id+'_td').click(function(){
				var salx=clsfun(can);
				me.showclose(id);
			});
		}
	};
	this.showwei=function(r,t){
		var cas = $("div[temp='notifyshow']");
		if(cas.length>0){
			var o  = cas[cas.length-1];
			var t1 = parseInt(o.style.top)+$(o).height()+20;
			t = ''+t1+'px';
		}
		return [r,t];
	};
	
	this.showclose=function(id){
		$('#'+id+'').fadeOut(function(){$(this).remove();})
	}
	
	this._init();
}