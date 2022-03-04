
yy.onshowdata=function(da){
	if(da.projectarr){
		var s = '<select id="projcetid" style="width:100px;border:none;background:white;font-size:14px"><option value="">所有项目</option></select>';
		$('#searsearch_bar').prepend(s);
		$('#projcetid').change(function(){
			yy.search({'projcetid':this.value});
		});
		js.setselectdata(get('projcetid'), da.projectarr, 'id');
	}
}