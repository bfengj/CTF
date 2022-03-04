<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		init:function(){
			$.get(js.getajaxurl('getset','{mode}','{dir}'), function(s){
				var a=js.decode(s);
				get('reimplatpurl_{rand}').value=a.purl;
				get('reimplatcnum_{rand}').value=a.cnum;
				get('reimplatsecret_{rand}').value=a.secret;
				get('reimplatdevnum_{rand}').value=a.devnum;
				get('reimplathuitoken_{rand}').value=a.huitoken;
				get('reimplathuiurl_{rand}').value=a.huiurl;
				
			});
		},
		save:function(o){
			var d={};
			d.purl = get('reimplatpurl_{rand}').value;
			d.cnum = get('reimplatcnum_{rand}').value;
			d.secret = get('reimplatsecret_{rand}').value;
			d.devnum = get('reimplatdevnum_{rand}').value;
			d.huitoken = get('reimplathuitoken_{rand}').value;
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('setsave','{mode}','{dir}'), d, function(s){
				if(s!='ok'){
					js.msg('msg',s);
				}else{
					js.msg('success','保存成功');
				}
			},'post');
		},
		cleareshe:function(){
		
		}
	};
	js.initbtn(c);
	c.init();

});
</script>

<div align="left">
<div  style="padding:10px;">
	
	
		
		<table cellspacing="0" width="600" border="0" cellpadding="0">
		
		<tr>
			<td  align="right"><font color=red>*</font> 平台地址：</td>
			<td class="tdinput"><input onblur="this.value=strreplace(this.value)" id="reimplatpurl_{rand}" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right"><font color=red>*</font> 单位编号：</td>
			<td class="tdinput"><input onblur="this.value=strreplace(this.value)" id="reimplatcnum_{rand}" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" width="190"><font color=red>*</font> 单位secret：</td>
			<td class="tdinput">
			<textarea id="reimplatsecret_{rand}" placeholder="设置了不要暴露给他人，防止企业信息泄漏" onblur="this.value=strreplace(this.value)" style="height:60px" class="form-control"></textarea>
			</td>
		</tr>
		
		
		<tr>
			<td  align="right">默认推送应用编号：</td>
			<td class="tdinput"><input placeholder="如不设置默认第一个应用"  onblur="this.value=strreplace(this.value)" id="reimplatdevnum_{rand}" class="form-control"></td>
		</tr>
		<tr>
			<td  align="right"></td>
			<td ><div class="tishi">这个是对接<a href="<?=URLY?>view_rockreim.html" target="_blank">REIM即时通讯平台</a>的，请到对应单位下获取</a></div></td>
		</tr>
		
		<tr>
			<td  colspan="2"><div class="inputtitle">回调Token设置(没有回调就不需要设置)</div></td>
		</tr>
		
		
		<tr>
			<td  align="right">回调URL：</td>
			<td class="tdinput"><input onfocus="this.select()" id="reimplathuiurl_{rand}" readonly class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right">回调密钥：</td>
			<td class="tdinput"><input id="reimplathuitoken_{rand}" onblur="this.value=strreplace(this.value)" class="form-control"></td>
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