var adminid='0',apiurl='',QOM='xinhu_',token='',adminname='',userinfo={},adminface='images/noface.png';
var get=function(id){return document.getElementById(id)};
var isempt=function(an){var ob	= false;if(an==''||an==null||typeof(an)=='undefined'){ob=true;}return ob;}
var form=function(an,fna){if(!fna)fna='myform';return document[fna][an]}
var xy10=function(s){var s1=''+s+'';if(s1.length<2)s1='0'+s+'';return s1;};
var isIE=true;
if(!document.all)isIE=false;
var js={};
js.getarr=function(caa,bo){
	var s='';
	for(var a in caa)s+=' @@ '+a+'=>'+caa[a]+'';
	if(!bo)alert(s);
	return s;
}
function initbody(){}
$(document).ready(function(){
	adminid=js.request('adminid');
	token=js.request('token');
	initbody();
});
if(typeof(api)=='undefined'){
	var api={};
	api.systemType='android';
	api.deviceId='';
}
js.getrand=function(){
	var r;
	r = ''+new Date().getTime()+'';
	r+='_'+parseInt(Math.random()*9999)+'';
	return r;
}
function winHb(){
	var winH=(!isIE)?window.innerHeight:document.documentElement.offsetHeight;
	return winH;
}
function winWb(){
	var winH=(!isIE)?window.innerWidth:document.documentElement.offsetWidth;
	return winH;
}
js.open=function(url,w,h,can){
	var ja=(url.indexOf('?')>=0)?'&':'?';
	if(!w)w=600;
	if(!h)h=500;
	if(!can)can='resizable=yes,scrollbars=yes';
	var l=(screen.width-w)*0.5;
	var t=(screen.height-h)*0.5;
	window.open(url,'','width='+w+'px,height='+h+'px,left='+l+'px,top='+t+'px,'+can+'');
}
js.request=function(name,url){
	if(!name)return '';
	if(!url)url=location.href;
	if(url.indexOf('\?')<0)return '';
	neurl=url.split('\?')[1];
	neurl=neurl.split('&');
	var value=''
	for(i=0;i<neurl.length;i++){
		val=neurl[i].split('=');
		if(val[0].toLowerCase()==name.toLowerCase()){
			value=val[1];
			break;
		}
	}
	if(!value)value='';
	return value;
}
js.getajaxurl=function(a,m,d,can){
	if(!can)can={};
	if(!m)m='';
	if(!d)d='';
	if(d=='null')d='';
	var jga	= a.substr(0,1);
	if(jga=='@')a = a.substr(1);
	var url	= 'index.php?a='+a+'&m='+m+'&d='+d+'';
	for(var c in can)url+='&'+c+'='+can[c]+'';
	if(jga!='@')url+='&ajaxbool=true';	
	url+='&rnd='+Math.random()+'';	
	return url;
}
js.formatsize=function(size){
	var arr = new Array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
	var e	= Math.floor(Math.log(size)/Math.log(1024));
	var fs	= size/Math.pow(1024,Math.floor(e));
	return js.float(fs)+' '+arr[e];
}
js.now=function(type,sj){
	if(!type)type='Y-m-d';
	if(type=='now')type='Y-m-d H:i:s';
	var dt,ymd,his,weekArr,Y,m,d,w,H=0,i=0,s=0,W;
	if(typeof(sj)=='string')sj=sj.replace(/\//gi,'-');
	if(/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/.test(sj)){
		sj=sj.split(' ');
		ymd=sj[0];
		his=sj[1];if(!his)his='00:00:00';
		ymd=ymd.split('-');
		his=his.split(':');
		H = his[0];if(his.length>1)i = his[1];if(his.length>2)s = his[2];
		dt=new Date(ymd[0],ymd[1]-1,ymd[2],H,i,s);
	}else{
		dt=(typeof(sj)=='number')?new Date(sj):new Date();
	}
	weekArr=new Array('日','一','二','三','四','五','六');
	Y=dt.getFullYear();
	m=xy10(dt.getMonth()+1);
	d=xy10(dt.getDate());
	w=dt.getDay();
	H=xy10(dt.getHours());
	i=xy10(dt.getMinutes());
	s=xy10(dt.getSeconds());
	W=weekArr[w];
	if(type=='time'){
		return dt.getTime();
	}else{
		return type.replace('Y',Y).replace('m',m).replace('d',d).replace('H',H).replace('i',i).replace('s',s).replace('w',w).replace('W',W);
	}
}
js.float=function(num,w){
	if(isNaN(num)||num==''||!num||num==null)num='0';
	num=parseFloat(num);
	if(!w&&w!=0)w=2;
	var m=num.toFixed(w);
	return m;	
}

js.getformdata=function(na){
	var da	={};
	if(!na)na='myform';
	var obj	= document[na];
	for(var i=0;i<obj.length;i++){
		var type	= obj[i].type;
		var val		= obj[i].value;
		if(type=='checkbox'){
			val	= '0';
			if(obj[i].checked)val='1';
		}
		da[obj[i].name]	= val;
	}
	return da;
}

js.setoption=function(k,v){
	k=QOM+k;
	try{
		if(isempt(v)){
			localStorage.removeItem(k);
		}else{
			localStorage.setItem(k, v);
		}
		return true;
	}catch(e){return false}
}
js.getoption=function(k,dev){
	var s = '';
	k=QOM+k;
	try{s = localStorage.getItem(k);}catch(e){}
	if(isempt(dev))dev='';
	if(isempt(s))s=dev;
	return s;
}
js.msg = function(lx, txt,sj){
	clearTimeout(this.msgshowtime);
	if(typeof(sj)=='undefined')sj=5;
	$('#msgshowdivla').remove();
	if(lx == 'none' || !lx){
		return;
	}
	if(lx == 'wait'){
		txt	= '<img src="images/loadings.gif" height="14" width="15" align="absmiddle"> '+txt;
		sj	= 60;
	}
	if(lx=='msg')txt='<font color=red>'+txt+'</font>';var t=10;
	var s = '<div onclick="$(this).remove()" id="msgshowdivla" style="position:fixed;top:'+t+'px;z-index:20;" align="center"><div style="padding:8px 20px;background:rgba(0,0,0,0.7);color:white;font-size:16px;">'+txt+'</div></div>';
	$('body').append(s);
	var w=$('#msgshowdivla').width(),l=(winWb()-w)*0.5;
	$('#msgshowdivla').css('left',''+l+'px');
	if(sj>0)this.msgshowtime= setTimeout("$('#msgshowdivla').remove()",sj*1000);	
}
js.decode=function(str){
	var arr	= {length:-1};
	try{
		arr	= new Function('return '+str+'')();
	}catch(e){}
	return arr;
}
js.apply=function(a,b){
	if(!a)a={};
	if(!b)b={};
	for(var c in b)a[c]=b[c];
	return a;
}
js.apiurl=function(m,a){
	var url='api.php?m='+m+'&a='+a+'&adminid='+adminid+'';
	var cfrom='app'+api.systemType+'';
	url+='&device='+api.deviceId+'';
	url+='&cfrom='+cfrom+'';
	url+='&token='+token+'';
	return url;
}
js.downshow=function(id){
	js.open('?id='+id+'&a=down',600,350);
	return false;
}
js.ajax=function(m,a,d,fun1,mod,checs,errf){
	if(js.ajaxbool)return;
	clearTimeout(js.ajaxrequestime);
	if(!fun1)fun1=function(){};
	if(!errf)errf=function(){};
	if(!checs)checs=function(){};
	var bs = checs(d);
	if(typeof(bs)=='string'&&bs!=''){
		js.msg('msg', bs);
		return;
	}
	if(typeof(bs)=='object')d=js.apply(d,bs);
	if(!mod)mod='wait';
	js.ajaxbool=true;
	var tsnr = '努力处理中...';
	if(mod=='wait'){
		js.msg(mod, tsnr);
	}
	var url=js.apiurl(m,a);if(m.indexOf('?')>0)url=m;
	$.ajax({
		url: url,method: 'post',dataType:'json',data: d,
		success:function(ret){
			js.ajaxbool=false;
			clearTimeout(js.ajaxrequestime);
			js.msg('none');
			if(ret){
				if(ret.code!=200){
					js.msg('msg', 'err1:'+ret.msg);
					errf(ret.msg);
				}else{
					fun1(ret.data);
				}
			}else{
				js.msg('msg', 'err:'+err.msg);
				errf(err);
			}
		},
		error:function(){
			js.msg('msg','内部错误：'+e.responseText);
			errf();
		}
	});
	js.ajaxrequestime=setTimeout(function(){
		js.ajaxbool=false;
		js.msg('msg', 'err:请求超时');
		errf();
	},1000*30);
}
js.backla=function(msg){
	if(msg)if(!confirm(msg))return;
	try{api.closeWin();}catch(e){}
}
js.sendevent=function(typ,na,d){
	if(!d)d={};
	d.opttype=typ;
	if(!na)na='xinhuhome';
	if(api.sendEvent)api.sendEvent({
		name: na,
		extra:d
	});
}
js.setmsg=function(txt,col,ids){
	if(!ids)ids='msgview';
	$('#'+ids+'').html(js.getmsg(txt,col));
}
js.getmsg  = function(txt,col){
	if(!col)col='red';
	var s	= '';
	if(!txt)txt='';
	if(txt.indexOf('...')>0){
		s='<img src="images/loading.gif" height="16" width="16" align="absmiddle"> ';
		col = '#ff6600';
	}	
	s+='<span style="color:'+col+'">'+txt+'</span>';
	if(!txt)s='';
	return s;
}

var changename_uuusw;
function changeuser(na,lx){
	changename_uuusw=na;
	if(!lx)lx='';
	var url=''+apiurl+'task.php?fn=dept&adminid='+adminid+'&token='+token+'&changetype='+lx+'';
	var s='<div style="height:100%;width:100%;position:fixed;top:0px;left:0px;z-index:99; background:rgba(0,0,0,0.2)"  align="center" id="changmodddid">';
	s+='<div style="max-width:300px;height:100%;max-height:450px;margin-top:5%; background:while;border:1px #dddddd solid">';
	s+='<iframe style="background:white" name="changdept" height="100%" frameborder="0" scrolling="auto" marginheight="0" marginwidth="0" width="100%" src="'+url+'"></iframe>';
	s+='</div>';
	s+='</div>';
	$('body').append(s);
}
function changecancel(){
	$('#changmodddid').remove();
}
function changeok(sna,sid){
	get(changename_uuusw).value=sna;
	get(changename_uuusw+'_id').value=sid;
}
function clearuser(na){
	get(na).value='';
	get(na+'_id').value='';
	get(na).focus();
}