//流程模块【carms.车辆信息登记】下录入页面自定义js页面,初始函数
function initbodys(){
	//c.fieldshide('money');
	$(form('otype')).change(function(){
		changeotype();
	});
	changeotype();
}
function changeotype(){
	var val = form('otype').value;
	if(val=='违章'||val=='事故'||val=='加油'){
		c.setfields('address',''+val+'地点');
	}else{
		c.setfields('address','地点');
	}
}
function changesubmit(d){
	if(d.enddt && d.enddt<=d.startdt)return '截止日期必须大于开始日期';
}