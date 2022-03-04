/**
	edittable 选择人员插件
	caratename：chenxihu
	caratetime：2014-04-06 21:40:00
	email:admin@rockoa.com
	homepage:www.rockoa.com
*/

(function ($) {
	
	function _getstyles(){
		var s='<style>.changeuserlist div.listsss{padding:10px; background:white;border-bottom:1px #eeeeee solid;cursor:default}.changeuserlist td{color:#333333}.changeuserlist div:active{ background:#f1f1f1}.changeuserbotton{height:30px;width:50px; background:#d9534f;color:white;font-size:14px;border:none;padding:0px;margin:0px;line-height:20px;cursor:default;opacity:1;outline:none;border-radius:5px}.changeuserbotton:active{color:white;border:none;opacity:0.8}.changeuserxuan span{background:white;border:1px #cccccc solid;padding:3px;border-radius:5px;font-size:12px;margin-left:5px;cursor:pointer}</style>';
		return s;
	}
	
	function chnageuser(sobj, options){
		var obj		= sobj;
		var rand	= ''+parseInt(Math.random()*9999999)+''; 
		var me		= this;
		this.rand	= rand;
		this.changesel = '';
		this.firstpid  = 0;
		
		this._init	= function(){
			for(var i in options)this[i]=options[i];
			this.oveob = false;
			if(this.showview!='' && get(this.showview))this.oveob=true;
			
			if(!this.oveob){
				window.onhashchange=function(){
					var has = location.hash;
					if(has.indexOf('#changeuser')==-1)me.hide();
				}
				js.location('#changeuser');
			}
	
			this.userarr = [];
			this.deptarr = [];
			this.grouparr= [];
			if(isempt(this.changerange) && isempt(this.changerangeno)){
				var us = js.getoption('userjson');
				if(us)this.userarr = js.decode(us);
				
				us = js.getoption('deptjson');
				if(us)this.deptarr = js.decode(us);
				us = js.getoption('groupjson');
				if(us)this.grouparr = js.decode(us);
			}
			this.show();
		};
		
		this.creatediv=function(){
			var type='checkbox';
			if(this.changetype.indexOf('check')==-1)type='radio';
			this.inputtype = type;
			$('#changeuser_'+rand+'').remove();
			var hei = $(window).height(),jhei=50,atts='position:fixed;';
			if(this.oveob){
				hei = $('#'+this.showview+'').height();
				atts='';
			}
			var s='<div style="'+atts+'z-index:100;width:100%;height:100%;overflow:hidden;left:0px;top:0px; background:white" id="changeuser_'+rand+'">';
			if(this.titlebool){
				s+='<div style="height:50px;line-height:50px;text-align:center; background:white;border-bottom:1px #cccccc solid"><b>'+this.title+'</b></div>';
				jhei+=50;
			}
			if(this.changetype.indexOf('user')>=0){
				s+='<div style="height:50px;overflow:hidden;border-bottom:1px #cccccc solid"><table width="100%" style="background:none"><tr><td width="100%" height="50" style="background:none"><input id="changekey_'+this.rand+'" placeholder="部门/姓名/职位" style="height:30px;border:none;background:none;width:100%;margin:0px 10px;outline:none"></td><td><button style="background:none;border:none;color:#666666" class="changeuserbotton" id="changesoubtn_'+this.rand+'" type="button" >查找</button></td></tr></table></div>';
				jhei+=50;
			}
			s+='<div style="-webkit-overflow-scrolling:touch;height:'+(hei-jhei)+'px;overflow:auto; background:#f1f1f1" class="changeuserlist">';
			s+='<span id="showdiv'+rand+'_0"></span>';
			s+='<span id="showdiv'+rand+'_search"></span>';
			s+='</div>';
			var s3= '<input id="changeboxs_'+rand+'" style="width:18px;height:18px;" align="absmiddle" type="checkbox" >';
			if(type!='checkbox1')s3='';
			if(1==1){
				s3='<select id="changesel_'+rand+'" style="height:30px;border:none;background:none;outline:none">';
				s3+='<option value="">默认显示</option>';
				if(this.changetype.indexOf('user')>=0)s3+='<option value="1">仅限显示人员</option>';
				if(this.changetype.indexOf('dept')>=0)s3+='<option value="2">仅限显示部门</option>';
				if(this.changetype.indexOf('user')>=0)s3+='<option value="3">显示组</option>';
				s3+='</select>';
			}
			if(type=='checkbox'){
				s+='<div class="changeuserxuan" style="padding:5px;border-right:1px #cccccc solid;border-top:1px #cccccc solid;line-height:30px;position:absolute;bottom:49px;background:white;"><font style="cursor:pointer" id="yixuanbtn_'+rand+'">∨</font><font id="yixuan_'+rand+'"></font></div>';
			}
			var cold = window['maincolor'];if(!cold)cold='#1389D3';
			s+='<div style="height:50px;line-height:50px;border-top:1px #cccccc solid" align="right"><table width="100%" style="background:none"><tr><td width="10" nowrap>&nbsp;</td><td width="80%">'+s3+'</td><td><button style="width:70px;border:none" type="button" id="changereload_'+rand+'" class="changeuserbotton" >刷新数据</button></td><td width="10" nowrap>&nbsp;</td><td><button class="changeuserbotton" type="button" id="changecancl_'+rand+'" >取消</button></td><td width="10" nowrap>&nbsp;</td><td height="50"><button style="background:'+cold+';" id="changeok_'+rand+'" type="button" class="changeuserbotton">确定</button></td><td width="10" nowrap>&nbsp;</td></tr></table></div>';
			s+=_getstyles();
			s+='</div>';
			if(atts==''){
				$('#'+this.showview+'').html(s);
			}else{
				obj.append(s);
			}
			
			$('#changecancl_'+this.rand+'').click(function(){
				me._clickcancel();
			});
			$('#changereload_'+this.rand+'').click(function(){
				me._loaddata();
			});
			$('#changeok_'+this.rand+'').click(function(){
				me.queding();
			});
			$('#changesoubtn_'+this.rand+'').click(function(){
				me._searchkey(true);
			});
			$('#changekey_'+this.rand+'').keydown(function(e){
				me._searchkeys(e)
			});
			$('#changeboxs_'+this.rand+'').click(function(){
				me._changboxxuan(this)
			});
			$('#changesel_'+this.rand+'').change(function(){
				me._changesel(this)
			});
			if(type=='checkbox'){
				$('#yixuanbtn_'+rand+'').click(function(){
					$('#yixuan_'+rand+'').toggle();
				});
				this._initmrvel();
			}
			rchanguserclick=function(o1){
				me._changexuan(o1);
			}
			rchanguserquxiao=function(o1){
				me._changequxiao(o1);
			}
		};
		this.showlist=function(pid,oi){
			var type=this.inputtype,hw=24;
			var s='',ssu='',s1='';
			var sel = this.changesel;
			var dob = this.changetype.indexOf('dept')==-1;
			var uob = this.changetype.indexOf('user')>=0;
			this.fid = 1;
			
			if(sel=='1'){
				ssu = this._showuser(0,'',type,sel);
			}else if(sel=='2'){
				s   = this._showdept(pid,oi,s1,type,sel,dob,uob);
			}else if(sel=='3'){
				s 	= this._showgorup(type);
			}else{
				s1='<div style="width:'+(hw*oi)+'px"></div>';
				s 	= this._showdept(pid,oi,s1,type,sel,dob,uob);
				if(uob){
					ssu+=this._showuser(pid,s1,type,sel);
				}
			}
			var xud = (oi==0)?'0' : pid;
			$('#showdiv'+rand+'_'+xud+'').html(ssu+s).attr('show','true');
			if(sel==''){
				if(oi==0)this.showlist(this.fid, 1);
				$('#showdiv'+rand+'_0 [deptxu]').unbind('click').click(function(){
					me._deptclicks(this);
				});
			}
			if(sel=='3'){
				$('#showdiv'+rand+'_0 [groupxu]').unbind('click').click(function(){
					me._groupclicks(this);
				});
			}
		};
		this._showuser=function(pid,s1,type,sel){
			var a,len,i,ssu='',dids,zoi=0,ids,chk='';
			a=this.userarr;
			len=a.length;
			for(i=0;i<len;i++){
				dids = ','+a[i].deptids+',';
				if(
				((a[i].deptid==pid || dids.indexOf(','+pid+',')>-1 || sel=='1') && sel!='3')
				||
				(sel=='3' && (','+a[i].groupname+',').indexOf(','+pid+',')>-1)//显示组下人员
				){
					chk='';
					if(get('show'+rand+'_u'+a[i].id+''))chk='checked ';
					ssu+='<div class="listsss">';
					ssu+='<table style="background:none" width="100%"><tr><td>'+s1+'</td><td width="100%"><img align="absmiddle" height="24" height="24" src="'+a[i].face+'">&nbsp;'+a[i].name+'<span style="font-size:12px;color:#888888">('+a[i].ranking+')</span></td><td><input name="changeuserinput_'+rand+'" xxu="'+i+'" xls="u" xname="'+a[i].name+'" value="'+a[i].id+'" style="width:18px;height:18px;" '+chk+'onclick="rchanguserclick(this)" type="'+type+'"></td></tr></table>';
					ssu+='</div>';
					zoi++;
					if(zoi>=200)break;
				}
			}
			return ssu;
		};
		this._showdept=function(pid,oi,s1,type,sel,dob,uob){
			var a,len,i,wwj,s2='',s='';
			a=this.deptarr;
			len=a.length;
			for(i=0;i<len;i++){
				if(a[i].pid==pid || sel=='2'){
					this.fid = a[i].id;
					wjj= 'images/files.png';
					if(a[i].ntotal=='0' && uob)wjj= 'images/file.png';
					s2 = '<input name="changeuserinput_'+rand+'" xls="d" xname="'+a[i].name+'" xu="'+i+'" value="'+a[i].id+'" style="width:18px;height:18px;" onclick="rchanguserclick(this)" type="'+type+'">';
					if(dob)s2='';
					if(s2!='' && !this._isdeptcheck(a[i]))s2='';
					s+='<div class="listsss">';
					s+='<table style="background:none" width="100%"><tr><td>'+s1+'</td><td deptxu="'+i+'_'+oi+'" width="100%"><img align="absmiddle" height="20" height="20" src="'+wjj+'">&nbsp;'+a[i].name+'</td><td>'+s2+'</td></tr></table>';
					s+='</div>';
					s+='<span show="false" id="showdiv'+rand+'_'+a[i].id+'"></span>';
				}
			}
			return s;
		};
		this._showgorup=function(type){
			var a,len,i,ssu='',s1;
			a=this.grouparr;
			len=a.length;
			for(i=0;i<len;i++){
				s1  = '<input name="changeuserinput_'+rand+'" xls="g" xname="'+a[i].name+'" value="'+a[i].id+'" style="width:18px;height:18px;" onclick="rchanguserclick(this)" type="'+type+'">';
				if(this.changetype.indexOf('deptuser')==-1)s1='';
				ssu+='<div class="listsss">';
				ssu+='<table style="background:none" width="100%"><tr><td></td><td groupxu="'+i+'" width="100%"><img align="absmiddle" height="24" height="24" src="images/group.png">&nbsp;'+a[i].name+' <span style="font-size:12px;color:#888888">('+a[i].usershu+'人)</span></td><td>'+s1+'</td></tr></table>';
				ssu+='</div>';
				ssu+='<span show="false" id="showgroup'+rand+'_'+a[i].id+'"></span>';
			}
			return ssu;
		};
		this._groupclicks=function(o){
			if(this.changetype.indexOf('user')==-1)return;
			var sxu = $(o).attr('groupxu');
			var a 	= this.grouparr[sxu];
			var o1	= $('#showgroup'+rand+'_'+a.id+'');
			var lx	= o1.attr('show');
			if(lx=='false'){
				var s1='<div style="width:24px"></div>';
				var s = this._showuser(a.id,s1,this.inputtype, this.changesel);
				o1.html(s).attr('show','true');
			}else{
				o1.toggle();
			}
		};
		this._changexuan=function(o1){
			if(this.inputtype!='checkbox')return;
			var o = $(o1),xls=o.attr('xls'),val=o.val();
			var sid = 'show'+rand+'_'+xls+''+val+'';
			if(o1.checked){
				var str='<span id="'+sid+'" xls="'+xls+'" xvl="'+val+'" onclick="rchanguserquxiao(this)">';
				str+='<input name="yixuancheck_'+rand+'" value="'+val+'" align="absmiddle" xls="'+xls+'" type="checkbox" checked xxu="" xname="'+o.attr('xname')+'">'+o.attr('xname')+'';
				str+='</span>';
				if(!get(sid))$('#yixuan_'+rand+'').append(str);
			}else{
				$('#'+sid+'').remove();
			}
		};
		this._changequxiao=function(o1){
			var o = $(o1),xls=o.attr('xls'),xvl=o.attr('xvl');
			o.remove();
			$("input[name='changeuserinput_"+rand+"'][xls='"+xls+"'][value='"+xvl+"']").attr('checked', false);
		};
		this._initmrvel=function(){
			var sid ='',sna='',i,str='',xls,val;
			if(this.idobj)sid=this.idobj.value;
			if(this.nameobj)sna=this.nameobj.value;
			if(!sid || !sna)return;
			var sida = sid.split(','),snaa=sna.split(',');
			var ob1=this.changetype.indexOf('dept')>-1,ob2=this.changetype.indexOf('user')>-1;
			for(i=0;i<sida.length;i++){
				xls=sida[i].substr(0,1);
				if(!isNaN(xls)){
					if(ob1)xls='d';
					if(ob2)xls='u';
				}
				val=sida[i].replace('u','').replace('g','').replace('d','');
				str+='<span id="show'+rand+'_'+xls+''+val+'" xls="'+xls+'" xvl="'+val+'" onclick="rchanguserquxiao(this)">';
				str+='<input name="yixuancheck_'+rand+'" value="'+val+'" align="absmiddle" xls="'+xls+'" type="checkbox" checked xxu="" xname="'+snaa[i]+'">'+snaa[i]+'';
				str+='</span>';
			}
			$('#yixuan_'+rand+'').html(str);
		};
		this._changesel=function(o1){
			var val = o1.value;
			this.changesel = val;
			this.showlist(this.firstpid, 0);
		};
		this._searchkeys=function(e){
			clearTimeout(this._searchkeystime);
			this._searchkeystime=setTimeout(function(){
				me._searchkey(false);
			},500);
		};
		this._isdeptcheck=function(a){
			if(this.inputtype=='checkbox' && this.changetype.indexOf('user')>=0 && this.changetype.indexOf('dept')>=0){
				var stotal,i,nstotal=0,len=this.userarr.length,spath;
				stotal = parseFloat(a.stotal);
				for(i=0;i<len;i++){
					spath = this.userarr[i].deptpath;
					if(spath.indexOf('['+a.id+']')>=0)nstotal++;
				}
				return nstotal>=stotal;
			}else{
				return true;
			}
		},
		this._clickcheckbox=function(o1){
			var o = $(o1),xu,a,stotal,i,nstotal=0,len=this.userarr.length,spath;
			if(o.attr('xls')!='d')return;
			xu = parseFloat(o.attr('xu'));
			a  = this.deptarr[xu];
			stotal = parseFloat(a.stotal);
			for(i=0;i<len;i++){
				spath = this.userarr[i].deptpath;
				if(spath.indexOf('['+a.id+']')>=0)nstotal++;
			}
			if(nstotal<stotal){
				o1.checked=false;
				o1.disabled=true;
				js.msg('msg','无权选择部门['+a.name+']');
			}
		},
		this._searchkey = function(bo){
			var key = $('#changekey_'+this.rand+'').val(),s='',a=[],d=[],len,i;
			a=this.userarr;
			len=a.length;
			if(key!='')for(i=0;i<len;i++)if(a[i].name.indexOf(key)>-1 || a[i].pingyin.indexOf(key)==0 || a[i].deptname.indexOf(key)>-1 || a[i].ranking.indexOf(key)>-1){a[i].xu=i;d.push(a[i])};
			len = d.length;
			for(i=0;i<len;i++){
				s+='<div class="listsss">';
				s+='<table style="background:none" width="100%"><tr><td></td><td width="100%"><img align="absmiddle" height="24" height="24" src="'+d[i].face+'">&nbsp;'+d[i].name+'<span style="font-size:12px;color:#888888">('+d[i].ranking+')</span></td><td><input name="changeuserinput_'+rand+'_soukey" xxu="'+d[i].xu+'" xls="u" xname="'+d[i].name+'" value="'+d[i].id+'" style="width:18px;height:18px;" onclick="rchanguserclick(this)" type="'+this.inputtype+'"></td></tr></table>';
				s+='</div>';
			}
			if(bo && s=='' && key!='')js.msg('msg','无相关['+key+']的记录', 2);
			$('#showdiv'+rand+'_search').html(s);
			var o1 = $('#showdiv'+rand+'_0');
			if(s==''){o1.show();}else{o1.hide();}
		};
		this._clickcancel=function(){
			if(!this.oveob)history.back();
			this.hide();
		};
		this.hide=function(){
			$('#changeuser_'+rand+'').remove();
			this.oncancel();
		};
		this.show=function(){
			this.creatediv();
			if(this.deptarr.length>0){
				this.firstpid = this.deptarr[0].pid;
				this.showlist(this.firstpid,0);
			}else{
				this._loaddata();
			}
		};
		this._deptclicks=function(o){
			var sxu = $(o).attr('deptxu').split('_');
			var a 	= this.deptarr[sxu[0]];
			var o1	= $('#showdiv'+rand+'_'+a.id+'');
			var lx	= o1.attr('show');
			if(lx=='false'){
				this.showlist(a.id, parseFloat(sxu[1])+1);
			}else{
				o1.toggle();
			}
		};
		
		this._loaddata=function(){
			var o1 = $('#showdiv'+rand+'_0'),url;
			o1.html('<div align="center" style="padding:30px"><img src="images/mloading.gif"></div>');
			var url = 'index.php?a=deptuserjson&m=dept&d=system&ajaxbool=true&changerange='+this.changerange+'&changerangeno='+this.changerangeno+'&gtype=change';
			$.getJSON(url, function(ret){
				if(ret.code==200){
					ret = ret.data;
					me._loaddatashow(ret);	
				}else{
					o1.html(ret.msg);
				}
			});
		};
		this._loaddatashow=function(ret){
			if(isempt(this.changerange) && isempt(this.changerangeno)){
				js.setoption('deptjson', ret.deptjson);
				js.setoption('userjson', ret.userjson);
				js.setoption('groupjson', ret.groupjson);
			}
			this.userarr = js.decode(ret.userjson);
			this.deptarr = js.decode(ret.deptjson);
			this.grouparr = js.decode(ret.groupjson);
			this.firstpid = 0;
			if(this.deptarr[0])this.firstpid = this.deptarr[0].pid;
			this.showlist(this.firstpid, 0);
		};
		this._changboxxuan=function(os){
			var ns= 'changeuserinput_'+rand+'';
			if($('#showdiv'+rand+'_search').html()!='')ns+='_soukey';
			var ob = os.checked,o=$("input[name='"+ns+"']"),i;
			for(i=0;i<o.length;i++)o[i].checked=ob;
		};
		this.queding=function(){
			var ns= 'changeuserinput_'+rand+'';
			if($('#showdiv'+rand+'_search').html()!='')ns+='_soukey';
			if(this.inputtype=='checkbox')ns='yixuancheck_'+rand+'';
			var o = $("input[name='"+ns+"']");
			var i,len=o.length,o1,xls,xna,xal,xxu,sid='',sna='',ob1=this.changetype.indexOf('dept')==-1,ob2=this.changetype.indexOf('user')==-1,xzarr=[];
			var ob3=ob1 || ob2;
			for(i=0;i<len;i++){
				o1 = $(o[i]);
				if(o[i].checked){
					xls= o1.attr('xls');
					xna= o1.attr('xname');
					xxu= o1.attr('xxu');
					xal= o1.val();
					if(ob3)xls='';
					sid+=','+xls+''+xal+'';
					sna+=','+xna+'';
					if(!isempt(xxu))xzarr.push(this.userarr[parseFloat(xxu)]);
				}
			}
			if(sid!=''){
				sid=sid.substr(1);
				sna=sna.substr(1);
			}
			if(this.idobj)this.idobj.value=sid;
			if(this.nameobj){
				this.nameobj.value=sna;
				this.nameobj.focus();
			}
			if(!this.oveob)history.back();
			this.onselect(sna, sid, xzarr);
			this.hide();
		}
	}
	
	function selectdata(sobj, options){
		var obj		= sobj;
		var rand	= ''+parseInt(Math.random()*9999999)+''; 
		var me		= this;
		this.rand	= rand;
		this.ismobile = false;
		this.selvalue = '';
		
		this._init	= function(){
			for(var i in options)this[i]=options[i];
			if(typeof(ismobile) && ismobile==1)this.ismobile = true;
			this._showcreate();
		};
		this._showcreate = function(){
			var ws = '350px';
			if(this.ismobile)ws='90%';
			var cold = window['maincolor'];if(!cold)cold='#1389D3';
			var s='<div style="width:100%;height:100%;overflow:hidden;left:0px;top:0px; background:rgba(0,0,0,0.3);position:fixed;z-index:11" id="selectdata_'+rand+'">';
			s+='<div tsid="main" id="mints_'+rand+'" style="position:absolute;top:30%; background:white;width:'+ws+';box-shadow:0px 0px 10px rgba(0,0,0,0.3);border-radius:5px">';
			s+='	<div onmousedown="js.move(\'mints_'+rand+'\')" style="line-height:50px;color:'+cold+';font-size:16px;border-bottom:1px #eeeeee solid;font-weight:bold;"> &nbsp; &nbsp;'+this.title+'</div>';
			s+='	<div style="height:40px;overflow:hidden;border-bottom:1px #cccccc solid;"><table width="100%" style="background:none"><tr><td><select id="selxuan_'+this.rand+'" style="width:120px;border:none;background:none;display:none"><option value="">选择所有</option></select></td><td width="100%" height="40"><input id="changekey_'+this.rand+'" placeholder="搜索关键词" style="height:30px;border:none;background:none;width:100%;margin:0px 10px;outline:none"></td><td><button style="background:none;color:#666666;" class="changeuserbotton" id="changesoubtn_'+this.rand+'" type="button" >查找</button></td></tr></table></div>';
			s+='	<div style="-webkit-overflow-scrolling:touch;height:300px;overflow:auto; background:#f1f1f1" id="selectlist_'+rand+'" class="changeuserlist"></div>';
			s+='	<div style="height:50px;line-height:50px;border-top:1px #cccccc solid;" align="right"><table width="100%" style="background:none"><tr><td width="10" nowrap>&nbsp;</td><td width="80%"><font color="#888888" tsid="count"></font></td><td><button type="button" id="changereload_'+rand+'" class="changeuserbotton">刷新</button></td><td width="10" nowrap>&nbsp;</td><td><button class="changeuserbotton" type="button" id="changecancl_'+rand+'">取消</button></td><td width="10" nowrap>&nbsp;</td><td height="50"><button style="background:'+cold+';" id="changeok_'+rand+'" type="button" class="changeuserbotton">确定</button></td><td width="10" nowrap>&nbsp;</td></tr></table></div>';
			s+='</div>';
			s+='</div>';
			s+=_getstyles();
			$('body').append(s);
			this.showdata(this.data);
			var o = this._getobj('main');
			var l = ($(window).width()-o.width())*0.5,t = ($(window).height()-o.height())*0.5;
			o.css({'left':''+l+'px','top':''+t+'px'});
			$('#changecancl_'+this.rand+'').click(function(){
				me._clickcancel();
			});
			$('#changeok_'+this.rand+'').click(function(){
				me.queding();
			});
			$('#changereload_'+this.rand+'').click(function(){
				me.reload();
			});
			$('#changesoubtn_'+this.rand+'').click(function(){
				me._searchkey(true);
			});
			$('#changekey_'+this.rand+'').keydown(function(e){
				me._searchkeys(e)
			});
			$('#changekey_'+this.rand+'').keyup(function(e){
				me._searchkeys(e)
			});
		};
		this._getobj=function(lx){
			var o = $('#selectdata_'+rand+'').find("[tsid='"+lx+"']");
			return o;
		};
		this._clickcancel=function(){
			this.hide();
		};
		this.hide=function(){
			$('#selectdata_'+rand+'').remove();
			this.oncancel();
		};
		this.queding=function(){
			var ns= 'changeuserinput_'+rand+'';
			var o = $("input[name='"+ns+"']");
			var i,len=o.length,o1,xna,xu,xal,sid='',sna='',seld=[];
			for(i=0;i<len;i++){
				o1 = $(o[i]);
				if(o[i].checked){
					xna= o1.attr('xname');
					xu = parseFloat(o1.attr('xu'));
					if(this.checked){
						seld.push(this.nowdata[xu]);
					}else{
						seld=this.nowdata[xu];
					}
					xal= o1.val();
					sid+=','+xal+'';
					sna+=','+xna+'';
				}
			}
			if(sid!=''){
				sid=sid.substr(1);
				sna=sna.substr(1);
			}
			if(this.idobj)this.idobj.value=sid;
			if(this.nameobj){
				this.nameobj.value=sna;
				this.nameobj.focus();
			}
			this.onselect(seld,sna, sid);
			this.hide();
		};
		this.showdata=function(a,inb){
			if(!a)a=[];
			this.showselectdata(a.selectdata);
			if(a.rows)a = a.rows;
			var s='',len=a.length,s1='';
			if(len==0){
				s='<div align="center" style="margin-top:30px;color:#cccccc;font-size:16px">无记录</div>';
			}else{
				s = this.showhtml(a);
				s1='共'+len+'条';
			}
			this._getobj('count').html(s1);
			var o = $('#selectlist_'+rand+'');
			o.html(s);
			if(!inb && len==0)this.loaddata();
		};
		this.seldatsse=[];
		this.showselectdata=function(da){
			if(this.showselbo || !da)return;
			var o = get('selxuan_'+this.rand+'');
			js.setselectdata(o,da,'value');
			if(da.length>0){
				this.showselbo=true;
			}
			$(o).change(function(){
				me._changeselval(this);
			}).show();
		};
		this._changeselval=function(o){
			this.selvalue = o.value;
			this.loaddata(o.value);
		};
		this.showhtml=function(a,ks){
			this.nowdata = a;
			var i,len=a.length,s='',s2,s1='',atr,oldvel='',d;
			if(this.nameobj)oldvel=this.nameobj.value;
			if(this.idobj)oldvel=this.idobj.value;
			var type='checkbox',ched='';
			if(!ks)ks=0;
			if(!this.checked)type='radio';
			oldvel = ','+oldvel+',';
			for(i=ks;i<len && i<this.maxshow+ks;i++){
				ched='';
				d = a[i];
				if(!isempt(d.value) && oldvel.indexOf(','+d.value+',')>-1)ched='checked';
				if(d.disabled)ched+=' disabled';
				s2 = '<input xu="'+i+'" '+ched+' name="changeuserinput_'+rand+'" xname="'+d.name+'" value="'+d.value+'" style="width:18px;height:18px;" align="absmiddle" type="'+type+'">';
				atr = '';
				if(d.padding)atr='style="padding-left:'+d.padding+'px"';
				if(!d.iconswidth)d.iconswidth=18;
				if(d.iconsimg)s2+=' <img align="absmiddle" src="'+d.iconsimg+'" height="'+d.iconswidth+'" width="'+d.iconswidth+'">';
				s+='<div class="listsss" '+atr+'><label>'+s2+'&nbsp;'+d.name+'';
				if(d.subname)s+='&nbsp;<span style="font-size:12px;color:#888888">('+d.subname+')</span>';
				s+='</label></div>';
			}
			if(len>i){
				s+='<div align="center" id="moreadd_'+rand+'" style="padding:10px;font-size:12px;color:#aaaaaa;cursor:pointer">还有'+(len-i)+'条，点我加载</div>';
				setTimeout(function(){$('#moreadd_'+rand+'').click(function(){me.moregegd(i);});},10);
			}
			return s;
		};
		this.moregegd=function(i1){
			$('#moreadd_'+rand+'').remove();
			var s = this.showhtml(this.nowdata, i1);
			if(s)$('#selectlist_'+rand+'').append(s);
		};
		this.reload=function(){
			this.loaddata(this.selvalue);
		};
		this.loaddata=function(svel){
			var url = this.url;
			if(svel)url+='&selvalue='+svel+'';
			if(url=='')return;
			$('#selectlist_'+rand+'').html('<div align="center" style="margin-top:30px"><img src="images/mloading.gif"></div>');
			$.ajax({
				type:'get',dataType:'json',url:url,
				success:function(ret){
					me.data = ret;
					me.onloaddata(ret);
					me.showdata(ret, true);
				},
				error:function(e){
					$('#selectlist_'+rand+'').html('加载错误：<br><textarea style="width:95%;height:60%;color:red">'+e.responseText+'</textarea>');
				}
			});
		};
		this._searchkeys=function(e){
			clearTimeout(this._searchkeystime);
			this._searchkeystime=setTimeout(function(){
				me._searchkey(false);
			},500);
		};
		this._searchkey = function(bo){
			var key = $('#changekey_'+this.rand+'').val(),a=[],d=[],d1,len,i,oi=0,s;
			a=this.data;
			if(a.rows)a=a.rows;
			len=a.length;if(len==0)return;
			if(key)key = key.toLowerCase();
			if(key!='')for(i=0;i<len;i++){
				d1 = a[i];
				if(d1.name.indexOf(key)>-1 || d1.name.toLowerCase().indexOf(key)>-1 || d1.value==key || (d1.subname && d1.subname.indexOf(key)>-1)){
					d.push(d1);
					oi++;
					if(oi>20)break;//最多显示搜索
				}
			}
			len = d.length;
			if(len==0){
				if(key){
					s='<div align="center" style="margin-top:30px;color:#cccccc;font-size:16px">无相关['+key+']的记录</div>';
				}else{
					s=this.showhtml(a);
				}
			}else{
				s=this.showhtml(d);
			}
			$('#selectlist_'+rand+'').html(s);
			if(bo && len==0 && key!='')js.msg('msg','无相关['+key+']的记录', 2);
		};
	}
	
	$.fn.chnageuser	= function(options){
		var defaultVal = {
			'title' : '请选择...',
			'titlebool':true,
			'showview':'',
			'changerange':'', //从哪些人员中选择
			'changerangeno':'', //不从哪些人选择
			'changetype' : 'user',
			'idobj':false,'nameobj':false,
			'onselect':function(){},
			'oncancel':function(){}
		};
		var can = $.extend({}, defaultVal, options);
		var funcls = new chnageuser($(this), can);
		funcls._init();
		return funcls;
	};
	
	$.selectdata	= function(options){
		var defaultVal = {
			'showview': '',
			'title' : '请选择...',
			'maxshow' : 100, //最多显示防止卡死浏览器
			'data'	  : [], 'url' : '',
			'checked' : false,
			'idobj'	  : false, 'nameobj':false,
			'onselect': function(){},
			'oncancel': function(){},
			'onloaddata':function(){}
		};
		var can = $.extend({}, defaultVal, options);
		var funcls = new selectdata(false, can);
		funcls._init();
		return funcls;
	};
	
})(jQuery);