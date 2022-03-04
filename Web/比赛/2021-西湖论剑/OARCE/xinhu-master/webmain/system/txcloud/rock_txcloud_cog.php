<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		init:function(){
			$.get(js.getajaxurl('getset','{mode}','{dir}'), function(s){
				var a=js.decode(s);
				get('txcloud_secretid_{rand}').value=a.secretid;
				get('txcloud_secretkey_{rand}').value=a.secretkey;
				get('txcloud_rlroupid_{rand}').value=a.rlroupid;
			});
		},
		save:function(o){
			var d={};
			d.secretid = get('txcloud_secretid_{rand}').value;
			d.secretkey = get('txcloud_secretkey_{rand}').value;
			d.rlroupid = get('txcloud_rlroupid_{rand}').value;
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('setsave','{mode}','{dir}'), d, function(s){
				js.msg('success','保存成功');
			},'post');
		}
	};
	js.initbtn(c);
	c.init();

});
</script>

<div align="left">
<div  style="padding:10px;">
	
	
		
		<table cellspacing="0" width="650" border="0" cellpadding="0">
		
		<tr>
			<td  align="right"><font color=red>*</font> API密钥SecretId：</td>
			<td class="tdinput"><input id="txcloud_secretid_{rand}" onblur="this.value=strreplace(this.value)" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right"><font color=red>*</font> API密钥SecretKey：</td>
			<td class="tdinput"><input id="txcloud_secretkey_{rand}" onblur="this.value=strreplace(this.value)" class="form-control">
			<font id="showddd_{rand}" color="#888888">请到<a href="https://console.cloud.tencent.com/cam/capi" target="_blank">[腾讯云管理后台]</a>下获取。</font>
			</td>
		</tr>
		
		<tr>
			<td  colspan="2"><div class="inputtitle">产品应用设置</div></td>
		</tr>
		
		<tr>
			<td  align="right">使用人脸人员库ID：</td>
			<td class="tdinput"><input onblur="this.value=strreplace(this.value)" id="txcloud_rlroupid_{rand}" class="form-control"></td>
		</tr>
		
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>
			</span>
		</td>
		</tr>
		
		</table>
	
</div>
</div>