//流程模块【carmwx.车辆维修】下录入页面自定义js页面,初始函数
function initbodys(){
	
}

function changesubmit(d){
	if(d.enddt && d.enddt<=d.startdt)return '截止时间必须大于维修时间';
}