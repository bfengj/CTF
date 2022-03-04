<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		init:function(){
			$.get(js.getajaxurl('getset','{mode}','{dir}'), function(s){
				var a=js.decode(s);
				get('push_{rand}').value=a.reimpushurl;
				get('host_{rand}').value=a.reimhost;
				get('receid_{rand}').value=a.reimrecid;
				get('chehui_{rand}').value=a.reimchehui;
				get('servertype_{rand}').value=a.servertype;
				get('appwx_{rand}').checked=(a.reimappwx=='1');
			});
		},
		save:function(o){
			if(ISDEMO){js.msg('msg','演示不要操作');return;}
			var d={push:get('push_{rand}').value};
			d.host = get('host_{rand}').value;
			if(d.host && NOWURL.indexOf('https')==0 && d.host.indexOf('wss')!=0){
				js.msg('msg','使用https必须配置wss才能通信');return;
			}
			d.receid = get('receid_{rand}').value;
			d.chehui = get('chehui_{rand}').value;
			d.reimappwx = get('appwx_{rand}').checked ? '1' : '0';
			d.servertype = get('servertype_{rand}').value;
			js.setmsg('保存中...','','msgview_{rand}');
			js.ajax(js.getajaxurl('setsave','{mode}','{dir}'), d, function(s){
				js.setmsg('保存成功','green','msgview_{rand}');
			});
		},
		testss:function(){
			js.msg('wait','推送地址测试发送中...');
			$.get(js.getajaxurl('testsend','{mode}','{dir}'), function(s){
				js.msg('success',s);
			});
		},
		testsss:function(){
			var url= get('host_{rand}').value.replace('ws','http');
			if(url=='')return;
			js.confirm('看这个：如出现《<b>400 Bad Request</b>或者<b>Upgrade Required</b>》说明<font color=green>成功</font>，否则失败，打不开地址也是失败！',function(jg){
				if(jg=='yes')js.open(url,500,300);
			});
		},
		kuanshu1:function(){
			c.chengeread(true);
			js.prompt('快速设置','请输入您服务器真实IP(本地测试用127.0.0.1)',function(jg,tet){
				if(jg=='yes' && tet)c.kusnsegeo(tet);
			});
		},
		kusnsegeo:function(ips){
			var asd= ips.split('.');
			var len=asd.length;
			if(len!=4){
				js.msg('msg','服务器真实IP格式不正确');
				return;
			}
			get('push_{rand}').value='http://127.0.0.1:6553/';
			get('host_{rand}').value='ws://'+ips+':6552/';
			get('receid_{rand}').value='rockxinhu';
			js.msg('success','设置完成，请点保存，并测试验证一下地址');
		},
		kuanshu2:function(){
			c.chengeread(false);
			js.alert('请详见说明在修改设置！');
		},
		chengeread:function(bo){
			get('push_{rand}').readOnly=bo;
			get('host_{rand}').readOnly=bo;
			get('receid_{rand}').readOnly=bo;
		},
		qingkong:function(){
			get('push_{rand}').value='';
			get('host_{rand}').value='';
			get('receid_{rand}').value='';
		},
		testays:function(){
			js.wait('<span id="yibutestabc">测试异步是否可用中...</span>');
			js.ajax(js.getajaxurl('yibutest', '{mode}', '{dir}'),false,function(str){
				$('#yibutestabc').html(str);			
			});
		},
		testqueue:function(){
			js.wait('<span id="yibutestabc">测试队列是否可用中...</span>');
			js.ajax(js.getajaxurl('testqueue', '{mode}', '{dir}'),false,function(str){
				$('#yibutestabc').html(str);			
			});
		},
		dengji:function(){
			addtabs({url:'main,xinhu,dengji',name:'登记系统地址','num':'dengji'});
		},
		tongbudw:function(){
			js.loading();
			js.ajax(js.getajaxurl('tongbudw','{mode}','{dir}'),false, function(ret){
				js.msgok(ret);
			},'get');
		}
	};
	
	js.initbtn(c);
	c.init();
});
</script>

<div align="left">
<div  style="padding:10px;">
	
	
		
		<table cellspacing="0" border="0" cellpadding="0">
		
		
		<tr>
			<td  align="right"></td>
			<td class="tdinput">
			<button click="kuanshu1" class="btn btn-info" type="button">快速设置(推荐)</button>&nbsp;&nbsp;
			<button click="kuanshu2" class="btn btn-default" type="button">自定义设置</button>
			</td>
		</tr>
	
		
		<tr>
			<td  align="right"><font color=red>*</font> 通信地址：</td>
			<td class="tdinput"><input id="host_{rand}" style="width:300px" readonly class="form-control"><div><font color=#888888>用于客户端连接的通信地址,ws://开头</font>&nbsp;&nbsp;<label><input type="checkbox" id="appwx_{rand}" value="1">同时用于最新APP通信地址</label></div></td>
		</tr>
		
		<tr>
			<td  align="right" width="200"><font color=red>*</font> 服务端推送地址：</td>
			<td class="tdinput"><input id="push_{rand}"  style="width:300px" readonly class="form-control"><div><font color=#888888>用于推送到信呼客户端,http://开头</font></div></td>
		</tr>
		
		<tr>
			<td  align="right"><font color=red>*</font> recID号：</td>
			<td class="tdinput"><input id="receid_{rand}"  style="width:300px" readonly class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right">可撤回时间(分钟)：</td>
			<td class="tdinput"><input id="chehui_{rand}"  style="width:300px" placeholder="0不可撤回" type="number" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right">服务端版本：</td>
			<td class="tdinput"><select id="servertype_{rand}" style="width:300px" class="form-control"><option value="1">nodejs版(推荐)</option><option value="0">PHP版(最新版弃用)</option></select></td>
		</tr>
		
		<tr>
			<td></td>
			<td class="tdinput"><font color="#888888">不知道地址？请先安装并运行服务端，</font><a href="<?=URLY?>view_server.html" target="_blank">[去下载安装，看帮助]</a></td>
		</tr>
		
		<tr>
			
			<td style="padding:15px 0px;padding-left:150px" colspan="3" align="left"
			>
			<a href="javascript:;" click="qingkong">清空</a>&nbsp;
			<button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; 
			<button click="testsss" class="btn btn-primary" type="button">测试通信地址</button>&nbsp; 
			<button click="testss" class="btn btn-primary" type="button">测试推送地址</button>&nbsp; 
			<button click="testays" class="btn btn-default" type="button">测试异步</button>&nbsp; 
			<button click="testqueue" class="btn btn-default" type="button">测试队列</button>&nbsp; 
			
			<?php
			if(!COMPANYNUM && getconfig('platdwnum'))echo '&nbsp;&nbsp;<button click="tongbudw" class="btn btn-primary" type="button">同步到其他单位</button>';
			?>
			
			<span id="msgview_{rand}"><font color=red>测试地址前，请先保存！</font></span>
		</td>
		</tr>
		<tr>
			<td  align="left" colspan="2"><div style="margin-left:100px;max-width:600px" class="tishi">
			1、【快速设置(推荐)】请使用这个,用这个,用这个，设置完成点保存就可以了。<br>
			2、【自定义设置】用于系统和服务端不在同一个服务器，或者你修改了服务端的的配置文件。<br>
			3、通信地址测试不成功，100%就是这个原因(服务端没有安装并启动或者服务器防火墙阻止了6552的端口，需要把6552端口加入安全组让可外部访问)。<br>
			4、推送地址测试不成功，100%就是这个原因(服务端没有安装并启动)。
			</div></td>
		</tr>
	</table>
	
</div>
	
</div>
