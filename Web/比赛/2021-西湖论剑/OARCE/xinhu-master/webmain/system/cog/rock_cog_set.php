<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var barr = {};
	var c={
		init:function(){
			this.loadys();
			js.ajax(js.getajaxurl('getinfo','{mode}','{dir}'),{},function(a){
				barr = a;
				for(var i in a)$('#'+i+'_{rand}').val(a[i]);
				if(a.isshou=='1')$('#benquan_{rand}').html('<font color=green>授权版</font>');
				if(isempt(a.asyntest))get('asynsend_{rand}').length=1;
				if(a.officebj=='1')$('#divofficebj_key').show();
			},'get,json');
		},
		loadys:function(){
			if(!get('defstype_{rand}'))return;
			var ysarr = 'cerulean,cosmo,cyborg,darkly,flatly,journal,lumen,paper,readable,sandstone,simplex,slate,spacelab,superhero,united,xinhu,yeti';
			var sear = ysarr.split(','),i,len=sear.length,das = [];
			for(i=0;i<len;i++){
				das.push({name:sear[i],value:i+1});
				das.push({name:sear[i]+'_def',value:i+1+len});
			}
			js.setselectdata(get('defstype_{rand}'),das,'value');
		},
		isurl:function(na,dz){
			if(dz){
				if(dz.substr(0,4)!='http')return ''+na+'必须http开头';
				if(dz.substr(-1)!='/')return ''+na+'必须/结尾';
			}
			return '';
		},
		save:function(o){
			var d={};
			for(var i in barr){
				d[i] = $('#'+i+'_{rand}').val();
			}
			if(d.title==''){
				js.msg('msg','系统标题不能为空');
				return;
			}
			var keys = d.xinhukey;
			if(keys && keys.indexOf('*')==-1 && keys.length!=32){
				js.msg('msg','官网key格式有误，是32位md5格式，请看帮助获取');
				get('xinhukey_{rand}').focus();
				return;
			}
			if(d.officeyl==5 && d.officebj!=1){
				js.msg('msg','文档在线编辑请选第二个，否则无法使用');
				get('officebj_{rand}').focus();
				return;
			}
			if(d.platurl && d.platurl==NOWURL){
				js.msg('msg','平台使用地址不能跟当前地址一样');
				get('platurl_{rand}').focus();
				return;
			}
			js.ajax(js.getajaxurl('savecong','{mode}','{dir}'), d, function(s){
				if(s!='ok')js.msg('msg', s);
			},'post',false,'保存中...,保存成功');
		},
		blurls:function(o){
			var val = strreplace(o.value);
			if(val=='')return;
			if(val.substr(0,4)!='http')val='http://'+val+'';
			var la  = val.substr(-1);
			if(la!='/')val+='/';
			o.value=val;
		},
		auther:function(){
			addtabs({name:'系统签授',num:'auther',url:'system,cog,auther',icons:'key'});
		},
		changebj:function(o1){
			if(o1.value=='1'){
				$('#divofficebj_key').show();
			}else{
				$('#divofficebj_key').hide();
			}
		},
		xuanys:function(){
			var zys = ['#1389D3','#99cc66','#003366','#6666CC','#CC3333','#009966','#333333','#990066','#333300','#333366','#99CC99','#663366','#003399','#666699'];
			var h = '<div style="padding:10px"><table class="cursor"><tr>';
			for(var i=0;i<zys.length;i++){
				h+='<td width="20" onclick="xuancolora{rand}(\''+zys[i]+'\')" width="20" bgcolor="'+zys[i]+'">&nbsp;</td>';
			}
			h+='</tr></table></div>';
			js.tanbody('color','选择主题颜色',310,200,{
				html:h
			});
		}
	};
	js.initbtn(c);
	c.init();
	
	$('#url_{rand}').blur(function(){
		c.blurls(this);
	});
	$('#localurl_{rand}').blur(function(){
		c.blurls(this);
	});
	$('#platurl_{rand}').blur(function(){
		c.blurls(this);
	});
	$('#outurl_{rand}').blur(function(){
		c.blurls(this);
	});
	$('#officebj_{rand}').change(function(){
		c.changebj(this);
	});
	xuancolora{rand}=function(col){
		get('apptheme_{rand}').value=col;
		js.tanclose('color');
	}
});
</script>

<div align="left">
<div  style="padding:10px;">

		
		<table cellspacing="0" width="900" border="0" cellpadding="0">
		
		<tr>
			<td  colspan="4"><div class="inputtitle">基本信息
			<div style="padding:5px;line-height:18px;font-size:12px;color:#888888">此保存在配置文件下，也可以自己打开配置文件(webmain/webmainConfig.php)来修改</div>
			</div></td>
		</tr>
		
		<tr>
			<td  colspan="4" class="tdinput" ><div align="center" style="line-height:30px">系统版本：<b  id="benquan_{rand}" style="font-size:20px"><font color=red>开源版</font></b><input id="isshou_{rand}" type="hidden" class="form-control">&nbsp;&nbsp;<button click="auther" class="btn btn-success btn-xs">系统签授</button></div></td>
		</tr>
	
		<tr>
			<td  align="right" width="15%" width="180">系统标题：</td>
			<td class="tdinput"  width="35%" ><input id="title_{rand}" class="form-control"></td>
		
			<td  align="right"  width="15%" >APP移动端上标题：</td>
			<td class="tdinput"  width="35%"><input id="apptitle_{rand}" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right">REIM即时通信标题：</td>
			<td class="tdinput"><input id="reimtitle_{rand}" class="form-control"></td>
			
			<td  align="right"><a target="_blank" href="<?=URLY?>">信呼官网</a>key：</td>
			<td class="tdinput"><input id="xinhukey_{rand}" class="form-control">
			<font color="#888888">用于在线升级使用,看<a target="_blank" href="<?=URLY?>view_xhkey.html">[帮助]</a>获取</font></td>
		</tr>
		
		<tr>
			<td  align="right">系统URL地址：</td>
			<td class="tdinput"><input id="url_{rand}" placeholder="为空默认自动识别" class="form-control"><font color="#888888">可以为空不用设置的，<a onclick="get('url_{rand}').value=''" href="javascript:;">[清空]</a></font></td>
		
			<td  align="right">系统本地地址：</td>
			<td class="tdinput"><input id="localurl_{rand}" class="form-control">
			<font color="#888888">用于计划任务异步任务使用，没有可跟系统URL一样</font></td>
		</tr>
		
		
		<tr>
			<td  align="right">系统外网地址：</td>
			<td class="tdinput"><input id="outurl_{rand}" placeholder="不知道做啥的，就不要去设置" class="form-control"></td>
			<?php if(getconfig('platdwnum')){?>
			<td  align="right">平台使用地址：</td>
			<td class="tdinput"><input id="platurl_{rand}" placeholder="不能跟当前地址一样" class="form-control"></td>
			<?php }?>
		</tr>
		
		<tr>
			<td  align="right">系统主题色：</td>
			<td class="tdinput">
				<div  class="input-group">
			<input placeholder="RGB格式颜色" class="form-control" id="apptheme_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="xuanys" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
			
			</td>
			
		</tr>
	
		
		<tr>
			<td  colspan="4"><div class="inputtitle">高级设置</div></td>
		</tr>
		
		<tr>
			<td  align="right">debug模式：</td>
			<td class="tdinput"><select id="debug_{rand}"  class="form-control"><option value="0">上线模式</option><option value="1">开发调试模式</option></select></td>
			
			<td  align="right">操作数据库驱动：</td>
			<td class="tdinput"><select id="db_drive_{rand}"  class="form-control"><option value="mysql">mysql(不推荐)</option><option value="mysqli">mysqli</option><option value="pdo">pdo</option></select></td>
		</tr>
		
		<tr>
			<td  align="right">异步任务key：</td>
			<td class="tdinput"><input id="asynkey_{rand}" class="form-control"></td>
	
			<td  align="right">对外接口openkey：</td>
			<td class="tdinput"><input id="openkey_{rand}" class="form-control"></td>
		</tr>
		
	
		
		<tr>
			<td  align="right">提醒消息发送方式：</td>
			<td class="tdinput"><select id="asynsend_{rand}"  class="form-control"><option value="0">同步发送</option><option value="1">异步发送(自己服务端)</option></select>
			<font color="#888888">提醒消息发送微信消息提示发送，邮件提醒发送等，异步发送能大大提高效率。</font></td>
		
			<td  align="right">是否记录访问sql日志：</td>
			<td class="tdinput"><select id="sqllog_{rand}"  class="form-control"><option value="0">否</option><option value="1">是</option></select><font color="#888888">开启了日志将记录在目录<?=UPDIR?>/sqllog下</font></td>
		</tr>
		
		<tr>
			<td  align="right">详情上线条颜色：</td>
			<td class="tdinput"><input id="bcolorxiang_{rand}" placeholder="用于单据详情默认颜色" maxlength="7" class="form-control"></td>
		
			<td  align="right">PC首页显示：</td>
			<td class="tdinput">REIM：<select id="reim_show_{rand}" ><option value="0">不显示</option><option value="1">显示</option></select>&nbsp;&nbsp;手机版：<select id="mobile_show_{rand}" ><option value="0">不显示</option><option value="1">显示</option></select></td>
		</tr>
	
		
		<tr>
			<td  align="right">腾讯地图KEY：</td>
			<td class="tdinput"><input id="qqmapkey_{rand}" placeholder="可不设置，可到https://lbs.qq.com/下申请" class="form-control"></select></td>
			
			<td  align="right">登录方式：</td>
			<td class="tdinput"><select id="loginyzm_{rand}"  class="form-control"><option value="0">仅使用帐号+密码</option><option value="1">帐号+密码/手机+手机验证码</option><option value="2">帐号+密码+手机验证码</option><option value="3">仅使用手机+手机验证码</option></select></td>
			
		</tr>
		
			
		<tr>
			<td  align="right">文档在线预览：</td>
			<td class="tdinput"><select id="officeyl_{rand}"  class="form-control"><option value="0">自己服务器安装office转PDF服务</option>
			<option value="1">使用官网插件(官网VIP专用)，不需要安装任何插件。</option>
			<option value="2">使用微软在线预览office服务(需要公网域名)</option>
			<option value="4">自己服务器安装LibreOffice转PDF服务(Win和Linux)都可以部署</option>
			<option value="5">使用在线编辑服务预览(→右边那需要选第二个)</option>
			</select></td>
			
			<td  align="right">文档在线编辑：</td>
			<td class="tdinput">
			<select id="officebj_{rand}"  class="form-control"><option value="">安装客户端在线编辑插件</option><option value="1">官网提供在线编辑服务(官网VIP专用)</option></select>
			<div id="divofficebj_key" style="display:none">在线编辑agentkey，看<a target="_blank" href="<?=URLY?>view_agentkey.html">[帮助]</a>获取<input class="form-control" id="officebj_key_{rand}"></div>
			</td>
			
		</tr>
		
		<tr>
			<td  align="right">PC后端默认主题：</td>
			<td class="tdinput"><select id="defstype_{rand}" style="width:80px"></select>&nbsp;必须去<a href="<?=URLY?>view_themes.html" target="_blank">下载主题包</a>，否则不能使用</td>
			
			<td  align="right">记录用户操作：</td>
			<td class="tdinput"><select id="useropt_{rand}"  class="form-control"><option value="">不记录</option><option value="1">记录到日志里</option></select></td>
			
		</tr>
		
		<tr>
			<?php if(!getconfig('platdwnum')){?>
			<td align="right">多单位模式：</td>
			<td class="tdinput"><select id="companymode_{rand}"  class="form-control"><option value="0">不开启</option><option value="1">开启(各单位分开数据管理)</option></select></td>
			<?php }?>
			
			<td align="right">登录修改密码：</td>
			<td class="tdinput"><select id="editpass_{rand}"  class="form-control"><option value="0">不用修改</option><option value="1">强制用户必须修改</option></select></td>
			
		</tr>
		
		
		
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>
		</td>
		</tr>
		
		</table>
</div>
</div>