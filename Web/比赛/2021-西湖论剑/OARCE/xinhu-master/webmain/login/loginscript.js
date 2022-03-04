var oldpass='',initlogo='images/logo.png',olduser,loginyzm='',mobilejsho='',abcpass='';

function getpassobj(){
	return $('input[type=password]');
}

function initbody(){
	var ltype = form('logintype').value;
	form('adminmobile').value=js.getoption('adminmobile');
	if(ltype=='0'){
		form('adminuser').focus();
		oldpass	= getpassobj().val();
		olduser	= form('adminuser').value;
		if(form('adminuser').value!=''){
			getpassobj().focus();
		}
		$(form('adminuser')).change(function(){
			changeuserface(this.value);
		});
	}else{
		olduser	= form('adminmobile').value;
	}
	
	resizewh();
	$(window).resize(resizewh);
	var sf = js.getoption('loginface');
	if(sf)get('imglogo').src=sf;
}


function resizewh(){
	var h = ($(document).height()-530)*0.5;
	$('#topheih').css('height',''+h+'px');
}
function changeuserface(v){
	var sf = js.getoption('loginface');
	if(!sf)return;
	if(v==''||v!=olduser){
		get('imglogo').src=initlogo;
	}else{
		get('imglogo').src=sf;
	}
}
function loginsubmit(){
	if(js.bool)return false;
	var ltype = form('logintype').value,user='',pass='';
	var data	= {};
	if(!abcpass){
		if(ltype=='0'){
			user = form('adminuser').value;
			pass = getpassobj().val();
			
			if(user==''){
				js.setmsg('用户名不能为空','red');
				form('adminuser').focus();
				return false;
			}
			if(pass==''){
				js.setmsg('密码不能为空','red');
				getpassobj().focus();
				return false;
			}
			data.rempass = form('rempass').checked ? '1':'0'; //记住密码？
		}else{
			user = form('adminmobile').value;
			if(user==''){
				js.setmsg('手机号不能为空','red');
				form('adminmobile').focus();
				return false;
			}
			js.setoption('adminmobile', user);
			loginyzm = form('adminmobileyzm').value;
			if(loginyzm=='' || loginyzm.length!=6){
				js.setmsg('手机验证码格式不对','red');
				form('adminmobileyzm').focus();
				return false;
			}
		}
	}
	js.tanstyle = 1;
	js.setmsg('登录中...','blue');
	form('button').disabled=true;
	if(abcpass){
		user = form('adminuser').value;
		pass = abcpass;
		ltype= '0';
	}
	var url		= js.getajaxurl('check','login');
	data.jmpass	= 'false';
	data.device = device;
	data.ltype  = ltype;
	data.adminuser = jm.base64encode(user);
	data.adminpass = jm.base64encode(pass);
	data.yanzm    = loginyzm;
	if(oldpass==pass)data.jmpass= 'true';
	js.bool		= true;
	loginyzm	= '';
	
	js.ajax(url,data,function(a){
		abcpass = '';
		if(a.success){
			get('imglogo').src=a.face;
			js.setoption('loginface', a.face);
			var burl = js.request('backurl');
			var curl = (burl=='')?NOWURL:jm.base64decode(burl);
			js.setmsg('登录成功,<a href="'+curl+'">跳转中</a>...','green');
			js.location(curl);
		}else{
			js.setmsg(a.msg,'red');
			form('button').disabled=false;
			js.bool	= false;
			if(a.shouji){
				mobilejsho = a.mobile;
				js.prompt('输入手机验证码','手机号：'+a.shouji+'&nbsp;<span><a class="zhu" href="javascript:;" onclick="getcodes(this)">[获取验证码]</a></span>',function(jg,txt){
					if(jg=='yes' && txt){
						loginyzm = txt;
						loginsubmit();
					}
				});
			}
		}
	},'post,json');
}

function getcodes(o1){
	var da = {'mobile':mobilejsho,'device':device};
	var o2 = $(o1).parent();
	o2.html(js.getmsg('获取中...'));
	js.ajax('api.php?m=yanzm',da,function(a){
		if(a.success){
			o2.html(js.getmsg('获取成功','green'));
		}else{
			o2.html(js.getmsg(a.msg));
		}
	},'post,json');
}

//获取验证码
function getyzm(o1){
	mobilejsho = form('adminmobile').value;
	if(!mobilejsho){
		js.setmsg('请输入手机号');
		form('adminmobile').focus();
		return;
	}
	var da = {'mobile':mobilejsho,'device':device};
	o1.value = '获取中...';
	js.setmsg();
	o1.disabled=true;
	js.ajax('api.php?m=yanzm&a=glogin',da,function(a){
		if(a.success){
			o1.value = '获取成功';
			js.msg('success', '验证码已发送到手机上');
			form('adminmobileyzm').value=a.data;
			dshitime(60, o1);
		}else{
			o1.value = '重新获取';
			o1.disabled=false;
			js.setmsg(a.msg);
		}
	},'post,json');
}

function dshitime(sj,o1){
	if(sj==0){
		o1.disabled=false;
		o1.value='重新获取';
		return;
	}
	o1.disabled=true;
	o1.value=''+sj+'';
	setTimeout(function(){dshitime(sj-1, o1)},1000);
}

function changlogin(){
	$('#loginview0').hide();
	$('#loginview1').show();
	form('logintype').value='1';
}

function erwmlogin(){
	$('#mainlogin').html('<div style="height:350px" align="center"><div style="padding-top:50px;height:200px;overflow:hidden;"><img src="images/noimg.jpg" id="logeweerew" height="200" width="200"></div><div style="color:#888888;padding-top:5px"><span id="miaoshuv">支持任何扫码登录</span>，还有<span id="miaoshu">60</span>秒，<a class="zhu" href="javascript:;" onclick="js.reload()">使用默认登录</a></div></div>');
	
	var stra    = parseInt(Math.random()*999999);
	randkey = js.getoption('ewmrandkey', 'ewm'+stra+'');
	js.setoption('ewmrandkey', randkey);
	get('logeweerew').src='api.php?m=login&a=getewm&randkey='+randkey+'&dfrom=pc';
	starttimest(60);
}

function starttimest(ms){
	if(!get('miaoshu'))return;
	if(ms<0){
		$('#miaoshu').parent().html('<font color=#888888>二维码已过期，请重新打开</font>');
		return;
	}
	$('#miaoshu').html(''+ms+'');
	if(ms<57){
		$.getJSON('api.php?m=login&a=checkewm&randkey='+randkey+'&dfrom=pc',function(ret){
			setTimeout('starttimest('+(ms-1)+')',1000);
			var dst = ret.data.val;
			if(dst=='0'){
				$('#miaoshuv').html('<font color=green>请在手机按确认登录</font>');
			}
			if(dst=='-1'){
				$('#miaoshu').parent().html('<font color=#888888>已取消，请重新打开</font>');
			}
			if(dst>0){
				$('#miaoshu').parent().html('<span id="msgview"><font color=#ff6600><img src="images/loadings.gif" align="absmiddle"> 已确认，登录中...</font></span>');
				var da = ret.data;
				var url= js.getajaxurl('check','login');
				var data={};
				data.device = device;
				data.ltype  = 0;
				data.adminuser = jm.base64encode(da.user);
				data.adminpass = jm.base64encode(da.pass);
				js.ajax(url,data,function(a){
					if(a.success){
						js.setoption('loginface', a.face);
						var burl = js.request('backurl');
						var curl = (burl=='')?NOWURL:jm.base64decode(burl);
						js.setmsg('登录成功,<a href="'+curl+'">跳转中</a>...','green');
						js.location(curl);
					}else{
						js.setmsg(a.msg,'red');
					}
				},'post,json');
			}
		});
	}else{
		setTimeout('starttimest('+(ms-1)+')',1000);
	}
}


function reimplatlogin(){
	js.loading('登录中...');
	js.ajax('api.php?m=login&a=reimplatlogin',false,function(ret){
		if(ret.success){
			var da = ret.data;
			form('adminuser').value = da.user;
			abcpass = da.pass;
			loginsubmit();
		}else{
			js.msgerror(ret.msg);
		}
	},'get,json');
}