<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#menu_{rand}').bootstable({
		tablename:'menu',url:js.getajaxurl('data','{mode}','{dir}'),method:'get',loadtree:false,
		tree:true,celleditor:!ISDEMO,bodyStyle:'height:'+(viewheight-70)+'px;overflow:auto',
		columns:[{
			text:'菜单名称',dataIndex:'name',align:'left',editor:true,renderstyle:function(v,d){
				return 'min-width:220px';
			}
		},{
			text:'编号',dataIndex:'num'	,editor:true,renderstyle:function(v,d){
				return 'width:70px';
			}
		},{
			text:'URL',dataIndex:'url',editor:true,repEmpty:true,renderstyle:function(v,d){
				return 'word-wrap:break-word;word-break:break-all;white-space:normal;width:180px';
			}
		},{
			text:'PID',dataIndex:'pid',editor:true
		},{
			text:'图标',dataIndex:'icons',editor:true,renderstyle:function(v,d){
				return 'width:70px';
			}
		},{
			text:'启用',dataIndex:'status',type:'checkbox',editor:true
		},{
			text:'验证',dataIndex:'ispir',type:'checkbox',editor:true
		},{
			text:'显首',dataIndex:'ishs',type:'checkbox',editor:true
		},{
			text:'排序',dataIndex:'sort'	,editor:true
		},{
			text:'颜色',dataIndex:'color',editor:true
		},{
			text:'级别',dataIndex:'type',editor:true,renderer:function(v){
				var s='&nbsp;';
				if(v==1)s='系统';
				return s;
			},type:'select',store:[['0','普通'],['1','系统']]
		},{
			text:'ID',dataIndex:'id'	
		}],
		itemclick:function(){
			btn(false);
		}
	});
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
		get('down_{rand}').disabled = bo;
	}
	
	var c = {
		del:function(){
			a.del({url:js.getajaxurl('delmenu','{mode}','{dir}')});
		},
		reload:function(){
			a.reload();
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		clickwin:function(o1,lx){
			if(ISDEMO){js.msg('success','演示站点禁止操作');return;}
			var h = $.bootsform({
				title:'菜单',height:500,width:400,
				tablename:'menu',isedit:lx,
				params:{int_filestype:'ispir,status,sort,pid,ishs'},
				submitfields:'num,name,url,icons,ispir,status,sort,pid,ishs,color',
				items:[{
					labelText:'编号',name:'num',repEmpty:true
				},{
					labelText:'菜单名称',name:'name',required:true
				},{
					labelText:'URL地址',name:'url',repEmpty:true
				},{
					labelText:'图标',name:'icons',repEmpty:true
				},{
					labelText:'上级ＩＤ',name:'pid',required:true,value:'0',type:'number'
				},{
					name:'status',labelBox:'启用',type:'checkbox',checked:true
				},{
					name:'ispir',labelBox:'验证(未√就是任何人可使用菜单)',type:'checkbox',checked:true
				},{
					name:'ishs',labelBox:'显示在首页',type:'checkbox'
				},{
					labelText:'颜色',name:'color',repEmpty:true
				},{
					labelText:'序号',name:'sort',type:'number',value:'0'
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1)h.setValues(a.changedata);
			h.getField('name').focus();
			if(lx==2)h.setValue('pid', a.changedata.id);
		}
	};
	js.initbtn(c);
});

</script>

<div>


<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增顶级</button> &nbsp; 
		<button class="btn btn-success" click="clickwin,2" id="down_{rand}" disabled type="button"><i class="icon-plus"></i> 新增下级</button>&nbsp; 
		<button class="btn btn-default" click="reload" type="button">刷新</button>
	</td>

	<td  style="padding-left:10px">
		<!--
		<div class="input-group" style="width:100px">
			<input class="form-control" id="key_{rand}"   placeholder="pid">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>-->
	</td>
	
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="menu_{rand}"></div>
