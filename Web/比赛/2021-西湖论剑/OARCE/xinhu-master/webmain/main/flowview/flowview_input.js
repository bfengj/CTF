var iview= {
	init:function(){
		$('.lumtr').click(function(){
			iview.clickvew(this)
		});
	},
	clickvew:function(o1){
		if(this.noobk)this.noobk.css('background','');
		this.noobk = $(o1);
		this.noobk.css('background',maincolor);
		var fid = this.noobk.find('[name]')[0];
		if(fid){
			fid = fid.name.replace('[]','');
			parent.ke.showzdxing(fid);
		}
	}
}
iview.init();