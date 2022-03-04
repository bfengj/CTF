<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#veiw_{rand}').bootstable({
		checked:false,method:'get',
		url:js.getajaxurl('getdata','{mode}','{dir}'),
		columns:[{
			text:'名称',dataIndex:'name',align:'left',renderer:function(v,d){
				return '<span title="'+jm.base64decode(d.path)+'"><i class="icon-'+d.type+'"></i> '+v+'</span>';
			}
		},{
			text:'说明',dataIndex:'explain'
		},{
			text:'大小',dataIndex:'filesize'
		},{
			text:'创建时间',dataIndex:'createdt'
		},{
			text:'最后修改',dataIndex:'lastdt'
		},{
			text:'',dataIndex:'isaz',align:'left',renderer:function(v,d){
				var str = '';
				if(d.lei==0){
					str='<a href="javascript:;" onclick="openfile{rand}('+d.lei+',\''+d.path+'\')">打开</a>';
				}else{
					if(d.isedit==1)str='<a href="javascript:;" onclick="openfile{rand}(2,\''+d.path+'\')">查看</a>';
					if(js.isimg(d.fileext))str='<a href="javascript:;" onclick="$.imgview({url:\''+jm.base64decode(d.path)+'\'})">预览</a>';
				}
				if(d.isdel==1)str+='&nbsp;<a href="javascript:;" onclick="openfile{rand}(3,\''+d.path+'\')">删</a>';
				return str;
			}
		}],
		load:function(d){
			$('#sviepath').html('文件不要随意删除');
			nowpath = d.nowpath;
			var str='',i,ad2,s1='';
			ad2=d.nowpath.split('/');
			for(i=0;i<ad2.length;i++){
				if(i>0){
					str+=' <font color="#cccccc">&gt;</font> ';
					s1+='/';
				}
				s1+=''+ad2[i]+'';
				str+='<a href="javascript:;" onclick="openfile{rand}(0,\''+jm.base64encode(s1)+'\')">'+ad2[i]+'</a>';
			}
			$('#nowpath').html(str);
		},
		itemdblclick:function(d){
			if(d.lei==0)openfile{rand}(0,d.path);
		}
	});

	var c = {
		reload:function(){
			a.reload();
		},
		clearlogs:function(){
			js.confirm('确定要全部删除<?=UPDIR?>/logs下的文件嘛？', function(jg){if(jg=='yes')c.clearlogss();});
		},
		clearlogss:function(){
			js.loading('清理中...');
			js.ajax(js.getajaxurl('clearlogs','{mode}','{dir}'),false,function(tss){
				js.msgok(tss);
			});
		},
		delfile:function(pts){
			js.confirm('确定要删除'+jm.base64decode(pts)+'吗？', function(jg){if(jg=='yes')c.delfiles(pts);});
		},
		delfiles:function(pts){
			js.loading('删除中...');
			js.ajax(js.getajaxurl('delfile','{mode}','{dir}'),{path:pts},function(tss){
				js.msgok(tss);
				a.reload();
			});
		},
		svnupdate:function(){
			js.loading('发送中...');
			js.ajax(js.getajaxurl('svnupdate','{mode}','{dir}'),false,function(tss){
				js.msgok(tss);
			});
		}
	};

	openfile{rand}=function(lx,pts){
		if(lx==0)a.setparams({path:pts},true);
		if(lx==2)window.open('?m=sysfile&d=system&a=edit&path='+pts+'');
		if(lx==3)c.delfile(pts);
	}
	
	js.initbtn(c);
});
</script>

<div>

<table width="100%">
	<tr>
	<td nowrap align="left">
	<button class="btn btn-default" click="reload"  type="button">刷新</button>&nbsp;&nbsp;
	</td>
	<td align="left"  width="100%" style="padding:0px 10px;">
		路径：<a href="javascript:;" onclick="openfile{rand}(0,'')"><?=ROOT_PATH?></a> <font color="#cccccc">&gt;</font> <span id="nowpath"></span>
	</td>
	<td align="right" nowrap>
		<?php
		if(getconfig('svnpath'))echo '<button class="btn btn-default" click="svnupdate"  type="button">SVN更新系统</button>';
		?>
		&nbsp;<button class="btn btn-default" click="clearlogs"  type="button">一键清除<?=UPDIR?>/logs下日志文件</button>
	</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
