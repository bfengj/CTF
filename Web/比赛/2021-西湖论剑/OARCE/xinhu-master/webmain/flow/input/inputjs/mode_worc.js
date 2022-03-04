//流程模块【worc.文档分区】下录入页面自定义js页面,初始函数
function initbodys(){
	if(mid==0){
		js.ajax(geturlact('getmyinfo'),{},function(d){
			if(d){
				form('receid').value=d.uid;
				form('recename').value=d.uname;
			}
		},'get,json');
	}
}