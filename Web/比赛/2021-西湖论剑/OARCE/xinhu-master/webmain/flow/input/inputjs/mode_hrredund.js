//初始函数
function initbodys(){
	if(mid==0)loadinstyrs();
	$(form('applyname')).blur(function(){
		loadinstyrs();
	});
}
function loadinstyrs(){
	var uid = '';
	if(form('uid'))uid = form('uid').value;
	js.ajax(geturlact('getuinfo'),{'uid':uid},function(d){
		if(d){
			if(form('base_deptname'))form('base_deptname').value=d.deptname;
			form('ranking').value=d.ranking;
			form('entrydt').value=d.workdate;
		}
	},'get,json');
}