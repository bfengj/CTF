function initbody(){
	objcont = $('#content_allmainview');
	objtabs = $('#tabs_title');
	menubadge = hongbadge = menutopid = {};//角标数组
	resizewh();
	$(window).resize(resizewh);
	clickhome();
	if(show_key!='')jm.setJmstr(jm.base64decode(show_key));
	var a = $("li[pmenuid]");
	a.click(function(){
		if(js.ajaxbool)return;
		a.removeClass();
		loadmenu(this);
	});
	loadmenu($("li[pmenuid]")[0]);
	if(typeof(FormData)=='undefined'){
		js.msg('msg','您的浏览器太低了无法达到想要的预览效果<br>建议使用IE10+，Firefox，Chrome等高级点的',60);
	}
	if(get('indexuserl')){
		var ddsata=[{
			name:'<i class="icon-cog"></i> 个人设置',num:'grcog','icon':'cog',url:'system,geren,cog',names:'个人设置'
		},{
			name:'<i class="icon-bell"></i> 提醒信息',num:'todo','icon':'bell',url:'system,geren,todo',names:'提醒信息'
		},{
			name:'<i class="icon-picture"></i> 修改头像',num:'face','icon':'picture'
		},{
			name:'<i class="icon-laptop"></i> 新版首页1',num:'jiu','icon':'laptop'
		},{
			name:'<i class="icon-certificate"></i> 新版首页2',num:'jiu2','icon':'laptop'
		},{
			name:'<i class="icon-user"></i> 帐号('+adminuser+')',num:'user'
		}];
		if(js.request('afrom')=='')ddsata.push({name:'<i class="icon-signout"></i> 退出',num:'exit'});
		$('#indexuserl').rockmenu({
			width:150,top:get('topheaderid').offsetHeight,
			data:ddsata,
			itemsclick:function(d){
				if(d.num=='exit'){
					js.confirm('确定要退出系统吗？',function(bn){
						if(bn=='yes')js.location('?m=login&a=exit');
					});
					return;
				}
				if(d.num=='face'){
					editfacechang(adminid, adminname);
					return;
				}
				if(d.num=='jiu'){
					js.location('?a=home');
					return;
				}
				if(d.num=='jiu2'){
					js.location('?a=new');
					return;
				}
				if(d.num=='user')return;
				addtabs({num:d.num,url:d.url,name:d.names,icons:d.icon});
			}
		});
	}
	$('#reordershla').click(function(){
		$('#indexmenu').hide();
		$('#indexmenuss').show();
		resizewh();
	});
	$('#indexmenuss').click(function(){
		$('#indexmenu').show();
		$('#indexmenuss').hide();
		resizewh();
	});
	_addbodykey();
	$('#indesearchmenu').click(function(){
		_searchmenus();
	});
	
	function _loadjsurl(){
		js.importjs('web/res/mode/echarts/echarts.common.min.js');
	}
	setTimeout(_loadjsurl,100);
	
	//禁止后退
	$(document.body).keydown(function(e){
		var lxs = e.target.nodeName.toLowerCase();
		var bo  = (lxs=='input' || lxs=='textarea');
		if(e.keyCode==8 && !bo)return false;
	});
	try{
		history.pushState(null, null, document.URL);
		window.addEventListener('popstate', function (){
			history.pushState(null, null, document.URL);
		});
	}catch(e){}
}


function _searchmenus(){
	js.prompt('搜索菜单','请输入搜索菜单名：',function(jg,txt){
		if(jg=='yes' && txt){
			$('#menulisttop').html('搜索结果');
			$('#menulist').html('<div style="padding:30px;" align="center"><img src="images/mloading.gif"></div>');
			js.ajax(js.getajaxurl('getmenusou','index'),{key:txt}, function(da){
				showmenula(da);
			},'post,json');
		}
	});
}
var loadmenuci=0;
function loadmenu(o){
	var o1 = $(o),id,s1,ico;
	o1.addClass('active');id = o1.attr('pmenuid');ico = o1.attr('icon');
	if(!id)return;
	loadmenuci++;
	s1 = o.title;
	if(ico)s1='<i class="icon-'+ico+'"></i> '+s1+'';
	$('#menulisttop').html(s1);
	showmenupid = id;
	$('#menulist').html('<div style="padding:30px;" align="center"><img src="images/mloading.gif"></div>');
	js.ajax(js.getajaxurl('getmenu','index'),{pid:id,loadci:loadmenuci}, function(da){
		if(da.menutopid)menutopid=da.menutopid;
		showmenula(da.menuarr);
	},'get,json');
}
function showmenula(a){
	menuarr = a;
	var i,s='',j,k,child,hhild,css1,to,sid,o;
	hongbadge={};
	for(i=0; i<a.length; i++){
		o   = a[i];
		css1='border-radius:0px;border-left:none;border-right:none;cursor:pointer;TEXT-DECORATION:none';
		s+='<a class="list-group-item" onClick="clickmenu(this,'+i+',-1,-1)" style="'+css1+'"><i class="icon-'+o.icons+'"></i> '+o.name+'';
		if(o.bh && o.stotal==0)s+=' <span class="badge" badge="'+o.bh+'" style="float:none;display:none;background:red;color:white"></span>';
		if(o.stotal>0)s+='<font color="red" id="hongdian_'+o.num+'" style="display:none">●</font><span style="float:right" id="menu_down_isons_'+o.num+'" class="icon-caret-down"></span>';
		s+='</a>';
		if(o.stotal>0){
			child = o.children;
			for(j=0; j<child.length; j++){
				o = child[j];
				s+='<a downmenu="'+i+'_'+j+'" class="list-group-item" style="'+css1+';padding-left:30px;background:rgba(0,0,0,0.05);display:none" id="menu_list_'+o.num+'" onClick="clickmenu(this,'+i+','+j+',-1)"><i class="icon-'+o.icons+'"></i> '+o.name+'';
				if(o.bh){
					hongbadge[o.bh]=a[i].num;
					s+=' <span class="badge" badge="'+o.bh+'" style="float:none;display:none;background:red;color:white"></span>';
				}
				if(o.stotal>0)s+='<span style="float:right" id="menu_down_isons_'+o.num+'" class="icon-caret-down"></span>';
				s+='</a>';
				if(o.stotal>0){
					hhild = o.children;
					for(k=0; k<hhild.length; k++){
						o = hhild[k];
						s+='<a downmenu="'+i+'_'+j+'_'+k+'" class="list-group-item" style="'+css1+';padding-left:60px;background:rgba(0,0,0,0.02);display:none" id="menu_list_'+o.num+'" onClick="clickmenu(this,'+i+','+j+','+k+')"><i class="icon-'+o.icons+'"></i> '+o.name+'</a>';
					}
				}	
			}
		}
	}
	if(s=='')s='<div style="padding:30px;color:#cccccc" align="center">暂无</div>';
	$('#menulist').html(s);
	showmenubadge();
}
//显示角标处理
function showmenubadge(){
	var o,sid,to,tnu,tid,o1;
	$('li[pmenuid] font').remove();
	for(sid in menubadge){
		to = menubadge[sid];
		o = $('span[badge="'+sid+'"]');
		tnu= hongbadge[sid];
		if(o){if(to<=0){
			o.hide();
			if(tnu)$('#hongdian_'+tnu+'').hide();
		}else{
			o.show();o.html(to);
			if(tnu)$('#hongdian_'+tnu+'').show();
			tid= menutopid[sid];
			if(tid){
				o1 = $('li[pmenuid="'+tid+'"]');
				o1.find('font').remove();
				o1.find('a').append('<font color="red">●</font>');
			}
		}}
	}
}
function removetadge(num){
	if(typeof(menubadge[num])=='undefined')return;
	$('span[badge="'+num+'"]').hide();
	menubadge[num]=0;
	showmenubadge();
}

function opentixiang(){
	addtabs({num:'todo',url:'system,geren,todo',icons:'bell',name:'提醒信息'});
	return false;
}

function clickhome(){
	var ad = {num:'home',url:'home,index',icons:'home',name:'首页',hideclose:true};
	if(homeurl!='')ad.url= homeurl;
	if(homename!='')ad.name= homename;
	addtabs(ad);
	return false;
}
var fistwk=0;
function resizewh(){
	var _lw = 0;
	if(get('indexmenu')){
		_lw = $('#indexmenu').width()+5;
		if(get('indexmenu').style.display=='none'){
			_lw = $('#indexmenuss').width()+5;
		}
	}
	var w = winWb()-_lw;
	var h = winHb(),_ht=0;
	if(get('topheaderid'))_ht=get('topheaderid').offsetHeight;
	viewwidth = w; 
	viewheight = h-_ht-44;
	$('#indexcontent').css({width:''+viewwidth+'px',height:''+(viewheight)+'px'});
	$('#tabsindexm').css({width:''+viewwidth+'px'});
	var nh = h-_ht;
	$('#indexmenu').css({height:''+nh+'px'});
	$('#indexsplit').css({height:''+nh+'px'});
	$('#indexmenuss').css({height:''+nh+'px'});
	$('#menulist').css({height:''+(viewheight)+'px'});
	_pdleftirng();
	
	if(fistwk==0){
		$('#topmenudiv span[pmenuid]').each(function(){
			fistwk+=$(this).width()+30;
		});
	}
	var lw=$('#logodiv').width();
	var rw = $('#topheaderright').width();
	var kj = winWb()-lw-rw;
	$('#topmenudiv').css('width',''+kj+'px');
	var o1 = $('#donwrigthw').css('right',''+rw+'px');
	o1.hide();
	if(fistwk>kj){
		o1.show();
		$('#topmenudiv').css('width',''+(kj-20)+'px');
	}
}

function clickmenu(o, i, j,k){
	var a = menuarr[i];
	if(j>-1)a=a.children[j];
	if(k>-1)a=a.children[k];
	var oi = a.stotal,dxu=''+i+'_';
	if(oi>0){
		$('#hongdian_'+a.num+'').hide();//隐藏红点
		if(j>-1){
			dxu+=''+j+'_';
			$('a[downmenu^="'+dxu+'"]').toggle();
		}
		var o1	= get('menu_down_isons_'+a.num+'');
		if(o1.className.indexOf('down')>0){
			o1.className='icon-caret-up';
			if(j==-1)$('a[downmenu^="'+dxu+'"]').show();
		}else{
			o1.className='icon-caret-down';
			if(j==-1)$('a[downmenu^="'+dxu+'"]').hide();
		}
	}else{
		addtabs(a);
	}
}

var coloebool = false;
function closetabs(num){
	tabsarr[num] = false;
	$('#content_'+num+'').remove();
	$('#tabs_'+num+'').remove();
	if(num == nowtabs.num){
		var now ='home',i,noux;
		for(i=opentabs.length-1;i>=0;i--){
			noux= opentabs[i];
			if(get('content_'+noux+'')){
				now = noux;
				break;
			}
		}
		changetabs(now);
	}
	coloebool = true;
	_pdleftirng();
	setTimeout('coloebool=false',10);
}

function closenowtabs(){
	var nu=nowtabs.num;
	if(nu=='home')return;
	closetabs(nu);
}

function nowtabssettext(srt,icos){
	var num=nowtabs.num;
	var txt = srt;
	tabsarr[num].name = srt;
	nowtabs.name = srt;
	if(!icos)icos=nowtabs.icons;
	if(icos)txt='<i class="icon-'+icos+'"></i>  '+txt+'';
	$('#tabs_'+num+' font').html(txt);
}

function changetabs(num,lx){
	if(coloebool)return;
	if(!lx)lx=0;
	$("div[temp='content']").hide();
	$("[temp='tabs']").removeClass();
	var bo = false;
	if(get('content_'+num+'')){
		$('#content_'+num+'').show();
		$('#tabs_'+num+'').addClass('active');
		nowtabs = tabsarr[num];
		if(typeof(nowtabs.onshow)=='function')nowtabs.onshow();
		bo = true;
	}
	opentabs.push(num);
	if(lx==0)_changhhhsv(num);
	return bo;
}
function _changhhhsv(num){
	var o=$("[temp='tabs']"),i,w1=0;
	for(i=0;i<o.length;i++){
		if(o[i].id=='tabs_'+num+'')break;
		w1=w1+o[i].scrollWidth;
	}
	$('#tabsindexm').animate({scrollLeft:w1});
}
function _changesrcool(lx){
	var l = $('#tabsindexm').scrollLeft();
	var w = l+200*lx;
	$('#tabsindexm').animate({scrollLeft:w});
}
function _pdleftirng(){
	var mw=get('tabs_title').scrollWidth;
	if(mw>viewwidth){$('.jtcls').show();}else{$('.jtcls').hide();}
}

function addiframe(a){
	a.url = 'index,iframe,url='+jm.base64encode(a.url)+'';
	addtabs(a);
}


//选择卡右键
function tabsright(num,e){
	function _closeother(nu){
		var nus,d1;
		for(nus in tabsarr){
			d1 = tabsarr[nus];
			if(d1 && !d1.hideclose && nus!=nu)closetabs(nus);
		}
	}
	if(typeof(tabsrights)=='undefined')tabsrights=$.rockmenu({
		width:150,
		data:[],
		itemsclick:function(d){
			var lx = d.lx,num=d.num;
			if(lx==0)closetabs(num);
			if(lx==1){
				var d1 = tabsarr[num],s1=''+PROJECT+'/'+d1.urlpath+'';
				var s  = '['+d1.name+']页面地址是：'+s1+'<div style="word-wrap:break-word;">，在[系统→基础管理→菜单管理]设置URL地址为：'+d1.url+'';
				if(HOST=='127.0.0.1' || HOST=='localhost')s+='&nbsp;<a onclick="_openfile(\''+jm.base64encode(s1)+'\')" href="javascript:;">[打开]</a>';
				s+='</div>';
				js.alert(s);
			}
			if(lx==2)_closeother(num);
			if(lx==3)location.reload();
			if(lx==4)_opentabls(d.tobj);
		}
	});
	var to= tabsarr[num],d = [];
	if(!to.hideclose)d.push({'name':'关闭','num':num,lx:0});
	if(num==nowtabs.num)d.push({'name':'关闭其它页面','num':num,lx:2});
	if(admintype==1)d.push({'name':'查看页面地址','num':num,lx:1});
	if(homeurl=='' && to.id)d.push({'name':'新窗口打开',tobj:to,'num':num,lx:4});
	d.push({'name':'全部刷新','num':num,lx:3});
	tabsrights.setData(d);
	tabsrights.showAt(e.clientX,e.clientY+5);
}
function _opentabls(d){
	var url = '?homeurl='+jm.base64encode(d.url)+'&homename='+jm.base64encode(d.name)+'&menuid='+jm.base64encode(d.id)+'';
	window.open(url);
}

//开发时打开文件
function _openfile(s){
	js.ajax(js.getajaxurl('openfile','index'),{file:s},function(ds){
		if(ds!='ok')js.msg('msg', ds);
	},'get');
}

/**
*	添加选择卡
*/
function addtabs(a){
	var url = a.url,
		num	= a.num;
	if(isempt(url))return false;
	removetadge(num);
	if(url.indexOf('add,')==0){openinput(a.name,url.substr(4));return;}
	if(url.indexOf('open:')==0){window.open(url.substr(5));return;}
	if(url.indexOf('http')==0 || url.substr(0,1)=='?'){addiframe(a);return;}
	if(nowtabs && nowtabs.id && !a.id)a.id=nowtabs.id;
	nowtabs = a;
	if(changetabs(num))return true;

	var s = '<td temp="tabs" oncontextmenu="tabsright(\''+num+'\',event);return false;"; nowrap onclick="changetabs(\''+num+'\',1)" id="tabs_'+num+'" class="active"><font>';
	if(a.icons)s+='<i class="icon-'+a.icons+'"></i>  ';
	s+=a.name+'</font>';
	if(!a.hideclose)s+='<span onclick="closetabs(\''+num+'\')"><img src="images/del.png" width="12" height="12"></span>';
	s+='</td>';
	objtabs.append(s);
	_changhhhsv(num);
	_pdleftirng();
	
	var rand = js.getrand(),i,oi=2,
		ura	= url.split(','),
		dir	= ura[0],
		mode= ura[1];
	url =''+dir+'/'+mode+'/rock_'+mode+'';
	if(ura[2]){
		if(ura[2].indexOf('=')<0){
			oi=3;
			url+='_'+ura[2]+'';
		}
	}
	var urlpms= '';
	for(i=oi;i<ura.length;i++){
		var nus	= ura[i].split('=');
		urlpms += ",'"+nus[0]+"':'"+nus[1]+"'";
	}
	if(urlpms!='')urlpms = urlpms.substr(1);
	var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
	$('#indexcontent').append(bgs);
	a.urlpath = url+'.php';
	objcont.append('<div temp="content" id="content_'+num+'"></div>');
	$.ajax({
		url:'?m=index&a=getshtml&surl='+jm.base64encode(url)+'&num='+num+'&menuname='+jm.base64encode(a.name)+'',
		type:'get',
		success: function(da){
			$('#mainloaddiv').remove();
			var s = da;
				s = s.replace(/\{rand\}/gi, rand);
				s = s.replace(/\{adminid\}/gi, adminid);
				s = s.replace(/\{adminname\}/gi, adminname);
				s = s.replace(/\{mode\}/gi, mode);
				s = s.replace(/\{dir\}/gi, dir);
				s = s.replace(/\{params\}/gi, "var params={"+urlpms+"};");
			var obja = $('#content_'+num+'');
			obja.html(s);
		},
		error:function(){
			$('#mainloaddiv').remove();
			var s = 'Error:加载出错喽,'+url+'';
			$('#content_'+num+'').html(s);
		}
	});
	tabsarr[num] = a;
	return false;
}