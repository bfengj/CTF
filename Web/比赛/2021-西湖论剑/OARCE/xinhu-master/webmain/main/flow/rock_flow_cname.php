<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var mid;
	var at = $('#optionview_{rand}').bootstable({
		tablename:'flow_cname',defaultorder:'`sort`',where:'and `pid`=0',celleditor:true,
		modedir:'{mode}:{dir}',
		columns:[{
			text:'名称',dataIndex:'name'
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'审核人',dataIndex:'checkname'
		},{
			text:'排序号',dataIndex:'sort'
		},{
			text:'ID',dataIndex:'id'
		}],
		itemdblclick:function(ad,oi,e){
			$('#downshow_{rand}').html('审核人员组<b>['+ad.num+'.'+ad.name+']</b>下的规则');
			mid=ad.id;
			a.search("and `pid`="+ad.id+"");
			get('add_{rand}').disabled=false;
		},
		itemclick:function(d){
			btns(false);
		},
		beforeload:function(){
			btns(true);
		}
	});
	
	function btn(bo){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	function btns(bo){
		get('edits_{rand}').disabled = bo;
		get('dels_{rand}').disabled = bo;
	}
	
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_cname',celleditor:true,defaultorder:'`sort`',
		autoLoad:false,where:'and `pid`=-1',modedir:'{mode}:{dir}',
		columns:[{
			text:'适用对象',dataIndex:'recename'
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'审核人',dataIndex:'checkname'
		},{
			text:'排序号',dataIndex:'sort',editor:true
		},{
			text:'ID',dataIndex:'id'
		}],
		load:function(d){
			
		},
		itemclick:function(d){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	var c = {
		reload:function(){
			a.reload();
		},
		del:function(){
			a.del();
		},
		dels:function(){
			at.del();
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'审核人员组下规则',height:400,width:400,
				tablename:'flow_cname',isedit:lx,beforesaveaction:'savebeforecname',
				params:{int_filestype:'sort,pid'},url:publicsave('{mode}','{dir}'),
				submitfields:'sort,num,checkid,checkname,recename,receid,pid',
				items:[{
					labelText:'编号',name:'num',required:true
				},{
					labelText:'适用对象',type:'changeuser',changeuser:{
						type:'deptusercheck',idname:'receid',title:'选择适用对象'
					},name:'recename',clearbool:true,required:true
				},{
					labelText:'审核人',type:'changeuser',changeuser:{
						type:'usercheck',idname:'checkid',title:'选择审核人'
					},name:'checkname',clearbool:true,required:true
				},{
					name:'receid',type:'hidden',value:'0'
				},{
					name:'checkid',type:'hidden',value:'0'
				},{
					name:'pid',value:'0'
				},{
					labelText:'序号',name:'sort',type:'number',value:'0'
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('pid').value = mid;
		},
		clickwins:function(o1, lx){
			var h = $.bootsform({
				title:'审核人员组',height:400,width:400,beforesaveaction:'savebeforecname',
				tablename:'flow_cname',isedit:lx,url:publicsave('{mode}','{dir}'),
				params:{int_filestype:'sort'},
				submitfields:'name,sort,num,checkid,checkname',
				items:[{
					labelText:'名称',name:'name',required:true
				},{
					labelText:'编号',name:'num',required:true
				},{
					labelText:'审核人',type:'changeuser',changeuser:{
						type:'usercheck',idname:'checkid',title:'选择审核人'
					},name:'checkname',clearbool:true,required:true
					
				},{
					name:'checkid',type:'hidden',value:'0'
				},{
					labelText:'序号',name:'sort',type:'number',value:'0'
				}],
				success:function(){
					at.reload();
				}
			});
			if(lx==1){
				h.setValues(at.changedata);
			}
			h.getField('name').focus();
		}
	};
	js.initbtn(c);
	$('#optionview_{rand}').css('height',''+(viewheight-62-50)+'px');
});
</script>


<table width="100%">
<tr valign="top">
<td width="45%">
	<table width="100%">
	<tr>
	<td align="left" nowrap>
		<button class="btn btn-warning" click="clickwins,0" type="button"><i class="icon-plus"></i> 新增组</button>
	</td>
	<td align="left"  style="padding:0px 10px;">
		
	</td>
	<td width="90%">
		
	</td>
	<td align="right" nowrap>
		
		<button class="btn btn-info" id="edits_{rand}" click="clickwins,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>&nbsp; 
		<button class="btn btn-danger" click="dels" disabled id="dels_{rand}" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
	</tr>
	</table>
	<div class="blank10"></div>
	<div class="panel panel-info" style="margin:0px">
	  <div class="panel-heading">
		<h3 class="panel-title">审核人员组(双击显示对应设置)</h3>
	  </div>
	  <div id="optionview_{rand}" style="height:400px;overflow:auto"></div>
	</div>
</td>
<td width="10"></td>
<td>
	<div>
	<ul class="floats">
		<li class="floats70">
			<button class="btn btn-primary" click="clickwin,0" disabled id="add_{rand}" type="button"><i class="icon-plus"></i> 新增组下规则</button>&nbsp;&nbsp;
			<span id="downshow_{rand}"></span>
		</li>
		<li class="floats30" style="text-align:right">
			<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button> &nbsp; 
			<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		</li>
	</ul>
	</div>
	<div class="blank10"></div>
	<div id="view_{rand}"></div>

	
</td>
</tr>
</table>