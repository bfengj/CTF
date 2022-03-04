<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var uid=params.uid;
	if(!uid)uid=adminid+'';
	
	var columns=[{
		text:'部门',dataIndex:'deptname'
	},{
		text:'姓名',dataIndex:'name'
	},{
		text:'月份',dataIndex:'month'
	},{
		text:'待收金额',dataIndex:'shou_moneyd'
	},{
		text:'已收金额',dataIndex:'shou_moneys'
	},{
		text:'应收金额',dataIndex:'shou_moneyz'
	},{
		text:'当月已收金额',dataIndex:'shou_moneyn'
	},{
		text:'收款单数',dataIndex:'shou_shu'
	},{
		text:'待付金额',dataIndex:'fu_moneyd'
	},{
		text:'已付金额',dataIndex:'fu_moneys'
	},{
		text:'应付金额',dataIndex:'fu_moneyz'
	},{
		text:'当月已付金额',dataIndex:'fu_moneyn'
	},{
		text:'付款单数',dataIndex:'fu_shu'
	}];
	var a = $('#view_{rand}').bootstable({
		tablename:'custfina',params:{'uid':uid},modedir:'{mode}:{dir}',storeafteraction:'custtotalgeafter',storebeforeaction:'custtotalgebefore',
		columns:columns,
		load:function(a){
			c.loadcharts('shou_moneys','已收金额');
		}
	});
	
	
	var myChart=false;
	var c = {

		reload:function(){
			a.reload();
		},
		daochu:function(){
			a.exceldown();
		},
		search:function(){
			var startdt = get('start_{rand}').value,
				enddt = get('end_{rand}').value;
			a.setparams({'startdt':startdt,'enddt':enddt},true);
		},
		loadcharts:function(sf, nas){
			if(!myChart){
				$('#ssssv_{rand}').after('<div class="blank20"></div><div id="main_show{rand}" style="width:98%;height:400px;"></div>');
				myChart = echarts.init(get('main_show{rand}'));
			}
			var rows = a.getData('rows'),i,len=rows.length,v;
			if(!sf)sf='shou_moneys';
			var xAxis=[],data=[];
			for(i=len-1;i>=0;i--){
				xAxis.push(rows[i].month);
				v = rows[i][sf];if(v=='')v=0;
				data.push(parseFloat(v));
			}
			var option = {
				title: {
					text: ''+nas+'图表',
					left: 'center'
				},
				tooltip: {},
				legend: {
					data:['']
				},
				xAxis: {
					data: xAxis
				},
				yAxis: {type : 'value'},
				series: [{
					name: nas,
					type: 'line',
					data: data
				}]
			};
			myChart.setOption(option);
		},
		inits:function(){
			var ss=[];
			for(var i=0;i<columns.length;i++){
				if(i>2)ss.push({name:columns[i].text,id:columns[i].dataIndex});
			}
			js.setselectdata(get('selectsss_{rand}'), ss,'id');
			get('selectsss_{rand}').value='shou_moneys';
			$('#selectsss_{rand}').change(function(){
				c.changesels(this);
			});
		},
		changesels:function(o){
			var val = o.value;if(val=='')return;
			var nam = o.options[o.selectedIndex].text;
			this.loadcharts(val, nam);
		}
	};
	js.initbtn(c);
	$('#start_{rand}').val(js.now('Y-01'));
	$('#end_{rand}').val(js.now('Y-m'));
	c.inits();
});
</script>
<div>
	<table width="100%">
	<tr>
	<td style="padding-right:10px">
		<button class="btn btn-default" click="reload" type="button"><i class="icon-refresh"></i></button> 
	</td>
	<td style="padding-right:10px">
		<div style="width:120px" class="input-group">
			<input readonly placeholder="月份从" class="form-control" id="start_{rand}" >
			<span class="input-group-btn">
				<button onclick="return js.selectdate(this,'start_{rand}','month')" class="btn btn-default" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td nowrap>至&nbsp; </td>
	<td>
		<div style="width:120px" class="input-group">
			<input readonly class="form-control" id="end_{rand}" >
			<span class="input-group-btn">
				<button onclick="return js.selectdate(this,'end_{rand}','month')" class="btn btn-default" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td  width="90%" style="padding-left:10px">
		
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="blank10"></div>
<div id="ssssv_{rand}"><select id="selectsss_{rand}" style="width:250px" class="form-control"><option value="">--选择图表字段--</option></select></div>
