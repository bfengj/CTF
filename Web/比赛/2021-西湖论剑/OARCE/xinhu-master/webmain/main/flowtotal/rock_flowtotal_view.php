<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var params  = js.decode(jm.base64decode(params.paramsstr));
	var myChart = false;
	var dccc = {
		'modeid' : params.modeid,
		'total_fields' : params.total_fields,
		'total_type' : params.total_type,
		'atype'		 : params.atype
	};
	var a = $('#viewshow_{rand}').bootstable({
		tablename:'todo',modedir:'flowtotal:main',storebeforeaction:'flowtotalbefore',storeafteraction:'flowtotalafter',
		params:dccc,
		columns:[{
			text:'名称',dataIndex:'name'
		},{
			text:'数值',dataIndex:'value'
		},{
			text:'比例',dataIndex:'bili'
		}],
		load:function(a){
			c.loadcharts();
		}
	});
	
	var c={
		search:function(o1,lx){
			var d = {
				
			};
			a.setparams(d,true);
		},
		
		loadcharts:function(){
			var rows = a.getData('rows'),i,len=rows.length,v;
			var xAxis=[],data=[];
			for(i=0;i<len;i++){
				if(rows[i].name!='合计'){
					xAxis.push(rows[i].name);
					v = rows[i].value;if(v=='')v=0;
					data.push({value:parseFloat(v),name:rows[i].name});
				}
			}
			
			if(!myChart)myChart = echarts.init(get('main_show{rand}'));
			var tlx= get('chattype_{rand}').value;
			var option = {
				title: {
					text: params.title,
					left: 'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{b} : {c} ({d}%)"
				},
				series: [{
					name: '数值',
					type: tlx,
					data: data
				}]
			};
			if(tlx!='pie'){
				option.xAxis={data: xAxis};
				option.yAxis={type : 'value'};
			}
			myChart.setOption(option);
		},
		daochu:function(){

			a.exceldown(params.title);
		}
	}

	js.initbtn(c);
	$('#main_show{rand}').css('height',''+(viewheight-110)+'px');
});
</script>

<div>
	<table width="100%">
	<tr>
	<td align="left">
		
	</td>
	<td style="padding-left:10px">
		<select style="width:100px" id="chattype_{rand}" class="form-control" ><option value="pie">饼图</option><option value="line">线图</option><option value="bar">柱状图</option></select>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search,0" type="button">重新统计</button>
	</td>
	<td  style="padding-left:10px">
		
	</td>
	<td width="90%">
		
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<table width="100%">
<tr valign="top">
	<td width="80%">
		<div id="main_show{rand}" style="width:100%;height:480px"></div>
	</td>
	<td>
		<div style="width:350px" id="viewshow_{rand}"></div>
	</td>
</tr>
</table>