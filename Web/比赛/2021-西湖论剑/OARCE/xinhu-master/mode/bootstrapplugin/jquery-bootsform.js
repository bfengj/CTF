/**
*	bootstable 表单录入插件
*	caratename：雨中磐石(rainrock)
*	caratetime：2014-04-06 21:40:00
*	email:admin@rockoa.com
*	homepage:www.rockoa.com
*/


(function ($) {
	
	function bootsform(element, options){
		var obj		= element;
		var can		= options;
		var rand	= can.rand; 
		var me		= this;
		this.form	= null;
		this.editdata = {};
		this.itemsdata = {};
		
		this.init	= function(){
			if(rand=='')rand=js.getrand();
			if(!can.window)return;
			can.windowid = 'window_'+rand+'';
			var s = this._create();
			js.tanbody(can.windowid, can.title, can.width, can.height,{bbar:'none',html:s,titlecls:can.saveCls});
			can.render	= ''+can.windowid+'_body';
			obj = $('#'+can.render+'');
			this.createafter();
			$('#cancel_'+rand+'').focus();
		};
		this.forminit = function(){
			this.createafter();
		};
		this.createafter	 = function(){
			this.form	= document['form_'+rand+''];
			var saveid = can.saveid;
			if(saveid=='')saveid = 'save_'+rand+'';
			$('#'+saveid+'').click(function(){
				me._save(this, 0);
			});
			$('#cancel_'+rand+'').click(function(){
				me._cancel(this);
			});
			var a = can.items,b=[],i;
			if(can.requiredfields!='')b = can.requiredfields.split(',');
			for(i=0; i<a.length; i++){
				if(a[i].required)b.push(a[i].name);
			}
			for(i=0; i<b.length; i++){
				$(this.form[b[i]]).change(function(){
					me.isValid();
				});
				$(this.form[b[i]]).blur(function(){
					me.isValid();
				});
			}		
			if(obj){
				obj.find("button[changeuser]").click(function(){
					me._changeuser($(this));
					return false;
				});
				obj.find("button[changeclear]").click(function(){
					me._changeclear($(this));
					return false;
				});
				obj.find("button[changedate]").click(function(){
					me._changedate($(this));
					return false;
				});
			}
		};
		this._changeuser = function(o){
			var cn = o.attr('changeuser');
			var ssa 	= this.itemsdata[cn].changeuser;
			if(!ssa)ssa={};
			ssa.nameobj = this.form[cn];
			if(ssa.idname){
				ssa.value = this.getValue(ssa.idname);
				ssa.idobj = this.form[ssa.idname];
			}
			js.getuser(ssa);
		};
		this._changedate  = function(o){
			var viev = o.attr('changedate'),atr='',
				inpu = o.attr('inputid');
			var cans = {view:viev,inputid:inpu,initshow:true};
			o.rockdatepicker(cans);
		};
		this._changeclear = function(o){
			var cn  = o.attr('changeclear');
			var ssa = this.itemsdata[cn].changeuser;
			if(!ssa)ssa={};
			if(ssa.idname){
				this.setValue(ssa.idname, '');
			}
			this.setValue(cn, '');
		};
		this._create = function(){
			var s = '',a=can.items,i,i1,style='padding:10px';
			if(can.bodyheight!=0)style+=';height:'+can.bodyheight+'px';
			s+='<div align="center"  style="'+style+';overflow-y:auto" >';
			s+='<form id="form_'+rand+'" name="form_'+rand+'" style="padding:0px;maring:0px">';
			s+='<input name="id" value="0" type="hidden">';
			s+='<table width="'+can.bodywidth+'">';
			for(i=0; i<a.length; i++){
				can.items[i]	= js.applyIf(a[i], can.defaultfields);
				a[i]			= can.items[i];
				this.itemsdata[a[i].name]  = a[i];
				var bl=a[i].blankText,bt='',attr = a[i].attr;
				if(!bl)bl='';if(!attr)attr='';
				if(a[i].required)bt='<font color="red">*</font>';
				if(a[i].readOnly)attr+=' readonly';
				if(a[i].repEmpty)attr+=' onblur="this.value=strreplace(this.value)"';
				if(a[i].type=='number')attr+=' onfocus="js.focusval=this.value" onblur="js.number(this)"';
				var inp = '<input placeholder="'+bl+'" '+attr+' type="'+a[i].type+'" value="'+a[i].value+'" name="'+a[i].name+'" class="form-control">';
				if(a[i].type=='checkbox'){
					if(a[i].checked)attr+=' checked';
					inp = '<label><input name="'+a[i].name+'" '+attr+'  value="1" type="checkbox"> '+a[i].labelBox+'</label>';
				}else if(a[i].type=='textarea'){
					inp = '<textarea placeholder="'+bl+'" '+attr+' name="'+a[i].name+'" class="form-control" style="height:'+a[i].height+'px">'+a[i].value+'</textarea>';
				}else if(a[i].type=='select'){
					inp	= '<select name="'+a[i].name+'" class="form-control">';
					var sto = a[i].store;
					for(i1=0;i1<sto.length;i1++){
						inp+='<option value="'+sto[i1][a[i].valuefields]+'">'+sto[i1][a[i].displayfields]+'</option>';
					}
					inp += '</select>';
				}else if(a[i].type=='changeuser'){
					inp	= '<div class="input-group"><input placeholder="'+bl+'" readonly class="form-control" name="'+a[i].name+'" >';
					inp+= '<span class="input-group-btn">';
					if(a[i].clearbool)inp+= '<button class="btn btn-default" changeclear="'+a[i].name+'" type="button"><i class="icon-remove"></i></button>';
					inp+= '<button class="btn btn-default" changeuser="'+a[i].name+'" type="button"><i class="icon-search"></i></button>';
					inp+= '</span></div>';
				}else if(a[i].type=='date'){
					inp	= '<div class="input-group"><input readonly class="form-control" id="'+a[i].name+'-'+rand+'-inputid" name="'+a[i].name+'" >';
					inp+= '<span class="input-group-btn">';
					inp+= '<button class="btn btn-default" '+attr+' changedate="'+a[i].view+'" inputid="'+a[i].name+'-'+rand+'-inputid" type="button"><i class="icon-calendar"></i></button>';
					inp+= '</span></div>';	
				}else if(a[i].type=='html'){
					inp = a[i].html;
				}
				if(a[i].type == 'hidden'){
					s+='<tr><td></td><td>'+inp+'</td></tr>';
				}else{
					s+='<tr na="'+a[i].name+'">';
					s+='<td align="'+can.labelAlign+'" style="padding-right:5px" width="'+can.labelWidth+'">'+bt+''+a[i].labelText+'</td>';
					s+='<td style="padding:5px" align="left">'+inp+'</td>'
					s+='</tr>';
				}
			}
			s+='</table>';
			s+='</form>';
			s+='</div>';
			s+='<div style="padding:8px 10px;background:#eeeeee;line-height:30px" align="right"><span id="msgview_'+rand+'"></span>&nbsp;';
			s+='	<button type="button" class="btn btn-'+can.saveCls+'" disabled id="save_'+rand+'"><i class="icon-save"></i>&nbsp;'+can.saveText+'</button>';
			if(can.cancelbtn)s+='&nbsp; <button type="button" class="btn btn-default" id="cancel_'+rand+'"><i class="icon-remove"></i>&nbsp;取消</button>';
			s+='</div>';
			return s;
		};
		this.setVisited=function(na, bo){
			var o = obj.find("tr[na='"+na+"']");
			if(bo){o.show();}else{o.hide();}
		};
		this.isValid  = function(){
			var bo= false,o;
			var a = can.items,s='';
			for(i=0; i<a.length; i++){
				if(a[i].required){
					if(isempt(this.getValue(a[i].name))){
						bo  = true;
						s	= a[i].tipText;
						if(!s)s=''+a[i].labelText+'不能为空';
						break;
					}
				}
			}
			if(!bo && can.requiredfields!=''){
				a = can.requiredfields.split(',');
				for(i=0; i<a.length; i++){
					if(isempt(this.getValue(a[i]))){
						bo = true;
						s = '*是必填的';
						break;
					}	
				}
			}
			o = get('save_'+rand+'');
			if(o){
				o.disabled=bo;
				o.title=s;
			}
			this.isValidText = s;
			return bo;
		};
		
		this._cancel	= function(o1){
			if(can.windowid!='')js.tanclose(can.windowid);
		};
		this.close 		= function(){
			this._cancel();
		};
		this.save = function(o1){
			this._save(o1, 1);
		};
		this.signature= function(da, url){
			var time = parseInt(js.now('time')*0.001);
			var siaa = ''+NOWURL+''+url+''+da.tablename_postabc+''+time+'_'+adminid+'';
			var sign = md5(siaa);
			da.sys_signature= sign;
			da.sys_timeature= time;
			return da;
		};
		this._save	= function(o1, lx){
			if(this.isValid()){
				this.setmsg(this.isValidText);
				return;
			}
			var data = this.getValues(),ac,ebo=false,s,i;
			var fids = can.submitfields.split(',');
			for(i=0;i<fids.length;i++){
				ac = fids[i];
				if(data[ac]!=this.editdata[ac]){
					ebo = true;
					break;
				}
			}
			if(!ebo && lx == 0 && can.pdedit){
				//this.setmsg('数据没修改，不用保存','#F92FB6');
				//return;
			};
			s	= can.submitcheck(data, me);
			if(typeof(s)=='string' && s){
				this.setmsg(s);
				return;
			}
			this.setmsg('处理中...','#ff6600');
			for(ac in can.params)data[ac]=can.params[ac];
			if(typeof(s)=='object'){
				for(ac in s)data[ac]=s[ac];
			}
			data.tablename_postabc 		= jm.encrypt(can.tablename);
			data.submitfields_postabc 	= jm.base64encode(can.submitfields);
			data.aftersaveaction 	= can.aftersaveaction;
			data.beforesaveaction 	= can.beforesaveaction;
			data.editrecord_postabc = can.editrecord;
			data.sysmodenumabc 		= can.modenum;
			o1.disabled = true;
			$.ajax({
				type:'post',
				url:can.url,
				data:this.signature(data, can.url),
				success:function(da){
					var a = js.decode(da);
					if(a.success){
						me.setmsg(a.msg, 'green');
						if(can.autoclose){
							js.msg('success', a.msg);
							me._cancel();
						}	
						can.success(a, me);
					}else{
						o1.disabled = false;
						me.setmsg(a.msg);
						if(!a.msg)js.msg('msg', da);
						can.error();
					}
				},
				error:function(e){
					o1.disabled = false;
					me.setmsg('Error:'+e.responseText+'');
					can.error();
				}
			});
		};
		this.getValue	= function(na){
			var o = this.form[na];
			if(!o)return '';
			return o.value;
		};
		this.setValue	= function(na,val){
			var o = this.form[na];
			if(!o)return;
			o.value	= val;
		};
		this.setUrl = function(url){
			can.url = url;
		};
		this.setmsg	= function(txt,col){
			if(!col)col='red';
			var msgid = can.msgviewid;
			if(msgid == '')msgid='msgview_'+rand+'';
			$('#'+msgid+'').html(js.getmsg(txt,col));
		};
		this.getValues=function(){
			var da ={},i,ona='',o;
			for(i=0;i<this.form.length;i++){
				o = this.form[i];
				var type = o.type, 
					val	 = o.value,
					na 	 = o.name;
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
					da[onas[i].replace('[]','')]	= js.getchecked(onas[i]);
				}
			}
			return da;
		};
		this.getType	= function(na){
			var o = this.form[na];
			if(!o)return '';
			return a.type;
		};
		this.setValues=function(da, otf){
			var na,type,v,o,i,
			fis = can.submitfields.split(',');
			if(otf){
				var otfs = otf.split(',');
				for(i=0; i<otfs.length; i++)fis.push(otfs[i]);
			}
			for(i=0;i<fis.length;i++){
				na = fis[i];
				o  = this.form[na];
				if(!o)continue;
				type= o.type;
				v	= da[na];
				if(v==null)v='';
				da[na]	= v;
				if(type == 'checkbox'){
					o.checked=(v=='1')?true:false;
				}else{
					o.value = v;
				}
			}
			this.setValue('id', da.id);
			this.editdata = da;
			this.isValid();
		};
		this.reset=function(){
			this.form.reset();
		};
		this.getField=function(na){
			return this.form[na];
		};
		this.setTitle = function(tit){
			$('#'+can.windowid+'_title').html(tit);
		};
		
		this.load = function(url){
			this.setmsg('加载中...');
			$.get(url, function(da){
				var a = js.decode(da);
				me._loadback(a);
			});
		};
		
		this._loadback = function(a){
			can.load(a, this, this.form);
			if(a.data)this.setValues(a.data);
			this.setmsg('');
			can.loadafter(a,this.form);
		};
		
		this.setReadOnly = function(na){
			if(na){
				this.getField(na).readOnly = true;
			}else{
				for(var i=0;i<this.form.length;i++){
					this.form[i].readOnly = true;
				}
			}
		};
		this.setDisabled = function(na){
			if(na){
				this.getField(na).disabled = true;
			}else{
				for(var i=0;i<this.form.length;i++){
					this.form[i].disabled = true;
				}
			}				
		}
	};
	
	
	$.bootsform	= function(options){
		var defaultVal = {
			items:[],labelWidth:90,width:500,height:400,
			labelAlign:'right',saveCls:'primary',
			tablename:'', //对应表名
			modenum:'',  //对应模块编号
			url:js.getajaxurl('publicsave','index'),
			submitfields:'',autoclose:true,cancelbtn:true,
			params:{},bodywidth:'90%',addCls:'primary',editCls:'info',bodyheight:0,isedit:0,
			render:'',saveText:'确定',window:true,windowid:'',
			editrecord:'false', //是否保存修改记录
			defaultfields:{type:'text',blankText:'',labelText:'',required:false,readOnly:false,labelBox:'',attr:'',value:''},
			success:function(){},loadafter:function(){},
			load:function(){},
			aftersaveaction:'', //保存后处理方法
			beforesaveaction:'', //保存前处理方法
			requiredfields:'',
			error:function(){},saveid:'',msgviewid:'',rand:'',
			pdedit:true,
			submitcheck:function(){return ''}
		};
		var can = $.extend({}, defaultVal, options);
		if(can.isedit==0){
			can.saveCls = can.addCls;
			can.title	= '<i class="icon-plus"></i> 新增 '+can.title+'';
		}
		if(can.isedit==1){
			can.saveCls = can.editCls;
			can.title	= '<i class="icon-edit"></i> 编辑 '+can.title+'';
		}
		var clsa = new bootsform(false, can);
		clsa.init();
		return clsa;
	};
})(jQuery);