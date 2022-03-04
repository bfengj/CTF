function initbody(){
	jm.setJmstr(jm.base64decode(show_key));
	objcont = $('#content_allmainview');
	objtabs = $('#tabs_title');
	resizewh();
	$(window).resize(resizewh);
	clickhome();
	_addbodykey();
}

function opentixiang(){
	addtabs({num:'todo',url:'system,geren,todo',icons:'bell',name:'提醒信息'});
	return false;
}

function clickhome(){
	addtabs({num:'home',url:'home,index',icons:'home',name:'首页',hideclose:true});
	return false;
}

function resizewh(){
	var _lw = 0;
	var w = winWb()-_lw-5;
	var h = winHb();
	viewwidth = w; 
	viewheight = h-44; 
	$('#indexcontent').css({width:''+viewwidth+'px',height:''+(viewheight)+'px'});
	$('#tabsindexm').css({width:''+viewwidth+'px'});
	var nh = h-50;
	$('#indexmenu').css({height:''+nh+'px'});
	$('#indexsplit').css({height:''+nh+'px'});
	$('#indexmenuss').css({height:''+nh+'px'});
	$('#menulist').css({height:''+(viewheight)+'px'});
	_pdleftirng();
}

function clickmenu(o, i, k){
	var a = menuarr[i];
	if(k>-1)a=menuarr[i].children[k];
	var oi = a.stotal;
	if(oi>0){
		$('#menu_down_'+a.num+'').toggle();
		var o1	= get('menu_down_isons_'+a.num+'');
		if(o1.className.indexOf('down')>0){
			o1.className='icon-caret-up';
		}else{
			o1.className='icon-caret-down';
		}
	}else{
		addtabs(a);
	}
}
function changelassa(){
	var o1 = $("div[temp='menu']"),i,cls,cls1;
	for(i=0;i<o1.length;i++){
		cls = o1[i].className,cls1='menuone';
		if(cls.indexOf('two')>0)cls1='menutwo';
		o1[i].className=cls1;
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

function changetabs(num,lx){
	if(coloebool)return;
	if(!lx)lx=0;
	$("div[temp='content']").hide();
	$("[temp='tabs']").removeClass();
	var bo = false;
	if(get('content_'+num+'')){
		$('#content_'+num+'').show();
		$('#tabs_'+num+'').addClass('accive');
		nowtabs = tabsarr[num];
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
function addtabs(a){
	var url = a.url,
		num	= a.num;
	if(isempt(url))return false;
	if(url.indexOf('add,')==0){openinput(a.name,url.substr(4));return;}
	if(url.indexOf('http')==0){window.open(url);return;}
	nowtabs = a;
	if(changetabs(num))return true;

	var s = '<td temp="tabs" nowrap onclick="changetabs(\''+num+'\',1)" id="tabs_'+num+'" class="accive">';
	if(a.icons)s+='<i class="icon-'+a.icons+'"></i>  ';
	s+=a.name;
	if(!a.hideclose)s+='<span onclick="closetabs(\''+num+'\')" class="icon-remove"></span>';
	s+='</td>';
	objtabs.append(s);
	_changhhhsv(num);
	_pdleftirng();
	
	var rand = js.getrand(),i,oi=2,
		ura	= url.split(','),
		dir	= ura[0],
		mode= ura[1];
	url =''+PROJECT+'/'+dir+'/'+mode+'/rock_'+mode+'';
	if(ura[2]){
		if(ura[2].indexOf('=')<0){
			oi=3;
			url+='_'+ura[2]+'';
		}
	}
	url+='.shtml?rnd='+Math.random()+'';
	var urlpms= '';
	for(i=oi;i<ura.length;i++){
		var nus	= ura[i].split('=');
		urlpms += ",'"+nus[0]+"':'"+nus[1]+"'";
	}
	if(urlpms!='')urlpms = urlpms.substr(1);
	var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
	$('#indexcontent').append(bgs);
	
	objcont.append('<div temp="content" id="content_'+num+'"></div>');
	$.ajax({
		url:url,
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
			if(num=='home')s+='<br><h3>你的服务器不支持shtml文件的类型，请设置添加，后缀名：.shtml，MIME类型：text/html</h3>';
			$('#content_'+num+'').html(s);
		}
	});
	tabsarr[num] = a;
	return false;
}