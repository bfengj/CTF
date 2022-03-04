<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var c={
		chush:function(o1){
			js.confirm('确定要初始化系统数据嘛？<font color=red>慎重！</font>',function(bt){
				if(bt=='yes'){
					o1.disabled=true;
					c.chushss(o1);
				}
			});
		},
		chushss:function(o1){
			js.msg('wait','初始中...');
			js.ajax(js.getajaxurl('chushua','{mode}','{dir}'),{}, function(s){
				if(s=='ok'){
					js.confirm('初始化成功，请重新登录',function(){
						js.location('?m=login&a=exit');
					});
				}else{
					js.msg('msg', s);
					o1.disabled=false;
				}
			});
		},
		beifen:function(o1,lx){
			js.msg('wait','备份中...');
			js.ajax(js.getajaxurl('beifen','{mode}','{dir}'),{lx:lx}, function(s){
				if(s=='ok'){
					js.msg('success', '备份成功请到目录<?=UPDIR?>/data下查看');
				}else{
					js.msg('msg', s);
				}
			});
		},
		huifu:function(){
			js.confirm('建议：恢复数据前请先备份一下数据啊！是否去备份？',function(jg){
				if(jg=='yes'){
					c.beifen();
				}else{
					addtabs({name:'数据恢复',num:'huifu',url:'system,beifen,huifu'});
				}
			});
		},
		deluser:function(o1){
			js.confirm('确定要初始化部门和用户数据嘛？<font color=red>慎重！</font>',function(bt){
				if(bt=='yes'){
					o1.disabled=true;
					c.delusers(o1);
				}
			});
		},
		delusers:function(o1){
			js.msg('wait','初始中...');
			js.ajax(js.getajaxurl('chushuser','{mode}','{dir}'),{}, function(s){
				js.msg();
				if(s=='ok'){
					js.alert('部门和用户已初始，请到用户管理导入用户。');
				}else{
					js.msg('msg', s);
					o1.disabled=false;
				}
			});
		}
	}
	js.initbtn(c);
});
</script>

<div align="left">
	<div>
		<button click="beifen,0" class="btn btn-success" type="button">系统数据备份</button>
	</div>
	<div class="tishi">数据备份会备份到系统目录<?=UPDIR?>/data文件下，建议每天备份一次！</div>
	
	<div class="blank10"></div>
	<div><button click="huifu" class="btn btn-info" type="button">系统数据恢复</button></div>
	<div class="tishi">恢复你备份过的数据！</div>
	
	
	<div class="blank20"></div>
	<div><button click="chush" class="btn btn-danger" type="button">系统数据初始化</button></div>
	<div class="tishi">初始化将会清空系统上所有数据(除了人员、组织结构、模块配置权限)，请谨慎使用！</div>
	
	<div class="blank20"></div>
	<div><button click="deluser" class="btn btn-danger" type="button">初始化部门和用户</button></div>
	<div class="tishi">初始化部门和用户，将会删除你创建的用户和部门，为了防止误删只能使用一次，请备份好数据谨慎使用！</div>
	
</div>