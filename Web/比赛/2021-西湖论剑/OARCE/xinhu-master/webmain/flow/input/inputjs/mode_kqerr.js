//初始函数
function initbodys(){
	$(form('uname')).blur(function(){
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