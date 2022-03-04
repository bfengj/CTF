var soumodeid = 0;
yy.onclickmenu=function(d){
	if(this.nowevent!=d.url)soumodeid=0;
	return true;
}
function myyingsinit(){
	var s = '<select id="modeid" style="width:100px;border:none;background:white;font-size:14px"><option value="0">选择模块</option></select>';
	$('#searsearch_bar').prepend(s);
	$('#modeid').change(function(){
		soumodeid = this.value;
		yy.search({'modeid':this.value});
	});
}
yy.onshowdata=function(da){
	if(da.modearr){
		var s = '<option value="0">选择模块</option>',len=da.modearr.length,i,csd,types='';
		for(i=0;i<len;i++){
			csd = da.modearr[i];
			if(types!=csd.type){
				if(types!='')s+='</optgroup>';
				s+='<optgroup label="'+csd.type+'">';
			}
			s+='<option value="'+csd.id+'">'+csd.name+'</option>';
			types = csd.type;
		}
		$('#modeid').html(s);
		if(soumodeid>0)get('modeid').value = soumodeid;
	}
}
myyingsinit();