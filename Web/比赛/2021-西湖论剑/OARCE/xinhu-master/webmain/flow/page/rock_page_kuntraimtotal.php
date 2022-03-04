<?php
/**
*	模块：knowtraim.考试培训统计
*	来源：http://www.rockoa.com/
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var mid = params.id;
	if(!mid)mid='0';
	
	var myChart = [],darr=[];
	
	darr[0] = $('#view0_{rand}').bootstable({
		fanye:true,tablename:'knowtrais',
		url:publicmodeurl('knowtraim'),storebeforeaction:'knowtraimuserbefore',storeafteraction:'knowtraimuserafter',
		params:{atype:'all',mid:mid},
		columns:[{
			text:"部门",dataIndex:"deptname"
		},{
			text:"姓名",dataIndex:"name"
		},{
			text:"考试时间",dataIndex:"kssdt",sortable:true
		},{
			text:"考试状态",dataIndex:"isks",sortable:true
		},{
			text:"分数",dataIndex:"fenshu",sortable:true
		}],
		load:function(d){
			
		}
	});
	
	darr[1] = $('#view1_{rand}').bootstable({
		url:publicmodeurl('knowtraim','tongji'),params:{mid:mid},
		columns:[{
			text:"名称",dataIndex:"name"
		},{
			text:"人数",dataIndex:"value"
		},{
			text:"比例",dataIndex:"bili"
		}],
		load:function(d){
			c.loadcharts(1);
		}
	});
	
	var c={
		search:function(){
		},
		reload:function(o1,lx){
			darr[lx].reload();
		},
		loadcharts:function(oi,tlx){
			if(!tlx)tlx='pie';
			var rows = darr[oi].getData('rows'),i,len=rows.length,v;
			var xAxis=[],data=[];
			for(i=0;i<len;i++){
				if(rows[i].name!='合计'){
					xAxis.push(rows[i].name);
					v = rows[i].value;if(v=='')v=0;
					data.push({value:parseFloat(v),name:rows[i].name});
				}
			}

			if(!myChart[oi])myChart[oi] = echarts.init(get('viewchats'+oi+'_{rand}'));
			var option = {
				title: {
					text: '',
					left: 'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{b} : {c}人 ({d}%)"
				},
				series: [{
					name: '人数',
					type: tlx,
					data: data
				}]
			};
			if(tlx!='pie'){
				option.xAxis={data: xAxis};
				option.yAxis={type : 'value'};
			}
			myChart[oi].setOption(option);
		}
		
	};
	js.initbtn(c);
});
</script>
<!--
<div>
	<table width="100%">
	<tr>
	<td nowrap>考试培训主题&nbsp;</td>
	<td>
		<select style="width:150px" class="form-control " id="dt1_{rand}" ></select>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">查看统计</button>
	</td>
	<td width="90%">
		
	</td>
	<td align="right" nowrap>
		
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>-->
<div align="left">
	<table  border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
		
		<td width="50%">
			<div align="left" style="min-width:300px" class="list-group">
			<div class="list-group-item  list-group-item-info">
				<i class="icon-search"></i> 按考试人员查询</span>
				<span style="float:right" ><a click="reload,0"><i class="icon-refresh"></i></a></span>
			</div>
			<div id="view0_{rand}"></div>
			</div>
			
		</td>
		
		<td style="padding-left:10px;">
			<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-success">
				<i class="icon-bar-chart"></i> 按考试合格率统计</span>
				<span style="float:right" ><a click="reload,1"><i class="icon-refresh"></i></a></span>
			</div>
			<div id="view1_{rand}"></div>
			<div id="viewchats1_{rand}" style="width:100%;height:250px;border:1px #dddddd solid;border-top:0px"></div>
			</div>
			
			
			
		</td>
		
		
		
	</tr>
	</table>
</div>