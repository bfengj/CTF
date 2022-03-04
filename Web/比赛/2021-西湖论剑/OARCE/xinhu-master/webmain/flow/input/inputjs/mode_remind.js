//流程模块【remind.单据提醒设置】下录入页面自定义js页面,初始函数
function initbodys(){
	$(form('startdt')).blur(function(){
		gettotal();
	});
}
function changerate(o1){
	changeshowval(o1,0);
}
function changeblur(o1){
	changeshowval(o1,1);
}
function changeblur2(o1){
	gettotal();
}
//新增记录
function changeadd(o1){
	var html = $(o1).parent().html();
	html = html.replace('rockdatepickerbool="true"','');
	html = html.replace('rockdatepickerbool="true"','');
	var o2 = $('#pinlv').append('<div style="padding-top:10px;margin-top:10px;border-top:1px #cccccc solid">'+html+'</div>');
	var inps = $('#pinlv div:last').find('input')[0];
	inps.value='';
	changeshowval(inps,1, 1);
}
function changejian(o1){
	var len = $('#pinlv div').length;
	if(len<=1){
		js.msg('msg','最后一行就不要删了');return;
	}
	$(o1).parent().remove();
	gettotal();
}
function changeshowval(o1,lx, isf){
	var o     = $(o1).parent();
	var sel   = o.find('select:eq(0)')[0];
	var span  = o.find('span:eq(0)');
	var font  = o.find('font:eq(0)');
	var vals  = o.find('input:eq(0)');
	var str   = '',rate=sel.value,jg = 11,val=vals.val();
	if(rate=='h'){
		font.show();
	}else{
		font.hide();
	}
	if(lx==0){
		vals.val('');
		gettotal();
		return;
	}
	if(isf==1){
		o.find('input:eq(1)').val('');
		return;
	}
	if(rate=='o')jg=0;
	if(rate=='m')jg=8;
	if(rate=='y')jg=5;
	if(rate=='h' || rate=='b')jg=14;
	if(!isempt(val) && val.indexOf('-')>0){
		str = sel.options[sel.selectedIndex].text;
		str+=' '+val.substr(jg)+'';
		vals.val(val.substr(jg));
	}
	gettotal();
}

function changesubmitbefore(){
	gettotal();
};

function gettotal(){
	var selobj = $("select[name='rave_pinlvs1']");
	var inpobj = $("input[name='rave_pinlvs2']");
	var inpsobj = $("input[name='rave_pinlvs3']");
	var i,rate1='',rate2='',rate3='',v1,v2,v3,v4;
	var sts = form('startdt').value;
	if(sts){
		sts = sts.substr(11);
	}else{
		sts = '00:00:00';
	}
	for(i=0;i<selobj.length;i++){
		v1= selobj[i].value;
		v3= selobj[i].options[selobj[i].selectedIndex].text;
		v2= inpobj[i].value;
		v4= inpsobj[i].value;
		if(v2!=''){
			rate1+=','+v1+'';
			rate2+=','+v2+'';
			rate3+=','+v3+' '+v2+'';
			if(v4 && (v1=='h')){
				rate2+='|'+v4+'';
				rate3+=' 每天从'+sts+'至'+v4+'提醒';
			}
		}
	}
	if(rate1!=''){
		rate1 = rate1.substr(1);
		rate2 = rate2.substr(1);
		rate3 = rate3.substr(1);
	}
	form('rate').value=rate1;
	form('rateval').value=rate2;
	form('ratecont').value=rate3;
}