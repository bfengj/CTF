<?php
/**
*	模块：finpiao.发票统计，
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	
	var a = $('#view_{rand}').bootstable({
		tablename:'finpiao',
		url:publicmodeurl('finpiao','piaototal'),
		columns:[{
			text:'月份',dataIndex:'month'
		},{
			text:'收到发票金额',dataIndex:'moneyshou'
		},{
			text:'开出去发票金额',dataIndex:'moneykai'
		},{
			text:'合计(开出去-收到)的发票',dataIndex:'moneyzong'
		}],
		load:function(d){
			get('dt1_{rand}').value = d.dt1;
			get('dt2_{rand}').value = d.dt2;
		}
	});
	var c = {
		search:function(){
			
			a.setparams({dt1:get('dt1_{rand}').value,dt2:get('dt2_{rand}').value},true);
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'发票统计',
				'btnobj':o1
			});
		}
	};
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>月份&nbsp;</td>
	<td>
		<input onclick="js.datechange(this,'month')" style="width:110px" readonly class="form-control datesss" id="dt1_{rand}" >
	</td>
	<td>&nbsp;至&nbsp;</td>
	<td align="left">
		<input onclick="js.datechange(this,'month')" style="width:110px" readonly class="form-control datesss" id="dt2_{rand}" >
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">统计</button>
	</td>

	<td width="90%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>