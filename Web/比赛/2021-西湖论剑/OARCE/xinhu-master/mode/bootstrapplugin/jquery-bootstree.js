/**
*	bootstable 表格树形插件
*	caratename：雨中磐石(rainrock)
*	caratetime：2014-04-06 21:40:00
*	email:admin@rockoa.com
*	homepage:www.rockoa.com
*/

(function ($) {
	
	function bootstree(element, options){
		var obj		= element;
		var can		= options;
		var rand	= can.rand; 
		var me		= this;
		this.bool   = false;
		this.changedata = false;
		
		//初始化
		this.init	= function(){
			if(rand=='')rand=js.getrand();
			this._init();
			this._create();
			if(can.autoLoad && !isempt(can.url))this._load();
		};
		
		this._init	= function(){
			var a,s,i;
			for(i=0; i<can.columns.length; i++){
				a = can.columns[i];
				if(typeof(a.align)=='undefined')can.columns[i].align='center';
				if(a.xtype=='treecolumn')can.columns[i].align='left';
			}
			s='<div style="position:relative;border:1px #dddddd solid;" class="bootstree" id="treebody_'+rand+'"></div>';
			obj.html(s);
		};
		this._create	= function(){
			var a	= can.columns;
			var s 	= '',i,len=a.length,hs;
			this._zoi = -1;
			this._zoiarr = [];
			this.changedata = false;
			if(!can.hideHeaders){
				s+='<div>';
				s+='<ul style="border-bottom:1px #dedede solid;border-top-width:0px;margin:0">';
				if(can.checked)s+='<li style="width:40px;text-align:center"><input id="seltablecheck_'+rand+'" type="checkbox"></li>';
				for(i=0;i<len;i++){
					hs = '';
					s+='<li '+hs+' style="width:'+a[i].width+';text-align:'+a[i].align+'">&nbsp;&nbsp;';
					if(can.celleditor&&a[i].editor)s+='<i class="icon-pencil"></i>&nbsp;';
					s+='<b>'+a[i].text+'</b>';
					s+='</li>';
				}
				s+='</ul>';
				s+='</div>';
			}
			
			s+=this._createjd(can.data, 0, 'treenode');
			$('#treebody_'+rand+'').html(s);
			
			obj.find("i[temp='nodeclick']").click(function(){
				me._explade(this);
			});
			
			this.trobj = obj.find('ul[dataid]');
			this.trobj.click(function(event){
				me._itemclick(this, event, 0);
			});
			this.trobj.dblclick(function(event){
				me._itemdblclick(this, event);
			});
			$('#seltablecheck_'+rand+'').click(function(){
				js.selall(this, 'treecheck_'+rand+'');
			});
		}
		this.viewreload = function(){
			this._create();
		};
		this._itemclick= function(o1, e, lx){
			this.trobj.css('background','');
			var o = $(o1);
			o.css('background', can.selectColor);
			var oi = parseFloat(o.attr('oi'));
			var a  = this._zoiarr[oi];
			this.changedata = a;
			this.changeid	= a.id;
			if(lx==0)can.itemclick(a, oi, e);
			if(lx==1)can.itemdblclick(a, oi, e);
		};
		this._itemdblclick=function(o1,e){
			this._itemclick(o1,e,1)
		};
		this._explade = function(o){
			var o1	= $(o);
			var sid = o1.attr('nodeclick');
			var exp = o1.attr('expanded');
			if(exp=='true'){
				obj.find("div[nodexu='"+sid+"']").hide();
				o1.attr('expanded','false');
			}else{
				obj.find("div[nodexu='"+sid+"']").show();
				o1.attr('expanded','true');
			}
		}
		
		this._createjd	= function(d, oi, xlx){
			var a	= can.columns;
			var s 	= '',i,len=d.length,j,jlen = a.length,s1,ionc,s2='',s3,id, diss='',ov;
			for(i=0;i<len;i++){
				id = xlx+'_'+i;
				s2 = '';
				if(d[i].children){
					if(d[i].children.length>0){
						diss = '';
						if(!d[i].expanded)diss='none';
						s2= this._createjd(d[i].children, oi+1, id);
						s2 = '<div style="display:'+diss+';" nodexu="'+id+'">'+s2+'</div>';
					}
				}
				this._zoi++;
				ov	= d[i];
				this._zoiarr[this._zoi] = ov;
				s+='<ul oi="'+this._zoi+'" style="margin:0" dataid="'+ov.id+'">';
				if(can.checked){
					s+='<li style="width:40px;text-align:center"><input name="treecheck_'+rand+'" value="'+this._zoi+'" type="checkbox"></li>';
				}
				for(j=0;j<jlen;j++){
					s1 = ov[a[j].dataIndex];
					if(!s1)s1='';
					if(typeof(a[j].renderer)=='function'){
						s3 = a[j].renderer(s1, ov);
						if(!isempt(s3))s1=s3;
					}
					s+='<li style="width:'+a[j].width+';text-align:'+a[j].align+'">';
					if(a[j].xtype == 'treecolumn'){
						ionc = 'folder-close-alt';
						if(s2=='')ionc='file-alt';
						if(ov.icons)ionc = ov.icons;
						s+= '<div style="padding-left:'+(24*oi+10)+'px">';
						s+= this._getshlist(ov, this._zoi);		
						s+= '	<i nodeclick="'+id+'" style="cursor:pointer" class="icon-'+ionc+'"';
						if(s2 != '')s+=' temp="nodeclick"';
						if(ov.expanded)s+=' expanded="true"';
						s+= '></i> ';
						s+= s1;
						s+= '</div>';
					}else{
						s+= s1;
					}
					s+='</li>';
				}
				s+='</ul>';
				s+=s2;
			}
			return s;
		};
		this._getshlist = function(a, oi){
			var s= '',chk='',dis='';
			if(typeof(a.checked)=='boolean'){
				if(a.checked)chk='checked';
				if(a.disabled)dis='disabled';
				s = '<input name="treecheck_'+rand+'" '+chk+' '+dis+' value="'+oi+'" type="checkbox" >';
			}
			return s;
		};
		//获取选中
		this.getchecked = function(){
			var s = js.getchecked('treecheck_'+rand+''),
				o = $("input[name='treecheck_"+rand+"']");
			var a = [],i;
			if(s!=''){
				var a1 = s.split(',');
				for(i=0; i<a1.length; i++){
					oi = parseInt(a1[i]);
					a.push(this._zoiarr[oi]);
				}
			}else if(o.size()==0){
				if(this.changedata)a.push(this.changedata);
			}
			return a;
		};
		this.setparams = function(cans, relo){
			if(!cans)cans={};
			can.params = js.apply(can.params,cans);
			if(relo)this._load();
		};
		this._load = function(){
			if(this.bool)return;
			var h= obj.height()-2,
			w= obj.width()-2,
			s = '';
			s='<div id="modeshow_'+rand+'" style="filter:Alpha(opacity=20);opacity:0.2;height:'+h+'px;width:'+w+'px;overflow:hidden;z-index:3;position:absolute;left:0px;line-height:'+h+'px;top:0px;background:#000000;color:white" align="center"><img src="images/mloading.gif"  align="absmiddle"></div>';
			$('#treebody_'+rand+'').append(s);
			this.bool = true;
			var parm = can.params;
			$.ajax({
				type:can.method,url:can.url,
				data:parm,
				success:function(da){
					if(!get('treebody_'+rand+''))return;
					var a = js.decode(da);
					me._loaddataback(a);
					$('#modeshow_'+rand+'').remove();
					me.bool = false;
				},
				error: function(e){
					$('#modeshow_'+rand+'').remove();
					js.msg('msg',e.responseText);
					me.bool = false;
				}
			});
		};
		this._loaddataback = function(a){
			var d 	 = a.rows ? a.rows : a;
			can.data = d;
			this._create();
			can.load(a, this);
		};
		this.reload = function(){
			this._load();
		};
		this.loadData = function(d){
			this._loaddataback(d);
		};
		
		this.del	= function(csa){
			if(this.bool)return;
			var a 	= js.apply({msg:'确定要删除选中的记录吗？',success:function(){},checked:false,check:function(){},id:this.changeid,url:'',params:{}},csa);
			if(!a.id ||a.url==''||a.id=='0')return;
			js.confirm(a.msg,function(lx){
				if(lx=='yes'){
					me._delok(a);
				}
			});
		};
		this._delok	= function(ds){
			js.msg('wait','删除中...');
			this.bool=true;
			var url = ds.url,ss = js.apply({id:ds.id},ds.params);
			$.ajax({
				url:url,type:'POST',data:ss,dataType:'json',
				success:function(a1){
					me.bool=false;
					if(a1.code==200){
						js.msg('success','删除成功');
						ds.success();
						me.reload();
					}else{
						js.msg('msg',a1.msg);
					}
				},
				error:function(e){
					js.msg('msg','err:'+e.responseText);
					me.bool = false;
				}
			});
		};
	};
	
	
	$.fn.bootstree	= function(options){
		var defaultVal = {
			data:[],rand:'',columns:[],hideHeaders:false,selectColor:'#DFF0D8',method:'GET',
			itemdblclick:function(){},checked:false,autoLoad:true,url:'',params:{},
			itemclick:function(da, index, e){},load:function(){}
		};
		var can = $.extend({}, defaultVal, options);
		var clsa = new bootstree($(this), can);
		clsa.init();
		return clsa;
	};
})(jQuery);