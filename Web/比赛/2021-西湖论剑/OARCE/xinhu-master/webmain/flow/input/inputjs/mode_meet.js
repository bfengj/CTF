function initbodys(){
	$(form('startdt')).blur(function(){
		changetotal();
	});
	$(form('enddt')).blur(function(){
		changetotal();
	});
	if(form('issms'))form('issms').checked=false; //每次编辑都取消
	
	//默认隐藏频率
	if(form('rate')){
		//初始或是普通会议编辑
		if(mid==0 || form('type').value=='0'){
			c.fieldshide('rate'); //隐藏
		}
		//切换到固定会议才显示
		$(form('type')).change(function(){
			if(this.value=='1'){
				c.fieldsshow('rate');
			}else{
				c.fieldshide('rate');
			}
		});
	}
}
function changesubmit(d){
	if(d.enddt<=d.startdt)return '截止时间必须大于开始时间';
	if(d.enddt.substr(0,10)!=d.startdt.substr(0,10)){
		return '不允许跨天申请';
	}
	if(d.type=='1' && d.rate=='')return '固定会议必须选择会议频率';
}

function changetotal(){
	var st = form('startdt').value,
		et = form('enddt').value;
	if(st.substr(0,10)!=et.substr(0,10)){
		js.setmsg('不允许跨天申请');
		return;
	}
	js.setmsg('');
}