<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_bill',celleditor:true,fanye:true,statuschange:false,
		url:publicstore('fwork','{dir}'),params:{atype:'error'},storebeforeaction:'flowbillbefore',storeafteraction:'flowbillafter',
		columns:[{
			text:'模块',dataIndex:'modename'
		},{
			text:'部门',dataIndex:'deptname'
		},{
			text:'申请人',dataIndex:'name'
		},{
			text:'单号',dataIndex:'sericnum'
		},{
			text:'申请日期',dataIndex:'applydt',sortable:true
		},{
			text:'摘要',dataIndex:'summary',align:'left',width:300
		},{
			text:'状态',dataIndex:'status',sortable:true
		},{
			text:'异常原因',dataIndex:'errorsm'
		},{
			text:'处理方法',dataIndex:'chuli',align:'left'
		},{
			text:'处理',dataIndex:'chulis',renderer:function(v,d,i){
				if(d.errtype==1){
					v='<button type="button" onclick="biaoshiyw{rand}('+i+',1)" class="btn btn-success btn-xs">标识已审核</button>';
					v+='<br><button type="button" onclick="biaoshiyw{rand}('+i+',2)" class="btn btn-danger btn-xs">退回提交人</button>';
				}
				return v;
			}
		}],
		itemclick:function(d){
			btn(false, d);
		},
		beforeload:function(){
			btn(true);
		},
		celldblclick:function(){
			c.view();
		}
	});
	function btn(bo, d){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	biaoshiyw{rand}=function(i,lx){
		c.biaowanc(i,lx);
	}
	var c = {
		view:function(){
			var d=a.changedata;
			openxiangs(d.modename,d.modenum,d.id);
		},
		pipei:function(){
			js.ajax(js.getajaxurl('reloadpipei','{mode}','{dir}'),{},function(s){
				js.msg('success', s);
				a.reload();
			},'get',false,'匹配中...,匹配完成');
		},
		biaowanc:function(i,lx){
			var d= a.getData(i);
			var sm='确定要标识已完成/已审核的单据状态吗';
			if(lx==2)sm='确定要退回给提交人让他重新提交吗';
			js.prompt('异常标识说明',''+sm+'？请输入说明：',function(jg, text){
				if(jg=='yes'){
					d.sm = text;
					c.biaowancss(d,lx);
				}
			});
		},
		biaowancss:function(d,lx){
			js.ajax(js.getajaxurl('oksuccess','flowopt','flow'),{modenum:d.modenum,mid:d.id,sm:d.sm,lx:lx},function(s){
				if(s=='ok'){
					a.reload();
				}else{
					js.msg('msg', s);
				}
			},'post',false,'标识中...,标识成功');
		},
		del:function(){
			a.del({
				url:js.getajaxurl('delmodeshuju','{mode}','{dir}'),
				params:{modenum:a.changedata.modenum,mid:a.changeid}
			});
		}
	};
	js.initbtn(c);

});
</script>

<div>
	<table width="100%">
	<tr>
	<td nowrap align="left">
	<button class="btn btn-default" click="pipei" type="button">重新匹配流程</button>&nbsp; 	
	</td>
	<td align="left"  width="100%" style="padding:0px 10px;">
		<div class="tishi">如有异常的记录请点击[重新匹配流程]，如出现无法解决，查看<a target="_blank" href="<?=URLY?>view_danerror.html">帮助</a>。<div>
	</td>
	<td align="right" nowrap>
		
		<button class="btn btn-default" id="edit_{rand}" click="view" disabled type="button">查看</button>&nbsp; 
		<button class="btn btn-danger" click="del" disabled id="del_{rand}" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>

