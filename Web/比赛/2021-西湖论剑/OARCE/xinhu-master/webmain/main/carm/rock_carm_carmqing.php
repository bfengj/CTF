<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var columns = [{
		text:'类型',dataIndex:'cartype'
	},{
		text:'车牌号',dataIndex:'carnum'
	}];
	var a = $('#view_{rand}').bootstable({
		tablename:'carm',fanye:false,modedir:'{mode}:{dir}',storebeforeaction:'carmuserbefore',storeafteraction:'carmuserafter',
		columns:columns,
		loadbefore:function(d){
			get('dt1_{rand}').value=d.startdt;
			get('dt2_{rand}').value=d.enddt;
			c.setcolumns(d.columns);
		}
	});
	
	var c = {
		search:function(){
			a.setparams({
				'startdt':get('dt1_{rand}').value,
				'enddt':get('dt2_{rand}').value
			},true);
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'车辆预定情况',
				'btnobj':o1
			});
		},
		setcolumns:function(d){
			var col=[],i,len=d.length;
			for(i=0;i<columns.length;i++)col.push(columns[i]);
			for(i=0;i<len;i++){
				col.push({text:d[i],dataIndex:'dt'+i+''});
			}
			a.setColumns(col);
		}
	};
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
	<td nowrap>日期&nbsp;</td>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt1_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<div style="width:140px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt2_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px" width="90%">
	
	
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button> 
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
