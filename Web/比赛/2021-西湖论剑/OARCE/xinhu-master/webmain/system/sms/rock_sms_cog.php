<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var utype = '0';
	var c={
		init:function(){
			var o = get('btnss{rand}');
			o.value='加载中...';
			js.ajax(js.getajaxurl('gettotal','{mode}','{dir}'),false,function(ret){
				var curl = '';
				if(ret.success){
					$('#stotal{rand}').html(ret.data.smsinfo);
					$('#typetext{rand}').html(ret.data.typetext);
					//if(ret.data.automy=='1')$('#tessh{rand}').show();
					curl = ret.data.chongurl;
					utype = ret.data.type;
				}else{
					js.msg('msg', ret.msg);
				}
				
				get('sms_iscb_{rand}').value=ret.sms_iscb;
				get('sms_cbnum_{rand}').value=ret.sms_cbnum;
				get('sms_apikey_{rand}').value=ret.sms_apikey;
				get('sms_txnum_{rand}').value=ret.sms_txnum;
				get('sms_mknum_{rand}').value=ret.sms_mknum;
				get('sms_qmnum_{rand}').value=ret.sms_qmnum;
				
				get('sms_yanzm_{rand}').value=ret.sms_yanzm;
				if(ret.sms_dirtype!=''){
					$('#stotal{rand}').html('请到对应短信平台下查看');
					curl='https://www.aliyun.com';
					$('#tessh{rand}').hide();
					get('sms_apikey_{rand}').value='';
				}
				
				get('chong{rand}').href=curl;
				o.value='刷新';
				var arr = ret.pingtarr;
				if(arr.length>0){
					var oo2 = get('sms_dirtype_{rand}');
					oo2.length=1;
					js.setselectdata(oo2, arr,'value');
				}
				
				//短信驱动
				get('sms_dirtype_{rand}').value=ret.sms_dirtype;
				
			},'get,json');
		},
		ceshi:function(){
			if(get('sms_apikey_{rand}').value!=''){
				js.alert('设置了“短信APIKEY”不需要测试发送。');
				return;
			}
			js.prompt('测试发短信','输入手机号码(测试成功会扣除一条短信)',function(lx,txt){
				if(lx=='yes'&&txt)c.sheniokx(txt)
			});
		},
		sheniokx:function(sj){
			js.msg('wait','发送中...');
			var lxs = get('sms_dirtype_{rand}').value;
			js.ajax(js.getajaxurl('testsend','{mode}','{dir}'),{mobile:sj,dirtype:lxs},function(ret){
				if(ret.success){
					js.msg('success','测试发送成功');
					c.init();
				}else{
					js.msg('msg', ret.msg);
				}
			},'get,json');
		},
		
		save:function(){
			var lxs = get('sms_dirtype_{rand}').value;
			var da  = {
				sms_iscb:get('sms_iscb_{rand}').value,
				sms_cbnum:get('sms_cbnum_{rand}').value,
				sms_apikey:'',
				sms_mknum:get('sms_mknum_{rand}').value,
				sms_qmnum:get('sms_qmnum_{rand}').value,
				sms_txnum:get('sms_txnum_{rand}').value,
				sms_yanzm:get('sms_yanzm_{rand}').value,
				sms_dirtype:lxs
			};
			
			if(da.sms_qmnum=='' && lxs!=''){
				js.msg('msg','使用短信签名不能为空，请输入如：信呼OA，更多查看帮助');
				return;
			}
			
			if(lxs=='alisms' && da.sms_cbnum.substr(0,4)!='SMS_'){
				js.msg('msg','流程短信催办模版编号格式不对，必须SMS_开头，请查看帮助');
				return;
			}
			
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('cogsave','{mode}','{dir}'),da,function(ret){
				js.msg('success','保存成功');
			},'post');
		},
		changetype:function(o1){
			var val = o1.value;
			if(val!=''){
				if(utype=='0'){
					js.alert('不是官网vip用户无法切换短信平台');
					o1.value='';
					return;
				}
				$('#stotal{rand}').html('请到对应短信平台下查看');
			}else{
				$('#stotal{rand}').html('请到刷新查看');
			}
		}
	};
	js.initbtn(c);
	c.init();
	
	$('#sms_dirtype_{rand}').change(function(){
		c.changetype(this);
	});
});
</script>

<div align="left">
<div  style="padding:10px;">
		
	<table cellspacing="0" width="550" border="0" cellpadding="0">
	
	<tr>
		<td  colspan="2"><div class="inputtitle">基本信息
		</div></td>
	</tr>
	
	<tr>
		<td  align="right">短信发送平台：</td>
		<td class="tdinput"><select id="sms_dirtype_{rand}" style="width:200px" class="form-control"><option value="">信呼短信服务</option><option value="alisms">阿里云(短信服务)</option></select></td>
	</tr>

	<tr>
		<td  align="right" width="180">剩余短信：</td>
		<td class="tdinput"><span id="stotal{rand}">0.00元(0条)</span>，<a id="chong{rand}" class="btn btn-success btn-xs" href="<?=URLY?>user_index_sms_a.html" target="_blank">去充值</a></td>
	</tr>
	
	<tr>
		<td  align="right" width="180">官网用户类型：</td>
		<td class="tdinput"><span id="typetext{rand}">普通用户</span></td>
	</tr>
	
	
	<tr style="display:none" id="tessh{rand}">
		<td  align="right">短信APIKEY：</td>
		<td class="tdinput"><input id="sms_apikey_{rand}" placeholder="这是从短信平台上获取，可不用写" style="width:250px" class="form-control">&nbsp;<a href="<?=URLY?>view_smsapi.html" target="_blank">如何获取?</a></td>
	</tr>
	
	<tr>
		<td  align="right" ></td>
		<td class="tdinput">
		<input type="button" click="init" id="btnss{rand}" value="刷新" class="btn btn-default">&nbsp; &nbsp;
		<a href="<?=URLY?>view_dxto.html" target="_blank">[使用帮助]</a>
		</td>
	</tr>
	
	<tr>
		<td  colspan="2"><div class="inputtitle">功能设置</div></td>
	</tr>
	
	<tr>
		<td  align="right">使用短信签名编号：</td>
		<td class="tdinput"><input id="sms_qmnum_{rand}" placeholder="编号到[短信签名]下获取，可不用设置。"  maxlength="50" value="" class="form-control"></td>
	</tr>
	
	<tr>
		<td  align="right">流程是否开短信催办：</td>
		<td class="tdinput"><select id="sms_iscb_{rand}" style="width:200px" class="form-control"><option value="0">不开启</option><option value="1">开启</option></select></td>
	</tr>
	
	<tr>
		<td  align="right">短信验证码模版编号：</td>
		<td class="tdinput"><input id="sms_yanzm_{rand}"  maxlength="50" value="" class="form-control"></td>
	</tr>
	
	<tr>
		<td  align="right">流程短信催办模版编号：</td>
		<td class="tdinput"><input id="sms_cbnum_{rand}"  maxlength="50" value="defnum" class="form-control"></td>
	</tr>
	
	<tr>
		<td  align="right">短信提醒给<font color=white>：</font><br>审批人模版编号：</td>
		<td class="tdinput"><input id="sms_txnum_{rand}"  maxlength="50" placeholder="编号到[短信模版]下获取，建议使用：default"  class="form-control"><div style="padding-top:0" class="tishi">设置了,有流程的模块当申请人提交时会短信提醒给审批人</div></td>
	</tr>
	
	<tr>
		<td  align="right">要提醒的模块编号：</td>
		<td class="tdinput"><textarea id="sms_mknum_{rand}" style="height:60px" class="form-control"></textarea><div style="padding-top:0" class="tishi">编号到[流程模块→流程模块列表]下获取,多个用,分开，写all代表所有模块。</div></td>
	</tr>
	
	<tr>
		<td  align="right"></td>
		<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>
		&nbsp;&nbsp;
		<input type="button" click="ceshi" id="test{rand}" value="测试发送" class="btn btn-default">
	</td>
	</tr>
	
	
	</table>
	<div class="tishi"></div>
</div>
</div>
