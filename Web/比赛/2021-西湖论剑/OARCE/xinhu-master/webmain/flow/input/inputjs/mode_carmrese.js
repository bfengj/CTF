function initbodys(){
	$(form('carid')).change(function(){
		var val = this.value,txt='';
		if(val!=''){
			txt = c.getseltext('carid');
			if(form('kmstart'))form('kmstart').value=c.getselattr('carid','kmstart'); //设置起始公里数
		}
		if(form('carnum'))form('carnum').value=txt;
	});
	c.onselectdata['carnum'] = function(d){
		if(form('kmstart'))form('kmstart').value = d.kmstart;
	}
}



function changesubmit(d){
	if(d.enddt<=d.startdt)return '结束时间必须大于开始时间';
}
