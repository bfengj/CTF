<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	{params}

	var a = $('#view_{rand}').bootstable({
		tablename:'chargems',url:js.getajaxurl('otherdata','{mode}','{dir}'),fanye:true,method:'get',
		columns:[{
			text:'名称',dataIndex:'name'
		},{
			text:'类型',dataIndex:'typename'
		},{
			text:'版本',dataIndex:'version'
		},{
			text:'作者',dataIndex:'zuozhe'
		},{
			text:'发布者',dataIndex:'fabuzhe'
		},{
			text:'价格',dataIndex:'money',sortable:true,renderer:function(v){
				if(v=='0')v='免费';
				return v;
			}
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'更新时间',dataIndex:'updatedt'
		},{
			text:'客服',dataIndex:'behst',renderer:function(v,d){
				return '<a href="'+d.kefuurl+'" target="_blank"><i class="icon-comment-alt"></i>客服</a>';
			}
		},{
			text:'',dataIndex:'anzt',renderer:function(v,d){
				var s = '&nbsp;';
				if(v==1)s='<font color=green>已安装</font> ';
				if(v==2)s='<button onclick="upsho{rand}.install(2,'+d.id+')" class="btn btn-danger btn-sm" type="button">升级</button>';
				if(v==0)s='<button onclick="upsho{rand}.install(0,'+d.id+')" class="btn btn-info btn-sm"  type="button">安装</button>';
				return '<span id="msg'+d.id+'_{rand}">'+s+'</span>';
			}
		}],
		beforeload:function(){
			get('resede_{rand}').disabled=true;
		},
		itemclick:function(){
			get('resede_{rand}').disabled=false;
		}
	});
	
	var c={
		reloads:function(){
			a.reload();
		},
		huliesss:function(){
			js.upload('_zpichangback',{maxup:'1','title':'选择要安装的zip包',uptype:'zip','urlparams':'noasyn:yes'});
		},
		bool:false,
		install:function(lx,id){
			if(this.bool)return;
			var msgid='msg'+id+'_{rand}',lxs='安装';
			if(lx==2)lxs='升级';
			js.setmsg(''+lxs+'中...','', msgid);
			this.bool = true;
			js.ajax(js.getajaxurl('otherinstall','{mode}','{dir}'),{id:id},function(ret){
				c.bool = false;
				if(ret.success){
					js.setmsg(ret.data.msg,'green', msgid);
					addtabs({name:'模块插件['+ret.data.name+']安装',num:'zipinstall',url:'system,upgrade,install,path='+jm.base64encode(ret.data.path)+''});
				}else{
					js.setmsg(ret.msg,'', msgid);
				}
			},'get,json');
		},
		delreload:function(){
			a.del({
				url:js.getajaxurl('delother', '{mode}','{dir}'),
				msg:'确定要删除选中模块插件后可重新安装的！'
			});
		}
	};

	js.initbtn(c);
	
	_zpichangback=function(da){
		if(da[0]){
			addtabs({name:'zip模块插件安装',num:'zipinstall',url:'system,upgrade,install,path='+jm.base64encode(da[0].filepath)+''});
		}
	}
	upsho{rand} = c;
	if(ISDEMO || adminid!=1)get('upbtnd{rand}').disabled=true;
});
</script>
<div>
	<table width="100%"><tr>
	<td nowrap>
		<h4>此列表模块插件来自信呼开发平台，<a href="<?=URLY?>view_anbao.html"target="_blank">进去看看</a><h4>
	</td>
	<td nowrap>
		
	</td>
	<td align="right">
		<button class="btn btn-default" id="upbtnd{rand}" click="huliesss,0"  type="button">本地上传安装</button>&nbsp; 
		<button class="btn btn-default" click="delreload" disabled id="resede_{rand}" type="button">删除重新安装</button>
	</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>