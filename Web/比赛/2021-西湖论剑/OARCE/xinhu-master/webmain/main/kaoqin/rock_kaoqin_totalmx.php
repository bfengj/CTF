<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var uid=params.uid,lobbo=false;
	
	var a = $('#view_{rand}').bootstable({
		tablename:'kqinfo',celleditor:true,fanye:true,params:{'uid':uid,'qjkind':'调休'},modedir:'{mode}:{dir}',storeafteraction:'kqtotalmxafter',storebeforeaction:'kqtotalmxbefore',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left',sortable:true
		},{
			text:'姓名',dataIndex:'uname',sortable:true
		},{
			text:'类型',dataIndex:'kind'
		},{
			text:'请假类型',dataIndex:'qjkind'
		},{
			text:'开始时间',dataIndex:'stime'
		},{
			text:'截止时间',dataIndex:'etime'
		},{
			text:'时间(时)',dataIndex:'totals'
		},{
			text:'剩余(时)',dataIndex:'totals1'
		}],
		itemclick:function(){
			
		},
		load:function(d){
			if(!lobbo){
				var da = [],i;
				for(i=0;i<d.kqkind.length;i++){
					da.push({name:d.kqkind[i].name.substr(2)});
				}
				var o = get('qjkind_{rand}');
				o.length=1;
				js.setselectdata(o,da);
			}
			lobbo=true;
		}
	});
	var c = {
		search:function(){
			a.setparams({qjkind:get('qjkind_{rand}').value},true);
		},
	
		daochu:function(){
			a.exceldown();
		}
	};
	
	js.initbtn(c);
	$('#qjkind_{rand}').change(function(){
		c.search();
	});
});
</script>
<div>
<table width="100%"><tr>

	<td >
		<select id="qjkind_{rand}" style="width:150px" class="form-control">
		<option value="调休">调休</option>
		<option value="年假">年假</option>
		</select>
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu" type="button">导出</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">明细来自考勤信息表kqinfo上字段status为0,1，灰色的记录说明已失效到了截止日期的。</div>