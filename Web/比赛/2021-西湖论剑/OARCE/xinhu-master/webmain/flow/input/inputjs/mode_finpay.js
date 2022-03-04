//流程模块【finpay.付款申请】下录入页面自定义js页面,初始函数
var oldcustid = '0';
function initbodys(){
	form('fullname').readOnly=false;
	$(form('fullname')).blur(function(){
		changeheits();
	});
	oldfullval = form('fullname').value;
	if(form('custid'))oldcustid=form('custid').value;
}
function changesubmit(){
	var jg = parseFloat(form('money').value);
	if(jg<=0)return '付款金额不能小于0';
}

c.onselectdata['fullname']=function(d){
	var nae = d.subname;
	if(isempt(nae))nae=d.name;
	form('fullname').value=nae;
	oldcustid = d.id;
	oldfullval = nae;
	changegetother();
}
function changegetother(){
	if(!form('custid'))return;
	js.ajax(geturlact('getother'),{id:form('custid').value},function(d){
		if(d){
			if(form('cardid'))form('cardid').value=d.cardid;
			if(form('openbank'))form('openbank').value=d.openbank;
		}
	},'get,json');
}

//判断是否用自己输入的
function changeheits(){
	if(!form('custid'))return;
	var val = form('fullname').value;
	if(oldfullval!=val){
		form('custid').value='0';
	}else{
		form('custid').value=oldcustid;
	}
}