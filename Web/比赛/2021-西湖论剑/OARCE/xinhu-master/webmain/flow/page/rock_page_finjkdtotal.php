<?php
/**
*	模块：finjkd.借款单统计，
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var atype = params.atype;
	if(!atype)atype='my';
	
	
	var a = $('#view_{rand}').bootstable({
		tablename:'userinfo',fanye:true,
		url:publicmodeurl('finjkd'),params:{atype:atype},storeafteraction:'jktotalaftershow',storebeforeaction:'jktotalbeforeshow',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left',sortable:true
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'人员状态',dataIndex:'state'
		},{
			text:'借款金额',dataIndex:'moneyjk'
		},{
			text:'已还款',dataIndex:'moneyhk'
		},{
			text:'需还款',dataIndex:'moneyhx'
		}],
		itemclick:function(){
			
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			var is1 = (get('iskqew_{rand}').checked)?'1':'0';
			a.setparams({key:s,kjk:is1},true);
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'借款单统计',
				'btnobj':o1
			});
		}
	};
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td nowrap style="padding-left:10px">
		<label><input id="iskqew_{rand}" type="checkbox">只看有借款的</label>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>

	<td width="90%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>