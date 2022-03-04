/**
*	居于touch.js下屏幕滚动操作的
*/	
(function ($) {
	

	function rockclass(element, options){
		var me 		= this;
		var opts 	= $.extend({
			ondragstart:function(){},
			ondrag:function(){},
			ondragrlend:function(){},
			ondragrl:function(){},
			scrollbool:false,
			dropdown_bgcolor:'#f1f1f1', //下拉背景色
			dropdown_success:function(){} //下拉回调
		}, options);
		var obj 	= element;
		
		this._init=function(){
			this.rand = js.getrand();
			this.reloadbo = false;
			this.mobj = $(obj);
			for(var a in opts)this[a]=opts[a];
			touch.on(obj, 'dragstart', function(e){
				me._dragstart(e);
			});
			touch.on(obj, 'drag', function(e){
				e.preventDefault()
				me._drag(e);
			});
			touch.on(obj, 'dragend', function(e){
				me._dragend(e);
			});
		};
		this._dragstart=function(e){
			if(this.reloadbo)return;
			this.startarr = [e.distanceX, e.distanceY, this.mobj.scrollTop(), e.timeStamp];
			this.mobj.stop();
			this.ondragstart(e);
		};
		this._drag=function(e){
			if(this.reloadbo)return;
			var lx = e.direction;
			if(lx=='right' || lx=='left'){
				this._dragrightleft(e)
				return;
			}
			this.upheight = 0;
			if(this.scrollbool){
				clearTimeout(this._hidescrollstime);
				var hei = e.distanceY-this.startarr[1];
				var lef = this.startarr[2]-hei;
				if(lef<0)lef=0;
				this.mobj.scrollTop(lef);
				if(lef==0 && hei>0 && this.startarr[2]==0)this._upstart(hei,e);//继续下拉刷新
			}
			
			this.ondrag(e);
		};
		this._dragrightleft=function(e){
			var yd = e.distanceX-this.startarr[0];
			this.ondragrl(yd,e);
		};
		this._upstart=function(hei){
			if(this.reloadbo)return;
			hei = hei*0.5;
			this.upheight = hei;
			if(hei>200)return;
			var sid = 'updowns_'+this.rand+'';
			$('#'+sid+'').remove();
			var tx= '↓ 下拉刷新';
			if(hei>50)tx='↑ 释放立即刷新';
			var s = '<div id="'+sid+'" style="height:'+hei+'px;overflow:hidden; line-height:50px;text-align:center;color:#666666;background:'+this.dropdown_bgcolor+';font-size:14px;position:relative"><div style="height:50px;line-height:50px;position:absolute;left:0px;bottom:0px;width:100%">'+tx+'</div></div>';
			this.mobj.before(s);
		};
		this._dragend=function(e){
			var lx = e.direction;
			var jg,hei,heis,hms,jgs,ass;
			jg 	= e.timeStamp-this.startarr[3];
			if(lx=='right' || lx=='left'){
				this._dragrightleftend(e);
				return;
			}
			hei = e.distanceY-this.startarr[1];
			heis= hei >0 ? -1 : 1;
			hms = 200;
			jgs = (hms-jg)/0.2 * heis;
			if(jg<hms){
				ass = this.mobj.scrollTop();
				this.mobj.animate({scrollTop:ass+jgs}, hms-jg+400);
			}
			var sid = 'updowns_'+this.rand+'';
			if(get(sid)){
				var o1 = $('#'+sid+'');
				if(this.upheight>50){
					this.reloadbo = true;
					o1.animate({'height':'50px'},200,function(){
						o1.html('<img src="images/loading.gif" align="absmiddle"> 刷新中...');
						me.dropdown_success(e);
					});
				}else{
					$('#'+sid+'').slideUp(200);
				}
			}
		};
		this._dragrightleftend=function(e){
			var yd = e.distanceX-this.startarr[0];
			this.ondragrlend(yd,e);
		};
		this.dropdown_ok=function(ts){
			this.reloadbo = false;
			var o1 = $('#updowns_'+this.rand+'');
			if(!ts)ts='√ 刷新成功';
			o1.html(ts);
			setTimeout(function(){o1.slideUp(200,function(){o1.remove();});}, 500);
		};
		this.scroll = function(){
			this.scrollbool = true;
			this.mobj.css('overflow','hidden');
			var off = this.mobj.offset();
			this.scrollsid = 'scrolllists_'+this.rand+'';
			var l 	= off.left+this.mobj.width()-6;
			var s 	= '<div style="height:80px;width:5px;background:rgba(0,0,0,0.3);display:none;overflow:hidden;border-radius:2px;right:0px;top:0px;position:absolute" id="'+this.scrollsid+'"></div>';
			s='<div id="'+this.scrollsid+'_min" style="height:'+obj.clientHeight+'px;width:5px;overflow:hidden;position:fixed;z-index:1;background:rgba(0,0,0,0);left:'+l+'px;top:'+off.top+'px">'+s+'</div>';
			$('body').append(s);
			this.resize();
			this.mobj.scroll(function(){
				me._scrollov();
			});
			this.mobj.resize(function(){
				me.resize();
			});
		};
		this._hidescrolls = function(){
			clearTimeout(this._hidescrollstime);
			this._hidescrollstime = setTimeout(function(){
				$('#'+me.scrollsid+'').fadeOut();
			},1000);
		};
		this._scrollov=function(){
			var top,zh,bl,mh,off,lets;
			top = this.mobj.scrollTop();
			off = this.mobj.offset();
			lets= off.top;
			zh	= obj.scrollHeight-obj.clientHeight;
			mh	= obj.clientHeight-80; //可滚动高度
			bl  = top/zh;
			var jgt = bl*mh;
			$('#'+this.scrollsid+'').css('top',''+jgt+'px').show();
			this._hidescrolls();
		};
		this.resize = function(){
			var off = this.mobj.offset();
			var l 	= off.left+this.mobj.width()-6,hei = obj.clientHeight;
			$('#'+this.scrollsid+'_min').css({'left':''+l+'px','top':''+off.top+'px','height':''+hei+'px'});
		}
	};
	
	$.fn.rocktouch	= function(lx, options){
		var can		= $.extend({}, options);
		var clsa 	= new rockclass(this[0], can);
		clsa._init();
		clsa[lx]();
		return clsa;
	};

	
})(jQuery);