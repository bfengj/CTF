//流程模块【user.用户】下录入页面自定义js页面,初始函数
function initbodys(){
	if(mid>0){
		if(form('temp_dwid'))form('temp_dwid').value=data.temp_dwid;
	}
}

function changesubmit(d){
	if(d.deptnames || d.rankings){
		var len1 = d.deptnames.split(',').length;
		var len2 = d.rankings.split(',').length;
		if(!d.deptnames)len1=0;
		if(!d.rankings)len2=0;
		if(len2!=len1)return '多部门和多职位的数量不一致';
	}
}