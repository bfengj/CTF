function initbodys(){
	$(form('sealid')).change(function(){
		var val = this.value,txt='';
		if(val!=''){
			txt = this.options[this.selectedIndex].text;
		}
		form('sealname').value=txt;
	});
	
	//下拉选择
	c.onselectdatabefore=function(fid){
		if(fid=='sealname' && form('mknum')){
			var mkv = form('mknum').value;
			if(mkv)return {'mknum':mkv};
		}
	}
	
	//读取相关信息
	if(mid==0 && form('mknum')){
		var mkv = form('mknum').value;
		js.ajax(geturlact('getbinfo',{mknum:mkv}),false, function(ret){
			if(ret.zhaiyao)form('explain').value=ret.zhaiyao+'，申请使用印章';
		},'get,json');
	}
}