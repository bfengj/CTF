<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#viewshow_{rand}').bootstable({
		tablename:'userinfo',modedir:'userinfo:main',storebeforeaction:'useranaybefore',storeafteraction:'useranayafter',
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
	var myChart=false;
	var c={
		search:function(){
			a.setparams({
				'type' : get('type_{rand}').value,
				'dt' : get('dt_{rand}').value,
			},true);
		},
		loadcharts:function(){
			var rows = a.getData('rows'),i,len=rows.length,v;
			var xAxis=[],data=[];
			for(i=0;i<len;i++){
				xAxis.push(rows[i].name);
				v = rows[i].value;if(v=='')v=0;
				data.push({value:parseFloat(v),name:rows[i].name});
			}
			var o1 = get('type_{rand}');
			if(!myChart)myChart = echarts.init(get('main_show{rand}'));
			var ss = o1.options[o1.selectedIndex].text;
			var option = {
				title: {
					text: '按'+ss+'人员分析',
					left: 'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{b} : {c}人 ({d}%)"
				},
				series: [{
					name: '数值',
					type: 'pie',
					data: data
				}]
			};
			myChart.setOption(option);
		},
		daochu:function(){
			var o1 = get('type_{rand}');
			var ss = o1.options[o1.selectedIndex].text;
			a.exceldown(''+ss+'人员分析');
		}
	}
	js.initbtn(c);
});
</script>
<div>
<table width="100%">
<tr>
	<td nowrap>按照&nbsp;</td>
	<td>
		<select class="form-control" id="type_{rand}" style="width:150px;">
		<option value="deptname">部门</option>
		<option value="sex">性别</option>
		<option value="xueli">学历</option>
		<option value="nian">年龄</option>
		<option value="year">入职年份</option>
		<option value="nianxian">入职年限</option>
		<option value="state">人员状态</option>
		<option value="ranking">职位</option>
		</select>
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="入职日期" readonly class="form-control" id="dt_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" onclick="js.selectdate(this,'dt_{rand}')" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">分析</button>
	</td>


	<td width="90%"></td>
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
<div class="blank10"></div>