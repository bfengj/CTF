//初始函数
function initbodys(){
	
	if(!form('zinum'))return;
	$(form('zinum')).change(function(){
		getfilenum();
	});
	$('#inputtitle').css('color','red');
	$('body').append('<style>.ys1,.ys2{border-color:red;color:red}</style>');
	form('unitname').readOnly=false;
	form('chaoname').readOnly=false;
}

//得到文件编号：类别+年份+三位编号
function getfilenum(){
	var type = form('zinum').value;
	if(type==''){
		form('num').value='';
		return;
	}
	
	js.ajax(geturlact('getfilenum'),{type:type},function(s){
		form('num').value=s;
	},'post');
}
