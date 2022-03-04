/**
	双击编辑格
*/

(function ($) {
	
	function bootsoedit(element, options){
		var obj		= element;
		var can		= options;
		var rand	= js.getrand(); 
		var me		= this;
		
		this.init	= function(){
			obj[can.trigger](function(event){
				me.setcontent(this, event);
			});
		};
		
		this.setcontent	= function(o1, e){
			alert(1);
		}
	};
	
	
	$.fn.bootsoedit	= function(options){
		var defaultVal = {
			trigger:'click',
			fields:'',
			data:[],//当前数据
			title:''
		};
	
	
		return this.each(function(){
			var can = $.extend({}, defaultVal, options);
			var clsa = new bootsoedit($(this), can);
			clsa.init();
			return clsa;
		});
	};
})(jQuery);