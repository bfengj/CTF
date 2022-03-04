var touchobj=false;
var strformat = {
	sendcodearr:{},
	sendcuxo:0,
	emotsstr:',[微笑],[撇嘴],[色],[发呆],[得意],[流泪],[害羞],[闭嘴],[睡],[大哭],[尴尬],[发怒],[调皮],[呲牙],[惊讶],[难过],[酷],[冷汗],[抓狂],[吐],[偷笑],[愉快],[白眼],[傲慢],[饥饿],[困],[恐惧],[流汗],[憨笑],[悠闲],[奋斗],[咒骂],[疑问],[嘘],[晕],[疯了],[衰],[骷髅],[敲打],[再见],[擦汗],[抠鼻],[鼓掌],[糗大了],[坏笑],[左哼哼],[右哼哼],[哈欠],[鄙视],[委屈],[快哭了],[阴险],[亲亲],[吓],[可怜],[菜刀],[西瓜],[啤酒],[篮球],[乒乓],[咖啡],[饭],[猪头],[玫瑰],[凋谢],[嘴唇],[爱心],[心碎],[蛋糕],[闪电],[炸弹],[刀],[足球],[瓢虫],[便便],[月亮],[太阳],[礼物],[拥抱],[强],[弱],[握手],[胜利],[抱拳],[勾引],[拳头],[差劲],[爱你],[NO],[OK],[爱情],[飞吻],[跳跳],[发抖],[怄火],[转圈],[磕头],[回头],[跳绳],[投降],[激动],[街舞],[献吻],[左太极],[右太极]',
	addcode:function(key, val){
		this.sendcuxo++;
		key	= key+','+this.sendcuxo;
		this.sendcodearr[key] = val;
		return '[C]'+key+'[/C]'
	},
	geturl:function(d){
		if(!d)d={'url':''};
		var url = d.url;
		if(!url&&d.table&&d.mid)url='?m=flow&a=view&d=taskrun&table='+d.table+'&mid='+d.mid+'&uid='+adminid+'';
		return url;
	},
	emotspath:'',
	strcont:function(nr){
		var str = unescape(nr),patt1,emu,i,st1,oi;
		
		if(str.indexOf('<img')==-1){
			var strRegex = "((https|http)?://){1}" 
				 + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@ 
				  + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184 
				  + "|" // 允许IP和DOMAIN（域名）
				  + "([0-9a-z_!~*'()-]+\.)*" // 域名- www. 
				  + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名 
				  + "[a-z]{2,6})" // first level domain- .com or .museum 
				  + "(:[0-9]{1,4})?" // 端口- :80 
				  + "((/?)|" // a slash isn't required if there is no file name 
				  + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)"; 
			patt1	= new RegExp(strRegex, 'gi'); 
			emu		= str.match(patt1);
			if(emu!=null){
				for(i=0;i<emu.length; i++){
					st1 = emu[i];
					if(st1.indexOf('http')==0){
						str = str.replace(st1, '{URL'+i+'}');
					}
				}
				for(i=0;i<emu.length; i++){
					st1 = emu[i];
					if(st1.indexOf('http')==0){
						str = str.replace('{URL'+i+'}', '<a onclick="return strformat.openurl(\''+st1+'\')" href="javascript:;">'+st1+'</a>');
					}
				}
			}
		}
		
		
		patt1	= new RegExp("\\[(.*?)\\](.*?)", 'gi');
		emu		= str.match(patt1);
		if(emu!=null){
			for(i=0;i<emu.length; i++){
				st1=emu[i];
				oi=this.emotsarrss[st1];
				if(oi)str	= str.replace(st1, '<img height="24" width="24" src="'+this.emotspath+'images/im/emots/qq/'+(oi-1)+'.gif">');
			}
		}
		str	= str.replace(/\n/gi, '<br>');
		return str;
	},
	downshow:function(sid){
		var url = 'mode/upload/uploadshow.php?id='+sid+'';
		openurlla(url, 400, 300);
		return false;
	},
	strcontss:function(str,bq,rstr){
		var patt1	= new RegExp("\\["+bq+"\\](.*?)\\[\\/"+bq+"\\]", "gi");
		var emu		= str.match(patt1);
		if(emu != null){
			bq1	= bq.toLowerCase();
			for(var i=0;i<emu.length; i++){
				var s0	= emu[i].replace('['+bq+']','').replace('[/'+bq+']','');
				s0		= s0.replace('['+bq1+']','').replace('[/'+bq1+']','');
				var s1	= s0,s2 = s0,s3='',sa;
				if(s0.indexOf('|')>0){
					sa = s0.split('|');
					s1 = sa[1];
					s2 = sa[0];
					s3 = sa[2];
				}
				var s4	= rstr.replace('{s1}',s1).replace('{s2}',s2).replace('{s3}',s3);
				str		= str.replace(emu[i], s4);
			}
		}
		return str;
	},
	sendinstr:function(str, tuas){
		var bq		= 'C';
		var patt1	= new RegExp("\\["+bq+"\\](.*?)\\[\\/"+bq+"\\]", "gi");
		var emu		= str.match(patt1);
		
		if(emu != null){
			for(var i=0;i<emu.length; i++){
				var s0	= emu[i].replace('['+bq+']','').replace('[/'+bq+']','');
				str		= str.replace(emu[i], this.sendcodearr[s0]);
			}
		}
		var nowa	= js.serverdt('Y-m-d H:i:s 星期W'),
			nowas	= nowa.split(' ');
		var ztstr	= [['now',nowa],['date',nowas[0]],['time',nowas[1]],['week',nowas[2]]];
		var patt1,a,thnr,ths='';
		for(var i=0; i<ztstr.length; i++){
			a	=	ztstr[i];
			if(a[2] == 1){
				patt1	= new RegExp(""+a[0]+"", "gi");
				thnr	= '[A]'+a[0]+'|'+a[1]+'[/A]';
			}else{
				thnr	= a[1];
				patt1	= new RegExp("\\["+a[0]+"\\]", "gi");
			}
			str	= str.replace(patt1, thnr);
		}
		return str;
	},
	picshow:function(str, wj){
		var s=str,sa;
		if(s.indexOf('[图片.')==0){
			s=s.substr(1,s.length-1);
			sa=s.split('.');
			if(wj)s='<img src="'+apiurl+''+wj+'">';
		}
		return s;
	},
	showdt:function(sj){
		if(!sj)sj='';
		var s='';
		sja=sj.split(' ');
		if(sj.indexOf(this.dt)==0){
			s=sja[1];
		}else{
			s=sj.substr(5,11);
		}
		return s;
	},
	showqp:function(type,name,dt,cont,nuid, fase,rnd,bqname,bqcor){
		var str = this.strcont(cont);
		if(!rnd)rnd=js.getrand();
		var nr	= '',bqs='';
		if(bqname && bqcor)bqs='<font style="background:'+bqcor+';font-size:10px;margin-right:2px;color:white;padding:1px 2px;border-radius:2px" >'+bqname+'</font>';
		this.showqpid = 'ltcont_'+rnd+'';
		nr+='<div id="'+this.showqpid+'" class="ltcont">';
		nr+='	<div class="qipao" align="'+type+'">';
		nr+='		<div class="dt" style="padding-'+type+':65px">'+bqs+'<font id="ltname_'+rnd+'">'+name+'</font>('+this.showdt(dt)+')</div>';
		
		nr+='		<table border="0" cellspacing="0" cellpadding="0">';
		
		nr+='		<tr valign="top">';
		if(type == 'left'){
			nr+='			<td width="50" align="center"><img src="'+fase+'" onclick="strformat.clickface(\''+rnd+'\',this)" class="qipaoface" width="40" height="40"></td>';
			nr+='			<td><div class="qipao'+type+'"></div></td>';
		}else{
			nr+='			<td width="30" align="right">';
			if(nuid)nr+='<img src="images/loadings.gif" title="发送中..." id="'+nuid+'" style="margin-top:5px" align="absmiddle">&nbsp;';
			nr+='			</td>';
		}
		
		nr+='			<td>';
		nr+='			<div ontouchstart="touchobj=this" id="qipaocont_'+rnd+'" rand="'+rnd+'" class="qipaocont qipaocont'+type+'">'+str+'</div>';
		nr+='			</td>';
		
		if(type == 'right'){
			nr+='			<td><div class="qipao'+type+'"></div></td>';
			nr+='			<td width="50" align="center"><img src="'+fase+'" onclick="strformat.clickface(\''+rnd+'\',this)" class="qipaoface" width="40" height="40"></td>';
		}else{
			nr+='			<td width="30"></td>';
		}
		
		nr+='		</tr></table>';
		nr+='	</div>';
		nr+='</div>';
		return nr;
	},
	clickface:function(){
		
	},
	showupfile:function(f, snr){
		var nuid= js.now('time'),optdt = js.serverdt(),nr='';
		nr = '<div id="showve_'+nuid+'">';
		if(f && f.filename){
			if(f.isimg){
				var src = ''+this.emotspath+'images/noimg.jpg';
				if(f.thumbpath)src = ''+apiurl+''+f.thumbpath+'';
				if(f.imgviewurl)src = f.imgviewurl;
				nr+='<div><img width="150" onclick="strformat.clickimg(this)" id="imgview_'+nuid+'" src="'+src+'"><br>'+f.filesizecn+'</div>';
			}else{
				nr+= '<div><img src="'+this.emotspath+'images/fileicons/'+js.filelxext(f.fileext)+'.gif" align="absmiddle">&nbsp;'+f.filename+'('+f.filesizecn+')</div>';
			}
		}
		if(snr){
			nr+= '<div><img src="'+snr+'" onclick="strformat.clickimg(this)" id="jietuimg_'+nuid+'" width="150"></div>';
			nr+= '<div><a onclick="im.upbase64(\''+nuid+'\')" href="javascript:;">[发送截图]</a>';
		}
		nr+= '<div class="progresscls"><div id="progresscls_'+nuid+'" class="progressclssse"></div><div class="progressclstext"  id="progresstext_'+nuid+'">0%</div></div>';
		nr+= '<div id="progcanter_'+nuid+'"><a href="javascript:;" onclick="strformat.cancelup(\''+nuid+'\')">取消</a></div>';
		nr+= '</div>';
		this.nuidup_tep = nuid;
		var nas = f.sendname;
		if(!nas)nas='我';
		var cont= this.showqp('right',nas,optdt, nr, nuid, f.face, nuid,f.bqname,f.bqcolor);
		return {'cont':cont,optdt:optdt,nuid:nuid};
	},
	upprogresss:function(per, nuid){
		if(!nuid)nuid=this.nuidup_tep;
		$('#progresscls_'+nuid+'').css('width',''+per+'%');
		$('#progresstext_'+nuid+'').html(''+per+'%');
		if(per==100)$('#progcanter_'+nuid+'').remove();
	},
	upsuccess:function(f,nuid){
		if(!nuid)nuid=this.nuidup_tep;
		this.upprogresss(100, nuid);
		$('#progresstext_'+nuid+'').html('上传成功');
		var contss;
		if(js.isimg(f.fileext)){
			contss = '[图片 '+f.filesizecn+']';
		}else{
			contss = '['+f.filename+' '+f.filesizecn+']';
		}
		var s = this.contshozt(f);
		$('#showve_'+nuid+'').html(s);
		return contss;
	},
	uperror:function(nuid){
		if(!nuid)nuid=this.nuidup_tep;
		$('#progresstext_'+nuid+'').html('<font color=red>上传失败</font>');
		$('#progcanter_'+nuid+'').remove();
	},
	cancelup:function(nuid){
		if(!nuid)nuid=this.nuidup_tep;
		try{if(this.upobj)this.upobj.abort();}catch(e){}
		$('#ltcont_'+nuid+'').remove();
	},
	openimg:function(src){
		var img = src;
		if(src.indexOf('thumb')>0){
			var ext = src.substr(src.lastIndexOf('.')+1);
			img = src.substr(0,src.lastIndexOf('_'))+'.'+ext;
		}
		js.open(img);
	},
	emotsarrss:{},
	init:function(){
		var a = this.emotsstr.split(',');
		this.emotsarr=a;
		var len = a.length,i;
		for(i=1;i<len;i++){
			this.emotsarrss[a[i]]=i;
		}
		this.dt=js.now();
	},
	contshozt:function(d, lj){
		var s='',slx,sttr;
		if(!d)return s;
		if(!d.fileid)d.fileid=d.id;
		if(js.isimg(d.fileext)){
			sttr='';
			if(d.thumbpath){
				s='<img src="'+d.thumbpath+'" onclick="strformat.clickimg(this)" fid="'+d.fileid+'">';
			}else{
				if(d.width){
					if(d.width>150)sttr='width="150"';
				}else{
					sttr='width="150"';
				}
				s='<img src="'+d.filepath+'"  onclick="strformat.clickimg(this)" '+sttr+' fid="'+d.fileid+'">';
			}
		}else if(d.fileext=='amr'){
			s+='<i class="icon-volume-up"></i> '+(parseInt(d.filesize/1000))+'"';
			s+='&nbsp;<a href="javascript:;" style="font-size:12px" onclick="js.fileopt('+d.fileid+',1)">下载</a>';
		}else{
			slx = d.fileext;if(!lj)lj='';
			if(js.fileall.indexOf(','+slx+',')<0)slx='wz';
			//s=''+d.filename+'<br><a href="javascript:;" onclick="js.fileopt('+d.fileid+',1)">下载</a>&nbsp;&nbsp;<a href="javascript:;" onclick="js.fileopt('+d.fileid+',0)">预览</a>&nbsp;'+d.filesizecn+'';
			s='<table><tr><td><div class="qipaofile">'+d.fileext.toUpperCase()+'</div></td><td>'+d.filename+'<br><span style="font-size:12px;color:#888888">('+d.filesizecn+')&nbsp;&nbsp;<a href="javascript:;" onclick="strformat.clickfile(\''+d.fileid+'\',1)">下载</a>&nbsp;&nbsp;<a href="javascript:;" onclick="strformat.clickfile(\''+d.fileid+'\',0)">预览</a></span></td></tr></table>';
		}
		return s;
	},
	clickfile:function(fid){
		js.msg('msg','没有开发打开');
	},
	clickimg:function(){
		
	}
}
strformat.init();