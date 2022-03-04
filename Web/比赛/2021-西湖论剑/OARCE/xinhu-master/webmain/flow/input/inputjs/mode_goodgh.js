//流程模块【goodgh.物品归还】下录入页面自定义js页面,初始函数
function initbodys(){
	
}	

c.addrow=function(o1){
	js.msg('msg','请用读取领用单，无需要自己新增');
	$(o1).remove();
}

c.delrow=function(o1){
	js.msg('msg','是不能删除的哦');
	$(o1).remove();
}

//读取需要归还的物品子表
c.onselectdata['custname']=function(d){
	js.ajax(geturlact('getgoodn'),{'wmid':d.value},function(ret){
		for(var i=0;i<ret.length;i++){
			if(i==0){
				c.setrowdata(0,0,ret[i]);
			}else{
				c.insertrow(0,ret[i],true);
			}
		}
	},'get,json');
}

c.onselectdatabefore=function(fid,zb){
	if(fid=='custname'){
		if(form('custid').value>'0')return '已经选择就不要重复选择，可刷新页面也重新选择';
	}
	
}