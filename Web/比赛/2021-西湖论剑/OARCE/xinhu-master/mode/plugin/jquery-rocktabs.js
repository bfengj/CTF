/**
	rocktabs 选择卡
	caratename：chenxihu
	caratetime：214-04-06 21:40:00
	email:qqqq2900@126.com
	homepage:www.xh829.com
	
	<div class="tabs" tabsindex="0">
		<div class="tabstitle">
			<ul>
				<li index="1" class="li01">公告</li>
				<li index="2">聚品茶</li>
				<li index="3">促销信息</li>
			</ul>
		</div>
		<div class="tabscont hborder" style="height:259px; border-top:none">
			<div index="1">
			</div>
			<div index="2">
			</div>
			<div index="3">
			</div>
		</div>
	</div>
*/

(function ($) {
	
	function rocktabs(element, options){
		var obj		= element;
		var can		= options;
		var rand	= ''+parseInt(Math.random()*99999)+''; 
		var me		= this;
		this.rand	= rand;

		var titobj,contobj;
		
		//初始化
		this.init	= function(){
			titobj	= obj.find("div[class^='tabstitle']").find('li[index]');
			contobj	= obj.find("div[class^='tabscont']").find('div[index]');
			var tri	= obj.attr('trigger');
			if(tri == null || !tri)tri='click';
			
			titobj[tri](function(){
				me.clicktitle(this);	
			});
			var ind	= obj.attr('tabsindex');//选中第几个选择卡
			if(ind == null || !ind)ind='0';
			if(ind == 'last')ind = titobj.length-1;
			this.indexshow(parseInt(ind));
		};
		
		this.clicktitle = function(o1){
			var o	= $(o1);
			var oi	= o.attr('index');
			this.indexshow(parseInt(oi));
		};
		
		this.indexshow	= function(oi){
			titobj.removeClass();
			$(titobj[oi]).addClass('li01');
			contobj.hide();
			$(contobj[oi]).show();
		};
	}
	
	$.fn.rocktabs	= function(options){
		var defaultVal = {
			trigger:'click'
		};

		var can = $.extend({}, defaultVal, options);
		return this.each(function() {
			var clsa = new rocktabs($(this), can);
			clsa.init();
			return clsa;
		});
	};
	
})(jQuery);