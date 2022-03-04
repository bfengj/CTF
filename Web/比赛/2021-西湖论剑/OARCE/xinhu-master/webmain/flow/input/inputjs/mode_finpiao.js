//流程模块【finpiao.发票管理】下录入页面自定义js页面,初始函数
function initbodys(){
	
}

c.onselectdata['custname']=function(d){
	var nae = d.subname;
	if(isempt(nae))nae=d.name;
	form('custname').value=nae;
}

c.onselectdata['maicustname']=function(d){
	var nae = d.subname;
	if(isempt(nae))nae=d.name;
	form('maicustname').value=nae;
}