/**
*	REIM即时通信主js-最新版本
*	caratename：雨中磐石(rainrock)
*	caratetime：2018-11-01 21:40:00
*	homepage:www.rockoa.com
*/

var agentarr={},userarr={},grouparr={},cnum='',windowfocus=true,jietubool=false;
//是不是xp和win7的版本
function jisxobo(){
	var llq = navigator.userAgent.toLowerCase();
	if(llq.indexOf('windows nt 5')>0 || llq.indexOf('windows nt 6.1')>0){
		return true;
	}
	return false;
}
var reim={
	chatobj:{},
	maindata:{},
	initci:0,
	timeloads:0,
	ruloadtime:5*60, //5分钟
	init:function(){
		js.ajaxwurbo   = true;
		js.xpbodysplit = 5;
		date = js.now('Y-m-d');
		nwjs.init();
		bodyunload=function(){
			nwjs.removetray();
		}
		this.resize();
		nwjs.serverdata=function(d){
			return reim.serverdata(d);
		}
		
		$(window).resize(this.resize);
		$(window).focus(function(){windowfocus=true;im.windowfocus()});
		$(window).blur(function(){windowfocus=false});
		//数秒
		setInterval('reim.timeload()', 1000);
		var fse=js.getoption('loginface');
		if(fse)get('myface').src=fse;

		nwjs.createtray(document.title+'-'+adminname, 1);
		strformat.ismobile=0; 
		//禁止后退
		try{
			history.pushState(null, null, document.URL);
			window.addEventListener('popstate', function (){
				history.pushState(null, null, document.URL);
			});
		}catch(e){}
		this.initload();
		$('#centlist').perfectScrollbar();
		
		
		uploadobj = $.rockupload({
			inputfile:'allfileinput',
			initpdbool:false,
			updir:'reimchat',
			urlparams:{noasyn:'yes'}, //不需要同步到文件平台上
			onchange:function(d){
				im.sendfileshow(d);
			},
			onprogress:function(f,per,evt){
				im.upprogresss(per);
			},
			onsuccess:function(f,str,o1){
				im.sendfileok(f,str);
			},
			onerror:function(str){
				js.msg('msg', str);
				im.senderror();
			}
		});
		strformat.upobj = uploadobj;
		
		
		$('body').keydown(function(e){
			return reim.bodykeydown(e);
		});
		
		//注册推送提醒的
		notifyobj=new notifyClass({
			title:'系统提醒',
			sound:'web/res/sound/todo.ogg',
			sounderr:'',
			soundbo:this.getsound(), //是否要声音
			showbool:false
		});
		
		this.righthistroboj = $.rockmenu({
			data:[],
			itemsclick:function(d){
				reim.rightclick(d);
			}
		});
		$('#reimcog').click(function(){
			reim.clickcog(this);
			return false;
		});
		document.ondragover=function(e){e.preventDefault();};
        document.ondrop=function(e){e.preventDefault();};
		
		//注册全局ajax的错误
		js.ajaxerror=function(msg,code){
			if(code==401){
				js.msg();
				js.msgerror('登录失效，重新登录');
				if(!nwjsgui){
					js.location('/users/login?backurl=reim');
				}else{
					js.location('/reim/login.html');
				}
			}
		}
		if(!nwjsgui){
			$('#closediv').remove();
		}
		$('#reim_keysou').keyup(function(){reim.searchss();});
		$('#reim_keysou').click(function(){reim.searchss();});
		
		if(jisxobo()){
			js.xpbodysplit = 0;
			get('mindivshow').style.margin='0px';
		}
	},
	resize:function(){
		viewheight = winHb()-10-50; //可操作高度
		var lx1 = 0;
		if(jisxobo())lx1=10;
		viewheight+=lx1;
		$('#mindivshow').css('height',''+(viewheight+50)+'px');
		$('#centlist').css('height',''+viewheight+'px');
		$('#viewzhulist').css('height',''+viewheight+'px');
		var obj = $('div[resizeh]'),o,hei;
		for(var i=0;i<obj.length;i++){
			o = $(obj[i]);
			hei=parseInt(o.attr('resizeh'));
			o.css('height',''+(viewheight-hei)+'px');
		}
		//控制最小宽高
		if(nwjsgui){
			var w1 = 900,h1 = 600;
			var wid = winWb();
			var hei = winHb();
			if(wid<w1)nwjs.win.width=w1;
			if(hei<h1)nwjs.win.height=h1;
		}
	},
	timeload:function(){
		this.timeloads++;
		//刷新
		if(this.timeloads >= this.ruloadtime){
			this.timeloads = 0;
			this.initload();
		}
		
		//if(this.timeloads==5)this.getonline();//获取在线人员id
	},
	bodykeydown:function(e){
		var code	= e.keyCode;
		if(code==27){
			if($.imgviewclose())return false;
			if(get('xpbg_bodydds')){
				js.tanclose($('#xpbg_bodydds').attr('xpbody'));
			}else{
				this.closenowtabss();
			}
			return false;
		}
	},

	winclose:function(){
		nwjs.win.hide();
	},
	winzuida:function(){
		if(!this.zdhbo){
			nwjs.win.maximize();
			this.zdhbo=true;
		}else{
			nwjs.win.unmaximize();
			this.zdhbo=false;
		}
	},
	changetabs:function(ind){
		$('div[id^="changetabs"]').css('color','#cccccc');
		$('div[id^="centshow"]').hide();
		$('#changetabs'+ind+'').css('color','#1389D3');
		$('#centshow'+ind+'').show();
		if(ind==1)this.showdept();
		if(ind==2){
			$('#maincenter').hide();
			this.showagent(true);
		}else{
			this.hideagent();
			$('#maincenter').show();
		}
	},
	getapiurl:function(m,a){
		return 'api.php?m='+m+'&a='+a+'&cfrom=reim';
	},
	//ajax访问处理
	ajax:function(url,cans, fun, lx,efun){
		if(!lx)lx='get';
		if(!fun)fun=function(){}
		if(!efun)efun=function(){}
		js.ajax(url,cans,function(ret){
			if(ret.code==200){
				fun(ret);
			}else if(ret.code==199){
				js.confirm(ret.msg, function(){
					reim.exitlogin(true);
				});
			}else{
				js.msg('msg',ret.msg);
				efun(ret);
			}
		},''+lx+',json', efun);
	},
	//初始加载数据
	initload:function(bo){
		this.initbool = true;
		this.ajax(this.getapiurl('indexreim','indexinit'),{'initci':this.initci,'gtype':'reim'}, function(ret){
			reim.initci++;
			reim.showdata(ret.data);
			if(bo)reim.reloaduser();
		});
	},
	firstpid:0,
	showdata:function(ret){
		if(!ret.userjson)return;
		this.lastloaddt		= ret.loaddt;
		this.maindata.darr 	= js.decode(ret.deptjson);
		this.maindata.uarr 	= js.decode(ret.userjson);
		this.maindata.garr  = js.decode(ret.groupjson);
		this.maindata.harr 	= js.decode(ret.historyjson);
		this.firstpid		= this.maindata.darr[0].pid;
		if(!this.showconfigarr){
			this.showconfigarr	= ret.config;
			this.websocketlink(ret.config);
		}
		
		var aarr  = js.decode(ret.agentjson);
		var cbarr = {},i;
		for(i=0;i<aarr.length;i++){
			if(!cbarr[aarr[i].types])cbarr[aarr[i].types]=[];
			cbarr[aarr[i].types].push(aarr[i]);
		}
		this.maindata.aarr=cbarr;
		aarr = this.maindata.garr;
		for(i=0;i<aarr.length;i++){
			grouparr[aarr[i].id]=aarr[i];
		}
		aarr = this.maindata.uarr;
		for(i=0;i<aarr.length;i++){
			userarr[aarr[i].id]=aarr[i];
		}
		this.showagent(false);
		this.myip 			= ret.ip;
		this.showhistory(this.maindata.harr);
		if(ret.editpass==0)this.editpass('请先修改密码后在使用','none');
	},
	//搜索联系人/会话/应用
	searchss:function(){
		clearTimeout(this.searchsstime);
		this.searchsstime=setTimeout('reim.searchssss()',500);
		if(!this.searchright)this.searchright=$.rockmenu({
			data:[],iconswh:20,width:210,
			itemsclick:function(d){
				reim.searchclick(d);
			}
		});
	},
	searchclick:function(d){
		var ty = d.type;
		if(ty=='user')this.showuserinfo(d.xuoi);
		if(ty=='group')this.openchat(d.type,d.id,d.name,d.icons);
		if(ty=='agent')this.openagenh(d.id);
	},
	searchssss:function(){
		var o = $('#reim_keysou'),val=strreplace(o.val());
		var d=[];
		if(val==''){
			this.searchright.hide();
			return;
		}
		val=val.toLowerCase();
		var off=o.offset(),sid,a,s1,arr,i,oi=1;
		arr = this.maindata.uarr;
		for(i=0;i<arr.length;i++){
			a=arr[i];
			if(a.name.indexOf(val)>-1 || a.pingyin.indexOf(val)==0 || a.deptname.indexOf(val)>-1 || a.ranking.indexOf(val)>-1){
				s1=''+a.name+'<font color=#888888>('+a.ranking+')</font>';
				d.push({name:s1,id:a.id,icons:a.face,type:'user',xuoi:i});
				oi++;
			}
			if(oi>10)break;//最多显示10人
		}
		arr = this.maindata.garr;
		for(i=0;i<arr.length;i++){
			a=arr[i];
			if(a.name.indexOf(val)>-1){
				s1=''+a.name+'<font color=#888888>(会话)</font>';
				d.push({name:s1,id:a.id,icons:a.face,type:'group'});
			}
		}
		for(sid in agenharr){
			a=agenharr[sid];
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
		this.searchright.showAt(off.left+1,off.top+25,$('#reim_headercenter').width()-2);
	},
	websocketlink:function(a){
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
		if(isempt(wsurl) || wsurl.indexOf('ws')<0){
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
				js.msg('msg','无法连接服务器1<br><span id="lianmiaoshoetime"></span><a href="javascript:;" onclick="reim.connectservers()">[重连]</a>',0);
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
				js.msg('msg','连接已经断开了<br><span id="lianmiaoshoetime"></span><a href="javascript:;" onclick="reim.connectservers()">[重连]</a>',0);
				reim.relianshotime(10);
			}
		});
	},
	connectservers:function(){
		if(this.connectbool){
			this.serverstatus(1);
			return;
		}
		var bo = this.websocketlink(this.showconfigarr);
		if(bo)js.msg('wait','连接中...');
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
	showdept:function(id){
		if($('#showdept').html()==''){
			this.reloaduser();
		}else{
		}
	},
	initnotify:function(){
		var lx=notifyobj.getaccess();
		if(lx!='ok'){
			//js.msg('msg','为了可及时收到信息通知 <br>请开启提醒,<span class="zhu cursor" onclick="reim.indexsyscogss()">[开启]</span>',-1);
		}
	},
	indexsyscogs:function(){
		var str = notifyobj.getnotifystr('reim.indexsyscogss()');
		return '桌面通知提醒'+str+'';
	},
	indexsyscogss:function(){
		notifyobj.opennotify(function(){
			js.msg('success', reim.indexsyscogs());
		});
	},
	reloaduser:function(){
		$('#showdept').html('');
		this.showuserlists(this.firstpid,0, 'showdept');
		this.showgroup();
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
	
	//别的地方登录
	otherlogins:function(){
		this.otherlogin = true;
		var msg='已在别的地方连接了';
		js.msg('success', msg, -1);
		this.serverstatus(2);
	},
	
	//收到推送消息
	receivemesb:function(d, lob){
		var lx=d.type,sendid=d.adminid;
		if(lx=='offoline'){
			this.otherlogins();
			return;
		}
	
		if(lx=='user' || lx=='group'){
			if(sendid!=adminid)this.receivechat(d);
		}
		if(lx=='agent'){
			this.receiveagenh(d);
			nwjs.jumpicon();
		}
		if(lx=='chehui'){
			$('#qipaocont_mess_'+d.messid+'').html(js.getmsg(jm.base64decode(d.cont),'green'));
			this.historyreload();
		}
	},
	showuserlists:function(pid,xu, svie){
		var o = $('#'+svie+'');
		var tx= o.text();
		if(tx){if(pid!=0){o.toggle();}return;}
		var a =this.maindata.uarr,i,len=a.length,d,dn,s='',wfj,zt,sids;
		
		for(i=0;i<len;i++){
			d=a[i];
			if(!userarr[d.id])userarr[d.id]=d;
			sids = ','+d.deptids+',';
			if(pid==d.deptid || sids.indexOf(','+pid+',')>-1){
				zt='';
				if(d.status==0)zt='&nbsp;<font style="font-size:12px" color=red>未加入</font>';
				s='<div class="lists" onclick="reim.showuserinfo('+i+')" style="padding-left:'+(xu*20+10)+'px" >';
				s+='<table cellpadding="0" border="0" width="100%"><tr>';
				s+='<td style="padding-right:5px"><div style="height:24px;overflow:hidden"><img src="'+d.face+'" style="height:24px;width:24px"></div></td>';
				s+='<td align="left" width="100%"><div class="name">'+d.name+''+zt+'</div></td>';
				s+='</tr></table>';
				s+='</div>';
				o.append(s);
			}
		}
		
		a = this.maindata.darr;
		len=a.length;
		for(i=0;i<len;i++){
			d = a[i];
			if(d.pid==pid){
				wfj = 'icon-folder-close-alt';
				s='<div class="lists" style="padding-left:'+(xu*20+10)+'px" onclick="reim.showuserlists('+d.id+','+(xu+1)+',\'showdept_'+d.id+'\')">';
				s+='	<i class="'+wfj+'"></i> '+d.name+'';
				if(d.ntotal>0)s+=' <span style="font-size:12px;color:#888888">('+d.ntotal+')</span>';
				s+='</div>';
				s+='<span id="showdept_'+d.id+'"></span>';
				o.append(s);
				if(xu==0)this.showuserlists(d.id, xu+1, 'showdept_'+d.id+'');
			}
		}
	},
	showgroup:function(){
		var a =this.maindata.garr,i,len=a.length,d,s='';
		s='<div style="padding:5px;margin-top:5px;color:#aaaaaa;border-bottom:1px #f1f1f1  solid">会话('+len+')</div>';
		for(i=0;i<len;i++){
			d = a[i];
			s+='<div onclick="reim.openchat(\'group\',\''+d.id+'\',\''+d.name+'\',\''+d.face+'\')" class="lists">';
			s+='<table cellpadding="0" border="0" width="100%"><tr>';
			s+='<td style="padding-right:5px"><div style="height:24px;overflow:hidden"><img src="'+d.face+'" style="height:24px;width:24px"></div></td>';
			s+='	<td align="left" width="100%"><div class="name">'+d.name+'';
			if(d.deptid=='1')s+=' <span class="reimlabel">全员</span>';
			if(d.deptid>'1')s+=' <span class="reimlabel1">部门</span>';
			s+='	</div></td>';
			s+='</tr></table>';
			s+='</div>';
		}
		$('#showgroup').html(s)
	},
	historyreload:function(){
		this.ajax(this.getapiurl('indexreim','gethistory'),{id:0},function(
		ret){
			var data = ret.data;
			reim.showhistory(data);
		});
	},
	showhistory:function(a){
		var i,len=a.length;
		$('#historylist').html('');
		$('#historylist_tems').show();
		for(i=0;i<len;i++){
			this.showhistorys(a[i]);
		}
		if(i>0)$('#historylist_tems').hide();
	},
	showhistorydata:{},
	showhistorys:function(d,pad, lex){
		var s,ty,o=$('#historylist'),d1,st,attr;
		var num = ''+d.type+'_'+d.receid+'';
		this.showhistorydata[num]=d;
		$('#history_'+num+'').remove();
		st	= d.stotal;if(st=='0')st='';
		var ops = d.optdt.substr(11,5);
		if(d.optdt.indexOf(date)!=0)ops=d.optdt.substr(5,5);
		ty	= d.type;
		var cls = lex ? ' active' : '';
		var na  = d.name;
		if(d.title)na = d.title;
		if(d.type=='group'){
			var d2 = grouparr[d.receid];
			if(d2)d.deptid = d2.deptid;
		}
		var s1 = '';
		if(d.deptid=='1')s1=' <span class="reimlabel">全员</span>';
		if(d.deptid>'1')s1=' <span class="reimlabel1">部门</span>';
		s	= '<div class="lists'+cls+'" rtype="hist" oncontextmenu="reim.historyright(this,event,\''+num+'\')" tsaid="'+d.receid+'" tsaype="'+d.type+'"  temp="hist" id="history_'+num+'" onclick="reim.openchat(\''+ty+'\',\''+d.receid+'\',\''+d.name+'\',\''+d.face+'\')">';
		s+='<table cellpadding="0" border="0" width="100%"><tr>';
		s+='<td style="padding-right:8px"><div style="height:30px;overflow:hidden"><img src="'+d.face+'"></div></td>';
		s+='<td align="left" width="100%"><div title="'+na+'" class="name">'+na+''+s1+'</div><div class="huicont">'+jm.base64decode(d.cont)+'</div></td>';
		s+='<td align="right" nowrap><span id="chat_stotal_'+num+'" class="badge red">'+st+'</span><br><span style="color:#cccccc;font-size:10px">'+ops+'</span></td>';
		s+='</tr></table>';
		s+='</div>';
		if(!pad){o.append(s);}else{o.prepend(s)}
		$('#historylist_tems').hide();
		this.showbadge('chat');
	},
	historyright:function(o1,e,num){
		var rt = $(o1).attr('rtype');
		if(isempt(rt))return false;
		this.rightdivobj = o1;
		var da=[{name:'打开',lx:0}],d=this.showhistorydata[num];
		if(d && d.stotal>0)da.push({name:'标识已读',lx:1});
		if(rt.indexOf('hist')>-1){
			da.push({name:'删除此记录',lx:2});
		}
		this.righthistroboj.setData(da);
		this.righthistroboj.showAt(e.clientX-3,e.clientY-3);
		return false;
	},
	rightclick:function(d){
		var o1 = $(this.rightdivobj),lx=d.lx;
		var tsaid = o1.attr('tsaid'),
			tsayp = o1.attr('tsaype');
		if(lx==0){
			this.rightdivobj.onclick();
		}			
		if(lx==2){
			o1.remove();
			var tst=$('#historylist').text();if(tst=='')$('#historylist_tems').show();
			js.ajax(this.getapiurl('reim','delhistory'),{type:tsayp,gid:tsaid},false,'get');
			this.showbadge('chat');
		}
		if(lx==1){
			var num = ''+tsayp+'_'+tsaid+'';
			$('#chat_stotal_'+num+'').html('');
			var d=this.showhistorydata[num];
			if(d)d.stotal='0';
			this.showbadge('chat');
			this.biaoyd('agent',tsaid);
		}
	},
	openmyinfo:function(){
		this.showuserinfo(0,userarr[adminid]);
	},
	showuserinfo:function(oi, d1){
		var d = this.maindata.uarr[oi];
		if(d1)d=d1;
		var num = 'userinfo_'+d.id+'';
		var s = '<div align="center"><div align="left" style="width:300px;margin-top:50px">';
		s+='	<div style="padding-left:70px"><img id="myfacess" onclick="$(this).imgview()" src="'+d.face+'" height="100" width="100" style="border-radius:50%;border:1px #eeeeee solid"></div>';
		if(d.id==adminid)s+='<div style="padding-left:90px"><a href="javascript:;" id="fupbgonet" onclick="reim.upfaceobj.click()" style="font-size:12px">修改头像</a></div>';

		s+='	<div style="line-height:30px;padding:10px;padding-left:20px;"><font color=#888888>姓名：</font>'+d.name+'<br><font color=#888888>部门：</font>'+d.deptallname+'<br><font color=#888888>职位：</font>'+d.ranking+'<br><font color=#888888>性别：</font>'+d.sex+'<br><font color=#888888>电话：</font>'+d.tel+'<br><font color=#888888>手机：</font>'+d.mobile+'<br><font color=#888888>邮箱：</font>'+d.email+'</div>';
		s+='	<div style="padding-top:10px;padding-left:50px"><input type="button" value="发消息" onclick="reim.openchat(\'user\',\''+d.id+'\',\''+d.name+'\',\''+d.face+'\')" class="btn">&nbsp; &nbsp; <input onclick="reim.closetabs(\''+num+'\')" type="button" value="关闭" class="btn btn-danger"></div>';
		s+='</div></div>';
		this.addtabs(num,s);
		if(d.id==adminid){
			if(!this.upfaceobj)this.upfaceobj=$.rockupload({
				inputfile:'upfacess',
				uptype:'image',
				urlparams:{noasyn:'yes'}, //不需要同步到文件平台上
				onsuccess:function(f,str){
					var a=js.decode(str);
					if(!a.id)return;
					reim.saveface(a.id);
				},
				onchange:function(){
					$('#fupbgonet').html('上传中...');
				}
			});
		}
	},
	saveface:function(fid){
		this.ajax(this.getapiurl('reim','changeface'),{id:fid},function(ret){
			var face = ret.data;
			get('myface').src=face;
			get('myfacess').src=face;
			adminface=face;
			js.setoption('loginface', face);
			js.setoption('adminface', face);
			$('#fupbgonet').html('修改成功');
		});
	},
	openchat:function(type,reid,na,fac){
		var num = ''+type+'_'+reid+'';
		$('#chat_stotal_'+num+'').html('');
		this.showbadge('chat');
		if(type=='agent'){
			var d = this.showhistorydata[num];
			var url='';
			if(d && d.stotal>0 && !isempt(d.xgurl)){
				d.stotal='0';
				var xga = d.xgurl.split('|');
				if(xga[1]>0)url='task.php?a=p&num='+xga[0]+'&mid='+xga[1]+'';
			}
			if(url==''){
				this.openagenh(reid);
			}else{
				this.biaoyd('agent',reid);
				js.open(url,760,500);
			}
			return;
		}
		var s = '<div style="background:#f5f9ff">';
		s+='<div id="viewtitle_'+num+'" style="height:50px;overflow:hidden;border-bottom:#dddddd solid 1px;">';
		s+='</div>';
		var hei = 206;
		s+='<div resizeh="'+hei+'" id="viewcontent_'+num+'" style="height:'+(viewheight-hei)+'px;overflow:hidden;position:relative;"><div style="margin-top:50px" align="center"><img src="images/mloading.gif"></div></div>';
		
		s+='<div class="toolsliao" id="toolsliao_'+num+'">';
		s+='	<span title="表情" tools="emts" class="cursor"><i class="icon-heart"></i></span>';
		s+='	<span title="文件/图片" tools="file" class="cursor"><i class="icon-folder-close"></i></span>';
		if(nwjsgui){
			s+='	<span title="粘贴图片" tools="paste" class="cursor"><i class="icon-paste"></i></span>';
			s+='	<span title="截屏" tools="crop" class="cursor"><i class="icon-cut"></i></span>';
		}	
		s+='</div>';
		
		s+='<div style="height:80px;overflow:hidden;"><div style="height:70px;margin:5px"><textarea onpaste="im.readclip(\''+num+'\',event)"  class="content" style="background:none;"  id="input_content_'+num+'"></textarea></div></div>';
		
		s+='<div style="height:40px;overflow:hidden;"><div align="right" style="padding:9px"><input id="chatclosebtn_'+num+'" class="webbtn" style="background:none;color:#aaaaaa" type="button" value="关闭(C)">&nbsp;<input class="webbtn" style="background:none;color:#1389D3" id="chatsendbtn_'+num+'" type="button" value="发送(S)"></div></div>';
		
		s+='</div>';
		var bo = this.addtabs(num,s);
		get('input_content_'+num+'').focus();
		if(!bo){
			this.chatobj[num]=new chatcreate({
				'type' : type,
				'gid'  : reid,
				'num'  : num,
				'name' : na,
				'face' : fac
			});
		}
		this.chatobj[num].onshow();
	},
	biaoyd:function(type,gid){
		js.ajax(this.getapiurl('reim','yiduall'),{type:type,gid:gid},false,'get');
	},

	receiveagenh:function(d){
		var gid = d.gid;
		var num = d.type+'_'+gid,stotal=0,msg;
		var so = $('#chat_stotal_'+num+'').html();
		if(!so)so=0;
		stotal = parseInt(so)+1;
		
		this.showhistorys({
			'cont' : d.cont,
			'name' : d.gname,
			'title' : d.title,
			'face' : d.gface,
			'optdt' : d.optdt,
			'type'	: d.type,
			'receid' : gid,
			'stotal' : stotal
		}, true);
		msg = jm.base64decode(d.cont);
		msg = msg.replace(/\\n/gi,' ');
		var sopenfun=function(b){
			js.alertclose();
			notifyobj.close();//关闭右下角的提示
			if(b.url){
				js.open(b.url,760,500);
				return true;
			}else{
				reim.openagenh(b.gid, b.url);
			}
			return true;  //不激活主窗口
		}
		js.alertclose();
		js.confirm(msg,function(jg){if(jg=='yes'){sopenfun(d)}},'','<img src="'+d.gface+'" align="absmiddle" width="20" height="20">&nbsp;'+d.title);
		if(this.getzhuom())notifyobj.showpopup(msg,{icon:d.gface,url:d.url,gid:gid,title:d.title,rand:num,click:function(b){
			return sopenfun(b);
		}});
	},
	receivechat:function(d){
		var gid = d.gid,lx = d.type,stotal=0,num,msg,name=d.gname,face=d.face,s1='';
		if(lx=='user'){
			gid = d.adminid;
			name= d.sendname;
		}
		if(lx=='group'){
			face = d.gface;
			s1   = jm.base64encode(''+d.sendname+':');
			if(isempt(face))face = 'images/group.png';
		}
		num = d.type+'_'+gid;
		var showtx = true;
		if(this.isopentabs(num)){
			this.chatobj[num].receivedata(d);
			if(this.nowtabs!=num){
				this.chatobj[num].newbool=true;
			}
		}
		if(windowfocus && this.nowtabs==num)showtx=false;
		//未读数
		if(this.nowtabs!=num){
			var so = $('#chat_stotal_'+num+'').html();
			if(!so)so=0;
			stotal = parseInt(so)+1;
		}
		this.showhistorys({
			'cont' : s1+d.cont,
			'name' : name,
			'face' : face,
			'optdt' : d.optdt,
			'type'	: d.type,
			'receid' : gid,
			'stotal' : stotal
		}, true, this.nowtabs==num);
		var nr = jm.base64decode(d.cont);
		if(showtx || nr.indexOf('@'+adminname+'')>-1){
			var title = '会话消息';
			msg  = '人员['+d.sendname+']，发来一条信息';
			if(lx == 'group'){
				msg += '，来自['+name+']';
			}
			if(this.getzhuom())notifyobj.showpopup(msg,{icon:face,type:lx,gid:gid,name:name,title:title,rand:num,click:function(b){
				reim.openchat(b.type, b.gid,b.name,b.icon);
			}});
			nwjs.jumpicon();
		}
	},
	addtabs:function(num, s){
		var ids = 'tabs_'+num+'',bo;
		if(!get(ids)){
			var s = '<div tabs="'+num+'" id="'+ids+'">'+s+'</div>';
			$('#viewzhulist').append(s);
			bo = false;
		}else{
			bo = true;
		}
		this.showtabs(num);
		return bo;
	},
	closetabs:function(num){
		var ids = 'tabs_'+num+'';
		$('#'+ids+'').remove();
		var ood = $('#viewzhulist div[tabs]:last');
		var snu = ood.attr('tabs');
		this.showtabs(snu);
	},
	closenowtabs:function(){
		if(this.nowtabs)this.closetabs(this.nowtabs);
	},
	closenowtabss:function(){
		var nun = this.nowtabs;
		if(!nun)return;
		if(nun.indexOf('user_')==0 || nun.indexOf('group_')==0 || nun.indexOf('userinfo_')==0)this.closenowtabs();
	},
	isopentabs:function(num){
		return get('tabs_'+num+'');
	},
	showtabs:function(num){
		$('div[tabs]').hide();
		var ids = 'tabs_'+num+'';
		$('#'+ids+'').show();
		$('div[temp]').removeClass('active');
		$('#history_'+num+'').addClass('active');
		this.nowtabs = num;
	},
	showagent:function(sbo){
		var agedt = this.maindata.aarr,s='',ty,a,len,d,d1,sno,so=0,sodd=1;
		s+='<div id="agenhview" resizeh="0" style="height:'+viewheight+'px;overflow:hidden;position:relative; background:#fcfdff" align="center"><div style="width:80%;padding:20px" align="left">';
		agenharr={};
		for(ty in agedt){
			a 	= agedt[ty];
			len	= a.length;
			s+='<div style="color:#aaaaaa;padding-left:20px;margin-bottom:10px;padding:5px;border-bottom:'+sodd+'px solid #eeeeee">&nbsp;&nbsp;'+ty+'</div>';
			s+='<div class="agenhclsdiv">';
			for(i=0;i<len;i++){
				d1 = a[i];
				if(!agenharr[d1.id])agenharr[d1.id]=d1;
				d   = agenharr[d1.id];
				sno = d.stotal;
				so += sno;
				if(sno==0)sno='';
				s+='<div onclick="reim.openagenh(\''+d.id+'\')" class="agenhcls"><div style="padding-top:5px"><img src="'+d.face+'"></div><div>'+d.name+'</div>';
				s+='<span id="agenh_stotal_'+d.id+'" class="badge">'+sno+'</span>';
				s+='</div>';
			}
			s+='</div>';
			sodd=1;
		}
		s+='</div>';
		if(!sbo){
			if(so==0)so='';
			$('#agenh_stotal').html(so);
			return;
		}
		var bo = this.addtabs('agenh',s);
		if(!bo)$('#agenhview').perfectScrollbar();
		this.showbadge('agenh');
	},
	hideagent:function(){
		if(get('tabs_agenh'))
			this.closetabs('agenh');
	},
	openagenh:function(id, url){
		var d = agenharr[id];
		if(!d){
			js.msg('msg','应用不存在，请刷新');
			return;
		}
		d.stotal=0;
		var num = 'agenh_'+d.id+'';
		$('#agenh_stotal_'+d.id+'').html('');
	
		this.showagent(false); 
		$('#chat_stotal_'+num+'').html('');

		this.showbadge('chat');
		this.biaoyd('agent',d.id);
		
		var w = 1100,h=600,url = d.urlpc;
		if(isempt(url)){
			url = d.urlm;
			if(isempt(url)){
				url = '?d=we&m=ying&num='+d.num+''; //先默认用移动端
			}
			w = 350;
		}
		var jg = (url.indexOf('?')>-1)?'&':'?';
		url+=''+jg+'openfrom=reim';
		//考勤打卡
		if(d.num=='kqdaka'){
			this.opendaka();return;
		}
		if(url.substr(0,4)=='http' && url.indexOf(HOST)<0 && nwjsgui){
			nwjs.openurl(url);
		}else{
			js.open(url, w,h,'agent'+d.num+'');
		}
	},
	//考勤打卡
	opendaka:function(bo){
		var url = '?d=reim&m=ying&a=daka',w = 550;h=300;
		js.open(url, w,h,'agentkqdaka',{},{icon:'images/adddk.png'});
	},
	showbadge:function(lx){
		var obj = $('span[id^="'+lx+'_stotal_"]'),so=0,s1,o,i;
		for(i=0;i<obj.length;i++){
			o = $(obj[i]);
			s1= o.html();
			if(!s1)s1='0';
			so+=parseInt(s1);
		}
		if(so==0)so='';
		$('#'+lx+'_stotal').html(so);
		var zoi = 0;
		so = $('#agenh_stotal').html();
		if(!so)so = 0;
		zoi+=parseInt(so);
		so = $('#chat_stotal').html();
		if(!so)so = 0;
		zoi+=parseInt(so);
		nwjs.changeicon(zoi);
	},
	clickcog:function(o1){
		if(!this.cogmenu)this.cogmenu =$.rockmenu({
			data:[],
			width:120,
			itemsclick:function(d){
				reim.clickcogclick(d);
			}
		});
		var d = [{'name':'消息记录',lx:'jl'},{'name':'刷新',lx:'sx'},{'name':'创建会话',lx:'create'},{'name':'修改密码',lx:'pass'}];
		if(companymode)d.push({'name':'切换单位',lx:'qhqy'});
		d.push({'name':'设置',lx:'cog'});
		d.push({'name':'退出',lx:'exit'});
		this.cogmenu.setData(d);
		var off = $(o1).offset();
		this.cogmenu.showAt(40,off.top-d.length*36);
	},
	openrecord:function(){
		var url = '?homeurl=cmVpbSxyZWNvcmQsYXR5cGU9bXk:&homename=5oiR55qE5Lya6K!d6K6w5b2V&menuid=MjI3';
		js.open(url,1000,550,'chatrecord');
	},
	clickcogclick:function(d){
		var lx=d.lx;
		if(lx=='sx'){
			js.loading('刷新中...');
			location.reload();
		}
		if(lx=='exit'){
			this.exitlogin();
		}
		if(lx=='cog'){
			this.cogshow();
		}
		if(lx=='jl'){
			this.openrecord();
		}
		if(lx=='create'){
			this.creategroup();
		}
		if(lx=='pass'){
			this.editpass();
		}
		if(lx=='qhqy'){
			this.changecom();
		}
	},
	//创建会话
	creategroup:function(){
		js.prompt('创建会话','请输入会话名称：',function(lx,v){
			if(lx=='yes'){
				if(!v){js.msg('msg','没有输入会话名称');return false;}
				js.msg('wait','创建中...');
				reim.ajax(reim.getapiurl('reim','createlun'),{val:jm.base64encode(v)}, function(da){
					js.msg('success','创建成功，请打开会话窗口邀请人员加入');
					reim.changetabs(1);
					reim.initload(true);
				});
			}
		});
		return false;
	},
	editpass:function(bt,cse){
		if(!bt)bt='修改密码';
		if(!cse)cse='';
		js.tanbody('winiframe',bt,350,300,{
			html:'<div style="height:250px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
			bbar:'none',
			closed:cse
		});
		openinputiframe.location.href='?m=index&d=we&a=editpass&hideheader=true&ofrom=reim';
	},
	changecom:function(){
		js.tanbody('winiframe','切换单位',350,300,{
			html:'<div style="height:250px;overflow:hidden"><iframe src="" name="openinputiframe" width="100%" height="100%" frameborder="0"></iframe></div>',
			bbar:'none'
		});
		openinputiframe.location.href='?m=index&d=we&a=company&hideheader=true&ofrom=reim';
	},
	changecomok:function(){
		js.tanclose('winiframe');
		js.msgok('切换成功');
		location.reload();
	},
	exitlogin:function(bo){
		if(!bo){
			js.confirm('确定要退出系统吗？',function(jg){
				if(jg=='yes')reim.exitlogin(true);
			});
			return;
		}
		if(nwjsgui){
			js.loading('退出中...');
			js.ajax(this.getapiurl('login','loginexit'),{},function(ret){
				js.setoption('autologin', '0');
				js.location('?d=reim&a=login&a=xin');
			});
		}else{
			window.close();
		}
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
	getzhuom:function(){
		var lx = js.getoption('zhuomcog'),chs=false;
		if(lx=='')lx='1';
		if(lx==1)chs=true;
		return chs;
	},
	setzhuom:function(o1){
		var lx=(o1.checked)?'1':'2';
		js.setoption('zhuomcog', lx);
	},
	cogshow:function(){
		var chs= (this.getsound())?'checked':'';
		var ch1= (this.getzhuom())?'checked':'';
		var num = 'userinfo_cogshow';
		var s = '<div align="center"><div align="left" style="width:300px;margin-top:50px">';
		s+='	<div style="line-height:30px"><b>设置</b></div>';
		s+='	<div style="padding:10px 0px;border-top:1px #eeeeee solid"><label><input '+ch1+' onclick="reim.setzhuom(this)" type="checkbox">新信息桌面提醒</label></div>';
		s+='	<div style="padding:10px 0px;border-top:1px #eeeeee solid"><label><input '+chs+' onclick="reim.setsound(this)" type="checkbox">新信息声音提示</label></div>';
		
		
		if(nwjsgui){
			
			var ips = nwjs.getipmac();
			s+='<div style="padding:10px 0px;border-top:1px #eeeeee solid">我局域网IP：'+ips.ip+'</div>';
			s+='<div style="padding:10px 0px;border-top:1px #eeeeee solid">我的MAC地址：'+ips.mac+'</div>';
		}
		
		s+='	<div style="padding:10px 0px;border-top:1px #eeeeee solid">网络IP：'+this.myip+'</div>';
		s+='	<div style="padding-top:10px;"><input onclick="reim.closetabs(\''+num+'\')" type="button" value="关闭" class="btn btn-danger"></div>';
		s+='</div></div>';
		this.addtabs(num,s);
	},
	//内部服务处理
	serverdata:function(a){
		var lx = a.atype;
		if(lx=='focus')nwjs.winshow();
		if(lx=='crop')this.cropScreen(true);
		if(lx=='notify')this.shownotify(a);
		if(lx=='openchat')this.openchat(a.id,a.type);
		if(lx=='getlogin')return {uid:adminid,uname:adminname,face:adminface};
		if(lx=='getipmac')return nwjs.getipmac();
		if(lx=='office')nwjs.editoffice(a.paramsstr);
		if(lx=='upfile')return nwjs.filetobase64(a.path);
		if(lx=='gpath')return nwjs.getpath();
	}
};

function chatcreate(cans){
	for(var i in cans)this[i]=cans[i];
	strformat.emotspath='web/';
	var me = this;
	this._init = function(){
		this.minid 	  = 999999999;
		this.showobj  = $('#viewcontent_'+this.num+'');
		this.inputobj = $('#input_content_'+this.num+'');
		this.sendbtn  = $('#chatsendbtn_'+this.num+'');
		this.listdata = {};
		this.loadci   = 0;
		this.objstr	  = 'reim.chatobj[\''+this.num+'\']';
		this.sendbtn.click(function(){
			me.sendcont();
		});
		$('#chatclosebtn_'+this.num+'').click(function(){
			me.closechat();
		});
		this.inputobj.keydown(function(e){
			return me.onkeydown(e);
		});
		$('#toolsliao_'+this.num+'').find('span').click(function(e){
			me.clicktools(this);
			return false;
		});
		this.showtitle();
		this.loaddata();
		get('tabs_'+this.num+'').addEventListener('drop', function(e) {
			var files = e.dataTransfer;
			me.filedrop(files);
		}, false);
	};
	this.showtitle=function(){
		var o = $('#viewtitle_'+this.num+''),s='';
		var od = this.receinfo;
		if(!od)od={deptid:'-1'};
		s+='<table width="100%"><tr>';
		s+='<td width="30" align="center"><div style="padding:0px 10px;padding-right:8px;height:30px;overflow:hidden"><img style="border-radius:0px" src="'+this.face+'" height="30" width="30"></div></td>';
		s+='<td height="50" width="100%">';
		s+='	<div><b style="font-size:16px;">'+this.name+'</b>';
		if(this.type=='group' && this.usershu)s+='('+this.usershu+')';
		if(this.type=='group'){
			if(od.deptid=='1')s+=' <span class="reimlabel">全员</span>';
			if(od.deptid>'1')s+=' <span class="reimlabel1">部门</span>';
		}
		if(od.ranking)s+=' <span style="font-size:12px;color:#aaaaaa">('+od.ranking+')</span>';
		s+='	</div>';
		if(od.unitname)s+='<div style="font-size:12px;color:#aaaaaa">'+od.unitname+'</div>';
		s+='</td>';
		if(this.type=='group'){
			if(!od.deptid || od.deptid=='0'){
				s+='<td width="30" title="邀请" id="yaoqingchat_'+this.num+'" class="chattitbtn" nowrap><i class="icon-plus"></i></td>';
				s+='<td width="30" title="退出会话" id="tuichuchat_'+this.num+'" class="chattitbtn" nowrap><i class="icon-signout"></i></td>';
			}
			s+='<td width="30" title="会话里的人员" id="tuiuserlist_'+this.num+'" class="chattitbtn" nowrap><i class="icon-group"></i></td>';
		}
		s+='</tr></table>';
		o.html(s);
		$('#yaoqingchat_'+this.num+'').click(function(){
			me.yaoqing();
		});
		$('#tuichuchat_'+this.num+'').click(function(){
			me.exitgroup();
		});
		$('#tuiuserlist_'+this.num+'').click(function(){
			me.showhuilist();
		});
	};
	this.loaddata=function(o1, iref){
		if(this.boolload)return;
		var iref = (!iref)?false:true;
		var minid= 0;
		if(iref)minid=this.minid;
		if(o1)$(o1).html('<img src="images/loadings.gif" height="14" width="15" align="absmiddle"> 加载中...');
		this.boolload 	= true;
		this.isshangla 	= false;
		reim.ajax(reim.getapiurl('reim','getrecord'),{type:this.type,gid:this.gid,minid:minid,loadci:this.loadci},function(ret){
			if(o1)$(o1).html('');
			var da = ret.data;
			if(me.loadci==0){
				me.showobj.html('');
				me.sendinfo = da.sendinfo;
				me.receinfo	= da.receinfor;
				me.usershu	= me.receinfo.utotal;
				me.showtitle();
				me.showobj.perfectScrollbar();
			}
			me.loadci++;
			me.boolload = false;
			me.loaddatashow(da, iref);
		});
	};
	this.loaddatashow=function(ret,isbf, isls){
		var a 		= ret.rows;
		this.lastdt = ret.nowdt;
		var i,len 	= a.length,cont,lex,nas,fase,nr,d,na=[],rnd,sid;
		$('#loadmored_'+this.num+'').remove();
		if(isbf){
			if(len>0)this.showobj.prepend('<div class="showblanks">---------↑以上是新加载---------</div>');
			na = a;
		}else{
			for(i= len-1; i>=0; i--)na.push(a[i]);
		}
		for(i= 0; i<len; i++){
			d   = na[i];
			sid = parseFloat(d.id);
			lex = 'right';
			nas = '我';
			fase= this.sendinfo.face;
			if(d.sendid!=this.sendinfo.id){
				lex='left';
				nas= d.sendname;
				fase= d.face;
			}
			nr  = this.contshozt(d.filers);
			if(nr=='')nr= jm.base64decode(d.cont);
			rnd = 'mess_'+sid+'';
			if(get('qipaocont_'+rnd+''))continue;
			cont= strformat.showqp(lex,nas,d.optdt,nr ,'', fase, rnd);
			if(!isbf){
				this.addcont(cont, isbf);
			}else{
				this.showobj.prepend(cont);
			}
			this.listdata[rnd]=d;
			$('#qipaocont_'+rnd+'').contextmenu(function(e){
				me.contright(this,e);
				return false;
			});
			if(sid<this.minid)this.minid=sid;
		}
		if(len>0 && !isls){
			var s = '<div class="showblanks" >';
			if(ret.wdtotal==0){
				s+='---------↑以上是历史记录---------';
				if(len>=5){
					this.showobj.prepend('<div id="loadmored_'+this.num+'" class="showblanks" ><a href="javascript:;" onclick="'+this.objstr+'.loadmoreda(this)"><i class="icon-time"></i> 查看更多消息</a></div>');
					this.isshangla = true;
				}
			}else{
				s+='---↑以上是历史,还有未读信息'+ret.wdtotal+'条,<a href="javascript:;" onclick="'+this.objstr+'.loaddata(this)">点击加载</a>---';
			}
			s+='</div>';
			if(!isbf)this.addcont(s);
			if(isbf)this._addclickf();
		}
	};
	
	//邀请
	this.yaoqing=function(){
		if(this.type!='group')return;
		js.changeuser(false,'usercheck','邀请人到会话中', {
			onselect:function(sna,sid){
				if(!sid)return;
				me.yaoqings(sid);
			}
		});
	};
	this.yaoqings=function(sid){
		js.msg('wait','邀请中...');
		reim.ajax(reim.getapiurl('reim','yaoqinguid'),{val:sid,gid:this.gid},function(da){
			js.msg();
			if(da.success){
				js.msgok('邀请成功');
				me.userlistarr=false;
				me.getreceinfor();
			}else{
				js.msg('msg',da);
			}
		});
	};
	this.getreceinfor=function(){
		reim.ajax(reim.getapiurl('reim','getreceinfor'),{type:this.type,gid:this.gid},function(ret){
			me.receinfo	= ret.data.receinfor;
			me.usershu	= me.receinfo.utotal;
			me.showtitle();
		});
	};
	this.exitgroup=function(){
		if(this.type!='group')return;
		js.confirm('确定要此退出会话吗？',function(lx){
			if(lx=='yes'){
				me.exitgroups();
			}
		});
	};
	this.showhuilist=function(){
		var s = '<div id="showuserlist" style="height:250px;overflow:auto;padding:5px 10px"><div align="center" style="padding:10px;"><img src="images/mloading.gif" align="absmiddle">&nbsp;加载人员...</div></div>';
		js.tanbody('syscogshow','会话上人员('+this.usershu+')',420,100,{html:s});
		if(!this.userlistarr){
			reim.ajax(reim.getapiurl('reim','getgroupuser'),{type:this.type,gid:this.gid},function(ret){
				me.showusershow(ret.data.uarr);
			},'get');
		}else{
			this.showusershow(this.userlistarr);
		}
	};
	this.showusershow=function(a){
		this.userlistarr = a;
		var i,len=a.length,s='',oi=0;
		s+='<table width="100%"><tr>';
		for(i=0;i<len;i++){
			oi++;
			s+='<td width="20%"><div style="padding:5px" align="center"><div><img style="height:34px;width:34px;border-radius:50%" onclick="$.imgview({url:this.src})" src="'+a[i].face+'"></div><div style="color:#888888">'+a[i].name+'</div></div></td>';
			if(oi%5==0)s+='</tr><tr>';
		}
		s+='</tr></table>';
		$('#showuserlist').html(s);
	};
	this.exitgroups=function(){
		js.msg('wait','退出中...');
		reim.ajax(reim.getapiurl('reim','exitgroup'),{gid:this.gid}, function(da){
			js.msgok('成功退出此会话，无法在此会话发消息了');
		});
	};
	this.contshozt=function(d){
		return strformat.contshozt(d,'web/');
	};
	
	this.addcont=function(cont, isbf){
		var o	= this.showobj;
		if(cont){if(isbf){o.prepend(cont);}else{o.append(cont);}}
		clearTimeout(this.scrolltime);
		this.scrolltime	= setTimeout(function(){
			me.scrollboot();
			me._addclickf();
		}, 50);
	};
	//滚动条到最下面
	this.scrollboot=function(){
		this.showobj.animate({scrollTop:get('viewcontent_'+this.num+'').scrollHeight},100);
	};
	this._addclickf=function(){
		var o = this.showobj.find('img[fid]');
		o.unbind('click');
		o.click(function(){
			me.clickimg(this);
		});
	};
	
	this.clickimg=function(o1){
		var o=$(o1);
		var fid=o.attr('fid');
		var src = o1.src.replace('_s.','.');
		$.imgview({url:src,ismobile:false});
	};
	
	this.loadmoreda=function(o1){
		this.loaddata(o1, true);
	};
	
	this.sendcont=function(ssnr){
		if(this.sendbool)return;
		js.msg('none');
		var o	= this.inputobj;
		var nr	= strformat.sendinstr(o.val());
		nr		= nr.replace(/</gi,'&lt;').replace(/>/gi,'&gt;').replace(/\n/gi,'<br>');
		if(ssnr)nr=ssnr;
		if(isempt(nr))return false;
		var conss = jm.base64encode(nr);
		if(conss.length>3998){
			js.msg('msg','发送内容太多了');
			return;
		}
		var nuid= js.now('time'),optdt = js.serverdt();
		if(optdt==this.nowoptdt){
			js.msg('msg','消息发太快了');
			return;
		}
		this.nowoptdt = optdt;
		var cont= strformat.showqp('right','我',optdt, nr, nuid, this.sendinfo.face, nuid);
		this.addcont(cont);
		o.val('').focus();
		this.sendconts(conss, nuid, optdt, 0);
		return false;
	};
	//收到推送消息来了
	this.receivedata=function(d){
		var minid=this.minid;
		reim.ajax(reim.getapiurl('reim','getrecord'),{type:this.type,gid:this.gid,minid:0,lastdt:this.lastdt,loadci:this.loadci},function(ret){
			me.loaddatashow(ret.data, false, true);
		});
	};
	
	this.onshow=function(){
		if(this.newbool){
			this.scrollboot();
		}
		this.newbool = false;
	};
	
	this.onkeydown=function(e){
		var code = e.keyCode;
		if(code==13 && !e.ctrlKey){
			this.sendcont();
			return false;
		}
		if(e.altKey && code==83){
			this.sendcont();
			return false;
		}
		if(e.altKey && code==67){
			this.closechat();
			return false;
		}
		if(e.ctrlKey && code==13){
			this.addinput('\n');
			return false;
		}
		return true;
	};
	this.sendconts=function(conss, nuid, optdt, fid){
		this.sendbool = true;
		var d 	 = {cont:conss,gid:this.gid,type:this.type,nuid:nuid,optdt:optdt,fileid:fid};
		reim.ajax(reim.getapiurl('reim','sendinfor'),d,function(ret){
			me.sendsuccess(ret.data,nuid);
		},'post',function(){
			me.senderror(nuid);
		});
		var s1='';
		if(this.type=='group')s1=jm.base64encode(''+adminname+':');
		//显示到会话里
		reim.showhistorys({
			'cont' : s1+d.cont,
			'name' : this.receinfo.name,
			'face' : this.receinfo.face,
			'optdt' : d.optdt,
			'type'	: this.type,
			'receid' : this.gid,
			'stotal' : 0
		}, true, true);
	};
	this.senderror=function(nuid){
		get(nuid).src='images/error.png';
		get(nuid).title='发送失败';
		this.sendbool=false;
	};
	this.sendsuccess=function(d,nuid){
		this.sendbool = false;
		if(!d.id){
			this.senderror(nuid);
			return;
		}
		$('#'+d.nuid+'').remove();
		var bo = false;
		d.messid=d.id;
		d.face  = this.sendinfo.face;
		if(this.type=='group')d.gface=this.receinfo.face;
		this.listdata[nuid]=d;
		//添加右键事件
		$('#qipaocont_'+nuid+'').contextmenu(function(e){
			me.contright(this,e);
			return false;
		});
		reim.serversend(d);
	};
	this.addinput=function(s){
		var val = this.inputobj.val()+s;
		this.inputobj.val(val).focus();
	};
	
	this.closechat=function(){
		if(this.sendbool)return;
		reim.chatobj[this.num]=false;
		reim.closetabs(this.num);
	};
	this.clicktools=function(o1){
		var o    = $(o1);
		var lx = o.attr('tools');
		if(lx=='emts')this.getemts(o);
		if(lx=='file')this.sendfile(o);
		if(lx=='paste')this.pasteimg();
		if(lx=='crop')this.cropScreen();
	};
	this.getemts=function(o){
		if(!get('aemtsdiv')){
			var s = '<div id="aemtsdiv" style="width:400px;height:200px;overflow-y:auto;border:1px #cccccc solid;background:white;box-shadow:0px 0px 5px rgba(0,0,0,0.3);left:3px;top:5px;position:absolute;display:none;z-index:6">';
			s+='<div style="padding:5px">';
			s+=this.getemtsbq('qq',0, 104, 11, 24);
			s+='</div>';
			s+='</div>';
			$('body').append(s);
			js.addbody('emts','hide','aemtsdiv');
		}
		var o1  = $('#aemtsdiv');
		o1.toggle();
		var off = o.offset();
		o1.css({'top':''+(off.top-205)+'px','left':''+(off.left)+'px'});
	};
	
	this.getemtsbq=function(wj, oi1,oi2, fzd, dx){
		var i,oi=0,j1 = js.float(100/fzd);
		var s = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
		for(i=oi1; i<=oi2; i++){
			oi++;
			s+='<td width="'+j1+'%" title="'+strformat.emotsarr[i+1]+'" align="center"><img onclick="im.backemts(\''+strformat.emotsarr[i+1]+'\')" src="web/images/im/emots/'+wj+'/'+i+'.gif" width="'+dx+'" height="'+dx+'"></td>';
			if(oi % fzd==0)s+='</tr><tr>';
		}
		s+='</tr></table>';
		return s;
	};
	this.sendfile=function(bo){
		uploadobj.nownum = this.num;
		uploadobj.click();
	};
	this.sendfileshow=function(f){
		f.face 	= this.sendinfo.face;
		var fa 	= strformat.showupfile(f);
		var cont= fa.cont;
		this.upfilearr = fa;
		this.addcont(cont);
	};
	this.sendfileok=function(f,str){
		var a=js.decode(str);
		if(!a.id){
			js.msg('msg', str);
			this.senderrornss();
			return;
		}
		var tm= this.upfilearr,conss='';
		f = a;
		strformat.upsuccess(a);
		if(js.isimg(f.fileext)){
			conss = '[图片 '+f.filesizecn+']';
			this._addclickf();
		}else{
			conss = '['+f.filename+' '+f.filesizecn+']'
		}
		this.sendconts(jm.base64encode(conss), tm.nuid, tm.optdt, a.id);
	};
	this.senderrornss=function(){
		this.senderror(this.upfilearr.nuid);
	};
	
	this.readclip=function(evt){
		var clipboardData = evt.clipboardData;
		if(!clipboardData)return;
		for(var i=0; i<clipboardData.items.length; i++){  
			var item = clipboardData.items[i];  
			if(item.kind=='file'&&item.type.match(/^image\//i)){  
				var blob = item.getAsFile(),reader = new FileReader();  
				reader.onload=function(){  
					var cont=this.result;
					me.readclipshow(cont);
				}  
				reader.readAsDataURL(blob);
			}  
		} 
	};
	
	this.readclipshow=function(snr){
		var fa 	= strformat.showupfile({face:this.sendinfo.face}, snr);
		var cont= fa.cont;
		this._sssnuid  = fa.nuid;
		this._sssoptdt = fa.optdt;
		this.upfilearr = fa;
		this.addcont(cont);	
	};
	this.sendbase64 = function(strnr){
		uploadobj.nownum = this.num;
		uploadobj.sendbase64(strnr);
	};
	this.clipobj = function(){
		if(!this.clipobj1)this.clipobj1 = nw.Clipboard.get();
		return this.clipobj1;
	};
	this.pasteimg=function(){
		var snr  = this.clipobj().get('png');
		//console.log(this.clipobj().readAvailableTypes());
		if(!snr){
			//js.msgerror('剪切板上没有图片');
			return;
		}
		this.readclipshow(snr);
	};
	this.cropScreen=function(){
		this.clipobj().clear();
		jietubool = true;
		im.cropScreen();
	};
	this.filedrop=function(o1){
		uploadobj.nownum = this.num;
		uploadobj.change(o1, 0);
	};
	this.contright=function(o1,e){
		var o=$(o1),rnd=o.attr('rand');
		this.rightqipao(o1,e,rnd);
	};
	this.rightqipao=function(o1,e,rnd){
		if(!this.rightqipaoobj)this.rightqipaoobj=$.rockmenu({
			data:[],
			width:130,
			itemsclick:function(d){
				me.rightqipaoclick(d);
			}
		});
		this.randmess = rnd;
		this.rightdata= this.listdata[rnd];
		var d=[{name:'复制',lx:0},{name:'删除',lx:1}];
		if(this.type=='group')d.push({name:'@TA',lx:3});
		var chehui = reim.showconfigarr.chehui;
		if(o1.className.indexOf('right')>0 && chehui>0){
			var t1 = js.now('time', this.rightdata.optdt),t2 = js.now('time');
			var t3 = (t2-t1)*0.001;
			if(t3<chehui*60)d.push({name:'撤回',lx:2});
		}
		this.rightqipaoobj.setData(d);
		this.rightqipaoobj.showAt(e.clientX,e.clientY);
	};
	this.rightqipaoclick=function(d){
		var lx=d.lx;
		var ids = this.rightdata.id;
		if(lx==0){
			var cont = $('#qipaocont_'+this.randmess+'').text();
			if(cont)js.setcopy(cont);
		}
		if(lx==1){
			$('#ltcont_'+this.randmess+'').remove();
			if(!isNaN(ids)){
				reim.ajax(reim.getapiurl('reim','clearrecord'),{type:this.type,gid:this.gid,ids:ids});
			}
		}
		if(lx==3){
			var cont = $('#ltname_'+this.randmess+'').text();
			if(cont)this.addinput('@'+cont+' ');
		}
		if(lx==2 && !isNaN(ids)){
			var o1dd = $('#qipaocont_'+this.randmess+'')
			o1dd.html(js.getmsg('撤回中...'));
			reim.ajax(reim.getapiurl('reim','chehuimess'),{type:this.type,gid:this.gid,ids:ids}, function(ret){
				o1dd.html(js.getmsg(ret.data.msg1,'green'));
			},'get', function(){
				o1dd.html(js.getmsg('撤回失败','red'));
			});
		}
	};
	this._init();
}

//相关回调
var im = {
	clickqipao:function(o1,e){
		
	},
	rightqipao:function(o1,e,rnd){
		reim.chatobj[reim.nowtabs].rightqipao(o1,e,rnd);
	},
	backemts:function(s){
		reim.chatobj[reim.nowtabs].addinput(s);
		$('#aemtsdiv').hide();
	},
	sendfileshow:function(f){
		var num = uploadobj.nownum;
		reim.chatobj[num].sendfileshow(f);
	},
	upprogresss:function(per){
		var num = uploadobj.nownum;
		strformat.upprogresss(per);
	},
	sendfileok:function(f,str){
		var num = uploadobj.nownum;
		reim.chatobj[num].sendfileok(f, str);
	},
	senderror:function(){
		var num = uploadobj.nownum;
		reim.chatobj[num].senderrornss();
	},
	readclip:function(num,e){
		reim.chatobj[num].readclip(e);
	},
	upbase64:function(nuid){
		var o = get('jietuimg_'+nuid+'');
		reim.chatobj[reim.nowtabs].sendbase64(o.src);
	},
	cropScreen:function(){
		if(nwjsgui){
			var oatg = nwjs.getpath();
			nw.Shell.openItem(''+oatg+'/images/reimcaptScreen.exe');
		}
	},
	windowfocus:function(){
		if(jietubool){
			reim.chatobj[reim.nowtabs].pasteimg();
		}
		jietubool = false;
		nwjs.jumpclear();
	},
	fileyulan:function(pn,fid){
		var url = '?m=public&a=fileviewer&id='+fid+'';
		js.open(url, 800, 500);
	}
}

//下载文件预览的,glx0预览,1下载
strformat.onopenfile=function(da,glx){
	var url = da.upurl;
	if(glx==0 && da.isimg=='1'){
		strformat.imgview(url);
	}else{
		if(glx==1){
			js.location(url);
		}else{
			js.open(url, 1000,600);
		}
	}
	return true;
}