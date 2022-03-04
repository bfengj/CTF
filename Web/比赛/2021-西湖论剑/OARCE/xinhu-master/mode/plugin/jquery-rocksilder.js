/**
	rocktabs 图片切换
	caratename：chenxihu
	caratetime：214-04-06 21:40:00
	email:qqqq2900@126.com
	homepage:www.xh829.com

*/

(function ($) {
	
	function rocksilder(options){
		var rand	= ''+parseInt(Math.random()*99999)+''; 
		var me		= this;
		this.rand	= rand;
		for(var i1 in options)this[i1]=options[i1];
		
		//初始化
		this.init	= function(){
			var i,len=this.data.length;
			this.oldoi = -1;
			var s = '<div id="rocksilder_'+this.rand+'" style="position:relative;height:'+this.height+'">';
			if(len==0)return;
			for(i=0;i<len;i++){
				s+='<div index="'+i+'" style="height:'+this.height+';overflow:hidden;display:none;position:absolute;left:0px;top:0px;width:100%">';
				s+='<div><img src="'+this.data[i].src+'" width="100%"></div>';
				s+='</div>';
			}
			var bo = 0;
			if(this.titlebool){
				bo = 30;
				s+='<div style="position:absolute;bottom:0px;left:0px;width:100%;background:rgba(0,0,0,0.3);text-align:center;color:white;line-height:30px;height:30px;padding:0px 0px;overflow:hidden" id="rocksildertitle_'+this.rand+'">'+this.data[0].title+'</div>';
			}
			s+='<div style="position:absolute;bottom:'+bo+'px;left:0px;width:100%;text-align:center;color:white;line-height:20px;height:20px;overflow:hidden;" id="rocksildertitlev_'+this.rand+'"></div>';
			s+='</div>';
			if(this.view==''){
				$('body').append(s);
			}else{
				$('#'+this.view+'').html(s);
			}
			this.mobj = $('#rocksilder_'+this.rand+'');
			this.mobj.find('div[index]').click(function(){
				me._click(this);
				return false;
			});
			this._showview(0);
		};
		this._click=function(o1){
			var oi= parseFloat($(o1).attr('index'));
			var d = this.data[oi];
			if(this.onclick){
				this.onclick(d);
			}else{
				if(d.url)js.location(d.url);
			}
		};
		this._showview=function(oi){
			clearTimeout(this.timeoutobj);
			if(!get('rocksilder_'+this.rand+''))return;
			var len = this.data.length;
			if(oi>=len)oi=0;
			var i,s='';
			if(this.oldoi>=0){
				this.mobj.find('div[index="'+this.oldoi+'"]').hide();
				this.mobj.find('div[index="'+oi+'"]').show();
			}else{
				this.mobj.find('div[index]').hide();
				this.mobj.find('div[index="'+oi+'"]').show();
			}
			$('#rocksildertitle_'+this.rand+'').html(this.data[oi].title);
			for(i=0;i<len;i++){
				if(i>0)s+='&nbsp;&nbsp;';
				if(i==oi){
					s+='<span style="font-size:18px">●</span>';
				}else{
					s+='<span style="font-size:16px" xu="'+i+'">○</span>';
				}
			}
			$('#rocksildertitlev_'+this.rand+'').html(s).find('span[xu]').click(function(){
				var xu = parseFloat($(this).attr('xu'));
				me._showview(xu);
				return false;
			});
			this.oldoi = oi;
			this.timeoutobj = setTimeout(function(){me._showview(oi+1);},this.changtime);
		};
		this.remove=function(){
			clearTimeout(this.timeoutobj);
			$('#rocksilder_'+this.rand+'').remove();
		}
	}
	
	$.rocksilder	= function(options){
		var defaultVal = {
			'view': '',
			'data':[],
			'height' :'150px',
			'titlebool':true,
			'onclick':false,
			'changtime':5000 //5秒
		};

		var can = $.extend({}, defaultVal, options);
		var clsa = new rocksilder(can);
		clsa.init();
		return clsa;
	};
	
})(jQuery);