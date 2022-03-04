<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'infor',fanye:true,sort:'sort',dir:'asc',celleditor:true,
		url:publicstore('{mode}','{dir}'),storeafteraction:'inforafter',storebeforeaction:'inforbefore',
		columns:[{
			text:'类型',dataIndex:'typename',sortable:true
		},{
			text:'名称',dataIndex:'title',align:'left'
		},{
			text:'序号',dataIndex:'sort',editor:true
		},{
			text:'显示首页',dataIndex:'isshow',type:'checkbox',editor:true,sortable:true
		},{
			text:'操作人',dataIndex:'optname',sortable:true
		},{
			text:'发布者',dataIndex:'zuozhe'
		},{
			text:'时间',dataIndex:'indate',sortable:true,sortable:true,renderer:function(v){
				return v.replace(' ','<br>');
			}
		},{
			text:'发布给',dataIndex:'recename'
		},{
			text:'修改时间',dataIndex:'optdt',sortable:true,renderer:function(v){
				return v.replace(' ','<br>');
			}
		}],
		itemclick:function(d){
			btn(false, d);
		},
		beforeload:function(){
			btn(true);
		}
	});

	function btn(bo, d){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	
	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			at.reload();
		},
		clickwin:function(o1,lx){
			var icon='plus',name='新增信息',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑信息';
			};
			guaninforlist = a;
			addtabs({num:'inforedit'+id+'',url:'system,infor,edit,id='+id+'',icons:icon,name:name});
		},
		getcans:function(){
			var can = {key:get('key_{rand}').value};
			return can;
		},
		search:function(o1){
			a.setparams(this.getcans(), true);
		},
		view:function(ids){
			this.openview(a.changedata.id);
		},
		openview:function(ids){
			openwork(ids);
		}
	};
	
	js.initbtn(c);
});
</script>

<div>
	<table width="100%">
	<tr>
	<td align="left" width="100">

		<button class="btn btn-warning" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
		
	 </td>
	 <td align="left"  style="padding:0px 10px;">
		<div class="input-group" style="width:300px;">
			<input  class="form-control" id="key_{rand}"  placeholder="类型/名称">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
	 </td>
	  <td align="right">  
			<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>&nbsp; 
			<button class="btn btn-danger" click="del" disabled id="del_{rand}" type="button"><i class="icon-trash"></i> 删除</button>
		</td>
		</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
