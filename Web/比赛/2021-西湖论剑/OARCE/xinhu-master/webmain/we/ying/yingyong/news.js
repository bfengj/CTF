function myyingsinit(){
	var s = '<select id="typeid" style="width:100px;border:none;background:white;font-size:14px"><option value="">所有分类</option></select>';
	$('#searsearch_bar').prepend(s);
	$('#typeid').change(function(){
		yy.search({'typeid':this.value});
	});
}
yy.onshowdata=function(da){
	if(da.typearr){
		js.setselectdata(get('typeid'), da.typearr, 'id');
	}
}
myyingsinit();