<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#veiw_{rand}').bootstable({
		tablename:'schedule',celleditor:false,fanye:true,modenum:'schedule',sort:'id',dir:'desc',modename:'日程',
		columns:[{
			text:'标题',dataIndex:'title',editor:true,align:'left'
		},{
			text:'时间',dataIndex:'startdt',sortable:true
		},{
			text:'截止时间',dataIndex:'enddt',sortable:true
		},{
			text:'重复',dataIndex:'rate'
		},{
			text:'是否提醒',dataIndex:'txsj'
		},{
			text:'提醒给',dataIndex:'recename'
		},{
			text:'添加人',dataIndex:'optname'
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		}],
		itemclick:function(d){
			btn(d.uid!=adminid);
		},
		beforeload:function(){
			btn(true);
		},
		itemdblclick:function(d){
			openxiangs(this.modename,this.modenum,d.id);
		}
	});
	
	var c = {
		del:function(){
			a.del({check:function(lx){if(lx=='yes')btn(true)}});
		},
		refresh:function(){
			a.reload();
		},
		clickdt:function(o1){
			js.changedate(o1,'dt1_{rand}');
		},
		search:function(o1){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt:get('dt1_{rand}').value},true);
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('日程','schedule',id,'wfhoew{rand}');
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var as = ['my','rece'];
			a.setparams({'atype':as[lx]},true);
		}
	};
	wfhoew{rand}=function(){
		c.refresh();
	}
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
});
</script>

<div>

<table width="100%">
<tr>
	<td align="left">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
	
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="标题">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td width="80%" style="padding-left:10px">
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">我创建</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">提醒给我的</button>
		</div>	
	</td>
	<td align="right" width="200" nowrap>
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
	<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</td>
</tr>
</table>

	

</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
