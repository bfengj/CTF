<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var a = $('#view_{rand}').bootstable({
		tablename:'meet',modenum:'meet',modename:'会议',params:{'atype':atype},fanye:true,
		columns:[{
			text:'',dataIndex:'caozuo'
		},{
			text:'会议室',dataIndex:'hyname'
		},{
			text:'主题',dataIndex:'title',align:'left'
		},{
			text:'参会人',dataIndex:'joinname'
		},{
			text:'星期',dataIndex:'week'
		},{
			text:'纪要人',dataIndex:'jyname'
		},{
			text:'开始时间',dataIndex:'startdt'
		},{
			text:'结束时间',dataIndex:'enddt'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'状态',dataIndex:'state',sortable:true
		},{
			text:'发起人',dataIndex:'optname'
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('会议','meet',d.id,'opegs{rand}');
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value,
				dt:get('dt1_{rand}').value
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var asty=['my','mybz','myall','myfq'];
			a.setparams({atype:asty[lx]});
			this.search();
		},
		clickwin:function(){
			openinput('会议','meet',0,'opegs{rand}');
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(function(){
		c.search();
	});
	opegs{rand}=function(){
		c.reload();
	}
});
</script>
<div>
	<table width="100%">
	<tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
	<td>
		<div style="width:120px"  class="input-group">
			<input placeholder="日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="主题/参会人">
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  width="80%" style="padding-left:10px">
		
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">今日会议</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">本周会议</button>
		<button class="btn btn-default" id="state{rand}_2" click="changlx,2" type="button">全部会议</button>
		<button class="btn btn-default" id="state{rand}_3" click="changlx,3" type="button">我发起</button>
		</div>	
	</td>
	
	
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">默认显示今日的会议，会议读取全部人员的</div>
