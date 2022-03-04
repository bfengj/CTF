//初始函数
function initbodys(){
	if(isinput==1){
		if(form('htfid') && form('htfid').value==''){
			$('#fileview_htfid').after('<div><a href="javascript:;" onclick="xuanwenj(this)" class="blue">＋选择模版文件</a></div>');
		}
	}
}

function changesubmit(d){
	if(d.tqenddt && d.tqenddt>=d.enddt)return '提前终止日期必须小于截止日期';
	if(d.startdt>=d.enddt)return '截止日期必须大于开始日期';
}

function xuanwenj(o1){
	var ne = form('uname').value;
	if(!ne){
		js.msg('msg','请先填写签署人');
		return;
	}
	c.xuanfile('htfid','员工合同',''+ne+'的劳动合同',o1);
}

c.uploadfileibefore=function(sna){
	if(sna=='htfid'){
		var val = form(sna).value;
		if(val)return '最多只能上传一个文件哦';
	}
}
