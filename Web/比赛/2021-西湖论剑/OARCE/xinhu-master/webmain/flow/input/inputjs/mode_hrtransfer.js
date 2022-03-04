function initbodys(){
	$(form('tranname')).blur(function(){
		changeuserss();
	});
	$(form('olddeptname')).click(function(){
		changeuserss();
	});
	$(form('oldranking')).click(function(){
		changeuserss();
	});
}
function changeuserss(){
	var sid = form('tranuid').value;
	if(sid=='')return;
	js.ajax(geturlact('chenguser'),{sid:sid},function(a){
		if(a){
			form('olddeptname').value=a.deptname;
			form('oldranking').value=a.ranking;
		}
	},'get,json');
}