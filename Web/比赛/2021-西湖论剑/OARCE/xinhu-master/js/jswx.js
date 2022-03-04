QOM='xinhuwx_'
js.wx={};
js.wx.alert=function(msg,fun,tit, cof1){
	$('#weui_dialog_alert_div').remove();
	var s='';
	if(!tit)tit='系统提示';
	s+='<div id="weui_dialog_alert_div" class="weui_dialog_alert" >';
    s+='<div class="weui_mask"></div>';
    s+='<div class="weui_dialog">';
    s+='    <div class="weui_dialog_hd"><strong class="weui_dialog_title">'+tit+'</strong></div>';
    s+='    <div class="weui_dialog_bd">'+msg+'</div>';
    s+='    <div class="weui_dialog_ft">';
	s+='        <a href="javascript:;" id="confirm_btn" sattr="yes" class="weui_btn_dialog primary">确定</a>';
    if(cof1==1)s+='       <a href="javascript:;" id="confirm_btn1" sattr="no" class="weui_btn_dialog default">取消</a>';
    s+='   </div>';
    s+='</div>';
	s+='</div>';
	$('body').append(s);
	function backl(e){
		var jg	= $(this).attr('sattr');
		if(typeof(fun)=='function')fun(jg,this);
		$('#weui_dialog_alert_div').remove();
		return false;
	}
	$('#confirm_btn1').click(backl);
	$('#confirm_btn').click(backl);
}
js.wx.confirm=function(msg,fun,tit){
	this.alert(msg,fun,tit, 1);
}
js.wx.prompt=function(tit,msg,fun,nr){
	if(!nr)nr='';
	if(apicloud){
		api.prompt({
			buttons: ['确定', '取消'],
			text:nr,title:tit,msg:msg
		}, function(ret, err) {
			var index = ret.buttonIndex;
			if(index==1)fun(ret.text);
		});
		return;
	}
	function func(lx){
		if(lx=='yes')fun(get('prompttxt').value);
	}
	var msg = '<div align="left">'+msg+'</div><div align="left"><input value="'+nr+'" class="r-input" id="prompttxt" type="text"></div>';
	this.alert(msg,func,tit, 1);
}
js.apiurl = function(m,a,cans){
	var url=''+apiurl+'api.php?m='+m+'&a='+a+'';
	var cfrom='mweb';
	if(adminid)url+='&adminid='+adminid+'';
	if(device)url+='&device='+device+'';
	url+='&cfrom='+cfrom+'';
	if(token)url+='&token='+token+'';
	if(!cans)cans={};
	for(var i in cans)url+='&'+i+'='+cans[i]+'';
	return url;
}
js.ajax  = function(m,a,d,funs, mod,checs, erfs, glx){
	if(js.ajaxbool && !js.ajaxwurbo)return;
	clearTimeout(js.ajax_time);
	var url = js.apiurl(m,a);
	js.ajaxbool = true;
	if(!mod)mod='mode';
	if(typeof(erfs)!='function')erfs=function(){};
	if(typeof(funs)!='function')funs=function(){};
	if(!checs)checs=function(){};
	var bs = checs(d);
	if(typeof(bs)=='string'&&bs!=''){
		js.msg('msg', bs);
		return;
	}
	if(typeof(bs)=='object')d=js.apply(d,bs);
	var tsnr = '努力处理中...';
	if(mod=='wait')js.msg(mod, tsnr);
	if(mod=='mode')js.wx.load(tsnr);
	function errsoers(ts, ds){
		js.wx.unload();
		js.setmsg(ts);
		js.msg('msg',ts);
		js.ajaxbool = false;
		erfs(ts, ds);
	}
	var type=(!d)?'get':'post';if(glx)type=glx;
	var ajaxcan={
		type:type,dataType:'json',data:d,url:url,
		success:function(ret){
			js.ajaxbool=false;
			js.wx.unload();
			clearTimeout(js.ajax_time);
			if(ret.code==199){
				js.wx.alert(ret.msg, function(){
					js.location('?d=we&m=login&backurl='+jm.base64encode(location.href)+'');
				});
				return;
			}
			if(ret.code!=200){
				errsoers(ret.msg, ret);
			}else{
				js.setmsg('');
				js.msg('none');
				funs(ret.data);
			}
		},
		error:function(e){
			errsoers('内部出错:'+e.responseText+'');
		}
	};
	$.ajax(ajaxcan);
	js.ajax_time = setTimeout(function(){
		if(js.ajaxbool){
			errsoers('Error:请求超时?');
		}
	}, 1000*30);
}
js.wx.load=function(txt){
	this.unload();
	if(txt=='none')return;
	if(!txt)txt='加载中...';
	var s='';
	var t = winHb()-150;
	s+='<div id="loadingToastsss" class="weui_loading_toast">'+
    '<div class="weui_mask_transparent"></div>'+
    '<div class="weui_toast" style="top:'+(t*0.5)+'px">'+
    '    <div class="weui_loading">'+
    '        <div class="weui_loading_leaf weui_loading_leaf_0"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_1"></div>'+
     '       <div class="weui_loading_leaf weui_loading_leaf_2"></div>'+
      '      <div class="weui_loading_leaf weui_loading_leaf_3"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_4"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_5"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_6"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_7"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_8"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_9"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_10"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_11"></div>'+
    '    </div>'+
    '    <p class="weui_toast_content">'+txt+'</p>'+
    '</div>'+
	'</div>';
	$('body').append(s);
}
js.wx.unload=function(){
	$('#loadingToastsss').remove();
}
js.loading=function(txt){
	this.wx.load(txt);
}
js.unloading=function(){
	this.wx.unload();
}
js.wx.msgok=function(txt,fun,ms){
	$('#toastssss').remove();
	clearTimeout(this.msgtime);
	if(txt=='none')return;
	if(!ms)ms=3;
	var t = winHb()-150;
	var s='<div id="toastssss">';
	s+='<div class="weui_mask_transparent"></div>';
	s+=	'<div class="weui_toast" style="top:'+(t*0.5)+'px">';
	s+=		'<i class="weui_icon_toast"></i>';
	s+=		'<p class="weui_toast_content">'+txt+'</p>';
	s+=	'</div>';
	s+='</div>';
	$('body').append(s);
	this.msgtime=setTimeout(function(){
		$('#toastssss').remove();
		if(typeof(fun)=='function')fun();

	}, ms*1000);
}

js.showmenu=function(d){
	$('#menulistshow').remove();
	var d=js.apply({width:200,top:'50%',renderer:function(){},align:'center',onclick:function(){},oncancel:function(){}},d);
	var a=d.data;
	if(!a)return;
	var h1=$(window).height(),h2=document.body.scrollHeight,s1;
	if(h2>h1)h1=h2;
	var col='';
	var s='<div onclick="$(this).remove();" align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:198" >';
	s+='<div id="menulistshow_s" style="width:'+d.width+'px;margin-top:'+d.top+';position:fixed;-webkit-overflow-scrolling:touch" class="menulist r-border-r r-border-l">';
	for(var i=0;i<a.length;i++){
		s+='<div oi="'+i+'" style="text-align:'+d.align+';color:'+a[i].color+'" class="r-border-t">';
		s1=d.renderer(a[i]);
		if(s1){s+=s1}else{s+=''+a[i].name+'';}
		s+='</div>';
	}
	s+='</div>';
	s+='</div>';
	$('body').append(s);
	var mh = $(window).height();
	var l=($(window).width()-d.width)*0.5,o1 = $('#menulistshow_s'),t = (mh-o1.height()-10)*0.5;
	if(t<10){
		t = 10;
		o1.css({height:''+(mh-20)+'px','overflow':'auto'});
	}
	o1.css({'left':''+l+'px','margin-top':''+t+'px'});
	$('#menulistshow div[oi]').click(function(){
		var oi=parseFloat($(this).attr('oi'));
		d.onclick(a[oi],oi);
	});
	$('#menulistshow').click(function(){
		$(this).remove();
		try{d.oncancel();}catch(e){}
	});
};

js.wx.actionsheet=function(d){
	$('#actionsheetshow').remove();
	var d=js.apply({onclick:function(){},oncancel:function(){}},d);
	var a=d.data,s='';
	if(!a)return;
	s+='<div onclick="$(this).remove();"  id="actionsheetshow">';
	s+='<div class="weui_mask_transition weui_fade_toggle" style="display:block"></div>';
	s+='<div class="weui_actionsheet weui_actionsheet_toggle" >';
	s+='	<div class="weui_actionsheet_menu">';
	for(var i=0;i<a.length;i++){
		s+='<div oi="'+i+'" style="color:'+a[i].color+'" class="weui_actionsheet_cell">'+a[i].name+'</div>';
	}
	s+='	</div>';
	s+='	<div class="weui_actionsheet_action"><div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div></div>';
	s+='</div>';
	s+='</div>';
	$('body').append(s);
	$('#actionsheetshow div[oi]').click(function(){
		var oi=parseFloat($(this).attr('oi'));
		d.onclick(a[oi],oi);
	});
	$('#actionsheetshow').click(function(){
		$(this).remove();
		try{d.oncancel();}catch(e){}
	});
}

js.isqywx=false;
js.iswxbo=function(){
	var bo = true;
	var ua = navigator.userAgent.toLowerCase(); 
	if(ua.indexOf('micromessenger')<0)bo=false;
	if(bo && ua.indexOf('wxwork')>0)js.isqywx=true;
	return bo;
}
js.jssdkcall  = function(bo){
	
}
js.jssdkstate = 0;
js.jssdkwixin = function(qxlist,afe){
	if(!js.iswxbo())return js.jssdkcall(false);
	//if(js.isqywx)var wxurl = 'https://res.wx.qq.com/open/js/jweixin-1.1.0.js';
	var wxurl = 'https://res.wx.qq.com/open/js/jweixin-1.2.0.js';
	if(!afe)$.getScript(wxurl, function(){
		js.jssdkwixin(qxlist, true);
	});
	if(!afe)return;
	var surl= location.href;
	if(!qxlist)qxlist= ['openLocation','getLocation','chooseImage','getLocalImgData','previewImage'];
	js.ajax('weixin','getsign',{url:jm.base64encode(surl),agentid:js.request('agentid')},function(ret){
		if(!ret.appId)return js.jssdkcall(false);
		wx.config({
			debug: false,
			appId: ret.appId,
			timestamp:ret.timestamp,
			nonceStr: ret.nonceStr,
			signature: ret.signature,
			jsApiList:qxlist
		});
		wx.ready(function(){
			if(js.jssdkstate==0)js.jssdkstate = 1;
			js.jssdkcall(true);
		});
		wx.error(function(res){
			js.jssdkstate = 2;
		});
	});
}

/**
*	微信公众号jssdk授权
*/
js.jssdkwxgzh = function(qxlist,afe){
	if(!js.iswxbo())return js.jssdkcall(false);
	var wxurl = 'https://res.wx.qq.com/open/js/jweixin-1.2.0.js';
	if(!afe)$.getScript(wxurl, function(){
		js.jssdkwxgzh(qxlist, true);
	});
	if(!afe)return;
	var surl= location.href;
	if(!qxlist)qxlist= ['openLocation','getLocation','chooseImage','getLocalImgData','previewImage'];
	js.ajax('wxgzh','getsign',{url:jm.base64encode(surl)},function(ret){
		if(!ret.appId)return js.jssdkcall(false);
		wx.config({
			debug: false,
			appId: ret.appId,
			timestamp:ret.timestamp,
			nonceStr: ret.nonceStr,
			signature: ret.signature,
			jsApiList:qxlist
		});
		wx.ready(function(){
			if(js.jssdkstate==0)js.jssdkstate = 1;
			js.jssdkcall(true);
		});
		wx.error(function(res){
			js.jssdkstate = 2;
		});
	});
}