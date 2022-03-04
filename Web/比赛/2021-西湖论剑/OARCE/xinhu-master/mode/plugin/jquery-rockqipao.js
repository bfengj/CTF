/**
	rockqipao 提示气泡
	caratename：chenxihu
	caratetime：2014-09-02 17:00:00
	email:qqqq2900@126.com
	homepage:www.xh829.com
*/	

(function ($) {
	
	function rockqipao(element, options){
		var obj		= element;
		var can		= options;
		var rand	= ''+parseInt(Math.random()*99999)+''; 
		var me		= this;
		this.rand	= rand;

	
		
		//初始化
		this.init	= function(){
			var s	= '';
			var id	= 'rockqipaoshowdiv_'+rand+'';
			var glid= obj.attr('id');
			s+='<div id="'+id+'" guanliid="'+glid+'" title="'+can.tip+'" style="position:absolute;z-index:8;left:5px;top:5px;width:'+can.width+'px;height:'+can.width+'px;background:'+can.bgcolor+';overflow:hidden;color:white;border-radius:'+(can.width*0.5)+'px;line-height:'+can.width+'px;cursor:pointer;font-size:12px" align="center">'+can.text+'</div>';
			$('body').append(s);
			
			$('#'+id+'').click(function(){
				can.click(this);
			});
			$('#'+id+'').mouseover(function(){
				me.setweizhi();
			});
			this.setweizhi();
		};
		this.setweizhi	= function(){
			var off	= obj.offset();
			var l	= off.left+ can.left - can.width*0.5;
			var t	= off.top + can.top-can.width;
			$('#rockqipaoshowdiv_'+rand+'').css({left:''+l+'px',top:''+t+'px'});
		}
	}
	
	$.fn.rockqipao	= function(options){
		var defaultVal = {
			click:function(){},
			text:'',
			left:5,
			top:0,
			width:20,
			bgcolor:'#ff6600',
			tip:''
		};
		var can = $.extend({}, defaultVal, options);
		var clsa = new rockqipao($(this), can);
		clsa.init();
		return clsa;
	};
	
})(jQuery);