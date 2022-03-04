<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	
	var a = $('#view_{rand}').bootstable({
		tablename:'official',fanye:false,url:publicmodeurl('officic','tongjiData'),
		params:{atype:params.atype},
		columns:[{
			text:'日期',dataIndex:'dt'
		},{
			text:'星期',dataIndex:'week'
		},{
			text:'发文单数量',dataIndex:'fwshu'
		},{
			text:'收文单数量',dataIndex:'swshu'
		}],
		loadbefore:function(d){
			get('dt1_{rand}').value=d.startdt;
			get('dt2_{rand}').value=d.enddt;
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
		<div style="width:150px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="return js.selectdate(this,'dt1_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<div style="width:150px"  class="input-group">
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
<div class="tishi">只统计审核完成的单据</div>