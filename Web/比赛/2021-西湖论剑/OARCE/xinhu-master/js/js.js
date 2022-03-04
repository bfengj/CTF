var MODE	= '',ACTION = '',DIR='',PROJECT='',HOST='',PARAMS='',QOM='xinhu_',apiurl='',token='',device='',CFROM='pc',ISDEMO=false,NOWURL='',nwjsgui=false,apicloud=false,isapp=false,homestyle=0,maincolor='#1389D3';
var windows	= null,ismobile=0;
function initbody(){}
function bodyunload(){}
function globalbody(){}
function initApp(){}
function apiready(){apicloud=true;var key = 'apiwinname';var svst=js.request(key);if(svst)sessionStorage.setItem(key,svst);js.setapptitle();initApp();}
$(document).ready(function(){
	try{if(typeof(nw)=='object'){nwjsgui = nw;}else{nwjsgui = require('nw.gui');}}catch(e){nwjsgui=false;}
	$(window).scroll(js.scrolla);
	HOST = js.gethost();
	adminid=js.request('adminid');
	token=js.request('token');
	js.getsplit();
	device= js.cookie('deviceid');
	if(device=='')device=js.now('time');
	js.savecookie('deviceid', device, 365);
	try{
		var winobj = js.request('winobj');
		if(nwjsgui)window.focus=function(){nw.Window.get().focus()}
		if(winobj!='')opener.js.openarr[winobj]=window;
	}catch(e){}
	globalbody();
	initbody();
	$('body').click(function(e){
		js.downbody(this, e);
	});
	$(window).unload(function(){
		js.onunload();
		bodyunload();
	});
	var openfrom = js.request('openfrom',js.getoption('openfrom','', true));
	js.setoption('openfrom', openfrom, true);
	
	if(HOST=='127.0.0.1' || HOST=='localhost' || HOST.indexOf('192.168.0')>-1)window.addEventListener('error',function(e){
		var msg = '文件：'+e.filename+'\n行：'+e.lineno+'\n错误：<font color=red>'+e.message+'</font>';
		js.alert(msg,'js错误');
	});
});
var js={path:'index',url:'',bool:false,login:{},initdata:{},openarr:{},scroll:function(){}};
var isIE=true;
if(!document.all)isIE=false;
var get=function(id){return document.getElementById(id)};
var isempt=function(an){var ob	= false;if(an==''||an==null||typeof(an)=='undefined'){ob=true;}if(typeof(an)=='number'){ob=false;}return ob;}
var strreplace=function(str){if(isempt(str))return '';return str.replace(/[ ]/gi,'').replace(/\s/gi,'')}
var strhtml=function(str){if(isempt(str))return '';return str.replace(/\</gi,'&lt;').replace(/\>/gi,'&gt;')}
var form=function(an,fna){if(!fna)fna='myform';return document[fna][an]}
var xy10=function(s){var s1=''+s+'';if(s1.length<2)s1='0'+s+'';return s1;};
js.getarr=function(caa,bo){
	var s='';
	for(var a in caa)s+=' @@ '+a+'=>'+caa[a]+'';
	if(!bo)alert(s);
	return s;
}
js.getarropen=function(caa){
	jsopenararass = caa;
	js.open('js/array.shtml');
}
if(typeof(api)=='undefined'){
	var api={};
	api.systemType='android';
	api.deviceId='';
}
js.str=function(o){
	o.value	= strreplace(o.value);
}
js.getcan = function(i,dev){
	var a = PARAMS.split('-');
	var val = '';
	if(!dev)dev='';
	if(a[i])val=a[i];
	if(!val)val=dev;
	return val;
}
js.gethost=function(){
	var url = location.href,sau='';
	try{sau = url.split('//')[1].split('/')[0];}catch(e){}
	if(sau.indexOf('demo.rockoa.com')>=0 || sau.indexOf('demo1.rockoa.com')>=0)ISDEMO=true;
	var lse = url.lastIndexOf('/');NOWURL = url.substr(0, lse+1);
	QOM		= NOWURL.replace(/\./g,'').replace(/\//g,'').replace(/\:/g,'')+'_';
	var cfrom= this.request('cfrom','',url);
	if(!cfrom)cfrom=this.getoption('CFROM');
	if(cfrom){this.setoption('CFROM', cfrom);CFROM = cfrom;}
	this.opentype = this.getoption('opentype');
	var otype= this.request('opentype','',url);
	if(otype){this.setoption('opentype', otype);this.opentype = otype;}
	this.reimapplx = 0;var llq = navigator.userAgent;if(llq.indexOf('REIMPLAT_APP')>0)this.reimapplx=1;
	return sau;
}
function winHb(){
	var winH=(!isIE)?window.innerHeight:document.documentElement.offsetHeight;
	return winH;
}
function winWb(){
	var winH=(!isIE)?window.innerWidth:document.documentElement.offsetWidth;
	return winH;
}
js.scrolla	= function(){
	var top	= $(document).scrollTop();
	js.scroll(top);
}
js.request=function(name,dev,url){
	this.requestarr = {};
	if(!dev)dev='';
	if(!name)return dev;
	if(!url)url=location.href;
	if(url.indexOf('\?')<0)return dev;
	if(url.indexOf('#')>0)url = url.split('#')[0];
	var neurl=url.split('\?')[1];
	neurl=neurl.split('&');
	var value=dev,i,val;
	for(i=0;i<neurl.length;i++){
		val=neurl[i].split('=');
		this.requestarr[val[0]] = val[1];
		if(val[0].toLowerCase()==name.toLowerCase()){
			value=val[1];
			break;
		}
	}
	if(!value)value='';
	return value;
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
js.splittime=0;
js.getsplit=function(){
	if(!js.servernow)return false;
	var dt=js.now('Y-m-d H:i:s');
	var d1=js.now('time',dt);	
	var d2=js.now('time',js.servernow);
	js.splittime=d1-d2;
}
js.serverdt=function(atype){
	if(!atype)atype='Y-m-d H:i:s';
	var d1=js.now('time')-js.splittime;
	var dt=js.now(atype,d1);
	return dt;
}
js.open=function(url,w,h,wina,can,wjcan){
	if(wina){try{var owina	= this.openarr[wina];owina.document.body;owina.focus();return owina;}catch(e){}}
	if(!w)w=600;if(!h)h=500;
	var l=(screen.width-w)*0.5,t=(screen.height-h)*0.5-50,rnd = parseInt(Math.random()*50);
	if(rnd%2==0){l=l+rnd;t=t-rnd;}else{l=l-rnd;t=t+rnd;}
	if(!can)can={};
	var s='resizable=yes,scrollbars=yes,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no';
	var a1={'left':''+l+'px','top':''+t+'px','width':''+w+'px','height':''+h+'px'};
	a1 = js.apply(a1,can);
	for(var o1 in a1)s+=','+o1+'='+a1[o1]+'';
	var ja=(url.indexOf('?')>=0)?'&':'?';
	if(wina)url+=''+ja+'winobj='+wina+'';
	if(typeof(nw)=='undefined'){
		var opar=window.open(url,'',s);
	}else{
		var ocsn=js.apply({'frame':true,width:w,height:h,x:l,y:t,icon:'images/logo.png'},wjcan);
		if(url.substr(0,4)!='http')url=NOWURL+url;
		var opar=nw.Window.open(url, ocsn, function(wis){
			if(wina)js.openarr[wina]=wis;
			if(w>=1000)wis.maximize();
		});
	}
	if(wina)this.openarr[wina]=opar;
	return false;
}
js.openrun=function(wina,act, ps1, ps2){
	var owina	= this.openarr[wina];
	try{
		if(owina)owina[act](ps1,ps2);
	}catch(e){
		owina = false;
	}
	return owina;
}
js.onunload=function(){
	var a=js.openarr;
	for(var b in a){
		try{a[b].close(true)}catch(e){}
	}
	try{
		var winobj = js.request('winobj');
		if(winobj!='')opener.js.openarr[winobj]=false;
	}catch(e){}
}
js.decode=function(str){
	var arr	= {length:-1};
	try{
		arr	= new Function('return '+str+'')();
	}catch(e){}
	return arr;
}
js.email=function(str){
	if(isempt(str) || str.indexOf(' ')>-1)return false;
	if(str.indexOf('.')==-1 || str.indexOf('@')==-1)return false;
	var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
	if(reg.test(str))return false ;
	return true;
}
js.reload=function(){
	location.reload();
}
js.move=function(id,rl){
	var _left=0,_top=0,_x=0,_right=0,_y=0;
	var obj	= id;if(!rl)rl='left';
	if(typeof(id)=='string')obj=get(id);
	var _Down=function(e){
		try{
			var s='<div id="divmovetemp" style="filter:Alpha(Opacity=0);opacity:0;z-index:99999;width:100%;height:100%;position:absolute;background-color:#000000;left:0px;top:0px;cursor:move"></div>';
			$('body').prepend(s);
			_x = e.clientX;_y = e.clientY;_left=parseInt(obj.style.left);_top=parseInt(obj.style.top);_right=parseInt(obj.style.right);
			document.onselectstart=function(){return false}
		}catch(e1){}		
	}
	var _Move=function(e){
		try{
			var c=get('divmovetemp').innerHTML;
			var x = e.clientX-_x,y = e.clientY-_y;
			if(rl=='left')obj.style.left=_left+x+'px';
			if(rl=='right')obj.style.right=_right-x+'px';
			obj.style.top=_top+y+'px';
		}catch(e1){_Down(e)}
	}
	var _Up=function(){
		document.onmousemove='';
		document.onselectstart='';
		$('#divmovetemp').remove();	
	}
	document.onmousemove=_Move;
	document.onmouseup=_Up;
}
js.setdev=function(val,dev){
	var cv	= val;
	if(isempt(cv))cv=dev;
	return cv;
}
js.upload=function(call,can, glx){
	if(!call)call='';
	if(!can)can={};
	js.uploadrand	= js.now('YmdHis')+parseInt(Math.random()*999999);
	var url = 'index.php?m=upload&d=public&callback='+call+'&upkey='+js.uploadrand+'';
	for(var i in can)if(i!='title')url+='&'+i+'='+can[i]+'';
	if(glx=='url')return url;
	var s='',tit=can.title;if(!tit)tit='上传文件';
	js.tanbody('uploadwin',tit,500,300,{
		html:'<div style="height:280px;overflow:hidden"><iframe src="" name="winiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
		bbar:'none'
	});
	winiframe.location.href=url;
	return false;
}
js.locationshow=function(sid){
	var url = 'index.php?m=kaoqin&d=main&a=location&id='+sid+'';
	if(ismobile==1){js.location(url);return;}
	js.winiframe('地图位置查看', url);
	return false;
}
js.winiframemax=65;
js.winiframewidth = '900x800'; //默认的宽x高
js.winiframe=function(tit, url){
	var mxw= 900,mxh=800,tar = this.winiframewidth.split('x');
	if(tar[0])mxw=parseFloat(tar[0]);
	if(tar[1])mxh=parseFloat(tar[1]);
	var hm = winHb()-150;if(hm>mxh)hm=mxh;if(hm<400)hm=400;
	if(url.indexOf('wintype=max')>0){
		if(mxw<1000)mxw= 1000;
		hm=winHb()-js.winiframemax;
	}
	var wi = winWb()-150;if(wi>mxw)wi=mxw;if(wi<700)wi=700;
	js.tanbody('winiframe',tit,wi,410,{
		html:'<div style="height:'+hm+'px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
		bbar:'none'
	});
	openinputiframe.location.href=url;
	return false;	
}

//下载
js.downshow=function(id, fnun, cans){
	if(this.fileoptWin(id))return;
	if(appobj1('openfile', id))return;
	if(!isempt(fnun)){this.fileopt(id, 1);return false;}
	var url = 'api.php?m=upload&id='+id+'&a=down';
	if(cans)for(var i in cans)url+='&'+i+'='+cans[i]+'';
	this.location(url);
	return false;
}
js.downupdels=function(sid, said, o1){
	js.confirm('确定要删除此文件吗？', function(lx){
		if(lx=='yes'){
			js.downupdel(sid, said, o1);
		}
	});
}
js.downupdel=function(sid, said, o1){
	if(sid>0){
		$.get(js.getajaxurl('delfile','upload','public',{id:sid}));
	}
	if(o1)$(o1).parent().remove();
	var o = $('#view_'+said+'');
	var to= $('#count_'+said+'');
	var o1 = o.find('span'),s1='';
	for(i=0;i<o1.length;i++)$(o1[i]).html(''+(i+1));
	to.html('');
	if(i>0)to.html('<font style="font-size:11px" color="#555555">文件:'+i+'</font>');
	o1 = o.find('font');
	for(i=0;i<o1.length;i++)s1+=','+$(o1[i]).html();
	if(s1!='')s1=s1.substr(1);
	$('#'+said+'-inputEl').val(s1);
	$('#fileid_'+said+'').val(s1);
}
js.downupshow=function(a, showid, nbj){
	var s = '',i=0,s1='',fis,ofisd=',doc,docx,xls,xlsx,ppt,pptx,';
	var o = $('#view_'+showid+'');
	for(i=0; i<a.length; i++){
		fis= 'web/images/fileicons/'+js.filelxext(a[i].fileext)+'.gif';
		if(js.isimg(a[i].fileext) && !isempt(a[i].thumbpath))fis=a[i].thumbpath;
		s='<div onmouseover="this.style.backgroundColor=\'#f1f1f1\'" onmouseout="this.style.backgroundColor=\'\'" style="padding:4px 5px;border-bottom:1px #eeeeee solid;font-size:14px"><span>'+(i+1)+'</span><font style="display:none">'+a[i].id+'</font>、<img src="'+fis+'" align="absmiddle" height="20" width="20"> '+a[i].filename+' ('+a[i].filesizecn+')';
		s+=' <a class="a" temp="yula" onclick="return js.fileopt('+a[i].id+',1)" href="javascript:;">下载</a>';
		s+=' <a class="a" temp="yula" onclick="return js.fileopt('+a[i].id+',0)" href="javascript:;">预览</a>';
		if(ofisd.indexOf(','+a[i].fileext+',')>=0)s+=' <a class="a" temp="dela" onclick="return js.fileopt('+a[i].id+',2)" href="javascript:;">编辑</a>';
		s+=' <a class="a" temp="dela" onclick="return js.downupdels('+a[i].id+',\''+showid+'\', this)" href="javascript:;">×</a>';
		s+='</div>';
		o.append(s);
	}
	js.downupdel(0, showid, false);
	if(nbj)o.find('[temp="dela"]').remove();//禁止编辑
}
js.loading=function(txt){
	js.msg('wait',txt);
}
js.msgerror=function(txt){
	js.msg('msg',txt);
}
js.unloading=function(){js.msg();}
//文件操作id文件id,lx0预览,1下载,2编辑
js.fileopt=function(id,lx){
	if(!lx)lx=0;
	if(ismobile==1 && lx==1 && this.fileoptWin(id))return;
	js.loading('加载中...');
	var gurl = 'api.php?a=fileinfo&m=upload&id='+id+'&type='+lx+'&ismobile='+ismobile+'';
	$.ajax({
		type:'get',url:gurl,dataType:'json',
		success:function(ret){
			js.unloading();
			if(ret.success){
				var da = ret.data;
				var ext= da.fileext;
				var url= da.url;
				if(ismobile==1){
					if(da.type==0 && !da.isview && appobj1('openfile', id))return; //不能预览就用app打开
					if(da.type==0 && !da.isview && js.fileoptWin(id))return; //不能预览就用app打开
					if(da.type==1 && appobj1('openfile', id))return; //下载用app的
					if(da.type==0 && !js.isimg(ext)){
						if(appobj1('openWindow', url))return;
						if(js.appwin('预览',url))return;
					}
				}
				if(da.type==1){js.location(url);return;}//下载直接跳转
				if(js.isimg(ext)){
					$.imgview({'url':url,'ismobile':ismobile==1,'downbool':false});
				}else if(ext=='rockedit'){
					if(ismobile==0){
						js.open(url,screen.width-200,screen.height-200);
					}else{
						js.location(url);return;
						var str = '<div id="rockeditdiv" style="background:white;position:fixed;z-index:99;top:0px;left:0px;width:100%;height:'+winHb()+'px"><iframe src="'+url+'" width="100%" height="100%" frameborder="0"></iframe></div>';
						$('body').append(str);
						js.location('#rockedit');
						window.onhashchange=function(){
							var has = location.hash;
							if(has.indexOf('#rockedit')==-1)$('#rockeditdiv').remove();
						}
					}
				}else if(ext=='rockoffice'){
					js.sendeditoffices(url);
				}else{
					url+='&wintype=max';
					if(ismobile==0){
						if(!nwjsgui){
							js.winiframe(da.filename,url);
						}else{
							js.open(url, 1000,500);
						}
					}else{
						js.location(url);
					}
				}
			}else{
				js.msgerror(ret.msg);
			}
		},
		error:function(e){
			js.unloading();
			js.msg('msg','处理出错:'+e.responseText+'');
		}
	});
}


//文件预览
js.yulanfile=function(id, ext,pts, sne, fnun,isxq){
	if(!isempt(fnun)){this.fileopt(id, 0);return false;}
	var url = 'index.php?m=public&a=fileviewer&id='+id+'&wintype=max';
	if(pts!=''&&js.isimg(ext)){
		$.imgview({'url':pts,'ismobile':ismobile==1,'downbool':false});
		$.get('api.php?m=upload&a=logs&fileid='+id+'&type=0');
		return false;
	}
	if(ismobile==1){
		var docsx = ',doc,docx,ppt,pptx,xls,xlsx,pdf,txt,html,';
		if(docsx.indexOf(','+ext+',')==-1)if(appobj1('openfile', id))return;
		if(appobj1('openWindow', url))return;
		if(js.appwin('预览',url))return;
		js.location(url);
	}else{
		if(!sne)sne='文件预览';
		if(isxq=='xq'){js.open(url,screen.width-200,screen.height-200)}else{js.winiframe(sne,url);}
	}
	return false;
}
js.apiurl = function(m,a,cans){
	var url='api.php?m='+m+'&a='+a+'';
	url+='&cfrom='+CFROM+'';
	if(!cans)cans={};
	for(var i in cans)url+='&'+i+'='+cans[i]+'';
	return url;
}
js.getajaxurl=function(a,m,d,can){
	if(!can)can={};
	if(!m)m=MODE;
	if(!d)d=DIR;
	if(d=='null')d='';
	var jga	= a.substr(0,1);
	if(jga=='@')a = a.substr(1);
	var url	= ''+this.path+'.php?a='+a+'&m='+m+'&d='+d+'';
	for(var c in can)url+='&'+c+'='+can[c]+'';
	if(jga!='@')url+='&ajaxbool=true';	
	url+='&rnd='+parseInt(Math.random()*999999)+'';	
	return url;
}
js.formatsize=function(size){
	var arr = new Array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
	var e	= Math.floor(Math.log(size)/Math.log(1024));
	var fs	= size/Math.pow(1024,Math.floor(e));
	return js.float(fs)+' '+arr[e];
}
js.getselectval=function(o){
	var str='';
	for(var i=0;i<o.length;i++){
		if(o[i].selected){
			str+=','+o[i].value+'';
		}
	}
	if(str!='')str=str.substr(1);
	return str;
}
js.setselectval=function(o,val){
	var str='',vals=','+val+',';
	for(var i=0;i<o.length;i++){
		if(vals.indexOf(','+o[i].value+',')>-1){
			o[i].selected=true;
		}
	}
}
js.getformdata=function(nas){
	var da	={},ona='',o,type,val,na,i,obj;
	if(!nas)nas='myform';
	obj	= document[nas];
	for(i=0;i<obj.length;i++){
		o 	 = obj[i];type = o.type,val = o.value,na = o.name;
		if(!na)continue;
		if(type=='checkbox'){
			val	= '0';
			if(o.checked)val='1';
			da[na]	= val;
		}else if(type=='radio'){
			if(o.checked)da[na]	= val;					
		}else{
			da[na] = val;
		}
		if(na.indexOf('[]')>-1){
			if(ona.indexOf(na)<0)ona+=','+na+'';
		}
	}
	if(ona != ''){
		var onas = ona.split(',');
		for(i=1; i<onas.length; i++){
			da[onas[i].replace('[]','')] = js.getchecked(onas[i]);
		}
	}
	return da;
}
js.getdata = function(na,da){
	if(!da)da={};
	var obj	= $('#'+na+'');
	var inp	= obj.find('input,select');
	for(var i=0;i<inp.length;i++){
		var type	= inp[i].type;
		var val		= inp[i].value;
		if(type=='checkbox'){
			val	= '0';
			if(inp[i].checked)val='1';
		}
		var ad1	= inp[i].name;
		if(!ad1)ad1 = inp[i].id;
		da[ad1]	= val;
	}
	return da;
}
js.selall = function(o,na,bh){
	var i,oi1;
	if(bh){
		o1=$("input[name^='"+na+"']");
	}else{
		o1=$("input[name='"+na+"']");
	}
	for(i=0;i<o1.length;i++){
		if(!o1[i].disabled)o1[i].checked = o.checked;
	}
}
js.getchecked=function(na,bh){
	var s = '';
	var o1;
	if(bh){
		o1=$("input[name^='"+na+"']");
	}else{
		o1=$("input[name='"+na+"']");
	}
	for(var i=0;i<o1.length;i++){
		if(o1[i].checked && !o1[i].disabled)s+=','+o1[i].value+'';
	}
	if(s!='')s=s.substr(1);
	return s;
}
js.cookie=function(name){
	var str=document.cookie,cda,val='',arr,i;
	if(str.length<=0)return '';
	arr=str.split('; ');
	for(i=0;i<arr.length;i++){
		cda=arr[i].split('=');
		if(name.toLowerCase()==cda[0].toLowerCase()){
			val=cda[1];
			break;
		}
	}
	if(!val)val='';
	return val;
}
js.savecookie=function(name,value,d){
	var expires = new Date();
	if(!d)d=365;
	if(!value)d=-10;
	expires.setTime(expires.getTime()+d*24*60*60*1000);
	var str=''+name+'='+value+';expires='+expires.toGMTString()+';path=/;SameSite=Strict';
	document.cookie = str;
}
js.backtop=function(ci){
	if(!ci)ci=0;
	$('body,html').animate({scrollTop:ci});
	return false;
}
js.backto = function(oid){
	if(!get(oid))return;
	var of	= $('#'+oid+'').offset();
	this.backtop(of.top);
	return false;
}
js.applyIf=function(a,b){
	if(!a)a={};
	if(!b)b={};
	for(var c in b)if(typeof(a[c])=='undefined')a[c]=b[c];
	return a;
}
js.apply=function(a,b){
	if(!a)a={};
	if(!b)b={};
	for(var c in b)a[c]=b[c];
	return a;
}
js.tanbody=function(act,title,w,h,can1){
	var H	= (document.body.scrollHeight<winHb())?winHb()-5:document.body.scrollHeight;
	var W	= document.documentElement.scrollWidth+document.body.scrollLeft;
	if(!this.tanbodyindex)this.tanbodyindex=80;
	this.tanbodyindex++;
	var l=(winWb()-w)*0.5,t=(winHb()-h-20)*0.5;
	var s = '',mid	= ''+act+'_main',i,d;
	var can	= js.applyIf(can1,{html:'',btn:[],bodystyle:'',showfun:function(){}});
	if(w>winWb())w=winWb()-50;
	var s = '<div id="'+mid+'" style="position:fixed;background-color:#ffffff;left:'+l+'px;width:'+w+'px;top:'+t+'px;box-shadow:0px 0px 10px rgba(0,0,0,0.3);border-radius:5px">';
	s+='	<div style="-moz-user-select:none;-webkit-user-select:none;user-select:none;border-bottom:1px #eeeeee solid">';
	s+='		<table border="0" width="100%" style="background:none" cellspacing="0" cellpadding="0"><tr>';
	s+='			<td height="50" style="font-size:16px; font-weight:bold;color:'+maincolor+'; padding-left:10px" width="100%" onmousedown="js.move(\''+mid+'\')" id="'+act+'_title">'+title+'</td>';
	s+='			<td><div  id="'+act+'_spancancel1" style="padding:0px 8px;height:50px;line-height:45px;overflow:hidden;cursor:pointer;color:gray;" onclick="js.tanclose(\''+act+'\')">✖</div></td>';
	s+='		</tr></table>';
	s+='	</div>';
	s+='	<div id="'+act+'_body" style="'+can.bodystyle+'">'+can.html+'</div>';
	s+='	<div id="'+act+'_bbar" style="overflow:hidden;padding:12px 10px;background:#f1f1f1;border-radius:0px 0px 5px 5px" align="right"><span id="msgview_'+act+'"></span>';
	for(i=0; i<can.btn.length; i++){
		d = can.btn[i];
		if(!d.bgcolor)d.bgcolor='';
		s+='<button type="button" oi="'+i+'" style="border-radius:5px;padding:8px 15px;margin-left:10px;background:'+d.bgcolor+'" id="'+act+'_btn'+i+'" class="webbtn">'+d.text+'</button>';
	}
	s+='		<button type="button" id="'+act+'_spancancel" onclick="js.tanclose(\''+act+'\')" style="border-radius:5px;padding:8px 15px;background:gray;margin-left:10px" class="webbtn">取消</button>';
	s+='		';
	s+='	</div>';
	s+='</div>';
	var str = '<div id="amain_'+act+'" tanbodynew="'+act+'" oncontextmenu="return false" style="position:absolute;height:'+H+'px;width:'+W+'px;background:rgba(0,0,0,0.3);z-index:'+this.tanbodyindex+';left:0px;top:0px">'+s+'</div>';
	$('body').append(str);
	if(can.closed=='none'){
		$('#'+act+'_spancancel').remove();
		$('#'+act+'_spancancel1').remove();
	}
	if(can.bbar=='none'){
		$('#'+act+'_bbar').remove();
		$('#'+mid+'').append('<div style="height:5px;overflow:hidden;border-radius:0px 0px 5px 5px"></div>');
	}
	this.resizetan(act);
	can.showfun(act);
}
js.resizetan=function(act){
	var mid	= ''+act+'_main';
	var o1  = $('#'+mid+'');
	var h1 = o1.height();
	var w1 = o1.width();	
	var l=(winWb()-w1)*0.5,t=(winHb()-h1-20)*0.5;if(t<0)t=5;
	o1.css({'left':''+l+'px','top':''+t+'px'});
}
js.tanclose=function(act){
	$('#amain_'+act+'').remove();
}
js.xpbodysplit = 0;
js.xpbody=function(act,type){
	if(type=='none'){
		$("div[xpbody='"+act+"']").remove();
		if(!get('xpbg_bodydds'))$('div[tanbody]').remove();
		return;
	}
	if(get('xpbg_bodydds'))return false;
	var H	= (document.body.scrollHeight<winHb())?winHb()-this.xpbodysplit-5:document.body.scrollHeight-this.xpbodysplit*2;
	var W	= document.documentElement.scrollWidth+document.body.scrollLeft-this.xpbodysplit*2;
	
	var bs='<div id="xpbg_bodydds" xpbody="'+act+'" oncontextmenu="return false" style="position:absolute;display:none;width:'+W+'px;height:'+H+'px;filter:Alpha(opacity=30);opacity:0.3;left:'+this.xpbodysplit+'px;top:'+this.xpbodysplit+'px;background-color:#000000;z-index:80"></div>';
	$('body').prepend(bs);	
	$('#xpbg_bodydds').fadeIn(300);
}
js.focusval	= '0';
js.number=function(obj){
	val=strreplace(obj.value);
	if(!val){
		obj.value=js.focusval;
		return false;
	}
	if(isNaN(val)){
		js.msg('msg','输入的不是数字');
		obj.value=js.focusval;
		obj.focus();
	}else{
		var o1 = $(obj);
		var min= o1.attr('minvalue');
		if(isempt(min))min= o1.attr('min');
		if(min && parseFloat(val)<parseFloat(min))val=min;
		var max= o1.attr('maxvalue');
		if(isempt(max))max= o1.attr('max');
		if(max && parseFloat(val)>parseFloat(max))val=max;
		obj.value=val;
	}
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
js.setcopy	= function(txt,nts){
	if(!txt)return;
	var str='<div id="copydiv" style="position:absolute;z-index:0;bottom:0px;right:0px;height:1px;overflow:hidden"><textarea id="copytext">'+txt+'</textarea></div>';
	$('body').append(str);
	get('copytext').select();
	document.execCommand('Copy');
	if(!nts)js.msg('success','复制成功');
	$('#copydiv').remove();
	return false;
}
js.getcopy = function(){
	var txt	= js.cookie('copy_text');
	txt	= unescape(txt);
	return txt;
}
js.chao=function(obj,shuzi,span,guo){
	var cont=(guo)?strreplace(obj.value):obj.value;
    if (cont.length>shuzi){
		alert("您输入的字符超过"+shuzi+"个字符\n\n将被截掉"+(cont.length-shuzi)+"个字符！");
		cont=cont.substring(0,shuzi);
		obj.value=cont;
	}
	if(guo)obj.value=cont;
	if(span)get(span).innerHTML=obj.value.length;
}
js.debug	= function(s){
	if(typeof(console)!='object')return;
	console.error(s);
}
js.alert = function(txt,tit,fun){
	js.confirm(txt, fun, '', tit, 2, '');
}
js.wait	= function(txt,tit,fun){
	js.confirm(txt, fun, '', tit, 3, '');
}
js.alertclose=function(){
	js.tanclose('confirm');
}
js.tanstyle = 0;
js.confirm	= function(txt,fun, tcls, tis, lx,ostr,bstr){
	if(!lx)lx=0;
	var h = '<div style="padding:20px;line-height:30px" align="center">',w=320;
	if(lx==1)w= 350;
	if(w>winWb())w=winWb()-10;
	if(lx==1){
		if(!tcls)tcls='';if(!ostr)ostr='';if(!bstr)bstr='';
		h='<div style="padding:10px;" align="center">'+ostr+'';
		h+='<div align="left" style="padding-left:10px">'+txt+'</div>';
		h+='<div ><textarea class="input form-control" id="confirm_input" style="width:'+(w-40)+'px;height:60px;border-radius:5px">'+tcls+'</textarea></div>'+bstr+'';
	}else if(lx==3){
		h+='<img src="images/mloading.gif" height="32" width="32" align="absmiddle">&nbsp; '+txt+'';
	}else{
		h+=''+txt+'';
	}
	h+='</div>';
	if(!tcls)tcls='danger';if(lx==1)tcls='info';
	if(!tis)tis='<i class="icon-question-sign"></i>&nbsp;系统提示';
	var btn=[{text:'确定'}];
	if(lx<2)btn.push({text:'取消',bgcolor:'gray'});
	js.tanbody('confirm', tis, w, 200,{closed:'none',bbar:'',html:h,titlecls:tcls,btn:btn});
	function backl(jg){
		var val=$('#confirm_input').val();
		if(val==null)val='';
		if(typeof(fun)=='function'){
			var cbo = fun(jg, val);
			if(cbo)return false;
		}
		js.alertclose();
		return false;
	}
	$('#confirm_btn0').click(function(){backl('yes')});
	if(get('confirm_btn1'))$('#confirm_btn1').click(function(){backl('no')});
	if(lx==1)get('confirm_input').focus();
}
js.prompt = function(tit,txt,fun, msg, ostr,bstr){
	js.confirm(txt, fun, msg, tit, 1, ostr,bstr);
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
	if(get('header_title'))t+=50;
	var s = '<div onclick="$(this).remove()" id="msgshowdivla" style="position:fixed;top:'+t+'px;z-index:200;" align="center"><div style="padding:8px 20px;background:rgba(0,0,0,0.7);color:white;font-size:16px;border-radius:5px">'+txt+'</div></div>';
	$('body').append(s);
	var w=$('#msgshowdivla').width(),l=(winWb()-w)*0.5;
	$('#msgshowdivla').css('left',''+l+'px');
	if(sj>0)this.msgshowtime= setTimeout("$('#msgshowdivla').remove()",sj*1000);	
}
js.repempt=function(stt,v){
	var s = stt;
	if(isempt(s))s=v;
	return s;
}
js.getrand=function(){
	var r;
	r = ''+new Date().getTime()+'';
	r+='_'+parseInt(Math.random()*9999)+'';
	return r;
}
js.arraystr=function(str){
	if(!str)str='1|是,0|否';
	var s = str.split(','),
		d = [],i,s1,nv,vv;
	for(i=0; i<s.length; i++){
		s1 = s[i].split('|');
		nv = s1[0];
		vv = nv;
		if(s1.length>1)nv=s1[1];
		d.push([vv,nv]);
	}	
	return d;
}
js._bodyclick = {};
js.downbody=function(o1, e){
	this.allparent = '';
	this.getparenta($(e.target),0);
	var a,s = this.allparent,a1;
	for(a in js._bodyclick){
		a1 = js._bodyclick[a];
		if(s.indexOf(a)<0){
			if(a1.type=='hide'){
				$('#'+a1.objid+'').hide();
			}else{
				$('#'+a1.objid+'').remove();
			}
			if(a1.fun)a1['fun']();
		}
	}
	return true;
}
js.addbody = function(num, type,objid, fun1){
	js._bodyclick[num] = {type:type,objid:objid,fun:fun1};
}
js.getparenta=function(o, oi){
	try{
	if(o[0].nodeName.toUpperCase()=='BODY')return;}catch(e){return;}
	var id = o.attr('id');
	if(!isempt(id)){
		this.allparent+=','+id;
	}
	this.getparenta(o.parent(), oi+1);
}
js.ajaxwurbo = false;
js.ajaxbool = false;
js.ajax = function(url,da,fun,type,efun, tsar){
	if(js.ajaxbool && !js.ajaxwurbo)return;
	if(!da)da={};if(!type)type='get';if(!tsar)tsar='';tsar=tsar.split(',');
	if(typeof(fun)!='function')fun=function(){};
	if(typeof(efun)!='function')efun=function(){};
	var atyp = type.split(','),dtyp='';type=atyp[0];
	if(atyp[1])dtyp=atyp[1];
	js.ajaxbool=true;if(tsar[0])js.msg('wait', tsar[0]);
	var ajaxcan={
		type:type,
		data:da,url:url,
		success:function(str){
			js.ajaxbool=false;
			try{
				if(tsar[1])js.msg('success', tsar[1]);
				fun(str);
			}catch(e){
				js.msg('msg', str);
				js.debug(e);
			}
		},error:function(e){
			js.ajaxbool=false;
			js.msg('msg','处理出错:'+e.responseText+'');
			efun(e.responseText);
		}
	};
	if(dtyp)ajaxcan.dataType=dtyp;
	$.ajax(ajaxcan);
}
js.setoption=function(k,v,qzb){
	if(!qzb)k=QOM+k;
	try{
		if(isempt(v)){
			localStorage.removeItem(k);
		}else{
			localStorage.setItem(k, escape(v));
		}
	}catch(e){
		js.savecookie(k,escape(v));
	}
	return true;
}
js.getoption=function(k,dev, qzb){
	var s = '';
	if(!qzb)k=QOM+k;
	try{s = localStorage.getItem(k);}catch(e){s=js.cookie(k);}
	if(s)s=unescape(s);
	if(isempt(dev))dev='';
	if(isempt(s))s=dev;
	return s;
}
js.location = function(url){
	location.href = url;
}
js.backla=function(msg){
	if(msg)if(!confirm(msg))return;
	try{api.closeWin();}catch(e){}
}
js.isimg = function(lx){
	var ftype 	= '|png|jpg|bmp|gif|jpeg|';
	var bo		= false;
	if(ftype.indexOf('|'+lx+'|')>-1)bo=true;
	return bo;
}
js.changeuser_before=function(na){}
js.changeuser_after=function(){}
js.changeuser=function(na, lx, tits,ocans){
	var h = winHb()-70,w=350;if(!ocans)ocans={};
	if(h>400)h=400;if(!tits)tits='请选择...';
	var nibo = ((lx=='changedeptusercheck'||lx=='deptusercheck') && ismobile==0);
	if(nibo)w=650;
	var formname = '';
	var can = {
		'changetype': lx,
		'showview' 	: 'showuserssvie',
		'titlebool'	:false,
		'changevalue':'',
		'changerange':'', //选择范围
		'oncancel'	:function(){
			js.tanclose('changeaction');
		},
		'onselect':function(sna,sid){
			js.changeuser_after(this.formname,this,sna,sid);
		}
	};
	if(na){
		can.idobj = get(na+'_id');
		can.nameobj = get(na);
		if(can.nameobj)formname = can.nameobj.name;
	}
	
	can.formname= formname;
	var bcar = js.changeuser_before(formname,1),i;
	for(i in ocans)can[i]=ocans[i];
	if(typeof(bcar)=='string' && bcar){js.msg('msg', bcar);return;}
	if(typeof(bcar)=='object')for(i in bcar)can[i]=bcar[i];
	
	js.tanbody('changeaction',tits,w,h,{
		html:'<div id="showuserssvie" style="height:'+h+'px"><iframe src="" name="winiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
		bbar:'none'
	});
	
	if(nibo){
		if(can.idobj)can.changevalue=can.idobj.value;
		changcallback=function(sna,sid){
			if(can.idobj)can.idobj.value = sid;
			if(can.nameobj){
				can.nameobj.value = sna;
				can.nameobj.focus();
			}
			js.changeuser_after(formname, can, sna,sid);
			js.tanclose('changeaction');
			if(can.callback)can.callback(sna,sid);
		}
		var url = 'index.php?d=system&m=dept&changetype='+lx+'&changevalue='+can.changevalue+'&callback=changcallback&changerange='+can.changerange+'';
		winiframe.location.href = url;
	}else{
		$('#showuserssvie').chnageuser(can);
	}
	return false;
}
js.back=function(){
	if(isapp){
		plus.webview.currentWebview().close('auto');
	}else if(apicloud){
		api.historyBack({},function(ret){if(!ret.status)api.closeWin();});
	}else{
		history.back();
	}
}
js.changeclear=function(na){
	var fne  = get(na).name;
	var bcar = js.changeuser_before(fne,0);
	if(typeof(bcar)=='string' && bcar){js.msg('msg', bcar);return;}
	get(na).value='';
	get(na+'_id').value='';
	get(na).focus();
	js.changeuser_after(fne,{nameobj:get(na),idobj:get(na+'_id')},'','');
}
js.changedate=function(o1,id,v){
	if(!v)v='date';
	$(o1).rockdatepicker({initshow:true,view:v,inputid:id});
}
js.fileall=',aac,ace,ai,ain,amr,app,arj,asf,asp,aspx,av,avi,bin,bmp,cab,cad,cat,cdr,chm,com,css,cur,dat,db,dll,dmv,doc,docx,dot,dps,dpt,dwg,dxf,emf,eps,et,ett,exe,fla,ftp,gif,hlp,htm,html,icl,ico,img,inf,ini,iso,jpeg,jpg,js,m3u,max,mdb,mde,mht,mid,midi,mov,mp3,mp4,mpeg,mpg,msi,nrg,ocx,ogg,ogm,pdf,php,png,pot,ppt,pptx,psd,pub,qt,ra,ram,rar,rm,rmvb,rtf,swf,tar,tif,tiff,txt,url,vbs,vsd,vss,vst,wav,wave,wm,wma,wmd,wmf,wmv,wps,wpt,wz,xls,xlsx,xlt,xml,zip,';
js.filelxext = function(lx){
	if(js.fileall.indexOf(','+lx+',')<0)lx='wz';
	return lx;
}
js.datechange=function(o1,lx){
	if(!lx)lx='date';
	$(o1).rockdatepicker({'view':lx,'initshow':true});
	return false;
}
js.selectdate=function(o1,inp,lx){
	if(!lx)lx='date';
	$(o1).rockdatepicker({'view':lx,'initshow':true,'inputid':inp});
	return false;
}
js.importjs=function(url,fun){
	var sid = jm.encrypt(url);
	if(!fun)fun=function(){};
	if(get(sid)){fun();return;}
	var scr = document.createElement('script');
	scr.src = url;
	scr.id 	= sid;
	if(isIE){
		scr.onreadystatechange = function(){
			if(this.readyState=='loaded' || this.readyState=='complete'){fun(this);}
		}
	}else{
		scr.onload = function(){fun(this);}
	}
	document.getElementsByTagName('head')[0].appendChild(scr);
	return false;	
}

js.replacecn=function(o1){
	var  val = strreplace(o1.value);
	val		 = val.replace(/[\u4e00-\u9fa5]/g,'');
	o1.value =val;
}

js.setselectdata = function(o, data, vfs, devs){
	var i,ty = data,sv,str='';
	if(!data)return;	
	if(!vfs)vfs='name';	
	if(typeof(devs)=='undefined')devs='&nbsp;';
	for(i=0;i<ty.length;i++){
		if(ty[i].optgroup){
			if(ty[i].optgroup=='start')str+='<optgroup label="'+ty[i].name+'">';
			if(ty[i].optgroup=='end')str+='</optgroup>';
		}else{
			str+='<option value="'+ty[i][vfs]+'">'+ty[i].name+'</option>';
		}
	}
	$(o).append(str);
}
//是否app上接口
function appobj1(act, can1){
	var bo = false;
	if(typeof(appxinhu)=='object'){
		if(appxinhu[act]){
			try{appxinhu[act](can1);bo = true;}catch(e){}
		}
	}
	return bo;
}
//向PC客户端发送命令
js.cliendsend=function(at, cans, fun,ferr){
	var dk  = '2829';
	if(at=='rockoffice')dk='2827';
	var url = unescape('http%3A//127.0.0.1%3A'+dk+'/%3Fatype');
	if(!cans)cans={};if(!fun)fun=function(){};if(!ferr)ferr=function(){return false;}
	url+='='+at+'&callback=?';
	var llq = navigator.userAgent.toLowerCase();
	if(llq.indexOf('windows nt 5')>0 && dk=='2829'){
		if(!ferr())js.msg('msg','XP的系统不支持哦');
		return;
	}
	var i,v,bo=typeof(jm);
	for(i in cans){
		v = cans[i];
		if(bo=='object')v='base64'+jm.base64encode(v)+'';
		url+='&'+i+'='+v+'';
	}
	var timeoout = setTimeout(function(){if(!ferr())js.msg('msg','无法使用，可能没有登录REIM客户端');},500);
	$.getJSON(url, function(ret){clearTimeout(timeoout);fun(ret);});
}

//发送文档编辑
js.sendeditoffice=function(id,lx){
	if(!lx)lx='0';
	this.ajax('api.php?m=upload&a=rockofficeedit',{id:id,lx:lx},function(ret){
		if(ret.success){
			js.sendeditoffices(ret.data);
		}else{
			js.msg('msg', ret.msg);
		}
	},'get,json');
}
js.sendeditoffices=function(str){
	js.cliendsend('rockoffice',{paramsstr:str},false,function(){js.msg('msg','无法使用，可能没有安装在线编辑插件');return true;});
}

js.ontabsclicks=function(){};
js.inittabs=function(){
	$('.r-tabs div').click(function(){
		js.tabsclicks(this);
	});
}
js.tabsclicks=function(o1){
	var o = $(o1);
	var tid= o.parent().attr('tabid');
	$('.r-tabs[tabid="'+tid+'"] div').removeClass('active');
	$('[tabitem][tabid="'+tid+'"]').hide();
	var ind = o.attr('index');
	o.addClass('active');
	var ho = $('[tabitem='+ind+'][tabid="'+tid+'"]');
	ho.show();
	this.ontabsclicks(ind, tid, o, ho);
}
js.changdu=function(o){
	var max = $(o).attr('maxlength');
	if(max>0){
		var zlen = o.value.length;
		if(zlen>parseFloat(max))js.alert('录入数据长度'+zlen+'超过'+max+'总长度，其余会被截取掉');
	}
}
js.showmap=function(str){
	var url = 'index.php?d=main&m=kaoqin&a=location&info='+jm.base64encode(str)+'';
   js.location(url);
}


js.setapptitle=function(tit){
	if(!apicloud)return;
	var svst = sessionStorage.getItem('apiwinname');
	if(svst){
		if(!tit)tit=document.title;
		js.sendevent('title',svst,{title:tit})
	}
}
js.fileoptWin=function(id){
	var otype = this.opentype,ourl='widget://index.html';
	if(otype && otype!='nei')ourl=jm.base64decode(otype);
	var bstr=jm.base64encode('{"name":"文件","fileid":"'+id+'","url":"fileopen","fileext":""}');
	var url = ''+ourl+'?bstr='+bstr+'';
	return this.apiopenWin(url);
}
js.apiopenWin=function(url){
	if(!apicloud)return false;
	api.openWin({name:'url'+js.getrand(),url: url,bounces:false,softInputBarEnabled:false,slidBackEnabled:true,vScrollBarEnabled:false,hScrollBarEnabled:false,allowEdit:false,progress:{type:'',title:'', text:'',   color:''}});	
	return true;
}
js.appwin=function(na,dz){	
	var otype = this.opentype,ourl='widget://index.html';
	if(otype && otype!='nei')ourl=jm.base64decode(otype);
	if(dz.substr(0,4)!='http')dz=NOWURL+dz;
	var jg  = (dz.indexOf('?')==-1)?'?':'&';
	if(!na)na='&nbsp;';
	var bstr=jm.base64encode('{"name":"'+na+'","url":"openurl","dizhi":"'+dz+''+jg+'hideheader=true"}');
	var url = ''+ourl+'?bstr='+bstr+'';
	return this.apiopenWin(url);	
}
js.sendevent=function(typ,na,d){
	if(!apicloud)return false;
	if(!d)d={};
	d.opttype=typ;
	if(!na)na='xinhuhome';
	if(api.sendEvent)api.sendEvent({name: na,extra:d});
}