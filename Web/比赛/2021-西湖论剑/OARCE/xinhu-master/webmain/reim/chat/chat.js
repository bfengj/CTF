js.apiurl = function(m,a,cans){
	var url='api.php?m='+m+'&a='+a+'&adminid='+adminid+'';
	url+='&cfrom=reim';
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
	function errsoers(ts){
		js.setmsg(ts);
		js.msg('msg',ts);
		js.ajaxbool = false;
		erfs(ts);
	}
	var type=(!d)?'get':'post';if(glx)type=glx;
	var ajaxcan={
		type:type,dataType:'json',data:d,url:url,
		success:function(ret){
			js.ajaxbool=false;
			clearTimeout(js.ajax_time);
			if(ret.code!=200){
				errsoers(ret.msg);
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
js.downshow=function(id){
	this.location('?id='+id+'&a=down');
}

var im={
	minid:999999999,
	init:function(){
		strformat.emotspath='web/';
		this.type 		= receinfor.type;
		this.gid 		= receinfor.gid;
		this.showobj  	= $('#showview');
		this.inputobj 	= $('#contentss');
		this.loaddata();
		$('a[tools]').click(function(e){
			im.toolsclick(this,e);
			return false;
		});
		this.inputobj.focus();
		$('body').keydown(im.onkeydown);
		
		this.showobj.mousewheel(function(e){
			im.mousewheel(e.deltaY);
		});
		this.resethie();
		$(window).resize(function(){
			im.resethie();
		});
		this.charcontobj = $.rockmenu({
			data:[],
			itemsclick:function(d){
				im.clickmenuss(d);
			}
		});
		this.initupfile();
		document.ondragover=function(e){e.preventDefault();};
		document.ondrop=function(e){e.preventDefault();};
		document.addEventListener('drop', function(e) {
			var files = e.dataTransfer;
			im.filedrop(files);
		}, false);
	},
	resethie:function(){
		var h = winHb()-40-30-80-40;
		this.showobj.css('height',''+h+'px');
	},
	submitinput:function(){
		try{im.sendcont();}catch(e){}
		return false;
	},
	mousewheel:function(lx){
		//向上
		if(lx==1){
			var t = this.showobj.scrollTop();
			if(t==0&&get('loadmored')&&!js.ajaxbool)this.loadmoreda(get('loadmored'));
		}
	},
	onkeydown:function(e){
		var code	= e.keyCode;
		if(code==27){im.close();return false;}
		if(e.altKey){
			if(code == 67){im.close();return false;}
			if(code == 83){im.sendcont();return false;}
		}
		if(e.ctrlKey){
			if(code == 13){im.sendcont();return false;}
		}
		return true;
	},
	toolsclick:function(o1,evt){
		var o = $(o1);
		var lx= o.attr('tools');
		if(lx=='send')this.sendcont();
		if(lx=='emts')this.getemts(o);
		if(lx=='clear')this.clearping();
		if(lx=='crop')im.cropScreen(false);
		if(lx=='close')this.close();
		if(lx=='file')this.sendfile(true);
		if(lx=='change')this.changesend(o);
		if(lx=='cropput')js.msg('msg','请使用快捷键Ctrl+V');
	},
	close:function(){
		window.close();
	},
	clearping:function(){
		this.showobj.html('');
	},
	getheight:function(ss){
		var hei = 50;if(!ss)ss=0;
		if(get('header_title'))hei+=50;
		return $(window).height()-hei+ss;
	},
	
	//判断是否可以发消息
	sendbool:function(){
		var bo = true;
		if(receinfor.type=='group' && receinfor.innei==0)bo=false;
		if(!bo)js.msg('msg','你不在此会话中，不允许发送');
		return bo;
	},
	
	loaddata:function(o1, iref){
		if(this.boolload)return;
		var iref = (!iref)?false:true;
		var minid= 0;
		if(iref)minid=this.minid;
		if(o1)$(o1).html('<img src="images/loadings.gif" height="14" width="15" align="absmiddle"> 加载中...');
		this.boolload 	= true;
		this.isshangla 	= false;
		js.ajax('reim','getrecord',{type:this.type,gid:this.gid,minid:minid,lastdt:''},function(ret){
			if(o1)$(o1).html('');
			im.boolload = false;
			im.loaddatashow(ret, iref);
		},'none', false,false,'get');
	},
	readinforshow:function(){
		//setTimeout('im.readinforshows()',1000*10);
	},
	readinforshows:function(){
		var minid=this.minid;
		js.ajax('reim','getrecord',{type:this.type,gid:this.gid,minid:0,lastdt:this.lastdt},function(ret){
			im.loaddatashow(ret, false, true);
			im.readinforshow();
		},'none', false,false,'get');
	},
	showdata:{},
	loaddatashow:function(ret,isbf, isls){
		var a 		= ret.rows;
		this.lastdt = ret.nowdt;
		$('#showviewload').remove();
		var i,len 	= a.length,cont,lex,nas,fase,nr,d,na=[],rnd,sid;
		$('#loadmored').remove();
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
			fase= adminface;
			if(d.sendid!=adminid){
				lex='left';
				nas= d.sendname;
				fase= d.face;
			}
			this.showdata[sid]=d;
			nr  = this.contshozt(d.filers);
			if(nr=='')nr= jm.base64decode(d.cont);
			rnd = 'mess_'+sid+'';
			cont= strformat.showqp(lex,nas,d.optdt,nr ,'', fase, rnd);
			if(!isbf){
				this.addcont(cont, isbf);
			}else{
				this.showobj.prepend(cont);
			}
			$('#qipaocont_'+rnd+'').contextmenu(function(e){
				im.contright(this,e);
				return false;
			});
			if(sid<this.minid)this.minid=sid;
		}
		if(len>0 && !isls){
			var s = '<div id="histordiv" class="showblanks" >';
			if(ret.wdtotal==0){
				s+='---------↑以上是历史记录---------';
				if(len>=5){
					this.showobj.prepend('<div id="loadmored" class="showblanks" ><a href="javascript:;" onclick="im.loadmoreda(this)">点击加载更多...</a></div>');
					this.isshangla = true;
				}
			}else{
				s+='---↑以上是历史,还有未读信息'+ret.wdtotal+'条,<a href="javascript:;" onclick="im.loaddata(this)">点击加载</a>---';
			}
			s+='</div>';
			if(!isbf)this.addcont(s);
			if(isbf)this._addclickf();
		}
	},
	contright:function(o1,e){
		var o=$(o1),rnd=o.attr('rand');
		this.randmess = rnd;//,{name:'转发...',lx:4}
		var ids=rnd.replace('mess_',''),da=this.showdata[ids];
		this.rightdata = da;
		var d=[{name:'复制',lx:0},{name:'删除',lx:1},{name:'刷新',lx:5}];
		if(this.type=='group')d.push({name:'@TA',lx:3});
		
		//if(da.sendid==adminid)d.push({name:'撤回',lx:6});

		this.charcontobj.setData(d);
		this.charcontobj.showAt(e.clientX,e.clientY,130);
	},
	clickmenuss:function(d){
		var lx=d.lx;
		var ids=this.randmess.replace('mess_','');
		if(lx==0){
			var cont = $('#qipaocont_'+this.randmess+'').text();
			if(cont)this.addinput(cont);
		}
		if(lx==1){
			$('#ltcont_'+this.randmess+'').remove();
			
			if(ids)js.ajax('reim','clearrecord',{type:this.type,gid:this.gid,ids:ids},false,'none');
		}
		if(lx==2){
			js.confirm('确定要清除1个月前的记录吗？',function(lx){
				if(lx=='yes')im.clearjilss(30);
			});
		}
		if(lx==3){
			var cont = $('#ltname_'+this.randmess+'').text();
			if(cont)this.addinput('@'+cont);
		}
		if(lx==4){
			js.changeuser(false,'usercheck','转发给...', {
				onselect:function(sna,sid){
					if(!sid)return;
					im.zhuangfa(sid, ids);
				}
			});
		}
		if(lx==5){
			location.reload();
		}
		if(lx==6){
			$('#qipaocont_'+this.randmess+'').html('撤回消息');
		}
	},
	//转发
	zhuangfa:function(touid, ids){
		
	},
	clearjilss:function(d){
		js.ajax('reim','clearrecord',{type:this.type,gid:this.gid,day:d},function(s){
			js.msg('success','清除成功');
		});
	},
	loadmoreda:function(o1){
		this.loaddata(o1, true);
	},
	addcont:function(cont, isbf){
		var o	= this.showobj;
		if(cont){if(isbf){o.prepend(cont);}else{o.append(cont);}}
		clearTimeout(this.scrolltime);
		this.scrolltime	= setTimeout(function(){
			im.showobj.animate({scrollTop:get('showview').scrollHeight},100);
			im._addclickf();
		}, 50);
	},
	_addclickf:function(){
		var o = this.showobj.find('img[fid]');
		o.unbind('click');
		o.click(function(){
			im.clickimg(this);
		});
	},
	clickimg:function(o1){
		var o=$(o1);
		var fid=o.attr('fid');
		var src = o1.src.replace('_s.','.');
		$.imgview({url:src,ismobile:false});
	},
	contshozt:function(d){
		return strformat.contshozt(d,'web/');
	},
	sendcont : function(ssnr){
		if(js.ajaxbool || !this.sendbool())return;
		js.msg('none');
		var o	= this.inputobj;
		var nr	= strformat.sendinstr(o.val());
		nr		= nr.replace(/</gi,'&lt;').replace(/>/gi,'&gt;').replace(/\n/gi,'<br>');
		if(ssnr)nr=ssnr;
		if(isempt(nr))return false;
		var conss = jm.base64encode(nr);
		if(conss.length>500){
			js.msg('msg','发送内容太多了');
			return;
		}
		var nuid= js.now('time'),optdt = js.serverdt();
		if(optdt==this.nowoptdt){
			js.msg('msg','消息发太快了');
			return;
		}
		this.nowoptdt = optdt;
		var cont= strformat.showqp('right','我',optdt, nr, nuid, adminface);
		this.addcont(cont);
		o.val('').focus();
		this.sendconts(conss, nuid, optdt, 0);
		return false;
	},
	sendconts:function(conss, nuid, optdt, fid){
		try{opener.reim.addhistory(this.type,this.gid,0,conss,optdt,adminname);}catch(e){}
		var d 	 = {cont:conss,gid:this.gid,type:this.type,nuid:nuid,optdt:optdt,fileid:fid};
		js.ajax('reim','sendinfor',d,function(ret){
			im.sendsuccess(ret,nuid);
		},'none',false,function(){
			im.senderror(nuid);
		});
	},
	senderror:function(nuid){
		js.ajaxbool = false;
		get(nuid).src='images/error.png';
		get(nuid).title='发送失败';
	},
	sendsuccess:function(d,nuid){
		this.bool = false;
		if(!d.id){
			this.senderror(nuid);
			return;
		}
		$('#'+d.nuid+'').remove();
		var bo = false;
		d.messid=d.id;
		d.face  = adminface;
		if(this.type=='group')d.gface=receinfor.face;
		var bo  = serversend(d);
		//if(!bo)js.msg('msg','信息不能及时推送，但已保存到服务器');
	},
	addinput:function(s){
		var val = this.inputobj.val()+s;
		this.inputobj.val(val).focus();
	},
	initupfile:function(){
		if(typeof(uploadobj)=='undefined')uploadobj = $.rockupload({
			inputfile:'allfileinput',
			initpdbool:false,
			updir:'reimchat',
			onchange:function(f){
				im.sendfileshow(f);
			},
			onprogress:function(f,per,evt){
				strformat.upprogresss(per,im._sssnuid);
			},
			onsuccess:function(f,str,o1){
				im.sendfileok(f,str);
			},
			onerror:function(str){
				js.msg('msg', str);
				im.senderror(im.upfilearr.nuid);
			}
		});
		strformat.upobj = uploadobj;
	},
	sendfile:function(bo){
		if(!this.sendbool())return;
		if(bo)uploadobj.click();
	},
	filedrop:function(fobj){
		if(!this.sendbool())return;
		uploadobj.change(fobj);
	},
	//上传的文件预览显示
	sendfileshow:function(f, snr){
		if(!f)f={};
		f.face 	= adminface;
		var fa 	= strformat.showupfile(f, snr);
		var cont= fa.cont;
		this._sssnuid  = fa.nuid;
		this._sssoptdt = fa.optdt;
		this.upfilearr = fa;
		this.addcont(cont);
	},
	sendfileok:function(f,str){
		var a=js.decode(str);
		if(!a.id){
			this.senderror(this._sssnuid);
			strformat.uperror(this._sssnuid);
			js.msg('msg', str);
			return;
		}
		var contss = strformat.upsuccess(a, this._sssnuid);
		this.sendconts(jm.base64encode(contss), this._sssnuid, this._sssoptdt, a.id);
	},
	fileyulan:function(pn,fid){
		var url = '?m=public&a=fileviewer&id='+fid+'';
		parent.js.open(url, 700, 500);
	},
	getemts:function(o){
		if(!get('aemtsdiv')){
			var s = '<div id="aemtsdiv" style="width:400px;height:200px;overflow-y:auto;border:1px #cccccc solid;background:white;box-shadow:0px 0px 5px rgba(0,0,0,0.3);left:3px;top:5px;position:absolute;display:none;z-index:6">';
			s+='<div style="padding:5px">';
			s+=this.getemtsbq('qq',0, 96, 11, 24);
			s+='</div>';
			s+='</div>';
			$('body').append(s);
			js.addbody('emts','hide','aemtsdiv');
		}
		var o1  = $('#aemtsdiv');
		o1.toggle();
		var off = o.offset();
		o1.css('top',''+(off.top-205)+'px');
	},
	getemtsbq:function(wj, oi1,oi2, fzd, dx){
		var i,oi=0,j1 = js.float(100/fzd);
		var s = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
		for(i=oi1; i<=oi2; i++){
			oi++;
			s+='<td width="'+j1+'%" title="'+strformat.emotsarr[i+1]+'" align="center"><img onclick="im.backemts(\''+strformat.emotsarr[i+1]+'\')" src="web/images/im/emots/'+wj+'/'+i+'.gif" width="'+dx+'" height="'+dx+'"></td>';
			if(oi % fzd==0)s+='</tr><tr>';
		}
		s+='</tr></table>';
		return s;
	},
	backemts:function(s){
		this.addinput(s);
		$('#aemtsdiv').hide();
	},
	getpath:function(){
		if(!this.pathobj)this.pathobj = require('path');
		var oatg = this.pathobj.dirname(process.execPath);
		oatg	 = oatg.replace(/\\/g, '/');
		return oatg;
	},
	//截屏
	cropScreen:function(lx){
		if(nwjsgui){
			var oatg = this.getpath();
			nw.Shell.openItem(''+oatg+'/images/reimcaptScreen.exe');
		}else{
			js.msg('msg','无法截屏，请使用REIM客户端/第三方快捷键，<a href="http://www.rockoa.com/view_client.html" target="_blank">[去下载]</a>');
			return;
		}
	},
	readclip:function(evt){
		var clipboardData = evt.clipboardData;
		if(!clipboardData)return;
		for(var i=0; i<clipboardData.items.length; i++){  
			var item = clipboardData.items[i];  
			if(item.kind=='file'&&item.type.match(/^image\//i)){  
				var blob = item.getAsFile(),reader = new FileReader();  
				reader.onload=function(){  
					var cont=this.result;
					im.sendfileshow(false, cont);
				}  
				reader.readAsDataURL(blob);
			}  
		} 
	},
	upbase64:function(nuid,nus){
		var o = get('jietuimg_'+nuid+'');
		this.sendfile(false);
		uploadobj.sendbase64(o.src);
	},
	
	//显示用户
	showuser:function(){
		var s = '<div id="showuserlist" style="height:160px;overflow:auto;padding:5px 10px"><div align="center" style="padding:10px;"><img src="images/mloading.gif" align="absmiddle">&nbsp;加载人员...</div></div>';
		js.tanbody('syscogshow','会话上人员',350,100,{html:s});
		js.ajax('reim','getgroupuser',{type:this.type,gid:this.gid},function(ret){
			im.showusershow(ret.uarr);
		},'none', false,false,'get');
	},
	showusershow:function(a){
		var i,len=a.length,s='',oi=0;
		
		s+='<table width="100%"><tr>';
		for(i=0;i<len;i++){
			oi++;
			s+='<td width="20%"><div style="padding:5px" align="center"><div><img style="height:40px;width:40px;border-radius:50%" onclick="$.imgview({url:this.src})" src="'+a[i].face+'"></div><div style="color:#888888">'+a[i].name+'</div></div></td>';
			if(oi%4==0)s+='</tr><tr>';
		}
		s+='</tr></table>';
		$('#showuserlist').html(s);
	},
	
	//邀请
	yaoqing:function(){
		if(this.type!='group')return;
		js.changeuser(false,'usercheck','邀请人到会话中', {
			onselect:function(sna,sid){
				if(!sid)return;
				im.yaoqings(sid);
			}
		});
	},
	yaoqings:function(sid){
		js.msg('wait','邀请中...');
		js.ajax('reim','yaoqinguid',{val:sid,gid:this.gid},function(da){
			if(da.indexOf('success')==0){
				var uids = da.replace('success','');
				js.alert('邀请成功','',function(){
					location.reload();
				});
				//if(uids != '')
			}else{
				js.msg('msg',da);
			}
		});
	},
	
	//退出会话
	exitgroup:function(){
		if(this.type!='group')return;
		js.confirm('确定要退出会话吗？',function(lx){
			if(lx=='yes'){
				im.exitgroups();
			}
		});
	},
	exitgroups:function(){
		js.msg('wait','退出中...');
		js.ajax('reim','exitgroup',{gid:this.gid}, function(da){
			js.alert('成功退出会话','',function(){
				try{opener.reim.exitgroup(im.gid)}catch(e){}
				im.close();
			});
		});
	}
}


strformat.clickfile=function(fid,lx){
	js.fileopt(fid,lx);
}