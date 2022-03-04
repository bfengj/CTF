/**
	rockbase 基础信息
*/	


var backautocloseupload = {};
(function ($) {
	

	function rockupload(element, options){
		var obj = element;
		var can = options;
		var me  = this,
			rand= 'sdh_'+js.getrand();
		this.init = function(){
			var s = '<div class="form-control" id="view_'+rand+'" style="height:'+can.height+'px;overflow:auto;padding:0px"></div><div><a onclick="return false" id="add_'+rand+'" href="javascript:"><i class="icon-plus"></i> '+can.uploadtext+'</a>&nbsp;<span id="count_'+rand+'"></span></div><input type="hidden"  id="fileid_'+rand+'" name="'+can.name+'">';
			obj.html(s);
			$('#add_'+rand+'').click(function(){
				me._upload();
			});
			this.loadfile();
		};
		
		this._upload= function(){
			if(!can.addbool)return;
			
			js.tanbody('uploadaction','上传文件',550,250,{
				html:'<iframe src="" name="uploadiframea" width="100%" height="250px" frameborder="0"></iframe>',bbar:'none'
			});
			var cans=can.uploadparams;
			cans.showid=rand;
			var url= js.upload('',cans,'url');
			uploadiframea.location.href=url;
			return false;
		};
		this.loadfile = function(mtype, mid){
			if(!mtype)mtype=can.mtype;
			if(!mid)mid=can.mid;
			if(!mtype || !mid || mid==0)return;
			var url = js.getajaxurl('getfile','upload','public',{mtype:mtype,mid:mid});
			var o = $('#view_'+rand+'');
			o.html('<div style="padding:10px"><img src="images/loading.gif" align="absmiddle">&nbsp;加载中...</div>');
			$.get(url, function(da){
				o.html('');
				var a = js.decode(da);
				js.downupshow(a,rand);
			});
		};
		this.removedel = function(){
			$('#view_'+rand+'').find("temp='dela'").remove();
		};
		this.idAdd = function(bo){
			can.addbool = bo;
		};
	};
	$.fn.rockupload	= function(options){
		var defaultVal = {
			name:'fileid',uploadtext:'添加文件',mtype:'',mid:0,height:80,delbool:true,addbool:true,
			uploadparams:{}
		};
		var can		= $.extend({}, defaultVal, options);
		var clsa 	= new rockupload($(this), can);
		clsa.init();
		return clsa;
	};
	
	
	//搜索工具条
	function rocksearch(element, options){
		var obj = element;
		var can = options;
		var me  = this,
			rand= js.getrand();
		this.luojiarr = [{
			name:'包含',value:'LIKE'
		},{
			name:'不包含',value:'NOT LIKE'
		},{
			name:'等于',value:'='
		},{
			name:'不等于',value:'!='
		},{
			name:'大于',value:'>'
		},{
			name:'大于等于',value:'>='
		},{
			name:'小于',value:'<'
		},{
			name:'小于等于',value:'<='
		}];	
		
		this.init = function(){
			
			if(can.listtable){
				this.createlisttable();
				return;
			}
			
			var s = '';
			s = '<div class="input-group" style="width:'+can.width+'px;">'+
				'<span class="input-group-btn">'+
				'	<a type="button" id="fields_'+rand+'" class="btn btn-default">字段 <span class="caret"></span></a>'+
				'	<a type="button" id="luoji_'+rand+'" class="btn btn-default">包含 <span class="caret"></span></a>'+
				'</span>'+
				'<input  class="form-control" id="key_'+rand+'" placeholder="关键词">'+
				'<input  class="form-control" style="display:none" readonly id="date_'+rand+'">'+
				'<span style="display:none" id="selectdivshoa_'+rand+'"></span>'+
				'<span class="input-group-btn">'+
				'	<button class="btn btn-default" style="display:none" id="datebtn_'+rand+'" type="button"><i class="icon-calendar"></i></button>'+
				'	<button class="btn btn-default" id="soubtn_'+rand+'" type="button"><i class="icon-search"></i> 查询 </button>';
				
			s+='	<button class="btn btn-default" id="soubtndown_'+rand+'" type="button"><i class="icon-caret-down"></i></button>';
			s+='	</span>';
			s+='</div>';
			obj.html(s);
			$('#soubtn_'+rand+'').click(function(){
				me._search(false);
			});
			
			$('#fields_'+rand+'').rockmenu({
				data:can.columns,top:35,width:150,
				itemsclick:function(d, oi){
					me.changefields(oi);
				}
			});
			
			$('#luoji_'+rand+'').rockmenu({
				data:this.luojiarr,top:35,width:100,
				itemsclick:function(d){
					me.changeluoji(d);
				}
			});
			
			var ds = [{name:'结果中查询',oi:0},{name:'高级查询...',oi:1}];
			if(!can.highsearch){
				ds = [{name:'(',val:'('},{name:')',val:')'},{name:'并且',val:'and'},{name:'或者',val:'or'}];
				$('#soubtn_'+rand+'').html('<i class="icon-plus"></i>');
			}
			$('#soubtndown_'+rand+'').rockmenu({
				data:ds,top:35,width:100,
				itemsclick:function(d){
					if(can.highsearch){
						if(d.oi==0)me._search(true);
						if(d.oi==1)me._highsearch();
					}else{
						me._showhighsearch(d.val, d.name, 0);
					}
				}
			});
			this.dateobj = $('#datebtn_'+rand+'').rockdatepicker({inputid:'date_'+rand+''});
			this.selobj	 = $('#selectdivshoa_'+rand+'').bootstigger({
				data:[],valuefields:'id',clearbool:true
			});
			this.changefields(0);
			this.changeluoji(this.luojiarr[0]);
		};
		this.oldkeysou = '';
		this._search = function(bo){
			var awhere = '',oper,key,fields,type;
			oper  = this.luojiobj.value;
			key	  = $('#key_'+rand+'').val();
			arr	  = this.fieldsobj;
			fields= arr.dataIndex;
			type  = arr.atype;
			if(!type)type='';
			if(type.indexOf('date')>-1)key = this.dateobj.getValue();
			if(type=='select')key = this.selobj.getValue();;
			var qz=arr.qz;
			if(isempt(qz))qz='';
			
			var keyss  = key+'',
				keyss1  = key+'';
			if(type=='select')keyss1=this.selobj.getRawValue();
			if(oper.indexOf('LIKE')>=0)key='%'+key+'%';
			key="[F]"+key+"[F]";
			if(can.highsearch)awhere='[K][A]';
			awhere+="[K]"+qz+"`"+fields+"`[K]"+oper+"[K]"+key+"[K]";
			if(arr.searchtpl){
				awhere = '[K][A][K]'+arr.searchtpl.replace('?0', oper);
				awhere = awhere.replace('?1', key);
			}
			if((oper.indexOf('LIKE')>=0 || oper=='=') && keyss =='')awhere='';
			if(bo)awhere = this.oldkeysou+awhere;
			this.oldkeysou	= awhere;
			var awhes = ''+arr.name+' '+this.luojiobj.name+' '+keyss1+'';
			can.backcall(awhere, awhes, this);
			return awhere;
		};
		this._highsearchstr	 = '';
		this._highsearch = function(){
			var s = '<div><div id="highsearch_list_tools"></div><div id="highsearch_list" style="height:180px;overflow:auto;">'+this._highsearchstr+'</div></div>';
			js.tanbody('highsearch','高级查询',450, 250,{html:s,btn:[{text:'查询',icons:'search'}]});
			var soutools=$('#highsearch_list_tools').rocksearch({
				columns:can.columns,width:448,highsearch:false,
				backcall:function(s, s1){
					if(s!='')me._showhighsearch(s, '&nbsp; &nbsp; '+s1, 1);
				}
			});
			$('#highsearch_btn0').click(function(){
				me._highsearchok();
			});
		};
		this._highsearchok	 = function(){
			var o = $('#highsearch_list');
			this._highsearchstr = o.html();
			var o1 = o.find('font'),s= '',i,v;
			for(i=0; i<o1.length; i++){
				v = o1[i].innerHTML;
				v = v.replace('&lt;','<');
				v = v.replace('&gt;','>');
				s+=''+v;
			}
			can.backcall(s);
			js.tanclose('highsearch');
		};
		this._showhighsearch = function(s, s1, lx){
			var o = $('#highsearch_list');
			var h = '<div ondblclick="$(this).remove()" onmouseover="this.style.backgroundColor=\'#f1f1f1\'" onmouseout="this.style.backgroundColor=\'\'" style="padding:8px 10px;border-bottom:1px #eeeeee solid"><span>'+s1+'</span><font style="display:none">'+s+'</font></div>';
			if(lx==1){
				var las = o.find('font:last').html(),
					lass= ',(,),and,or,';
				if(lass.indexOf(','+las+',')<0){
					this._showhighsearch('and','并且',0);
				}
			}
			o.append(h);
			this._highsearchstr = o.html();
		};
		this.changefields = function(oi){
			var d = can.columns[oi];
			$('#fields_'+rand+'').html(''+d.name+' <span class="caret"></span>');
			this.fieldsobj = d;
			if(!d.atype)d.atype='';
			if(d.atype.indexOf('date')>-1){
				$('#key_'+rand+'').hide();
				this.selobj.hide();
				$('#date_'+rand+'').show();
				$('#datebtn_'+rand+'').show();
				this.dateobj.setView(d.atype);
			}else if(d.atype=='select'){
				$('#key_'+rand+'').hide();
				this.selobj.show();
				$('#date_'+rand+'').hide();
				$('#datebtn_'+rand+'').hide();
				var sdv = d.valuefields;
				if(!sdv)sdv='value';
				this.selobj.setData(d.data,d.displayfields, sdv);
				this.selobj.setValue('');
				this.changeluoji(this.luojiarr[2]);
			}else{
				this.selobj.hide();
				$('#key_'+rand+'').show();
				$('#date_'+rand+'').hide();
				$('#datebtn_'+rand+'').hide();
			}
		};
		this.changeluoji  = function(d){
			$('#luoji_'+rand+'').html(''+d.name+' <span class="caret"></span>');
			this.luojiobj = d;
		};
		this.setData	  = function(fi, da){
			var i,
				a = can.columns;
			for(i=0; i<a.length; i++){
				if(a[i].dataIndex==fi){
					can.columns[i].data = da;
					break;
				}
			}
		};
		this.createlisttable = function(){
			var s = '';
			s='<table><tr>';
			var i,a = can.columns;
			for(i=0; i<a.length; i++){
				s+='<td>';
				if(a[i].atype=='select'){
				}else{
					s+='<input class="form-control" name="'+a[i].dataIndex+'" placeholder="'+a[i].name+'" style="width:100px">';
				}
				s+='</td>';
			}
			s+='</tr></table>';
			obj.html(s);
		}
	};
	
	$.fn.rocksearch	= function(options){
		var defaultVal = {
			columns:[{
				
			}],
			highsearch:true,
			listtable:false,
			width:500,backcall:function(){}
		};
		var can		= $.extend({}, defaultVal, options);
		var clsa 	= new rocksearch($(this), can);
		clsa.init();
		return clsa;
	};
	
	
	
	
})(jQuery);