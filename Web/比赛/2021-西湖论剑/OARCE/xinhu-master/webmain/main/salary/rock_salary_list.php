<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	var modenum = 'hrsalary';
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,params:{'atype':atype},fanye:true,modenum:modenum,modedir:'{mode}:{dir}',statuschange:false,checked:false,storeafteraction:'xinziafter',
		columns:[{
			text:'部门',dataIndex:'deptallname',sortable:true
		},{
			text:'人员',dataIndex:'uname',sortable:true
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'月份',dataIndex:'month',sortable:true
		},{
			text:'基本工资',dataIndex:'base',sortable:true
		},{
			text:'核算人',dataIndex:'optname'
		},{
			text:'核算时间',dataIndex:'optdt',sortable:true
		},{
			text:'实发工资',dataIndex:'money',sortable:true
		},{
			text:'是否核算',dataIndex:'isturnss'
		},{
			text:'发放',dataIndex:'ispay',sortable:true
		},{
			text:'状态',dataIndex:'statustext'
		},{
			text:'',dataIndex:'caozuo',callback:'callback{rand}'
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
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'薪资',
				'modenum':modenum,
				'btnobj':o1
			});
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('薪资', modenum,id,'callback{rand}');
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		create:function(){
			var yef = get('dt2_{rand}').value;
			if(yef==''){
				js.msg('msg','请先选择月份');
				return false;
			}
			js.ajax(js.getajaxurl('createdata','{mode}','{dir}'),{month:yef},function(s){
				js.msg('success',s);
				if(s.indexOf('成功')>-1)a.reload();
			});
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			a.setparams({isturn:lx});
			this.search();
		},
		daoru:function(){
			managelisthrsalary = a;
			addtabs({num:'daoru'+modenum+'',url:'flow,input,daoru,modenum='+modenum+'',icons:'plus',name:'导入薪资核算'});
		}
	};
	js.initbtn(c);
	callback{rand}=function(){
		a.reload();
	}
});
</script>

<div>
	<table width="100%">
	<tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增核算</button>
	</td>
	<td>
		<input class="form-control" style="width:180px" id="key_{rand}"  placeholder="部门/姓名/职位">
	</td>
	<td  style="padding-left:10px">
		<div style="width:120px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>	
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td style="padding:0px 10px">
		<button class="btn btn-default" click="create" type="button">一键生成人员核算数据</button> 
	</td>
	<td width="80%">
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_" click="changlx," type="button">全部</button>
		<button class="btn btn-default" id="state{rand}_0" style="color:red" click="changlx,0" type="button">待核算</button>
		<button class="btn btn-default" id="state{rand}_1" style="color:green" click="changlx,1" type="button">已核算</button>
		</div>	
	
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daoru" type="button">导入</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" disabled id="daochu{rand}" type="button">导出 <i class="icon-angle-down"></i></button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">提示：薪资核算前，请先核算考勤状态哦，需要核算+已审核才能发放哦！</div>
