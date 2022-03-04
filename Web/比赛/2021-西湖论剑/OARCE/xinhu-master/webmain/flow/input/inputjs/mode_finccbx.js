function initbodys(){
	$.getScript('js/rmb.js');
	form('applydt').readOnly=true;
	form('money').readOnly=true;
	form('moneycn').readOnly=true;
	$(form('money')).click(function(){
		cchangtongss();
	});
	$(form('moneycn')).click(function(){
		cchangtongss();
	});
	addchengesss();
	
	if(mid=='0'){
		if(form('cardid'))js.ajax(geturlact('getlast'),{},function(d){
			if(d){
				form('paytype').value=d.paytype;
				form('cardid').value=d.cardid;
				form('openbank').value=d.openbank;
				form('fullname').value=d.fullname;
			}
		},'get,json');
	}
}
function addchengesss(){
	if(isedit==0)return;
	$("[name^='sdt0_']").unbind('change').change(function(){
		cchangtongss();
	});
	$("[name^='name0_']").unbind('change').change(function(){
		cchangtongss();
	});
	$("[name^='money0_']").unbind('change').change(function(){
		cchangtongss();
	});
}
function changesubmit(){
	var jg = parseFloat(form('money').value);
	if(jg<=0)return '报销金额不能小于0';
}
function changesubmitbefore(){
	cchangtongss();
}
function eventaddsubrows(){
	cchangtongss();
	addchengesss();
}
function eventdelsubrows(){
	cchangtongss();
}
function cchangtongss(){
	var d=c.getsubdata(0);
	var to=0,i,len=d.length;
	for(i=0;i<len;i++){
		if(d[i].name!=''&&d[i].sdt!=''){
			to=to+parseFloat(d[i].money);
		}
	}
	form('money').value=js.float(to)+'';
	form('moneycn').value=AmountInWords(to);
}