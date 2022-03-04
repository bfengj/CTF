/**
	edittable 编辑单元格
	caratename：chenxihu
	caratetime：214-04-06 21:40:00
	email:qqqq2900@126.com
	homepage:www.xh829.com
*/

(function ($) {
	
	function edittable(element, options){
		var obj		= element;
		var can		= options;
		var rand	= ''+parseInt(Math.random()*99999)+''; 
		var me		= this;
		var timeci	= 0;
		this.rand	= rand;
		
		//初始化
		this.init	= function(){
			var o	= obj.find('[edittable]');
			var len	= o.length;
			for(var i=0;i<len;i++){
				$(o[i])[can.trigger](function(){
					me.changcell($(this));
				});
			}
		};
		this.changcell = function(o){
			var ntml	= o.html();
			if(ntml.indexOf('input')>=0 || ntml.indexOf('INPUT')>=0 || ntml.indexOf('SELECT')>=0 || ntml.indexOf('select')>=0)return false;
			
			timeci++;
			var fid	= o.attr('edittable');//字段名
			var ocaa= can.data[fid];
			var xty	= 'text';
			if(ocaa)if(ocaa.xtype)xty = ocaa.xtype;
			var val = o.text(),
				nid = 'edittable_'+rand+'_'+timeci+'',
				off	= o.offset(),
				wid = o.width(),
				canslw = 'fields="'+fid+'" id="'+nid+'"  changeid="'+js.changeid+'"';
				s='',
				othval = ntml.replace(val,'');
			if(xty == 'text'){
				s+= '<input style="width:'+(wid-10)+'px;line-height:18px;height:25px;border:1px #888888 solid;padding:0px 3px" '+canslw+'>';
				o.html(s);
				var o1	= $('#'+nid+'');
				o1.focus();
				o1.val(val);
				o1.blur(function(){
					me.textblur($(this),val,o,othval);
				});
			}
			if(xty == 'select'){
				var da	= ocaa.store;
				s+= '<select style="width:'+wid+'px" '+canslw+'>';
				for(var i=0; i<da.length; i++){
					var v1 = '',v2 = '',v = da[i];
					if(typeof(v)!='object'){
						v1	= v;
						v2	= v;
					}else{
						var oi = 0;
						if(v[0]){
							v1	= v[0];
							v2	= v[0];
							if(v[1])v2=v[1];
						}else{
							v1	= v.value;
							v2	= v.name;
						}
					}
					s+='<option '+((v1==val)?'selected':'')+' value="'+v1+'">'+v2+'</option>';
				}
				s+='</select>';
				o.html(s);
				var o1	= $('#'+nid+'');
				o1.focus();
				o1.blur(function(){
					me.textblur($(this),val,o);
				});
			}
			if(xty == 'checkbox'){
				var che	= '',
					dis = ocaa.display,
					cval= 0;
				for(var i=0;i<dis.length;i++){
					if(dis[i]==strreplace(val)){
						cval = i;
					}
				}
				if(cval==1)che='checked';
				s+= '<input type="checkbox" '+che+' '+canslw+'>'+ocaa.label+'';
				o.html(s);
				var o1	= $('#'+nid+'');
				o1.focus();
				o1.blur(function(){
					me.checkboxblur(this,cval,o);
				});
			}
			can.changcell(o);
		};
		this.checkboxblur = function(o1,oval,oba){
			var nval  = '0';
			if(o1.checked)nval='1';
			var o = $(o1);
			var fields	= o.attr('fields');
			this.savedata(o,nval,oval);
			var ocaa= can.data[fields];
			var s	= '<font color="'+ocaa.displaycolor[nval]+'">'+ocaa.display[nval]+'</font>';
			oba.html(s);
		};
		//保存数据
		this.savedata	= function(o,nval,oval){
			if(nval == oval)return;
			var canid	= o.attr('changeid');
			var fields	= o.attr('fields');
			var saveurl	= can.saveurl;
			can.savedata(saveurl,{id:canid,fields:fields,newvalue:nval,table:can.table,keyfields:can.keyfields},function(da){
				
			});
		}
		this.textblur = function(o,oval,oba,ovs){
			var nval	= o.val();
			if(!ovs)ovs = '';
			o.remove();
			this.savedata(o,nval,oval);
			oba.html(ovs+nval);
		}
	}
	
	$.fn.edittable	= function(options){
		var defaultVal = {
			trigger:'dblclick', //默认双击编辑
			data:{},
			keyfields:'id',//主键字段名
			saveurl:js.getajaxurl('saveeditable','user','system'),	//保存表格的地址
			savedata:function(url,das,sboole){
				$.post(url,das,sboole);
			},
			changcell:function(){}
		};
		var can = $.extend({}, defaultVal, options);
		var funcls = new edittable($(this), can);
		funcls.init();
		return funcls;
	};
	
})(jQuery);