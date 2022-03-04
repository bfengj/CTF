//流程模块【custplan.跟进计划】下录入页面自定义js页面,初始函数
function initbodys(){
	
}

function changesubmit(d){
	if(d.status=='0' && !d.plandt){
		return '计划时间不能为空';
	}
	if(d.status=='1' && !d.findt){
		return '完成时间不能为空';
	}
}

function oninputblur(na){
	var zt = form('status').value;
	if((zt=='0' || zt=='5') && form('findt'))form('findt').value='';
}