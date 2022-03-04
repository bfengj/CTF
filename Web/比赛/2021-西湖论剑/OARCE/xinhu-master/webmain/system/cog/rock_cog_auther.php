<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var barr = {};
	var c={
		save:function(o1){
			var key = get('autherkey_{rand}').value,mvd='msgview{rand}';
			if(!key){js.setmsg('请输入签授密钥','',mvd);return;}
			o1.disabled=true;
			$('#savewen{rand}').hide();
			js.setmsg('验证中...','',mvd);
			js.ajax(js.getajaxurl('saveauther','{mode}','{dir}'),{key:key,ym:HOST}, function(s){
				if(s!='ok'){
					js.setmsg(s,'',mvd);
					o1.disabled=false;
				}else{
					js.setmsg('验证成功','green',mvd);
					c.load();
				}
			},'post',false);
		},
		wenyz:function(o1){
			var key = get('autherkey_{rand}').value,mvd='msgview{rand}';
			if(!key){js.setmsg('请输入签授密钥','',mvd);return;}
			o1.disabled=true;
			$('#savebtn{rand}').hide();
			js.setmsg('验证中...','',mvd);
			js.ajax(js.getajaxurl('savelixian','{mode}','{dir}'),{key:key,ym:HOST}, function(s){
				if(s!='ok'){
					js.setmsg(s,'',mvd);
					o1.disabled=false;
				}else{
					js.setmsg('验证成功','green',mvd);
					c.load();
				}
			},'post',false);
		},
		load:function(){
			var mvd='msgview{rand}';
			js.setmsg('获取中...','',mvd);
			js.ajax(js.getajaxurl('auther','{mode}','{dir}'),false, function(ret){
				var da = ret.data;
				js.setmsg('','',mvd);
				if(da.use=='1'){
					$('#auther_kq{rand}').html('<font color="green">已签授</font>');
					get('autherkey_{rand}').readOnly=true;
					get('autherkey_{rand}').value=da.aukey;
					$('#auther_enddt{rand}').html(jm.uncrypt(da.enddt));
					var str = jm.uncrypt(da.yuming);
					if((','+str+',').indexOf(','+HOST+',')<0)str+='<font style="font-size:12px" color="red">(与当前'+HOST+'域名不符合)</font>';
					$('#auther_yuming{rand}').html(str);
					$('#savebtn{rand}').hide();
					$('#savewen{rand}').hide();
					$('#savedel{rand}').show();
				}else{
					$('#auther_kq{rand}').html('<font color="red">未签授</font>');
					$('#auther_enddt{rand}').html('-');
					$('#auther_yuming{rand}').html('-');
					get('autherkey_{rand}').value='';
					get('autherkey_{rand}').readOnly=false;
					$('#savebtn{rand}').show();
					$('#savewen{rand}').show();
					$('#savedel{rand}').hide();
				}
			},'get,json');
		},
		savedel:function(o1){
			js.confirm('确定要删除签授吗？', function(jg){if(jg=='yes')c.savedel2(o1);});
		},
		savedel2:function(o1){
			var mvd='msgview{rand}';
			js.setmsg('删除中...','',mvd);
			o1.disabled=true;
			js.ajax(js.getajaxurl('autherdel','{mode}','{dir}'),false, function(ret){
				if(ret.success){
					js.setmsg('','',mvd);
					c.load();
				}else{
					js.setmsg(ret.msg,'',mvd);
				}
			},'get,json');
		},
		tongbudw:function(){
			js.loading();
			js.ajax(js.getajaxurl('tongbudw','{mode}','{dir}'),false, function(ret){
				js.msgok(ret);
			},'get');
		}
	};
	js.initbtn(c);
	c.load();
});
</script>

<div style="padding:10px;" align="center">
	<div align="left" style="width:400px">
	
	<table cellspacing="0"  border="0" cellpadding="0">
		
		<tr>
			<td colspan="2"><h4>系统签授(这个跟<font color=red>授权</font>没有任何关系)</h4></td>
		</tr>
		<tr>
			<td colspan="2"><div style="font-size:13px;color:#888888;padding-bottom:20px">是为了防止系统盗版使用，需要把你部署的系统签名授权，绑定了域名和有效时间，签授后才能有效享有系统功能，更好的升级维护，登录<a href="<?=URLY?>" target="_blank">官网</a>用户中心→系统签授，获取签授密钥。</div></td>
		</tr>
		<tr>
			<td height="40" width="70">签授情况</td>
			<td><span id="auther_kq{rand}"><font color="red">未签授</font></span> <a click="load" href="javascript:;"><i class="icon-refresh"></i></a></td>
		</tr>
		<tr>
			<td height="40">有效期至</td>
			<td id="auther_enddt{rand}">-</td>
		</tr>
		<tr>
			<td height="40">绑定域名</td>
			<td><div id="auther_yuming{rand}" class="wrap">-</div></td>
		</tr>
		<tr>
			<td height="60">签授密钥</td>
			<td ><input id="autherkey_{rand}" readOnly placeholder="可从官网用户中心中获取" style="width:300px" class="form-control"></td>
		</tr>
		<tr>
			<td ></td>
			<td style="padding-top:20px">
			<button click="save" id="savebtn{rand}" style="display:none" class="btn btn-success" type="button"><i class="icon-key"></i> 提交验证</button>&nbsp;&nbsp;
			<button click="wenyz" id="savewen{rand}" style="display:none" class="btn btn-primary" type="button"><i class="icon-file"></i> 文件验证</button>
			<button click="savedel" id="savedel{rand}" style="display:none" class="btn btn-danger" type="button"><i class="icon-key"></i> 删除签授</button>
			<?php
			if(!COMPANYNUM && getconfig('platdwnum'))echo '&nbsp;&nbsp;<button click="tongbudw" class="btn btn-primary" type="button">同步到单位数据</button>';
			?>
			&nbsp;<span id="msgview{rand}"></span></td>
		</tr>
	</table>
	
	</div>
</div>