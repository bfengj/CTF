/**
*	jqury的下拉上拉加载插件，带样式滚动条，呵呵。
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (xh829.com)
*	Date:2016-11-24
*/	

(function ($) {

	function rockclass(element, options){
		var me 		= this;
		var opts 	= $.extend({
			ondragstart:function(){},
			ondrag:function(){},
			ondrayrl:function(){},
			ondrayrlend:false,
			scrollbool:true,		//是否添加滚动条样式
			downbool:false,			//是否下拉加载
			upbool:false,			//是否上拉加载
			leftbool:false,			//左
			rightbool:false,		//右
			ondownbefore:function(){return true},
			onupbefore:function(){return true},
			downbgcolor:'#f1f1f1', 	//下拉背景
			upmsgdiv:'' 			//上来提示区域
		}, options);
		
		var obj 	= element;
		this._init=function(){
			this.rand = js.getrand();
			this.reloadbo = false;
			this.mobj = $(obj);
			for(var a in opts)this[a]=opts[a];
			obj.addEventListener('touchstart',function(e){
				me._dragstart(e);
			},false);
			obj.addEventListener('touchmove',function(e){
				me._dragmove(e);
			},false);
			obj.addEventListener('touchend',function(e){
				this.removeEventListener('touchmove', function(){}, false);
				this.removeEventListener('touchstart', function(){}, false);
				me._dragend(e);
			},false);
			this._initscrool();
		};
		this._dragstart=function(e){
			if(this.reloadbo)return;
			$('#updowns_'+this.rand+'').remove();
			this.startarr = [e.touches[0].clientX, e.touches[0].clientY, this.mobj.scrollTop(),obj.scrollHeight-this.mobj.height()];
			if(this.upbool && this.upmsgdiv)this.startarr[4]=$('#'+this.upmsgdiv+'').html();
			this.upstartbo 	= false;
			this.up_ysa 	= 0;
			this.movearr 	= [0,0];
			this.ondragstart(e);
		};
		this._dragmove=function(e){
			if(this.reloadbo)return;
			this.upheight 	= 0;
			var hei 		= e.touches[0].clientY-this.startarr[1],downbo=false,upbo=false,ler = e.touches[0].clientX-this.startarr[0];
			this.movearr 	= [ler,hei];
			var updown 		= Math.abs(hei) > Math.abs(ler);
			if(this.up_ysa==0)this.up_ysa = updown ? 1 :2;
			//js.msg('msg',''+hei+'.'+ler+'');
			
			//下拉刷新
			if(this.downbool && this.up_ysa==1){
				var dowbcak = this.ondownbefore();
				if(hei>0 && dowbcak && this.startarr[2]==0){
					e.preventDefault();
					downbo 	= true;
				}
				if(downbo){
					this._downstart(hei, dowbcak);
				}
				if(!downbo)$('#downs_'+this.rand+'').remove();
			}
			
			//上拉刷新
			if(this.upbool && this.up_ysa==1){
				var upback = this.onupbefore();
				if(hei<0 && upback && this.startarr[2]==this.startarr[3]){
					e.preventDefault();
					upbo	= true;
				}
				if(upbo){
					this._upstart(hei, upback);
				}
				if(!upbo)this.translateY(0);
			}
			
			if(this.up_ysa==2){
				e.preventDefault();
				this.ondrayrl(ler, e);
			}

			//左滑动
			if(this.leftbool && ler<0  && this.up_ysa==2){
				this.mobj.css('transform','translateX('+ler+'px)');
			}
			
			//右滑动
			if(this.rightbool && ler>0 && this.up_ysa==2){
				this.mobj.css('transform','translateX('+ler+'px)');
			}
			
			this.ondrag(e,this.movearr);
		};
		this._downstart=function(hei){
			if(this.reloadbo)return;
			hei = hei*0.6;
			this.upheight = hei;
			if(hei>200)return;
			var sid = 'downs_'+this.rand+'',tx= '↓ 下拉刷新',o1;
			if(hei>50)tx='↑ 释放立即刷新';
			if(get(sid)){
				o1 = $('#'+sid+'');
				o1.css('height',''+hei+'px').find('div').html(tx);
			}else{
				var s = '<div id="'+sid+'" style="height:'+hei+'px;overflow:hidden; line-height:50px;text-align:center;color:#666666;background:'+this.downbgcolor+';font-size:14px;position:relative"><div style="height:50px;line-height:50px;position:absolute;left:0px;bottom:0px;width:100%">'+tx+'</div></div>';
				this.mobj.before(s);
			}
		};
		this._upstart=function(hei, bsrs){
			if(this.reloadbo)return;
			hei = hei*0.6;
			this.upheight = hei;
			if(hei<-200)return;
			var a = {msg:'↑ 上拉刷新','msgok': '↓ 释放立即刷新','msgdiv':this.upmsgdiv},i;
			if(typeof(bsrs)=='object'){
				for(i in bsrs)a[i]=bsrs[i];
			}
			var tx= a.msg;
			if(hei<-50)tx=a.msgok;
			if(a.msgdiv)$('#'+a.msgdiv+'').html(tx);
			this.upstartbo = true;
			this.translateY(hei);
		};
		this.translateY=function(h){
			var o = obj,val= "translateY("+h+"px)";
			o.style.transform=val;
			o.style.webkitTransform=val;
			o.style.msTransform=val;
			o.style.MozTransform=val;
			o.style.OTransform=val;
		};
		this._dragend=function(e){
			var sid = 'downs_'+this.rand+'';
			if(get(sid)){
				var o1 = $('#'+sid+'');
				if(this.upheight>50){
					this.reloadbo = true;
					o1.animate({'height':'50px'},200,function(){
						o1.html('<img src="images/loading.gif" align="absmiddle"> 刷新中...');
						me.ondownsuccess ? me.ondownsuccess(e) : me.ondownok();
					});
				}else{
					$('#'+sid+'').slideUp(200);
				}
			}
			var bhui = true;
			if(this.upheight<0){
				this.translateY(0);
				if(this.upheight<-50){
					this.reloadbo = true;
					if(this.onupsuccess){
						this.onupsuccess();
					}else{
						setTimeout(function(){me.onupok()}, 500);
					}
					bhui = false;
				}
			}
			if(this.upstartbo || bhui){
				if(this.upmsgdiv)$('#'+this.upmsgdiv+'').html(this.startarr[4]);
			}
			if(this.ondrayrlend){
				this.ondrayrlend(this.movearr[0], e);
			}
		};
		this.ondownok=function(ts){
			this.reloadbo = false;
			var o1 = $('#downs_'+this.rand+'');
			if(!ts)ts='√ 刷新成功';
			o1.html(ts);
			setTimeout(function(){o1.slideUp(200,function(){o1.remove();});}, 500);
		};
		this.ondownerror=function(ts){
			if(!ts)ts='× 超时失败';
			this.ondownok(ts);
		};
		this.onupok=function(ts){
			this.reloadbo 	= false;
			this.upstartbo 	= false;
		};
		this.onuperror=function(ts){
			if(!ts)ts='× 超时失败';
			this.ondownok(ts);
		};
		this._initscrool=function(){
			if(!this.scrollbool)return;
			var off = this.mobj.offset();
			this.scrollsid = 'scrolllists_'+this.rand+'';
			var l 	= off.left+this.mobj.width()-6;
			this.scroor_h  = 80;
			this.scroor_hs = obj.scrollHeight;
			var s 	= '<div style="height:'+this.scroor_h+'px;width:5px;background:rgba(0,0,0,0.3);display:none;overflow:hidden;border-radius:2px;right:0px;top:0px;position:absolute" id="'+this.scrollsid+'"></div>';
			s='<div id="'+this.scrollsid+'_min" style="height:'+obj.clientHeight+'px;width:5px;overflow:hidden;position:fixed;z-index:1;background:rgba(0,0,0,0);left:'+l+'px;top:'+off.top+'px">'+s+'</div>';
			$('body').append(s);
			this.resize();
			this.mobj.scroll(function(){
				me._scrollov();
			});
		};
		this._hidescrolls = function(){
			clearTimeout(this._hidescrollstime);
			this._hidescrollstime = setTimeout(function(){
				$('#'+me.scrollsid+'').fadeOut();
			},1000);
		};
		this._scrollovs=function(){
			clearTimeout(this._scrollovstime);
			this._scrollovstime=setTimeout(function(){
				me._scrollov();
			},1);
		};
		this._scrollov=function(){
			var top,zh,bl,mh,wzh;
			top = this.mobj.scrollTop();
			wzh = obj.scrollHeight;
			if(wzh!=this.scroor_hs){
				this.resize();
			}
			zh	= wzh-obj.clientHeight;
			mh	= obj.clientHeight-this.scroor_h; //可滚动高度
			bl  = top/zh;
			var jgt = bl*mh;
			$('#'+this.scrollsid+'').css('top',''+jgt+'px').show();
			this._hidescrolls();
		};
		
		
		this.hidescrolls=function(){
			$('#'+this.scrollsid+'').hide();
		}
		
		/**
		*	窗口改变时重新设置滚动条
		*/
		this.resize = function(){
			if(!this.scrollbool)return;
			var off = this.mobj.offset(),zh;
			var l 	= off.left+this.mobj.width()-6,hei = obj.clientHeight;
			zh		= obj.scrollHeight;
			$('#'+this.scrollsid+'_min').css({'left':''+l+'px','top':''+off.top+'px','height':''+hei+'px'});
			var bl  = hei/zh;if(bl>1)bl=0.9;
			this.scroor_h = bl * hei;
			$('#'+this.scrollsid+'').css({'height':''+this.scroor_h+'px'});
			this.scroor_hs = zh;
		}
	};
	
	$.fn.rockdoupull	= function(options){
		var can		= $.extend({}, options);
		var clsa 	= new rockclass(this[0], can);
		clsa._init();
		return clsa;
	};

	/**
	*	长按
	*/
	function longpress(element, options){
		var me 		= this;
		var opts 	= $.extend({
			ondragstart:function(){return true;}, //按下前
			ondragend:function(){return true;}, //按下后
			downbgcolor:'#f1f1f1', 	//下拉背景
			presstime:500,
			onpress:function(){}
		}, options);
		
		var obj 	= element;
		
		var obj 	= element;
		this.ele	= obj;
		this._init=function(){
			this.mobj = $(obj);
			for(var a in opts)this[a]=opts[a];
			obj.addEventListener('touchstart',function(e){
				me._dragstart(e);
			},false);
			obj.addEventListener('touchend',function(e){
				me._dragend(e);
			},false);
		};
		
		this._dragstart=function(e){
			if(!this.ondragstart(e))return false;
			e.preventDefault();
			this.oldbackcolor = obj.style.backgroundColor;
			obj.style.backgroundColor = this.downbgcolor;
			this.anxiamiao = 0;
			clearInterval(this.shumiaotime);
			this.shumiaotime=setInterval(function(){
				me.downtimes(e);
			},100);
		};
		
		this.downtimes=function(e){
			this.anxiamiao+=100;
			
			if(this.anxiamiao>=this.presstime){
				this._dragend(e);
				this.onpress();//触发
			}
		};
		
		this._dragend=function(e){
			obj.removeEventListener('touchstart', function(){}, false);
			clearInterval(this.shumiaotime);
			if(typeof(this.oldbackcolor=='string'))obj.style.backgroundColor = this.oldbackcolor;
			this.ondragend(this.anxiamiao>=this.presstime,e);
		};
	}
	
	$.fn.longpress	= function(options){
		var can		= $.extend({}, options);
		var clsa 	= new longpress(this[0], can);
		clsa._init();
		return clsa;
	};
	
})(jQuery);