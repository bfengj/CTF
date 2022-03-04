<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var columns = [{
		text:'日期',dataIndex:'dt'
	}];
	var a = $('#view_{rand}').bootstable({
		tablename:'option',fanye:false,modedir:'{mode}:{dir}',storebeforeaction:'meetqingkbefore',storeafteraction:'meetqingkafter',
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
		daochu:function(){
			a.exceldown();
		},
		setcolumns:function(d){
			var col=[],i,len=d.length;
			for(i=0;i<columns.length;i++)col.push(columns[i]);
			for(i=0;i<len;i++){
				col.push({text:d[i].name,dataIndex:'meet_'+d[i].id+'',align:'left'});
			}
			a.setColumns(col);
		},
		clickwin:function(){
			openinput('会议','meet',0,'opegs{rand}');
		}
	};
	js.initbtn(c);
	opegs{rand}=function(){
		a.reload();
	}
});
</script>
<div>
<table width="100%">
<tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
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
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
