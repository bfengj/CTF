<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#veiw_{rand}').bootstable({
		tablename:'contacts',celleditor:true,sort:'sort',dir:'asc',keywhere:'and optid={adminid}',
		columns:[{
			text:'姓名',dataIndex:'uname'
		},{
			text:'用户id',dataIndex:'uid'
		},{
			text:'排序号',dataIndex:'sort',editor:true
		}],
		itemclick:function(){
			btn(false);
		}
	});
	
	var c = {
		del:function(){
			a.del({check:function(lx){if(lx=='yes')btn(true)}});
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'常联系人',height:400,width:400,
				tablename:'contacts',isedit:lx,url:publicsave(),params:{int_filestype:'sort',otherfields:'optdt={now},optid={adminid},optname={admin}'},
				submitfields:'uname,sort,uid',
				items:[{
					name:'uid',type:'hidden'
				},{
					labelText:'常联系人',type:'changeuser',changeuser:{
						type:'user',idname:'uid',title:'选择人员'
					},name:'uname',clearbool:true,required:true
				},{
					labelText:'排序号',name:'sort',type:'number',value:'0'
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
		},
		refresh:function(){
			a.reload();
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
});
</script>
<div>
<ul class="floats">
	<li class="floats50">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button> &nbsp; 
		<button class="btn btn-success" click="refresh" type="button"><i class="icon-refresh"></i> 刷新</button>
	</li>
	<li style="text-align:right" class="floats50">
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</li>
</ul>
</div>	
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
