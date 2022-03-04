<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var qianss='';
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_set',dir:'asc',sort:'sort',celleditor:true,checked:true,
		url:publicstore('{mode}','{dir}'),fanye:true,pageSize:10,storeafteraction:'modeafter',storebeforeaction:'modebefore',
		columns:[{
			text:'类型',dataIndex:'type',sortable:true,editor:true
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'模块名',dataIndex:'name',editor:true
		},{
			text:'对应表',dataIndex:'table'
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'针对人员',dataIndex:'recename'
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		},{
			text:'流程模式',dataIndex:'isflow',sortable:true,renderer:function(v){
				var s = '&nbsp;';
				if(v=='1')s='顺序流程';
				if(v=='2')s='顺序前置';
				if(v=='3')s='自由流程';
				if(v=='4')s='选择流程';
				return s;
			}
		},{
			text:'PC端提醒',dataIndex:'pctx',type:'checkbox',editor:true,sortable:true
		},{
			text:'邮件提醒',dataIndex:'emtx',type:'checkbox',editor:true,sortable:true
		},{
			text:'APP提醒',dataIndex:'mctx',type:'checkbox',editor:true,sortable:true
		},{
			text:'微信提醒',dataIndex:'wxtx',type:'checkbox',editor:true,sortable:true
		},{
			text:'钉钉提醒',dataIndex:'ddtx',type:'checkbox',editor:true,sortable:true
		},{
			text:'录入',dataIndex:'islu',type:'checkbox',editor:true,sortable:true
		},{
			text:'同步更新',dataIndex:'isup',type:'checkbox',editor:true,sortable:true
		},{
			text:'开评论',dataIndex:'ispl',type:'checkbox',editor:true,sortable:true
		},{
			text:'提醒设置',dataIndex:'istxset',type:'checkbox',editor:true,sortable:true
		},{
			text:'编号规则',dataIndex:'sericnum'
		},{
			text:'ID',dataIndex:'id'
		}],
		itemclick:function(d){
			btn(false, d);
		},
		beforeload:function(){
			btn(true);
		},
		load:function(d){
			qianss=d.qian;
		}
	});
	function btn(bo, d){
		if(ISDEMO)return;
		get('edit_{rand}').disabled = bo;
		get('copy_{rand}').disabled = bo;
		get('downbtn_{rand}').disabled = bo;
		get('biaoge_{rand}').disabled = bo;
		get('biaoges_{rand}').disabled = bo;
		if(d && d.type=='系统')bo=true;
		get('del_{rand}').disabled = bo;
	}
	var c = {
		del:function(){
			js.confirm('确定要删除选中模块吗？删除了所有数据就都没了，不能恢复的哦',function(jg){
				if(jg=='yes')c.delss();
			});
		},
		delss:function(){
			if(a.changeid==0)return;
			js.ajax(js.getajaxurl('delmode','{mode}','{dir}'),{id:a.changeid},function(s){
				if(s=='ok'){
					a.reload();
				}else{
					js.msg('msg',s);
				}
			},'post',false,'删除中...,删除成功');
		},
		allcreate:function(){
			js.ajax(js.getajaxurl('allcreate','{mode}','{dir}'),{},function(s){
				js.msg('success', s);
			},'get',false,'生成中...');
		},
		copy:function(){
			if(a.changeid==0)return;
			js.prompt('输入新模块编号','将会从模块['+a.changedata.name+']复制主表子表和表单元素字段的！', function(jg,txt){
				if(jg=='yes' && txt)c.copys(txt);
			});
		},
		copys:function(txt){
			if(a.changeid==0)return;
			js.ajax(js.getajaxurl('copymode','{mode}','{dir}'),{id:a.changeid,name:txt},function(s){
				if(s=='ok'){
					a.reload();
				}else{
					js.msg('msg',s);
				}
			},'post',false,'复制中...,复制成功：还是要做其他很多事的，具体请到官网看模块开发视频。');
		},
		reload:function(){
			a.reload();
		},
		clickwin:function(o1,lx){
			var icon='plus',name='新增流程模块',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑流程模块';
			};
			guanflowsetlist = a;
			addtabs({num:'flowset'+id+'',url:'main,flow,setedit,id='+id+'',icons:icon,name:name});
		},
		input:function(at){
			var url='?m=flow&d=main&a=input&setid='+a.changeid+'&atype='+at+'';
			js.open(url,900,500);
		},
		elements:function(){
			var name='['+a.changedata.name+']表单元素';
			var id = a.changeid;
			addtabs({num:'flowelements'+id+'',url:'main,flow,element,mid='+id+'',name:name});
		},
		pipei:function(){
			js.ajax(js.getajaxurl('reloadpipei','{mode}','{dir}'),{},function(s){
				js.msg('success', s);
			},'get',false,'匹配中...,匹配完成');
		},
		clearalldata:function(id){
			js.confirm('确定要清空此['+a.changedata.name+']模块所有数据嘛？<b style="color:red">慎重慎重慎重慎重！</b>',function(ssal){
				if(ssal=='yes')c.clearalldatas(id);
			});
		},
		clearalldatas:function(id){
			if(a.changeid==0)return;
			js.ajax(js.getajaxurl('clearallmode','{mode}','{dir}'),{id:a.changeid},function(s){
				if(s=='ok'){
					a.reload();
				}else{
					js.msg('msg',s);
				}
			},'post',false,'清空中...,清空成功');
		},
		biaoge:function(){
			this.showtalbe(a.changedata.table);
		},
		biaoges:function(){
			var ssa = a.changedata.tables;
			if(isempt(ssa)){
				js.msg('success','没有子表无需管理');return;
			}
			this.showtalbe(ssa.split(',')[0]);
		},
		search:function(){
			var s = get('key_{rand}').value;
			a.setparams({key:s}, true);
		},
		showtalbe:function(table){
			if(!table)return;
			var name=''+qianss+''+table+'';
			addtabs({num:'tablefields'+name+'',url:'system,table,fields,table='+name+'',name:'['+name+']字段管理'});
		},
		copyss:function(){
			js.msg('msg','由于关联过多，无法复制');return;
			js.prompt('输入新模块编号','输入模块编号和主表表名,只能用英文，不能用数字中文，随意写系统将出错：', function(jg,txt){
				if(jg=='yes'){
					c.copysss(txt);
				}
			});
		},
		copysss:function(txt){
			js.ajax(js.getajaxurl('copymode','{mode}','{dir}'),{id:a.changeid,nmode:txt},function(s){
				if(s=='ok'){
					a.reload();
				}else{
					js.msg('msg',s);
				}
			},'post',false,'复制...,清空成功');
		},
		tongbu:function(){
			var num=a.changedata.num;
			js.prompt('从官网中拉取模块同步','输入要同步的模块编号如(gong)：将会覆盖你模块设置。', function(jg,txt){
				if(jg=='yes' && txt)c.tongbuss(txt);
			},num);
		},
		tongbuss:function(nk){
			js.loading('拉取同步中...');
		},
		shengsheng:function(){
			var sid = a.getchecked();
			addtabs({'name':'创建安装包',url:'main,flow,createinstall,sid='+jm.base64encode(sid)+'',num:'createinstall'});
			
		}
	};
	js.initbtn(c);
	
	$('#downbtn_{rand}').rockmenu({
		width:170,top:35,donghua:false,
		data:[{
			name:'表单元素管理',lx:0
		},{
			name:'PC端录入页面布局',lx:1
		},{
			name:'清空此模块数据',lx:2
		}],
		itemsclick:function(d, i){
			var id = a.changedata.id;
			if(!id)return;
			if(d.lx==1)c.input(0);
			if(d.lx==0)c.elements();
			if(d.lx==2)c.clearalldata(id);
		}
	});
});
</script>

<div>
	<table width="100%">
	<tr>
	<td align="left" nowrap>
		<button class="btn btn-warning" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>&nbsp; 
		<button class="btn btn-primary" disabled id="downbtn_{rand}" type="button">模块开发管理 <i class="icon-angle-down"></i></button>&nbsp; 
		<button class="btn btn-default" click="pipei" type="button">重新匹配流程</button>&nbsp; 
		<button class="btn btn-default" id="biaoge_{rand}" disabled click="biaoge,1" type="button"><i class="icon-table"></i> 主表管理</button>&nbsp; 
		<button class="btn btn-default" id="biaoges_{rand}" disabled click="biaoges,1" type="button"><i class="icon-table"></i> 子表管理</button>&nbsp; 
		<button class="btn btn-default" click="allcreate" type="button">生成所有列表页</button>
	</td>
	<td align="left"  style="padding:0px 10px;">
		<div class="input-group" style="width:130px">
			<input class="form-control" id="key_{rand}" placeholder="搜模块">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
	</td>
	<td width="90%">
		
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="shengsheng" type="button">生成安装包</button>&nbsp; 
		<button class="btn btn-default" id="copy_{rand}" click="copy,1" disabled type="button">复制</button>&nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>&nbsp; 
		<button class="btn btn-danger" click="del" disabled id="del_{rand}" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">提示：对应表请使用数据库管理工具管理，如phpMyadmin,Navicat等，模块列表页面会生成到webmain/flow/page下<div>
