//流程模块【hrcheck.考核评分】下录入页面自定义js页面,初始函数
function initbodys(){
	$(form('applyname')).blur(function(){
		loadinstyrs();
	});
}

function loadinstyrs(){
	if(!form('base_deptname'))return;
	var uid = '';
	if(form('uid'))uid = form('uid').value;
	js.ajax(geturlact('getuinfo'),{'uid':uid},function(d){
		if(d){
			form('base_deptname').value=d.deptname;
		}
	},'get,json');
}