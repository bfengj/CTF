<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var sid = params.sid;
	var info = {
		mode:'',
		table:'',
		file:'',
		menu:'',
		agent:''
	}
	var c={
		init:function(){
			if(sid){
				this.loadmode(jm.base64decode(sid));
			}
		},
		loadmode:function(sd1){
			js.ajax(js.getajaxurl('loadmodeinfo','{mode}','{dir}'),{sid:sd1},function(ret){
				js.unloading();
				if(!ret.success){
					js.msgerror(ret.msg);
				}else{
					var da = ret.data;
					if(da.mode){
						c.showlist('table',da.table);
						c.showlist('file',da.file);
						c.showlist('menu',da.menu);
						c.showlist('agent',da.agent);
						c.showlist('mode',da.mode);
						$('#modelist_{rand}').append(da.mname);
					}
				}
			},'get,json');
		},
		save:function(){
			var bo = false;
			var da = {
				name:get('name_{rand}').value,
				ver:get('ver_{rand}').value,
				zuozhe:get('zuozhe_{rand}').value,
				explain:get('explain_{rand}').value
			}
			for(var i in info){
				if(info[i])bo=true;
				da[i] = info[i];
			}
			if(!bo){
				js.msgerror('包没有包含任何信息');
				return;
			}
			var msgid= 'msgview_{rand}';
			js.setmsg('创建中...','', msgid);
			js.ajax(js.getajaxurl('createinstse','{mode}','{dir}'),da,function(ret){
				if(!ret.success){
					js.setmsg(ret.msg,'', msgid);
				}else{
					js.setmsg(ret.data,'green', msgid);
				}
			},'post,json');
		},
		showlist:function(lx,vs){
			if(!vs)return;
			var o1 = $('#'+lx+'list_{rand}');
			var str= info[lx];
			if(vs){
				if(str)str+=',';
				str+=''+vs+'';
				info[lx]=str;
				
				if(lx=='file'){
					var sidt = str.split(',');
					str = sidt.join('<br>');
				}

				if(lx!='mode' && lx!='menu' && lx!='agent')o1.html(str);
			}
		},
		addbtn:function(o1,lx){
			if(lx==3){
				$.selectdata({
					title:'选择需要的菜单',
					url:js.getajaxurl('getmenu','upgrade','system',{glx:1}),
					checked:true,maxshow:500,
					onselect:function(d1,sna,sid){
						if(sid)c.addmode(sid,lx);
					}
				});
				return;
			}
			if(lx==4){
				$.selectdata({
					title:'选择需要的应用',
					url:js.getajaxurl('getyydata','upgrade','system',{glx:1}),
					checked:true,maxshow:500,
					onselect:function(d1,sna,sid){
						if(sid)c.addmode(sid,lx);
					}
				});
				return;
			}
			var stra=['模块ID','表名','文件路径','菜单ID(菜单管理)下查看','应用ID(应用管理)下查看'];
			js.prompt('请输入','请输入'+stra[lx]+'，多个,分开',function(jg,txt){
				if(jg=='yes' && txt){
					c.addmode(txt,lx);
				}
			});
		},
		addmode:function(txt,lx){
			js.loading('处理中...');
			if(lx==0){
				this.loadmode(txt);
			}else{
				js.ajax(js.getajaxurl('loadotein','{mode}','{dir}'),{lx:lx,sid:txt},function(ret){
					js.unloading();
					if(!ret.success){
						js.msgerror(ret.msg);
					}else{
						var da = ret.data;
						c.showlist('table',da.table);
						c.showlist('file',da.file);
						c.showlist('menu',da.menu);
						c.showlist('agent',da.agent);
						if(da.menu_str)$('#menulist_{rand}').append(da.menu_str);
						if(da.agent_str)$('#agentlist_{rand}').append(da.agent_str);
					}
				},'post,json');
			}
		}
	};
	c.init();
	js.initbtn(c);
	
	
});
</script>

<div style="padding:10px" align="center">
<div style="max-width:730px" align="left">

	<h3>这里是制作一个zip安装包</h3>
	<div style="color:gray">不是开发者不要去操作搞这个，更多看<a href="<?=URLY?>view_anbao.html"target="_blank">[帮助]</a>。</div>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div style="padding:10px 0px">
	<table>
	<tr>
	<td nowrap>&nbsp;安装包名称&nbsp;</td>
	<td><input class="form-control" id="name_{rand}"></td>
	<td nowrap>&nbsp;版本&nbsp;</td>
	<td style="padding:8px 0px"><input class="form-control" value="1.0" id="ver_{rand}"></td>
	<td nowrap>&nbsp;作者&nbsp;</td>
	<td style="padding:8px 0px"><input class="form-control" value="" id="zuozhe_{rand}"></td>
	</tr>
	<tr>
	<td nowrap>&nbsp;说明&nbsp;</td>
	<td colspan="5" style="padding:8px 0px"><textarea class="form-control" id="explain_{rand}"></textarea></td>
	</tr>
	</table>

	</div>

	<h4>包含的模块&nbsp;<button class="btn btn-default btn-xs" click="addbtn,0" type="button"><i class="icon-plus"></i></button></h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="modelist_{rand}"></div>

	<h4>包含数据库&nbsp;<button class="btn btn-default btn-xs" click="addbtn,1" type="button"><i class="icon-plus"></i></button></h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="tablelist_{rand}"></div>

	<h4>包含的文件&nbsp;<button class="btn btn-default btn-xs" click="addbtn,2" type="button"><i class="icon-plus"></i></button></h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="filelist_{rand}"></div>
	
	<h4>包含的菜单&nbsp;<button class="btn btn-default btn-xs" click="addbtn,3" type="button"><i class="icon-plus"></i></button></h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="menulist_{rand}"></div>
	
	<h4>包含的应用&nbsp;<button class="btn btn-default btn-xs" click="addbtn,4" type="button"><i class="icon-plus"></i></button></h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="agentlist_{rand}"></div>

	<div class="blank10"></div>
	<div >
	<button class="btn btn-success" click="save" type="button">生成打包</button>&nbsp;<span id="msgview_{rand}"></span>
	</div>
</div>
</div>