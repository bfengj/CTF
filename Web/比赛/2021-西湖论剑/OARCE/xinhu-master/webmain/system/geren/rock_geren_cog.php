<?php if(!defined('HOST'))die('not access');?>
<script>
$(document).ready(function(){
	{params}
	var stype = params.stype;
	var valchange = ''+adminstyle,zleng=-1;
	var ysarr = '使用默认,cerulean,cosmo,cyborg,darkly,flatly,journal,lumen,paper,readable,sandstone,simplex,slate,spacelab,superhero,united,xinhu,yeti';
	var companyinfoall;
	var c = {
		init:function(){
			js.ajax(js.getajaxurl('getinit','{mode}','{dir}'),false,function(ret){
				get('gerentodo{rand}').checked = (ret.gerentodo=='1');
				var imgs = ret.qmimgstr;
				if(imgs){
					var s = '<br><img id="imgqianming" src="'+imgs+'"  height="90">';
					$('#qianmingshow').append(s);
				}
				c.showcompany(ret.carr);
			},'get,json');
			
			
			var sear = ysarr.split(','),i,len=sear.length,s='<tr>',oi=0,zarr=[],za,sel='';
			zleng = len-1;
			for(i=0;i<len;i++){
				zarr.push({text:sear[i],value:i});
				if(i>0)zarr.push({text:sear[i]+'_default',value:i+zleng});
			}
			for(i=0;i<zarr.length;i++){
				za = zarr[i];
				oi++;
				sel = (valchange==za.value)?'checked' : '';
				s+='<td align="center" style="padding:10px"><label><a style="TEXT-DECORATION:none">'+za.text+'</a><br><input type="radio" '+sel+' name="_stylechange" value="'+za.value+'"></label>';
				s+='</td>';
				if(oi%7==0)s+='</tr><tr>';
			}
			s+='</tr>';
			$('#tablstal2{rand}').prepend(s);
			if(adminid!=1)$('#zhutibao{rand}').remove();
		},
		showcompany:function(ad){
			var s='',a1,i,col;
			var darr = ad.companyinfoall,s1='',act;
			companyinfoall = darr;
			$('#companylist{rand} a[temp]').remove();
			for(i=0;i<darr.length;i++){
				a1=darr[i];
				s1=a1.name;
				act='';
				//if(a1.id==ad.companyinfo.id)act=' active';
				if(!isempt(a1.city))s1+='<font color=#aaaaaa>('+a1.city+''+a1.address+')</font>';
				s+='<a temp="list" style="TEXT-DECORATION:none" class="list-group-item'+act+'"><img src="'+a1.logo+'" align="absmiddle" height="20" width="20"> '+s1+'';
				if(a1.id==ad.companyinfo.id){
					s+=' <span class="label label-success"><i class="icon-ok"></i>当前</span>';
				}else{
					s+=' <button type="button" onclick="qiehun{rand}('+i+')" class="btn btn-default btn-xs">切换</button>';
				}
				s+='</a>';
			}
			$('#companylist{rand}').append(s);
		},
		savecog:function(){
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('cogsave','{mode}','{dir}'),{
				gerentodo:get('gerentodo{rand}').checked ? 1 : 0
			},function(ret){
				js.msg('success','保存成功');
			},'get');
		},
		savepass:function(o1){
			var fm		= 'form_{rand}';
			var msgview		= 'msgview_{rand}';
			
			var opass	= form('passoldPost',fm).value;
			var pass	= form('passwordPost',fm).value;
			var pass1	= form('password1Post',fm).value;
			
			if(opass==''){
				js.setmsg('旧密码不能为空','red', msgview);
				form('passoldPost',fm).focus();
				return false;
			}

			if(pass.length <4){
				js.setmsg('新密码不能少于4个字符','red', msgview);
				form('passwordPost',fm).focus();
				return false;
			}
			if(!/[a-zA-Z]{1,}/.test(pass) || !/[0-9]{1,}/.test(pass)){
				js.setmsg('新密码必须使用字母+数字','red', msgview);
				form('passwordPost',fm).focus();
				return false;
			}

			if(opass==pass){
				js.setmsg('新密码不能和旧密码相同','red', msgview);
				form('passwordPost',fm).focus();
				return false;
			}
			
			if(pass!=pass1){
				js.setmsg('确认密码不一致','red', msgview);
				form('password1Post',fm).focus();
				return false;
			}

			var data	= js.getformdata(fm);
			form('submitbtn',fm).disabled=true;
			js.setmsg('修改中...','#ff6600', msgview);
			$.post(js.getajaxurl('editpass','geren','system'),data,function(da){
				if(da=='success'){
					var msg = '密码修改成功';
					js.setmsg(msg,'green', msgview);
					if(stype=='pass'){
						js.alert(msg+'，点确定后继续使用系统','', function(){
							location.reload();
						});
					}
				}else{
					if(da=='')da='修改失败';
					js.setmsg(da,'red', msgview);
					form('submitbtn',fm).disabled=false;
				}
			});
		},
		tesgs:function(o1,lx){
			$('#tagsl{rand}').find('li').removeClass('active');
			o1.className='active';
			$('#tablstal0{rand}').hide();
			$('#tablstal1{rand}').hide();
			$('#tablstal2{rand}').hide();
			$('#tablstal3{rand}').hide();
			
			$('#tablstal'+lx+'{rand}').show();
			if(lx==3)js.importjs('mode/plugin/jquery-signature.js');
		},
		savestyle:function(){
			adminstyle = valchange;
			js.ajax(js.getajaxurl('changestyle','geren','system'),{style:valchange},function(s){
				js.msg('success','保存成功');
			});
		},
		qmimgstr:'',
		qianming:function(o1){
			this.qianmingbo=false;
			js.tanbody('qianming','请在空白区域写上你的姓名',500,300,{
				html:'<div data-width="480" data-height="220" data-border="1px dashed #cccccc" data-line-color="#000000" data-auto-fit="true" id="qianmingdiv" style="margin:10px;height:220px;cursor:default;width:480px"></div>',
				btn:[{text:'确定签名'},{text:'重写'}]
			});
			$('#qianmingdiv').jqSignature().on('jq.signature.changed', function() {
				c.qianmingbo=true;
			});

		
			$('#qianming_btn0').click(function(){
				c.qianmingok();
			});
			$('#qianming_btn1').click(function(){
				$('#imgqianming').remove();
				$('#qianmingdiv').jqSignature('clearCanvas');
				c.qianmingbo = false;
				c.qmimgstr	 = '';
			});
		},
		qianmingok:function(){
			if(!this.qianmingbo)return;
			$('#imgqianming').remove();
			var dataUrl = $('#qianmingdiv').jqSignature('getDataURL');
			var s = '<br><img id="imgqianming" src="'+dataUrl+'"  height="90">';
			c.qmimgstr = dataUrl;
			$('#qianmingshow').append(s);
			js.tanclose('qianming');
		},
		saveqian:function(){
			this.saveqians(false);
		},
		saveqians:function(bo){
			if(this.qmimgstr=='' && !bo){
				js.msg('msg','没有修改无需保存');
				return;
			}
			js.msg('wait','保存中...');
			js.ajax(js.getajaxurl('qmimgsave','{mode}','{dir}'),{
				qmimgstr:this.qmimgstr
			},function(ret){
				js.msg('success','保存成功');
			},'post');
		},
		saveqians1:function(){
			this.qmimgstr = '';
			$('#imgqianming').remove();
			this.saveqians(true);
		},
		qianup:function(){
			js.upload('upimg{rand}',{maxup:'1',thumbnail:'150x150','title':'上传签名图片',uptype:'image'});	
		}
	};
	js.initbtn(c);
	c.init();
	
	upimg{rand}=function(a){
		var f = a[0];
		$('#imgqianming').remove();
		var dataUrl = f.filepath;
		var s = '<br><img id="imgqianming" src="'+dataUrl+'"  height="90">';
		c.qmimgstr = dataUrl;
		$('#qianmingshow').append(s);
	}
	
	$("input[name='_stylechange']").click(function(){
		var val = parseFloat(this.value);
		valchange=val;
		var sear = ysarr.split(',')
		if(val>0){
			var xz = val+0,tou='inverse';
			if(xz>zleng){
				xz=xz-zleng;
				tou='default';
			}
			if(get('navtopheader'))get('navtopheader').className='navbar navbar-'+tou+' navbar-static-top';
			get('mainstylecss').href='mode/bootstrap3.3/css/bootstrap_'+sear[xz]+'.css';
		}else{
			js.msg('success','使用默认主题的保存后，刷新页面即可');
		}
	});
	if(stype=='pass'){
		c.tesgs(get('passli{rand}'),1);
		changetabs=c.tesgs=function(){}
	}
	
	qiehun{rand}=function(oi){
		var d1 = companyinfoall[oi];
		js.confirm('确定要切换到单位上“'+d1.name+'”吗？', function(jg){
			if(jg=='yes'){
				js.loading('切换中...');
				js.ajax('api.php?m=index&a=changecompany',{id:d1.id}, function(){
					js.msgok('切换成功');
					location.reload();
				});
			}
		});
	}
	
	if(!companymode)$('#companylist{rand}').hide();
});
</script>
<div style="padding:10px">
	
	<ul id="tagsl{rand}" class="nav nav-tabs">
	  
	  <li click="tesgs,0" class="active">
		<a style="TEXT-DECORATION:none"><i class="icon-cog"></i> 基本设置</a>
	  </li>
	  <li id="passli{rand}" click="tesgs,1">
		<a style="TEXT-DECORATION:none"><i class="icon-lock"></i> 修改密码</a>
	  </li>
	
	 <li click="tesgs,2">
		<a style="TEXT-DECORATION:none"><i class="icon-magic"></i> 切换主题皮肤</a>
	  </li>
	  <li click="tesgs,3">
		<a style="TEXT-DECORATION:none"><i class="icon-edit"></i> 签名图片</a>
	  </li>
	</ul>

	<div style="padding-top:20px">
	
		<table cellspacing="0" id="tablstal0{rand}" border="0" cellpadding="0">
		
		<tr>
	
		<td colspan="2">

			<div align="left" id="companylist{rand}" style="max-width:400px;margin-left:50px" class="list-group">
				<div class="list-group-item  list-group-item-info">
				我加入的单位
				</div>
			</div>
		</td>
		</tr>

		<tr>
			<td  align="right" width="80"></td>
			<td class="tdinput"><label><input id="gerentodo{rand}" type="checkbox"> <a  style="TEXT-DECORATION:none">后台不显示提醒消息</a></label></td>
		</tr>
	
	
	
	<tr>
		<td  align="right"></td>
		<td style="padding:15px 0px" colspan="3" align="left"><button click="savecog" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>
	</td>
	</tr>
	
	
	</table>
	
	
	<form  id="tablstal1{rand}" style="display:none" name="form_{rand}">
	<table cellspacing="0"  cellpadding="0">
	<tr>
		<td width="100" align="right" height="50">旧密码：</td>
		<td><input style="width:250px" name="passoldPost" type="password" class="form-control"></td>
	</tr>
	
	<tr>
		<td align="right" height="70">新密码：</td>
		<td><input style="width:250px" name="passwordPost" placeholder="至少4位字母+数字组合" type="password" class="form-control"></td>
	</tr>
	
	<tr>
		<td align="right" height="70">确认密码：</td>
		<td><input style="width:250px" name="password1Post" type="password" class="form-control"></td>
	</tr>
	

	
	<tr>
	<td height="60" align="right"></td>
	<td align="left"><input class="btn btn-success" click="savepass" name="submitbtn" value="修改" type="button">&nbsp;<span id="msgview_{rand}"></span>
	</td>
	</tr>
	
	</table>
	</form>
	
	
	<table  id="tablstal2{rand}" style="display:none;margin-left:70px">
		
		<tr>
			<td colspan="10" style="padding-left:20px"><input class="btn btn-success" click="savestyle" name="submitbtn" value="保存修改" type="button">
			<span id="zhutibao{rand}">&nbsp;切换出现无样式请到官网<a href="<?=URLY?>view_themes.html" target="_blank">下载主题包</a>。</span>
			</td>
		</tr>
	</table>
	
	<table  id="tablstal3{rand}" style="display:none;margin-left:70px">
		
		<tr>
			<td align="center" style="padding:15px">
			<div id="qianmingshow" align="left"><input type="button" click="qianming" class="btn btn-default btn-xs"  value="手写签名">&nbsp;&nbsp;<input type="button" click="qianup" class="btn btn-default btn-xs"  value="上传签名图片"></div>
			</td>
		</tr>
		
		<tr>
			<td style="padding-left:15px"><input class="btn btn-success" click="saveqian" value="保存签名图片" type="button"> <input class="btn btn-default btn-xs" click="saveqians1"  value="清空签名" type="button"></td>
		</tr>
	</table>

	</div>
</div>