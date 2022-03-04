var im={
	minid:999999999,
	init:function(){
		strformat.emotspath='web/';
		this.type = receinfor.type;
		this.gid = receinfor.gid;
		this.showobj  = $('#showview');
		this.inputobj = $('#contentss');
		$('#btn').click(function(){
			im.sendcont();
		});
		this.loaddata();
		this.readinforshow();
		
		im.touchobj = $('#showview').rockdoupull({
			downbgcolor:'',
			downbool:true,
			ondownsuccess:function(){
				im.dropdown_success();
			}
		});
		this.resizehei();
		$(window).resize(this.resizehei);
	},
	submitinput:function(){
		try{im.sendcont();}catch(e){}
		return false;
	},
	getheight:function(ss){
		var hei = 50;if(!ss)ss=0;
		if(get('header_title'))hei+=50;
		return $(window).height()-hei+ss;
	},
	resizehei:function(){
		var h = im.getheight();
		im.showobj.css('height',''+h+'px');
		im.touchobj.resize();
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
		setTimeout('im.readinforshows()',1000*10);
	},
	readinforshows:function(){
		var minid=this.minid;
		js.ajax('reim','getrecord',{type:this.type,gid:this.gid,minid:0,lastdt:this.lastdt},function(ret){
			im.loaddatashow(ret, false, true);
			im.readinforshow();
		},'none', false,false,'get');
	},
	loaddatashow:function(ret,isbf, isls){
		var a 		= ret.rows;
		this.lastdt = ret.nowdt;
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
			nr  = this.contshozt(d.filers);
			if(nr=='')nr= jm.base64decode(d.cont);
			rnd = 'mess_'+sid+'';
			cont= strformat.showqp(lex,nas,d.optdt,nr ,'', fase, rnd);
			if(!isbf){
				this.addcont(cont, isbf);
			}else{
				this.showobj.prepend(cont);
			}
			if(sid<this.minid)this.minid=sid;
		}
		if(len>0 && !isls){
			var s = '<div id="histordiv" class="showblanks" >';
			if(ret.wdtotal==0){
				s+='---------↑以上是历史记录---------';
				if(len>=5){
					//this.showobj.prepend('<div id="loadmored" class="showblanks" ><a href="javascript:;" onclick="im.loadmoreda(this)">点击加载更多...</a></div>');
					this.isshangla = true;
				}
			}else{
				s+='---↑以上是历史,还有未读信息'+ret.wdtotal+'条,<a href="javascript:;" onclick="im.loaddata(this)">点击加载</a>---';
			}
			s+='</div>';
			if(!isbf)this.addcont(s);
			if(isbf)this._addclickf();
		}
		if(im.touchobj)im.touchobj.ondownok();
	},
	dropdown_success:function(){
		if(this.isshangla){
			this.loadmoreda(false);
		}else{
			setTimeout(function(){im.touchobj.ondownok()},1000);
		}
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
		$.imgview({url:src,ismobile:true});
	},
	contshozt:function(d){
		return strformat.contshozt(d,'web/');
	},
	sendcont : function(ssnr){
		if(js.ajaxbool)return;
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
		var cont= strformat.showqp('right','我',optdt, nr, nuid, adminface);
		this.addcont(cont);
		o.val('').focus();
		this.sendconts(conss, nuid, optdt, 0);
		return false;
	},
	sendconts:function(conss, nuid, optdt, fid){
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
	},
	addinput:function(s){
		var val = this.inputobj.val()+s;
		this.inputobj.val(val).focus();
	},
	showemit:function(){
		var da = [];
		var a = strformat.emotsarr,i;
		for(i=1;i<50;i++)da.push({name:'&nbsp; <img src="web/images/im/emots/qq/'+(i-1)+'.gif" align="absmiddle">&nbsp;'+a[i], num:a[i]});
		js.showmenu({
			data:da,width:150,align:'left',
			onclick:function(d){
				im.addinput(d.num);
			}
		});
	},
	sendfile:function(){
		if(typeof(uploadobj)=='undefined')uploadobj = $.rockupload({
			inputfile:'allfileinput',
			updir:'reimchat',
			urlparams:{noasyn:'yes'}, //不需要同步到文件平台上
			initpdbool:true,
			onchange:function(d){
				im.sendfileshow(d);
			},
			onprogress:function(f,per,evt){
				strformat.upprogresss(per);
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
		uploadobj.click();
	},
	sendfileshow:function(f){
		f.face 	= adminface;
		var fa 	= strformat.showupfile(f);
		var cont= fa.cont;
		this.upfilearr = fa;
		this.addcont(cont);
	},
	sendfileok:function(f,str){
		var tm= this.upfilearr,conss='';
		var a = js.decode(str);
		a.isimg = f.isimg;
		strformat.upsuccess(a);
		if(f.isimg){
			conss = '[图片 '+a.filesizecn+']';
		}else{
			conss = '['+f.filename+' '+f.filesizecn+']'
		}
		this.sendconts(jm.base64encode(conss), tm.nuid, tm.optdt, a.id);
	},
	fileyulan:function(pn,fid){
		var url = '?m=public&a=fileviewer&id='+fid+'';
		js.location(url);
	}
}

strformat.clickfile=function(fid,lx){
	js.fileopt(fid,lx);
}