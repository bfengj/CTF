<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var path = params.path;
	
	var c={
		menuarr:{},
		init:function(){
			this.setmsg('加载中...');
			this.loadmode(path);
		},
		save:function(o1){
			var csn = {'path':path};
			for(var i in this.menuarr){
				if(this.menuarr[i]=='-1'){

				}
				csn['menupid'+i+'']=this.menuarr[i];
			}
			o1.disabled = true;
			this.setmsg('安装中...');
			js.ajax(js.getajaxurl('newinstallinfo','{mode}','{dir}'),csn,function(ret){
				if(ret!='ok'){
					c.setmsg(ret);
					o1.disabled = false;
				}else{
					c.setmsg('');
					js.msgok('安装完成可刷新查看效果');
				}
			});
		},
		setmsg:function(st){
			js.setmsg(st,'', 'msgview_{rand}');
		},
		loadmode:function(ljs){
			js.ajax(js.getajaxurl('loadinstallinfo','{mode}','{dir}'),{path:ljs},function(ret){
				if(!ret.success){
					js.msgerror(ret.msg);
					get('btn_{rand}').disabled=true;
				}else{
					c.showdata(ret.data);
				}
				c.setmsg('');
			},'get,json');
		},
		showdata:function(da){
			$('#ver_{rand}').html(da.ver);
			$('#name_{rand}').html(da.name);
			$('#zuozhe_{rand}').html(da.zuozhe);
			$('#explain_{rand}').html(da.explain);
			$('#filesizecn_{rand}').html(da.filesizecn);
			$('#modelist_{rand}').html(da.modestr);
			$('#tablelist_{rand}').html(da.tablestr);
			$('#menulist_{rand}').html(da.menustr);
			$('#agentlist_{rand}').html(da.agentstr);
			$('#filelist_{rand}').html(da.filestr);
			this.menuarr = da.menuarr;
			path = da.pathstr;
			js.initbtn(c);
		},
		xuancaid:function(o1, id){
			$.selectdata({
				title:'选择上级菜单',
				url:js.getajaxurl('getmenu','{mode}','{dir}'),
				checked:false,maxshow:500,
				onselect:function(d1,sna,sid){
					o1.value='已选【'+sna+'】';
					c.menuarr[id] = sid;
				}
			});
		}
	};
	c.init();

	
});
</script>

<div style="padding:10px" align="center">
<div style="max-width:730px" align="left">

	<h3>安装zip包信息</h3>
	
	<div style="border-bottom:1px #cccccc solid"></div>
	<div>
	<div><font color="gray">名称：</font><span id="name_{rand}"></span>&nbsp; <font color="gray">版本：</font><span id="ver_{rand}"></span>&nbsp; <font color="gray">作者：</font><span id="zuozhe_{rand}"></span>&nbsp; <font color="gray">文件大小：</font><span id="filesizecn_{rand}"></span>
</div>
	<div><font color="gray">说明：</font><span id="explain_{rand}"></span></div>

	</div>

	<h4>包含的模块</h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="modelist_{rand}"></div>

	<h4>包含数据库</h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="tablelist_{rand}"></div>

	<h4>包含的文件</h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="filelist_{rand}"></div>
	
	<h4>包含的菜单(不选上级菜单就是不添加菜单)</h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="menulist_{rand}"></div>
	
	<h4>包含的应用</h4>
	<div style="border-bottom:1px #cccccc solid"></div>
	<div class="wrap" id="agentlist_{rand}"></div>

	<div class="blank10"></div>
	<div >
	<button class="btn btn-success" id="btn_{rand}" click="save" type="button">确定安装</button>&nbsp;<span id="msgview_{rand}"></span>
	</div>
</div>
</div>