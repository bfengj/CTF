/**
	rockmenu 菜单选择插件
	caratename：chenxihu
	caratetime：214-04-06 21:40:00
	email:qqqq2900@126.com
	homepage:www.xh829.com
*/
var rockmenuobj	= null;
(function ($) {
	
	function rockmenu(element, options){
		var obj		= element;
		var can		= options;
		var json	= can.data;
		var rand	= js.getrand(); 
		var me		= this;
		this.obj	= obj;
		
		//初始化
		this.init	= function(){
			if(!obj)return;
			obj[can.trigger](function(){
				me.setcontent();
				return false;
			});
		};
		this.hide	= function(){
			var o = this.mdivobj;
			if(!o)return;
			o.hide();
			if(can.bgcolor!='')obj.css('background','');
			if(can.autoremove)o.remove();
		};
		this.setcontent	= function(){
			$('.rockmenu').remove();
			rockmenuobj	= this;
			can.beforeshow(this);
			if(json.length<=0)return false;
			if(can.bgcolor!='')obj.css('background',can.bgcolor);
			if(can.autoremove)$('#rockmenu_'+rand+'').remove();
			if(document.getElementById('rockmenu_'+rand+'')){
				this.setweizhi();
				return false;
			}
			var len	= json.length;
			var str	= '<div class="rockmenu" id="rockmenu_'+rand+'">';
			if(can.arrowup)str+='<div class="arrow-up"></div>';
			str+='<div style="background:'+can.background+';"  id="rockmenuli_'+rand+'" class="rockmenuli '+can.maincls+'"><ul>';
			var s	= '',ys='',col,va;
			for(var i=0; i<len; i++){
				ys= '',
				va= json[i][can.display];
				if(i==len-1)ys+='border:none;';
				col	= '';
				if(json[i].color)ys+='color:'+json[i].color+';';
				if(va==can.value)col='#e1e1e1';
				if(json[i].background)col=json[i].background;
				if(col)ys+='background:'+col+';';
				s = '<li temp="'+i+'" style="'+ys+'">';
				var s1	= can.resultbody(json[i], this, i);
				if(!s1){
					if(json[i].icons)s+='<img src="'+json[i].icons+'" width="'+can.iconswh+'" height="'+can.iconswh+'" align="absmiddle">&nbsp;';
					s+=va;
				}else{
					s+=s1;
				}
				s+='</li>';
				str+=s;
			}
			str+='</ul></div></div>';
			$('body').prepend(str);
			var oac	= $('#rockmenu_'+rand+'');
			can.aftershow(this);
			oac.find('li').mouseover(function(){this.className=can.overcls;});
			oac.find('li').mouseout(function(){this.className='';});
			oac.find('li').click(function(){me.itemsclick(this);});
			if(can.width!=0){
				$('#rockmenuli_'+rand+'').css('width',''+can.width+'px');
			};
			js.addbody(rand, 'remove', 'rockmenu_'+rand+''); 
			this.mdivobj = oac;
			this.setweizhi();
		};
		this.showAt		= function(l, t, w){
			this.setcontent();
			var oac	= this.mdivobj;
			if(!oac)return;
			if(w)this.setWidth(w);
			this._reshewhere(l,t);
		};
		this.offset=function(l,t){
			this._reshewhere(l,t);
		};
		this.getHeight = function(){
			return get('rockmenu_'+rand+'').scrollHeight;
		};
		this._reshewhere=function(l,t){
			var oac	= this.mdivobj;
			var jg	= (l+oac.width()+5 - winWb()),jg1=0;	
			if(jg>0)l=l-jg;
			jg1	= t+get('rockmenu_'+rand+'').scrollHeight+10-winHb();
			if(jg1>0)t=t-jg1;
			if(t<5)t=5;
			oac.css({'left':''+l+'px','top':''+t+'px'});
		};
		this.setValue	= function(v){
			can.value	= v;
		};
		this.removeItems		= function(oi){
			$('#rockmenu_'+rand+'').find("li[temp='"+oi+"']").remove();
		};
		this.setWidth	= function(w){
			var oac	= this.mdivobj;
			if(!oac)return;
			oac.css({'width':''+w+'px'});
		};
		this.setweizhi = function(){
			var oac		= this.mdivobj;
			if(can.donghua)oac.slideDown(100);
			oac.show();
			if(!obj)return;
			var off		= obj.offset(),
				l		= off.left+ can.left,
				t		= off.top+can.top;
			this._reshewhere(l,t);
		};
		//项目单击
		this.itemsclick = function(o){
			var oi	= parseInt($(o).attr('temp'));
			can.itemsclick(json[oi],oi,me);
			if(can.autohide)this.hide();
		};
		this.setData	= function(da){
			can.data= da;
			json	= da;
			can.autoremove = true;
		};
		this.remove		= function(){
			this.hide();
		}
	};
	
	$.rockmenu	  = function(options, dxo){
		var defaultVal = {
			data:[],
			display:'name',//显示的名称
			left:0,
			overcls:'li01',
			maincls:'',
			top:0,
			width:0,value:'',
			iconswh:16,
			itemsclick:function(){},
			beforeshow:function(){},
			aftershow:function(){},
			autoremove:true,
			trigger:'click',
			autohide:true,
			arrowup:false,	//是否有箭头
			background:'#ffffff',//背景颜色
			bgcolor:'',
			resultbody:function(){
				return '';
			},
			donghua:false
		};
		var can = $.extend({}, defaultVal, options);
		var menu = new rockmenu(dxo, can);
		menu.init();
		return menu;
	}
	
	$.fn.rockmenu = function(options){
		return $.rockmenu(options, $(this));
	};
})(jQuery); 