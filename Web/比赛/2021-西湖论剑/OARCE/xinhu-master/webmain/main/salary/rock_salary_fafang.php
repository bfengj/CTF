<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var modenum = 'hrsalary';
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,params:{'atype':'faf'},fanye:true,modenum:modenum,modedir:'{mode}:{dir}',statuschange:false,checked:true,storeafteraction:'xinziafafter',
		columns:[{
			text:'部门',dataIndex:'deptallname',sortable:true
		},{
			text:'人员',dataIndex:'uname',sortable:true
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'月份',dataIndex:'month',sortable:true
		},{
			text:'开户行',dataIndex:'bankname'
		},{
			text:'工资卡号',dataIndex:'banknum'
		},{
			text:'基本工资',dataIndex:'base',sortable:true
		},{
			text:'实发工资',dataIndex:'money',sortable:true
		},{
			text:'状态',dataIndex:'ispay',sortable:true
		},{
			text:'核算人',dataIndex:'optname'
		},{
			text:'核算时间',dataIndex:'optdt'
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		},
		itemdblclick:function(){
			c.view();
		},
		load:function(d){
			get('daochu{rand}').disabled= (!d.isdaochu);
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
			openxiangs('薪资',modenum,d.id);
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value,
				dt:get('dt2_{rand}').value
			},true);
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('薪资', modenum,id);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			a.setparams({ispay:lx});
			this.search();
		},
		biaoshi:function(){
			var sid = a.getchecked();
			if(sid==''){
				js.msg('msg','没有选中行');
				return;
			}
			js.confirm('确定要将未发放标识已发放嘛？',function(slx){
				if(slx=='yes')c.biaoshis();
			});
		},
		biaoshis:function(){
			var sid = a.getchecked();
			js.msg('wait','标识中...');
			js.ajax(js.getajaxurl('biaoshiff','{mode}','{dir}'),{sid:sid},function(s){
				js.msg('success','标识成功');
				a.reload();
			},'post');
		}
	};
	js.initbtn(c);
});

</script>
<div>
	<table width="100%">
	<tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="biaoshi" type="button">选中标为已发放</button>
	</td>
	<td>
		<input class="form-control" style="width:180px" id="key_{rand}"  placeholder="部门/姓名/职位">
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>	
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td width="80%" style="padding-left:10px">
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_" click="changlx," type="button">全部</button>
		<button class="btn btn-default" id="state{rand}_0" style="color:red" click="changlx,0" type="button">待发放</button>
		<button class="btn btn-default" id="state{rand}_1" style="color:green" click="changlx,1" type="button">已发放</button>
		</div>	
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" disabled id="daochu{rand}" type="button">导出</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">标识已发放会将对应薪资信息(工资条)发送给对应人员。</div>