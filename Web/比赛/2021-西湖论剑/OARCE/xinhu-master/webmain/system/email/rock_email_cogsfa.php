<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		init:function(){
			$.get(js.getajaxurl('getset','{mode}','{dir}'), function(a){
				for(var i in a)get(''+i+'_{rand}').value = a[i];
			},'json');
		},
		save:function(o){
			var d={};
			d.sendhost = get('sendhost_{rand}').value;
			d.sendport = get('sendport_{rand}').value;
			d.recehost = get('recehost_{rand}').value;
			d.sendsecure = get('sendsecure_{rand}').value;
			d.sysname 	= get('sysname_{rand}').value;
			d.sysuser 	= get('sysuser_{rand}').value;
			d.syspass 	= get('syspass_{rand}').value;
			d.receyumi 	= get('receyumi_{rand}').value;
			if(!js.email(d.sysuser)){
				js.msg('msg','发邮件邮箱帐号的格式不对，请填写正确邮箱格式');
				return;
			}
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('setsave','{mode}','{dir}'), d, function(s){
				js.msg('success','保存成功');
			});
		},
		test:function(){
			if(ISDEMO){js.msg('success','demo上就不要测试，我们都测试通过的');return;}
			var url = js.getajaxurl('testsend','{mode}','{dir}');
			js.ajax(url,false,function(s){
				js.msg('success', s);
			},'get',false,'测试发送中...');
		}
	};
	js.initbtn(c);
	c.init();
});
</script>

<div align="left">
<div  style="padding:10px;">
	
	
		
		<table cellspacing="0" width="500" border="0" cellpadding="0">
		
		<tr>
			<td  colspan="2"><div class="inputtitle">发邮件设置</div></td>
		</tr>
		
		<tr>
			<td width="150" align="right">SMTP服务器：</td>
			<td class="tdinput"><input id="sendhost_{rand}" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right">SMTP服务器端口：</td>
			<td class="tdinput"><input id="sendport_{rand}" onfocus="js.focusval=this.value" onblur="js.number(this)" type="number" class="form-control"></td>
		</tr>
		<tr>
			<td align="right">发送方式：</td>
			<td class="tdinput"><select id="sendsecure_{rand}" class="form-control"><option value="ssl">ssl</option><option value="">默认</option></select></td>
		</tr>
		
		<tr>
			<td  colspan="2"><div class="inputtitle">系统发邮件帐号</div></td>
		</tr>
		<tr>
			<td  align="right">名称：</td>
			<td class="tdinput"><input id="sysname_{rand}" class="form-control"><font color="#888888">用于发送系统邮件的名称</font></td>
		</tr>
		<tr>
			<td  align="right">发邮件邮箱帐号：</td>
			<td class="tdinput"><input id="sysuser_{rand}" class="form-control"></td>
		</tr>
		<tr>
			<td  align="right">发邮件邮箱密码：</td>
			<td class="tdinput"><input id="syspass_{rand}" class="form-control">
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td class="tdinput"><button click="test" class="btn btn-default" type="button">测试发邮件</button>
			</td>
		</tr>
		
		<tr>
			<td  colspan="2"><div class="inputtitle">IMAP收邮件设置</div></td>
		</tr>
		
		
		<tr>
			<td  align="right">IMAP连接主机：</td>
			<td class="tdinput"><input id="recehost_{rand}" class="form-control"><font color="#888888">收邮件我们使用的IMAP协议，如是其他的，本系统上没有。</font></td>
		</tr>
		<tr>
			<td  align="right">收信邮箱域名：</td>
			<td class="tdinput"><input id="receyumi_{rand}" class="form-control">
			</td>
		</tr>
		

		
		
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp;<a href="<?=URLY?>view_email.html" target="_blank">[?查看邮件帮助]</a>
			</span>
		</td>
		</tr>
		
		</table>
	
</div>
</div>
