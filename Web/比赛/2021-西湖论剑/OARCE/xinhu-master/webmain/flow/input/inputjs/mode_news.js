function initbodys(){
	if(form('issms'))form('issms').checked=false; //每次编辑都取消
}

function changesubmit(d){
	if(d.startdt && d.enddt && d.startdt>d.enddt)return '展示截止日期必须大于开始日期';
}