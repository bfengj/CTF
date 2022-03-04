var initshujubs=false,yunci=0;

function initbodys(){
	$('#AltS').before('<input type="button" style="background:#888888" onclick="return initshuju()" value="初始数据" class="webbtn">&nbsp; &nbsp;');

	$(form('uname')).blur(function(){
		chuangeusername();
	});
	
	if(mid==0)jisuantongzi();
	
	//核算的
	var actlx = js.request('actlx');
	if(actlx=='hesuan'){
		if(isedit==1){
			jisuantongzi();
			setTimeout(function(){
				initshuju(function(){
					setTimeout('hesuanwancheng()',200); //延时保存
				});
			},100);
		}else{
			try{parent.js.msgerror('无权限核算编辑');}catch(e){}
		}
	}
}

//自动核算完成
function hesuanwancheng(){
	c.boolint = 1;
	c.save();
}

function saveerror(msg){
	if(c.boolint!=1)return;
	try{parent.js.msgerror(msg);}catch(e){}
}

c.onselectdata['month']=function(){
	chuangeusername();
};

function initshuju(fun){
	var xuid=form('xuid').value,month=form('month').value;
	if(!fun)fun=function(){};
	if(xuid==''){
		js.msg('msg','请选择人员');
		return;
	}
	if(month==''){
		js.msg('msg','请选择月份');
		return;
	}
	js.ajaxbool=false;
	js.msg('wait','初始化中...');
	js.setmsg();
	initshujubs = false;
	js.ajax(geturlact('initdatas'),{'xuid':xuid,'month':month},function(adds){
		js.msg('success','初始化完成，请认真核对');
		for(var i in adds){
			if(form(i))form(i).value=adds[i];
		}
		jisuantongzi();
		initshujubs=true;
		fun();
	},'get,json');
}
function changesubmitbefore(){
	jisuantongzi();
}
function changesubmit(){
	if(!initshujubs){
		var bo1 = form('taxes') && parseFloat(form('taxes').value)==0;
		var bo2 = form('socials') && parseFloat(form('socials').value)==0;
		if(mid=='0' || bo1 || bo2){
			return '请先初始数据';
		}
	}
	
}

//个人所得税计算公式，起征点3500
function faxgeren(v){
	var jshu = 0.03;
	if(v<=0){
		return 0;
	}else if(v<=1500){
		return v*0.03;
	}else if(v<=4500){
		return v*0.10-105;
	}else if(v<=9000){
		return v*0.20-555;
	}else if(v<=35000){
		return v*0.25-1005;
	}else if(v<=55000){
		return v*0.30-2755;
	}else if(v<=80000){
		return v*0.35-5505;
	}else{
		return v*0.45-13505;
	}
	return 0;
}

//个人所得税计算公式，起征点5000
function faxgerenn(v){
	if(v<=0){
		return 0;
	}else if(v<=3000){
		return v*0.03;
	}else if(v<=12000){
		return v*0.10-210;
	}else if(v<=25000){
		return v*0.20-1410;
	}else if(v<=35000){
		return v*0.25-2660;
	}else if(v<=55000){
		return v*0.30-4410;
	}else if(v<=80000){
		return v*0.35-7160;
	}else{
		return v*0.45-15160;
	}
	return 0;
}

//公式触发
oninputblur=function(nae,zb, o1){
	jisuantongzi();
}

var jisuantongzitime;
function jisuantongzi(){
	
	yunci++;
	clearTimeout(jisuantongzitime);
	jisuantongzitime=setTimeout('yunci=0',100);
	if(yunci>10)return;

	var i,len=arr.length;
	var gw = 0,val=0,d,slx;
	if(form('postjt'))gw=parseFloat(form('postjt').value);
	var yf=gw+0,sf=gw+0;//应发,实发
	//0|字段,1|增加,2|减少,3|仅实发增加,4|仅实发减少,5|仅应发增加,6|仅应发减少
	for(i=0;i<len;i++){
		d = arr[i];
		val=0;
		slx=d.suantype;
		if(form(d.fields))val=parseFloat(form(d.fields).value);
		
		if(slx==1 || slx==5)yf=yf+val;//应发增加
		if(slx==2 || slx==6)yf=yf-val;//应发减少
		
		if(slx==1 || slx==3)sf=sf+val;//实发增加
		if(slx==2 || slx==4)sf=sf-val;//实发减少
	}
	form('money').value=js.float(sf);   //实发 
	form('mones').value=js.float(yf);   //应发 
	setTimeout('c.rungongsi()',10);
}

function chuangeusername(){
	var xuid=form('xuid').value,month=form('month').value;
	//切换人员和月份
	js.loading();
	js.ajax(geturlact('changemonth'),{'xuid':xuid,'month':month},function(a){
		if(a){
			var url = '?a='+js.request('a')+'&m=input&d=flow&num=hrsalary&mid='+a.mid+'&callback='+js.request('callback')+'';
			if(a.mid==0)url+='&xuid='+xuid+'&month='+month+'';
			js.location(url);
		}
	},'get,json');
	
	return;
	
	initshujubs=false;
	if(xuid!='')js.ajax(geturlact('changeuname'),{'xuid':xuid},function(a){
		if(a){
			form('udeptname').value=a.deptname;
			form('ranking').value=a.ranking;
		}
	},'get,json');
}