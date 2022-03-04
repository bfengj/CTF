//初始函数
function initbodys(){
	$(form('typeid')).change(function(){
		getfilenum();
	});
	$(form('state')).change(function(){
		if(this.value=='0'){
			form('usename').value='';
			form('useid').value='';
		}
	});
}

//得到文件编号：类别+年份+三位编号
function getfilenum(){
	var type = form('typeid').value;
	if(type==''){
		return;
	}
	
	js.ajax(geturlact('getfilenum'),{type:type},function(s){
		form('num').value=s;
	},'get');
}
function changesubmit(d){
	if(d.state=='1' && form('usename').value=='')return '在用状态请选择使用者';
};
