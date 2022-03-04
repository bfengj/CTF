function initbodys(){
	$(form('applyname')).blur(function(){
		loadinstyrs();
	});
}
function changesubmit(d){
	if(d.intime<=d.outtime)return '预计回岗必须大于外出时间';
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