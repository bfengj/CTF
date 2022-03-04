/**
*	REIM即时通信主js
*	caratename：雨中磐石(rainrock)
*	caratetime：2017-07-20 21:40:00
*	homepage:www.rockoa.com
*/

//打开聊天会话
function openchat(id, lx,face){
	if(!lx)lx=0;var types=['user','group'];
	var sle = (types[lx]) ? types[lx] : lx;
	var url = '?d=reim&m=chat&uid='+id+'&type='+sle+'',csne={};
	if(face)csne.icon=face;
	js.open(url, 480,500, ''+sle+'_'+id+'',{},csne);
}
var windowfocus=true;
var reim = {
	connectbool:false,
	otherlogin:false,
	timeloads:0,
	ruloadtime:10*60, 
	userarr:{},
	deptarr:{},
	grouparr:{},
	maindata:{},
	agentarr:{},
	lastloaddt:'',
	apiurl:function(m,a){
		return 'api.php?m='+m+'&a='+a+'';
	},
	show:function(){
		nwjs.init();
		nwjs.createtray(''+systitle+'-'+adminname+'', 1);
		
		bodyunload=function(){
			nwjs.removetray();
		}
		
		nwjs.serverdata=function(d){
			return reim.serverdata(d);
		}
		
		this.date = js.now();
		notifyobj=new notifyClass({
			title:'系统提醒',
			sound:'web/res/sound/todo.ogg',
			sounderr:'',
			soundbo:true,
			showbool:false
		});
		
		this.mainshow();//显示主界面
		this.righthistroboj = $.rockmenu({
			data:[],
			itemsclick:function(d){
				reim.rightclick(d);
			}
		});
		$(window).resize(this.resize);
		$(window).focus(function(){windowfocus=true});
		$(window).blur(function(){windowfocus=false});
		//数秒
		setInterval('reim.timeload()', 1000);
		
		document.ondragover=function(e){e.preventDefault();};
		document.ondrop=function(e){e.preventDefault();};
	},
	openrecord:function(){
		var url = '?homeurl=cmVpbSxyZWNvcmQsYXR5cGU9bXk:&homename=5oiR55qE5Lya6K!d6K6w5b2V&menuid=MjI3';
		js.open(url,1000,550,'chatrecord');
	},
	timeload:function(){
		this.timeloads++;
		//刷新
		if(this.timeloads >= this.ruloadtime){
			this.timeloads = 0;
			this.reload();
		}
		
		if(this.timeloads==5)this.getonline();
		
		//如果服务端断开，用ajax连接
		if(this.timeloads % 10==0){
			this.loadredata();
		}
	},
	
	//内部服务处理
	serverdata:function(a){
		var lx = a.atype;
		if(lx=='cropfinish' && !this.cropScreenbo && a.filepath){
			nwjs.winshow();
		}
		if(lx=='focus')nwjs.winshow();
		if(lx=='crop')this.cropScreen(true);
		if(lx=='notify')this.shownotify(a);
		if(lx=='openchat')this.openchat(a.id,a.type);
		if(lx=='getlogin')return {uid:adminid,uname:adminname,face:adminface};
		if(lx=='getipmac')return nwjs.getipmac();
		if(lx=='office')nwjs.editoffice(a.paramsstr);
		if(lx=='upfile')return nwjs.filetobase64(a.path);
		if(lx=='gpath')return nwjs.getpath();
	},
	shownotify:function(d1){
		var d = js.apply({icon:'images/logo.png','title':'系统提醒',url:''}, d1);
		if(d.msg)notifyobj.showpopup(d.msg,{icon:d.icon,title:d.title,url:d.url,click:function(b){
			if(b.url)nwjs.openurl(b.url);
			return true;
		}});
	},
	resize:function(){
		var h = winHb()-140-40;
		$('#reim_mainview').css('height',''+h+'px');
	},
	
	cropScreen:function(){
		var oatg = nwjs.getpath();
		nw.Shell.openItem(''+oatg+'/images/reimcaptScreen.exe');
	},
	
	//显示聊天主界面
	mainshow:function(bo){
		if(get('reim_main')){
			this.connectservers();
			return;
		}
		var h = winHb()-140-40;
		var s = '<div id="reim_main" style="position:relative" class="reim_main">';
		
		s+='<div class="gradienth" style="height:70px;overflow:hidden">';
		s+='	<table style="top:0px;left:0px" cellspacing="0" cellpadding="0" width="100%"><tr><td height="70" style="padding:0px 10px"><div style="height:40px;overflow:hidden"><img onclick="reim.openuserzl('+adminid+')" style="border-radius:50%;cursor:pointer" src="'+adminface+'" id="myface" width="40" height="40"></div></td><td width="100%"><div>'+adminname+'<span style="font-size:12px;color:#999999">('+adminranking+')</span></div><div style="font-size:12px;color:#999999" class="blank20">'+deptallname+'</div></tr></table>';
		s+='</div>';
		s+='	<div style="border-bottom:1px #dedede solid"><input placeholder="搜索联系人/会话/应用" id="reim_keysou" style="width:100%;padding:0px 5px;height:40px;line-height:30px;border:none;background:none url(web/images/im/sousuo.png) 200px 5px no-repeat;"></div>';
		s+='	<span class="badge red" style="position:absolute;top:100px;left:10%;display:none" id="chat_alltotal">0</span>';
		s+='	<span class="badge red" style="position:absolute;top:100px;left:35%;display:none" id="agent_alltotal">0</span>';
		s+='	<div class="headertab"><div style="width:25%" class="active" title="消息会话" onclick="reim.tabchagne(0, this)"><i class="icon-comment-alt"></i></div><div style="width:25%" title="应用" onclick="reim.tabchagne(3,this)"><i class="icon-th-large"></i></div><div style="width:25%" title="组织结构" onclick="reim.tabchagne(1,this)"><i class="icon-sitemap"></i></div><div style="width:25%" title="会话/群" onclick="reim.tabchagne(2,this)"><i class="icon-group"></i></div></div>';
		
		s+='	<div id="reim_mainview" style="height:'+h+'px;overflow:auto">';
		s+='		<div id="reim_headercenter" style="position:relative">';
		s+='			<div tabdiv="0"><div id="historylist_tems" style="padding-top:50px;text-align:center;color:#cccccc"><span style="font-size:40px"><i class="icon-comment-alt"></i></span><br>暂无消息</div><div class="listviewt" id="historylist"></div>	</div>';
		s+='			<div tabdiv="1" class="usersslist" style="display:none"><span id="showdept_0"></span></div>';
		s+='			<div tabdiv="2" id="reim_showgroupdiv" class="usersslist" style="display:none;"></div>';
		s+='			<div tabdiv="3" id="reim_showagetdiv" style="display:none;"></div>';
		s+='		</div>';
		s+='	</div>';
		
		s+='<div style="height:30px;overflow:hidden;border-top:1px #dddddd solid; background-color:#eeeeee"><div class="toolsliao"><span onclick="reim.indexsyscog()" title="设置"><i class="icon-cog"></i></span><span onclick="reim.creategroup()" title="创建会话"><i class="icon-plus"></i></span><span onclick="location.reload()" title="刷新"><i class="icon-refresh"></i></span><span onclick="reim.openrecord()" title="会话记录"><i class="icon-file-alt"></i></span><span onclick="reim.loginexit()" style="float:right" title="退出"><i class="icon-signout"></i></span>&nbsp;<font id="reim_statusserver" style="font-size:12px"></font></div></div>';
		
		s+='</div>';
		$('#reim_viewshow').html(s);
		this.resize();
		$('#reim_keysou').keyup(function(){reim.searchss();});
		$('#reim_keysou').click(function(){reim.searchss();});
		this.initdata(); //初始化数据
	},
	ajax:function(url,da,fun,type,efun){
		if(!da)da={};if(!type)type='get';
		if(typeof(fun)!='function')fun=function(){};
		if(typeof(efun)!='function')efun=function(){};
		var atyp = type.split(',');type=atyp[0];
		function errshow(esr){
			js.msg('msg',esr);
			efun(esr);
		}
		var ajaxcan={
			type:type,
			data:da,url:url+'&cfrom=reim',
			success:function(ret){
				if(ret.code==199){
					reim.loginexits();
					return;
				}
				if(ret.code==200){
					fun(ret.data);
				}else{
					errshow(ret.msg);
				}
			},error:function(e){
				errshow('处理出错:'+e.responseText+'');
			}
		};
		ajaxcan.dataType='json';
		$.ajax(ajaxcan);
	},
	//连接到服务端
	connectserver:function(){
		this.loaddata('config');
	},
	connectservers:function(){
		if(this.connectbool){
			this.serverstatus(1);
			return;
		}
		var bo = this.showconfig(this.showconfigarr);
		if(bo)js.msg('wait','连接中...');
	},
	showconfig:function(a){
		if(this.connectbool){
			this.serverstatus(1);
			return false;
		}
		if(!a){
			this.serverstatus(3);
			return false;
		}
		var wsurl = jm.base64decode(a.wsurl),receid = a.recid;
		this.showconfigarr = a;
		if(isempt(wsurl) || wsurl.indexOf('ws')!=0){
			this.serverstatus(3);
			return false;
		}
		clearTimeout(this.relianshotime_time);
		websocketobj = new websocketClass({
			adminid:adminid,
			reimfrom:receid,
			wshost:wsurl,
			sendname:adminname,
			onerror:function(o,ws){
				reim.connectbool=false;
				reim.serverstatus(0);
				js.msg('msg','REIM无法连接服务器1<br><span id="lianmiaoshoetime"></span><a href="javascript:;" onclick="reim.connectservers()">[重连]</a>',0);
				reim.relianshotime(30);
			},
			onmessage:function(str){
				reim.connectbool=true;
				clearTimeout(reim.relianshotime_time);
				var a=js.decode(str);
				reim.receivemesb(a);
			},
			onopen:function(){
				reim.connectbool=true;
				reim.serverstatus(1);
				clearTimeout(reim.relianshotime_time);
				js.msg('none');
				reim.initnotify();
			},
			onclose:function(o,e){
				reim.connectbool=false;
				if(reim.otherlogin)return;
				reim.serverstatus(0);
				js.msg('msg','REIM连接已经断开了<br><span id="lianmiaoshoetime"></span><a href="javascript:;" onclick="reim.connectservers()">[重连]</a>',0);
				reim.relianshotime(30);
			}
		});
		return true;
	},
	
	loginexit:function(){
		js.confirm('确定要退出'+systitle+'吗？',function(lx){
			if(lx=='yes')reim.loginexits();
		});
	},
	loginexits:function(){
		if(!nwjsgui){
			window.close();
		}else{
			this.ajax(this.apiurl('login','loginexit'),{},function(ret){
				js.setoption('autologin', '0');
				js.location('?d=reim&a=login');
			});
		}
	},
	
	serverstatus:function(lx){
		var s = '<font color="green">已连接</font>';s='';
		if(lx==0)s='<font color="red">未连接</font>'
		if(lx==2)s='<font color="#ff6600">在别处连接</font>'
		if(lx==3)s='<font color="blue">没服务端</font>';
		$('#reim_statusserver').html(s);
	},
	relianshotime:function(oi){
		clearTimeout(this.relianshotime_time);
		$('#lianmiaoshoetime').html('('+oi+'秒后重连)');
		if(oi<=0){
			this.connectservers();
		}else{
			this.relianshotime_time=setTimeout('reim.relianshotime('+(oi-1)+')',1000);
		}
	},
	
	//服务端发消息调用opener.reim.serversend(a);
	serversend:function(a){
		if(!this.connectbool)return false;
		websocketobj.send(a);
		return true;
	},
	
	//获取在线人员
	getonline:function(){
		this.serversend({'atype':'getonline'});
	},
	
	//加载数据
	loadhistory:function(){
		this.loaddata('history');
	},
	reload:function(){
		this.initdata();
	},
	initdatas:function(){
		this.initbackd(this.bdats);
	},
	
	//没有服务端定时去ajax读取。
	loadredata:function(){
		if(this.connectbool)return;
		this.ajax(this.apiurl('indexreim','ldata'),{type:'history','loaddt':jm.base64encode(this.lastloaddt)}, function(ret){
			var hist = js.decode(ret.json);
			reim.lastloaddt = ret.loaddt;
			reim.reloadhistory(hist);
		});
	},
	reloadhistory:function(a){
		var i,len=a.length,d;
		for(i=0;i<len;i++){
			d = a[i];d.form = 'ajax';
			if(d.sendid!=adminid)this.receivemesb(d);
		}
	},
	
	initdata:function(){
		this.ajax(this.apiurl('indexreim','indexinit'),{}, function(ret){
			reim.bdats = ret;
			js.servernow 	= ret.loaddt;
			js.getsplit();
			reim.initbackd(ret);
		});
	},
	firstpid:0,
	initbackd:function(ret){
		if(!ret.userjson)return;
		this.lastloaddt		= ret.loaddt;
		this.maindata.darr 	= js.decode(ret.deptjson);
		this.maindata.uarr 	= js.decode(ret.userjson);
		this.maindata.garr  = js.decode(ret.groupjson);
		this.maindata.aarr  = js.decode(ret.agentjson);
		this.maindata.harr 	= js.decode(ret.historyjson);
		this.firstpid		= this.maindata.darr[0].pid;
		this.myip 			= ret.ip;
		this.showuser(this.maindata.uarr);
		this.adminmyrs 		= this.userarr[adminid];
		adminname	= this.adminmyrs.name;
		adminface	= this.adminmyrs.face;
		
		this.showgroup(this.maindata.garr);
		this.showagent(this.maindata.aarr);
		this.showhistory(this.maindata.harr);
		
		this.shownowci = 0;
		this.showconfig(ret.config);
		if(ret.editpass==0)this.editpass('请先修改密码后在使用','none');
	},
	loaddata:function(type){
		this.ajax(this.apiurl('indexreim','ldata'),{type:type}, function(ret){
			var hist = js.decode(ret.json);
			reim['show'+ret.type+''](hist);
		});
	},
	//显示历史聊天列表
	showhistory:function(a){
		var i,len=a.length;
		$('#historylist_tems').show();
		$('#historylist').html('');
		for(i=0;i<len;i++){
			this.showhistorys(a[i]);
		}
	},
	showhistorys:function(d,pad, lex){
		if(!d)return;
		var s,ty,o=$('#historylist'),d1,st,attr,stst,cont,cls,nastr;
		ty	= d.type;
		if(ty=='user')d1=this.userarr[d.receid];
		if(ty=='group')d1=this.grouparr[d.receid];
		if(d1){
			d.name = d1.name;
			d.face = d1.face;
		}else if(!lex && ty=='user'){
			this.ajax(this.apiurl('indexreim','loadinfo'),{type:ty,receid:d.receid}, function(ret){
				if(!ret){
					console.error('无法读取到'+ty+','+d.receid+'的信息');
				}else{
					reim.showuser(ret);
					reim.showhistorys(d, pad, true);
				}
			});
			return;
		}
		var num = ''+ty+'_'+d.receid+'';
		$('#history_'+num+'').remove();
		var stotal = 0;
		cont 	= jm.base64decode(d.cont);
		var ops = d.optdt.substr(11,5);
		if(d.optdt.indexOf(this.date)!=0)ops=d.optdt.substr(5,5);
		attr = 'oncontextmenu="return reim.historyright(this,event);" tsaid="'+d.receid+'" tsaype="'+d.type+'" ';
		st	 = d.stotal;if(st=='0')st='';
		stotal+=parseFloat(d.stotal);
		stst = '';
		cls  = '';
		nastr= d.name;
		if(d.type=='user'){
			stst='uid="'+d.receid+'"';
			if(d1.online=='0')cls=' offline';
		}
		if(d.type=='group' && d1){
			if(d1.deptid=='1')nastr+=' <span class="reimlabel">全员</span>';
			if(d1.deptid>'1')nastr+=' <span class="reimlabel1">部门</span>';
		}
		s	= '<div '+attr+' '+stst+' class="lists'+cls+'" rtype="hist" id="history_'+num+'" onclick="reim.openchat('+d.receid+',\''+ty+'\')">';
		s+='<table cellpadding="0" border="0" width="100%"><tr>';
		s+='<td style="padding-right:8px"><div style="height:34px;overflow:hidden"><img src="'+d.face+'"></div></td>';
		s+='<td align="left" width="100%"><div class="name">'+nastr+'</div><div class="huicont">'+cont+'</div></td>';
		s+='<td align="center" nowrap><span id="chatstotal_'+num+'" class="badge red">'+d.stotal+'</span><br><span style="color:#cccccc;font-size:10px">'+ops+'</span></td>';
		s+='</tr></table>';
		s+='</div>';
		if(!pad){o.append(s);}else{o.prepend(s)}
		$('#historylist_tems').hide();
		this.showbadge('chat');
	},
	
	//在线离线设置
	setonline:function(online){
		$('div[uid]').addClass('offline');
		var onlies = online.split(','),i,uid;
		for(i=0;i<onlies.length;i++){
			uid=onlies[i];
			this.setonlines(uid, 1);
		}
	},
	setonlines:function(uid, on){
		var d = this.userarr[uid];
		if(on==1){
			$('div[uid='+uid+']').removeClass('offline');
		}else{
			$('div[uid='+uid+']').addClass('offline');
		}
		this.userarr[uid].online = on;
		this.maindata.uarr[d.i].online = on;
	},
	
	historyright:function(o1,e){
		var rt = $(o1).attr('rtype');
		if(isempt(rt))return false;
		this.rightdivobj = o1;
		var d=[{name:'打开',lx:0}];
		if(rt.indexOf('agent')>-1){
			d.push({name:'打开窗口',lx:1});
		}
		if(rt.indexOf('hist')>-1){
			d.push({name:'删除此记录',lx:2});
		}
		this.righthistroboj.setData(d);
		this.righthistroboj.showAt(e.clientX-3,e.clientY-3);
		return false;
	},
	rightclick:function(d){
		var o1 = $(this.rightdivobj),lx=d.lx;
		var tsaid = o1.attr('tsaid'),
			tsayp = o1.attr('tsaype');
		if(lx==0){
			if(tsayp=='user')this.openuser(tsaid);
			if(tsayp=='group')this.opengroup(tsaid);
		}
		if(lx==2){
			o1.remove();
			var tst=$('#historylist').text();if(tst=='')$('#historylist_tems').show();
			this.ajax(this.apiurl('reim','delhistory'),{type:tsayp,gid:tsaid},false);
		}		
	},
	
	//打开聊天界面
	openchat:function(id,type){
		this.showbadge('chat',''+type+'_'+id+'', 0);
		if(type=='agent'){
			this.openagent(id);
		}else{
			openchat(id,type);
		}
		this.setyd(type,id);
	},
	opengroup:function(id){
		this.openchat(id, 'group');
	},
	openuser:function(id){
		this.openchat(id, 'user');
	},
	
	//会话标识已读
	setyd:function(type,id){
		this.ajax(this.apiurl('reim','yiduall'),{type:type,gid:id},false);
	},
	
	//切换聊天主界面隐藏显示
	mainclose:function(){
		$('#reim_main').toggle();
		this.showzhu();
	},
	//隐藏主界面
	hidezhu:function(){
		$('#topheaderid').hide();
		$('.topcenter').hide();
		$('#zhutable').hide();
	},
	//显示主界面
	showzhu:function(){
		$('#topheaderid').show();
		$('.topcenter').show();
		$('#zhutable').show();
	},
	//选择卡切换
	tabchagne:function(oi,o1){
		$('.headertab div').removeClass();
		o1.className = 'active';
		$('#reim_headercenter').find("div[tabdiv]").hide();
		$('#reim_headercenter').find("div[tabdiv='"+oi+"']").show();
		if(oi=='1')this.showdept(this.firstpid,0);
	},
	//加载群会话列表
	loadgroup:function(){
		this.loaddata('group');
	},
	
	loadagent:function(){
		this.loaddata('agent');
	},
	showgroup:function(a){
		var i=0,len=a.length,s,d,o=$('#reim_showgroupdiv'),msg='';
		o.html('');
		for(i=0;i<len;i++){
			d 	= a[i];
			s	= '<div style="padding-left:10px" id="group_'+d.id+'" onclick="reim.opengroup('+d.id+')"><img src="'+d.face+'" align="absmiddle">'+d.name+'';
			if(d.deptid=='1')s+=' <span class="reimlabel">全员</span>';
			if(d.deptid>'1')s+=' <span class="reimlabel1">部门</span>';
			s	+='</div>';
			this.grouparr[d.id] = d;
			o.append(s);
		}
	},
	
	//显示组织结构
	showdept:function(pid, xu){
		var i=0,len,s='',a,wfj,cls;
		var sv= '#showdept_'+pid+'';if(xu==0)sv='#showdept_0';
		var o = $(sv);
		var tx= o.text();
		if(tx){if(xu!=0){o.toggle();}return;}
		
		len=this.maindata.uarr.length;
		for(i=0;i<len;i++){
			a=this.maindata.uarr[i];
			if(pid==a.deptid || a.deptidss.indexOf(','+pid+',')>-1){
				cls='offline';if(a.online==1)cls='';
				s='<div uid="'+a.id+'" class="'+cls+'" style="padding-left:'+(xu*20+10)+'px" onclick="reim.openuserzl('+a.id+')">';
				s+='	<img src="'+a.face+'" align="absmiddle"> '+a.name+' <font color="#888888">('+a.ranking+')<font>';
				s+='</div>';
				o.append(s);
			}
		}
		
		len = this.maindata.darr.length
		for(i=0;i<len;i++){
			a=this.maindata.darr[i];
			if(pid==a.pid){
				wfj = 'icon-folder-close-alt';
				if(a.ntotal=='0')wfj='icon-file-alt';
				s='<div style="padding-left:'+(xu*20+10)+'px" onclick="reim.showdept('+a.id+','+(xu+1)+')">';
				s+='	<i class="'+wfj+'"></i> '+a.name+'';
				s+='</div>';
				s+='<span id="showdept_'+a.id+'"></span>';
				o.append(s);
				if(xu==0)this.showdept(a.id, xu+1);
			}
		}
		
	},
	
	//显示用户
	showuser:function(a){
		var i=0,len=a.length,d;
		for(i=0;i<len;i++){
			d 	= a[i];
			d.i = i;
			d.online=0;
			this.maindata.uarr[i] = d;
			this.userarr[d.id] = d;
		}
	},
	
	//显示用户信息
	openuserzl:function(id){
		var d=this.userarr[id];
		if(!d)return;
		if(isempt(d.tel))d.tel='';if(isempt(d.email))d.email='';
		if(isempt(d.sex))d.sex='?';
		var s = '<div>';
		s+='<div align="center" style="padding:10px;"><img id="myfacess" onclick="$(this).imgview()" src="'+d.face+'" height="80" width="80" style="border-radius:40px;border:1px #eeeeee solid">';
		if(id==adminid)s+='<br><a href="javascript:;" id="fupbgonet" onclick="reim.upfaceobj.click()" style="font-size:12px">修改头像</a>';
		s+='</div>';
		s+='<div style="line-height:25px;padding:10px;padding-left:20px;"><font color=#888888>姓名：</font>'+d.name+'<br><font color=#888888>部门：</font>'+d.deptallname+'<br><font color=#888888>职位：</font>'+d.ranking+'<br><font color=#888888>性别：</font>'+d.sex+'<br><font color=#888888>电话：</font>'+d.tel+'<br><font color=#888888>手机：</font>'+d.mobile+'<br><font color=#888888>邮箱：</font>'+d.email+'</div>';
		s+='</div>';
		js.tanbody('userziliao',''+d.name+'', 240,350,{
			html:s,
			btn:[{text:'发消息'}]
		});
		$('#userziliao_btn0').click(function(){
			reim.openuser(id);
		});
		if(id==adminid){
			this.upfaceobj=$.rockupload({inputfile:'upfacess',uptype:'image',
				urlparams:{noasyn:'yes'},
				onsuccess:function(f,str){
					var a=js.decode(str);
					if(!a.id)return;
					reim.saveface(a.id);
				},
				onchange:function(){
					$('#fupbgonet').html('上传中...');
				}
			});
		};
	},
	saveface:function(fid){
		this.ajax(this.apiurl('reim','changeface'),{id:fid},function(face){
			get('myface').src=face;
			get('myfacess').src=face;
			adminface=face;
			js.setoption('adminface', face);
			$('#fupbgonet').html('修改成功');
		});
	},
	
	//显示应用
	showagent:function(a){
		var i=0,len=a.length,d,types,ty,o=$('#reim_showagetdiv'),s='',oi,atr;
		o.html('');
		var typearr = {};
		for(i=0;i<len;i++){
			d 	= a[i];
			d.i = i;
			this.agentarr[d.id] = d;
			if(!typearr[d.types])typearr[d.types]=[];
			typearr[d.types].push(d);
		}
		var col = 3,wd = 100/col;
		for(ty in typearr){
			len = typearr[ty].length;
			s='<div class="reim_agent_types">'+ty+'</div><table class="reim_agent_grid" width="100%"><tr>';
			oi	= 0;
			for(i=0;i<len;i++){
				oi++;atr='';
				if(oi==1)atr='style="border-left:none"';
				if(oi==col)atr='style="border-right:none"';
				d = typearr[ty][i];
				s+='<td '+atr+' onclick="reim.openagent('+d.id+')" width="'+wd+'%">';
				s+='<div class="reim_agent_div">';
				s+='	<span id="agentstotal_'+d.num+'" class="badge red">'+d.stotal+'</span>';
				s+='	<div class="reim_agent_img"><img src="'+d.face+'"></div>';
				s+='	<div class="reim_agent_text">'+d.name+'</div>';
				s+='</div>';
				s+='</td>';
				if(oi%col==0){
					if(i<len-1)s+='</tr><tr>';
					oi=0;
				}
			}
			if(oi>0)for(i=oi;i<col;i++){
				atr='';
				if(i==col-1)atr='style="border-right:none"';
				s+='<td '+atr+' width="'+wd+'%">&nbsp;</td>';
			}
			s+='</tr></table>';
			o.append(s);
		}
		this.showbadge('agent');
	},
	showbadge:function(slx,num,lx){
		var to = 0,chanu=''+slx+'stotal_'+num+'';
		$("span[id^='"+slx+"stotal_']").each(function(){
			var o1,vd,ids;
			ids= this.id;
			o1 = $(this);
			vd = parseFloat(o1.text());
			if(ids==chanu){
				if(lx==0){vd=0;o1.text('0');}
				if(lx==1){vd++;o1.text(''+vd+'');}
			}
			if(vd==0){o1.hide();}else{o1.show();}
			to+=vd;
		});
		var o1 = $('#'+slx+'_alltotal');
		o1.text(''+to+'');
		if(to==0){o1.hide();}else{o1.show();}
		var oi = parseFloat($('#agent_alltotal').text()) + parseFloat($('#chat_alltotal').text());
		nwjs.changeicon(oi); //托盘图标
	},
	
	//打开应用
	openagent:function(id){
		var d = this.agentarr[id];
		this.showbadge('agent',d.num,0);
		var w = 1000,h=570,url = d.urlpc;
		if(isempt(url)){
			url = d.urlm;
			if(isempt(url)){
				url = '?d=we&m=ying&num='+d.num+''; //先默认用移动端
			}
			w = 320;
		}
		var jg = (url.indexOf('?')>-1)?'&':'?';
		url+=''+jg+'openfrom=reim';
		//考勤打卡
		if(d.num=='kqdaka'){
			this.opendaka();return;
		}
		js.open(url, w,h,'agent'+d.num+'');
	},
	
	//考勤打卡
	opendaka:function(bo){
		var url = '?d=reim&m=ying&a=daka',w = 550;h=300;
		js.open(url, w,h,'agentkqdaka',{},{icon:'images/adddk.png'});
	},
	
	//显示到会话列表上
	addhistory:function(lx,id,sot,cont,opt,sne,name,face){
		if(!sne)sne='';
		if(sne)sne=jm.base64encode(sne+':');
		if(lx!='group')sne='';
		var d={type:lx,receid:id,stotal:sot,cont:sne+cont,optdt:opt};
		if(name)d.name = name;
		if(face)d.face = face;
		this.showhistorys(d, true);
	},
	editpass:function(bt,cse){
		if(!bt)bt='修改密码';
		if(!cse)cse='';
		js.tanbody('winiframe',bt,260,300,{
			html:'<div style="height:250px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
			bbar:'none',
			closed:cse
		});
		openinputiframe.location.href='?m=index&d=we&a=editpass&hideheader=true&ofrom=reim';
	},
	//别的地方登录
	otherlogins:function(){
		this.otherlogin = true;
		var msg='已在别的地方连接了';
		js.msg('success', msg, -1);
		this.serverstatus(2);
	},
	
	//服务端接收到推送消息
	receivemesb:function(d, lob){
		var lx=d.type,sendid=d.adminid,num,face,ops=false,msg='',ot,ots,garr,tits,gid;
		if(!sendid)sendid = d.sendid;
		if(lx=='offoline'){
			this.otherlogins();
			return;
		}
		if(lx=='getonline'){
			this.setonline(d.online);
			return;
		}
		var a 	= this.userarr[sendid];
		if(a){
			d.sendname=a.name;
			d.face = a.face;
		}else if(!lob){
			this.ajax(this.apiurl('indexreim','loadinfo'),{type:'user',receid:sendid}, function(ret){
				if(!ret){
					console.error('无法读取到'+sendid+'的信息');
				}else{
					reim.showuser(ret);
					reim.receivemesb(d, true);
				}
			});
			return;
		}
		gid = d.gid;
		if(lx == 'user' || lx == 'group'){
			num		= ''+lx+'_'+sendid+'';
			if(lx == 'group')num = ''+lx+'_'+gid+'';
			ops 	= js.openrun(num, 'recemess', d);
			
			if(lx == 'group'){
				garr= this.grouparr[gid];
				if(!garr){
					this.loadgroup();
					garr={face:'images/group.png'};
				}
				face= garr.face;
			}
			this.setonlines(sendid,1);//说明是在线的
			var title = document.title+'消息';
			if(ops){
				ops.focus();
			}else{
				if(lx == 'user'){
					msg = '人员['+d.sendname+']，发来一条信息';
					notifyobj.showpopup(msg,{icon:d.face,sendid:sendid,title:title,rand:num,click:function(b){
						reim.openuser(b.sendid);
						return true;
					}});
				}
				if(lx == 'group'){
					if(!d.gname)d.gname = d.name;
					msg = '人员['+d.sendname+']，发来一条信息，来自['+d.gname+']';
					if(d.form=='ajax')d.sendname='';
					notifyobj.showpopup(msg,{icon:garr.face,gid:gid,title:title,rand:num,click:function(b){
						reim.opengroup(b.gid);
						return true;
					}});
				}
			}
			
			
			ot 		= $('#chatstotal_'+num+'');ots=ot.text();if(ots=='')ots='0';
			if(!ops)ots = parseFloat(ots)+1;
			
			if(lx=='user')this.addhistory(lx,sendid,ots,d.cont,d.optdt);
			if(lx=='group')this.addhistory(lx,gid,ots,d.cont,d.optdt,d.sendname);
		}

		//应用的通知提醒
		if(lx == 'agent'){
			garr = this.agentarr[gid];
			num	 = 'agent_'+gid+'';
			if(!garr){
				this.loadagent();
				garr={face:'images/logo.png',pid:0};
			}
			msg		= ""+jm.base64decode(d.cont)+"";
			tits	= d.title;
			if(!tits)tits = d.gname;
			notifyobj.showpopup(msg,{icon:garr.face,title:tits,gid:gid,rand:'agent_'+gid+'',url:d.url,click:function(b){
				if(b.url){
					js.open(b.url,760,500);
					return true;
				}else{
					//im.openagent(b.gid);
					return false;
				}
			}});
			
			ot 		= $('#chatstotal_'+num+'');ots=ot.text();if(ots=='')ots='0';
			if(!ops)ots = parseFloat(ots)+1;
			this.addhistory(lx,gid,ots,d.cont,d.optdt,'', tits, garr.face);
		}
	
		if(lx == 'loadgroup'){
			this.loadgroup();
		}
	},
	
	//通知设置
	initnotify:function(){
		var lx=notifyobj.getaccess();
		if(lx!='ok'){
			js.msg('msg','为了可及时收到信息通知 <br>请开启提醒,<span class="zhu cursor" onclick="reim.indexsyscog()">[开启]</span>',-1);
		}
	},
	indexsyscogs:function(){
		var str = notifyobj.getnotifystr('reim.indexsyscogss()');
		return '桌面通知提醒'+str+'';
	},
	indexsyscogss:function(){
		notifyobj.opennotify(function(){
			$('#indexsyscog_msg').html(reim.indexsyscogs());
		});
	},
	getsound:function(){
		var lx = js.getoption('soundcog'),chs=false;
		if(lx=='')lx='1';
		if(lx==1)chs=true;
		return chs;
	},
	setsound:function(o1){
		var lx=(o1.checked)?'1':'2';
		js.setoption('soundcog', lx);
		notifyobj.setsound(o1.checked);
	},
	indexsyscog:function(){
		var chs= (this.getsound())?'checked':'';
		var s='<div style="height:160px;overflow:auto;padding:5px 10px">';
		s+='<div style="padding:5px 0px;" id="indexsyscog_msg">'+this.indexsyscogs()+'</div>';
		s+='<div style="padding:5px 0px;border-top:1px #eeeeee solid"><label><input '+chs+' onclick="reim.setsound(this)" type="checkbox">新信息声音提示</label></div>';
		if(nwjsgui){
			var ksj=js.getoption('kuaijj','Q');
			var strw='ABCEDFGHIJKLMNOPQRSTUVWYZ',s1,cls1='';
			s+='<div style="padding:5px 0px;border-top:1px #eeeeee solid">主窗口快捷键：Ctrl+Alt+<select onchange="nwjs.changekuai(this)">';
			for(var i=0;i<strw.length;i++){
				s1= strw.substr(i,1);
				cls1='';if(ksj==s1){cls1='selected';}
				s+='<option '+cls1+' value="'+s1+'">'+s1+'</option>';
			}
			s+='</select></div>';
			var ips = nwjs.getipmac();
			s+='<div style="padding:5px 0px;border-top:1px #eeeeee solid">我局域网IP：'+ips.ip+'</div>';
			s+='<div style="padding:5px 0px;border-top:1px #eeeeee solid">我的MAC地址：'+ips.mac+'</div>';
		}else{
			
		}
		s+='<div style="padding:5px 0px;border-top:1px #eeeeee solid">网络IP：'+this.myip+'</div>';
		s+='</div>';
		js.tanbody('syscogshow','REIM设置',240,100,{html:s});
	},
	
	//搜索联系人/会话/应用
	searchss:function(){
		clearTimeout(this.searchsstime);
		this.searchsstime=setTimeout('reim.searchssss()',500);
		if(!this.searchright)this.searchright=$.rockmenu({
			data:[],iconswh:20,
			itemsclick:function(d){
				reim.searchclick(d.type,d.id);
			}
		});
	},
	searchclick:function(ty,id){
		if(ty=='user')this.openuser(id);
		if(ty=='group')this.opengroup(id);
		if(ty=='agent')this.openagent(id);
	},
	searchssss:function(){
		var o = $('#reim_keysou'),val=strreplace(o.val());
		var d=[];
		if(val==''){
			this.searchright.hide();
			return;
		}
		val=val.toLowerCase();
		var off=o.offset(),sid,a,s1;
		for(sid in this.userarr){
			a=this.userarr[sid];
			if(a.name.indexOf(val)>-1 || a.pingyin.indexOf(val)==0 || a.deptname.indexOf(val)>-1 || a.ranking.indexOf(val)>-1){
				s1=''+a.name+'<font color=#888888>('+a.ranking+')</font>';
				d.push({name:s1,id:a.id,icons:a.face,type:'user'});
			}
		}
		for(sid in this.grouparr){
			a=this.grouparr[sid];
			if(a.name.indexOf(val)>-1){
				s1=''+a.name+'<font color=#888888>(会话)</font>';
				d.push({name:s1,id:a.id,icons:a.face,type:'group'});
			}
		}
		for(sid in this.agentarr){
			a=this.agentarr[sid];
			if(a.name.indexOf(val)>-1){
				s1=''+a.name+'<font color=#888888>(应用)</font>';
				d.push({name:s1,id:a.id,icons:a.face,type:'agent'});
			}
		}
		if(d.length==0){
			this.searchright.hide();
			return;
		}
		this.searchright.setData(d);
		this.searchright.showAt(off.left+1,off.top+40,$('#reim_headercenter').width()-2);
	},
	
	//创建会话
	creategroup:function(){
		js.prompt('创建会话','请输入会话名称：',function(lx,v){
			if(lx=='yes'){
				if(!v){js.msg('msg','没有输入会话名称');return false;}
				js.msg('wait','创建中...');
				reim.ajax(reim.apiurl('reim','createlun'),{val:jm.base64encode(v)}, function(da){
					js.msg('success','创建成功，请打开会话窗口邀请人员加入');
					reim.loadgroup();
				});
			}
		});
		return false;
	},
	
	//退出会话
	exitgroup:function(gid){
		this.loadgroup();
		//$('#history_group_'+gid+'').remove();
	}
};