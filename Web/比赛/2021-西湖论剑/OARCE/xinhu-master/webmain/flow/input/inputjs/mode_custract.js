function initbodys(){
	$(form('custid')).change(function(){
		var val = this.value,txt='';
		if(val!=''){
			txt = this.options[this.selectedIndex].text;
		}
		form('custname').value=txt;
		form('saleid').value = '';
	});
	
	$(form('saleid')).change(function(){
		salechange(this.value);
	});
	
	if(isinput==1){
		if(form('htfileid') && form('htfileid').value==''){
			$('#fileview_htfileid').after('<div><a href="javascript:;" onclick="xuanwenj(this)" class="blue">＋选择模版文件</a></div>');
		}
	}
}
function salechange(v){
	if(v==''){
		form('custid').value='';
		form('custname').value='';
		return;
	}
	js.ajax(geturlact('salechange'),{saleid:v},function(a){
		form('custid').value=a.custid;
		form('custname').value=a.custname;
		form('money').value=a.money;
	},'get,json');
}

function xuanwenj(o1){
	var ne = form('custname').value;
	if(!ne){
		js.msg('msg','请先选择客户');
		return;
	}
	var bh = form('num').value;
	c.xuanfile('htfileid','客户合同',''+ne+'('+bh+')的合同',o1);
}

c.uploadfileibefore=function(sna){
	if(sna=='htfileid'){
		var val = form(sna).value;
		if(val)return '最多只能上传一个文件，其他文件可到相关文件添加';
	}
}
