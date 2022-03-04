<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var c={
		init:function(){
			$.get(js.getajaxurl('getset','{mode}','{dir}'), function(s){
				var a=js.decode(s);
				get('wxgzhappid_{rand}').value=a.appid;
				get('wxgzhsecret_{rand}').value=a.secret;
				get('wxgzhtplmess_{rand}').value=a.tplmess;

			});
		},
		save:function(o){
			var d={};
			d.appid = get('wxgzhappid_{rand}').value;
			d.secret = get('wxgzhsecret_{rand}').value;
			d.tplmess = get('wxgzhtplmess_{rand}').value;
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('setsave','{mode}','{dir}'), d, function(s){
				js.msg('success','保存成功');
			},'post');
		}
		,
		testss:function(o1,lx){
			if(ISDEMO){js.msg('success','demo上就不要测试，我们都测试通过的');return;}
			js.msg('wait','测试中...');
			js.ajax(js.getajaxurl('testsend','{mode}','{dir}'),{lx:lx}, function(a){
				if(a.success){
					js.msg('success',a.msg);
				}else{
					js.msg('msg',a.msg);
				}
			},'get,json');
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
			<td  align="right"><font color=red>*</font> AppID(应用ID)：</td>
			<td class="tdinput"><input id="wxgzhappid_{rand}" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" width="180"><font color=red>*</font>AppSecret(应用密钥)：</td>
			<td class="tdinput">
			<textarea id="wxgzhsecret_{rand}" style="height:60px" class="form-control"></textarea>
			<font color="#888888">可以使用公众号的订阅号/服务号都可以，到公众号后台【开发→基本配置】下获取,<a href="<?=URLY?>view_wxgzh.html"  target="_blank">[帮助]</a></font>
			</td>
		</tr>
	
		<tr>
			<td  align="right">使用模版消息：</td>
			<td class="tdinput"><select id="wxgzhtplmess_{rand}" class="form-control"><option value="">否</option><option value="1">是(需认证的服务号，OA系统安装企业微信插件)</option></select></td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td  class="tdinput" align="left"><button click="testss,0" class="btn btn-default" type="button">测试是否有效</button>
		</td>
		</tr>
		
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>
			</span>
		</td>
		</tr>
		

	
</div>
</div>