var daytime = 8;//每天上班默认8个小时
function initbodys(){
	$(form('uname')).blur(function(){
		getdeptsutr();
	});
}
function oninputblur(na){
	if(na=='stime' || na=='etime'){
		changetotal();
	}
	if(na=='totals'){
		changedays();
	}
}
function getdeptsutr(){
	if(!form('base_deptname'))return;
	var uid = form('uid').value;
	if(!uid)return;
	js.ajax(geturlact('getuinfo'),{uid:uid}, function(ret){
		form('base_deptname').value = ret.deptname;
	},'get,json');
}
function changesubmit(d){
	if(d.etime<=d.stime)return '截止时间必须大于开始时间';
	if(d.stime.substr(0,7)!=d.etime.substr(0,7)){
		return '不允许跨月申请';
	}
	var st=parseFloat(d.totals);
	if(st<=0)return '请假时间必须大于0';
}

function changetotal(){
	var st = form('stime').value,
		et = form('etime').value;
	if(isempt(st)||isempt(et)){
		form('totals').value='0';
		return;
	}
	if(st.substr(0,7)!=et.substr(0,7)){
		js.setmsg('不允许跨月申请');
		return;
	}
	var uid = '';
	if(form('uid'))uid = form('uid').value;
	js.ajax(geturlact('total'),{stime:st,etime:et,uid:uid}, function(a){
		form('totals').value=a[0];
		daytime = parseFloat(a[2]);
		js.setmsg(a[1]);
		changedays();
	},'post,json');
}
//计算天数
function changedays(){
	if(!form('totday'))return;
	var to = parseFloat(form('totals').value);
	var day= js.float(to / daytime);
	form('totday').value = day;
}