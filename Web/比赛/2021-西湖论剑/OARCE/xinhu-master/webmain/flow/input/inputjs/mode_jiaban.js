/**
*	加班费的计算每个企业都不一样，我们没办法写出公式，请自己修改代码哦
*/
function initbodys(){
	$(form('stime')).blur(function(){
		changetotal();
	});
	$(form('etime')).blur(function(){
		changetotal();
	});
	
	$(form('jiatype')).change(function(){
		changetype(true);
	});
	
	changetype(false);
	
	$(form('uname')).blur(function(){
		loadinstyrs();
	});
}

function loadinstyrs(){
	if(!form('base_deptname'))return;
	var uid = '';
	if(form('uid'))uid = form('uid').value;
	js.ajax(geturlact('getuinfo'),{'uid':uid},function(d){
		if(d){
			form('base_deptname').value=d.deptname;
		}
	},'get,json');
}

function changetype(bo){
	var v = form('jiatype').value;
	var o = $('#div_jiafee').parent().parent();
	if(v=='1'){
		o.show();
		if(bo)changetotal();
	}else{
		o.hide();
		if(bo)form('jiafee').value='0';
	}
}

function changesubmit(d){
	if(d.etime<=d.stime)return '截止时间必须大于开始时间';
	if(d.stime.substr(0,10)!=d.etime.substr(0,10)){
		//return '不允许跨日申请';
	}
	var st=parseFloat(d.totals);
	if(st<=0)return '加班时间必须大于0';
}

function changetotal(){
	var st = form('stime').value,
		et = form('etime').value;
	if(isempt(st)||isempt(et)){
		form('totals').value='0';
		return;
	}
	if(et<=st){
		js.setmsg('截止时间必须大于开始时间');
		return;
	}
	if(st.substr(0,10)!=et.substr(0,10)){
		//js.setmsg('不允许跨日申请');
		//return;
	}
	js.ajax(geturlact('total'),{stime:st,etime:et,jiatype:form('jiatype').value}, function(da){
		var a= js.decode(da);
		form('totals').value=a[0];
		form('jiafee').value=a[2];
		js.setmsg(a[1]);
	},'post');
}