<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype,zt=params.zt,mid=params.modeid;
	if(!zt)zt='';
	if(!mid)mid='0';
	var bools=false;
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_bill',params:{'atype':atype,'zt':zt,'modeid':mid},fanye:true,
		url:publicstore('{mode}','{dir}'),checked:atype=='daib',
		storeafteraction:'flowbillafter',storebeforeaction:'flowbillbefore',
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
			text:'摘要',dataIndex:'summary',align:'left',renderer:function(v){
				return '<div style="min-width:400px">'+v+'</div>';
			}
		},{
			text:'操作人',dataIndex:'optname',sortable:true
		},{
			text:'状态',dataIndex:'status',sortable:true
		},{
			text:'',dataIndex:'caozuo',callback:'opegs{rand}'
		}],
		celldblclick:function(){
			c.view();
		},
		load:function(a){
			if(!bools){
				var s = '<option value="0">-选择模块-</option>',len=a.flowarr.length,i,csd,types='';
				for(i=0;i<len;i++){
					csd = a.flowarr[i];
					if(types!=csd.type){
						if(types!='')s+='</optgroup>';
						s+='<optgroup label="'+csd.type+'">';
					}
					s+='<option value="'+csd.id+'">'+csd.name+'</option>';
					types = csd.type;
				}
				$('#mode_{rand}').html(s);
				if(mid>0)get('mode_{rand}').value=mid;
			}
			bools=true;
		},
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	function btn(bo){
		//get('xiang_{rand}').disabled = bo;
	}
	xing{rand}=function(oi){
		a.changedata = a.getData(oi);
		c.view();
	}
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs(d.modename,d.modenum,d.id,'opegs{rand}');
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value,
				dt1:get('dt1_{rand}').value,
				dt2:get('dt2_{rand}').value,
				modeid:get('mode_{rand}').value
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
			a.setparams({zt:lx});
			this.search();
		},
		plliang:function(){
			if(this.plbool)return;
			var d = a.getcheckdata();
			if(d.length<=0){
				js.msg('msg','请先用复选框选中行');
				return;
			}
			this.checkd = d;
			js.prompt('批量处理同意','请输入批量处理同意说明(选填)',function(lxbd,msg){
				if(lxbd=='yes'){
					setTimeout(function(){c.plliangso(msg);},10);
				}
			});
		},
		plliangso:function(sm){
			this.plbool = true;
			this.plchusm = sm;
			this.cgshu = 0;
			this.sbshu = 0;
			js.wait('<span id="plchushumm"></span>');
			this.plliangsos(0);
		},
		plliangsos:function(oi){
			var len = this.checkd.length;
			$('#plchushumm').html('批量处理中('+len+'/'+(oi+1)+')...');
			if(oi>=len){
				$('#plchushumm').html('处理完成，成功<font color=green>'+this.cgshu+'</font>条，失败<font color=red>'+this.sbshu+'</font>条');
				this.reload();
				this.plbool=false;
				return;
			}
			var d = this.checkd[oi];
			var cns= {sm:this.plchusm,zt:1,modenum:d.modenum,mid:d.id};
			js.ajax(js.getajaxurl('check','flowopt','flow'),cns, function(ret){
				if(ret.success){
					c.cgshu++;
				}else{
					c.sbshu++;
					js.msg('msg','['+d.modename+']'+ret.msg+'，不能使用批量来处理，请打开详情去处理。');
				}
				c.plliangsos(oi+1);
			},'post,json');
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(function(){
		c.search();
	});
	opegs{rand}=function(){
		c.reload();
	}
	
	$('#state{rand}_'+zt+'').addClass('active');
	
	if(atype=='mywtg' || atype=='daiturn'){
		$('#stewwews{rand}').hide();
	}
	if(atype!='daib'){
		$('#tdleft_{rand}').hide();
	}else{
		$('#state{rand}_').html('全部待办');
		$('#state{rand}_0').remove();
		$('#state{rand}_1').remove();
		$('#state{rand}_2').remove();
		$('#state{rand}_5').remove();
		$('#state{rand}_6').remove();
	}
	if(atype!='my')$('#state{rand}_6').remove();
});
</script>

<div>
	<table width="100%">
	<tr>
	<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button class="btn btn-primary" click="plliang,0" type="button">批量处理同意</button></td>
	<td nowrap>
		<select style="width:150px" id="mode_{rand}" class="form-control" ><option value="0">-选择模块-</option></select>	
	</td>
	<td  style="padding-left:10px">
		<input style="width:110px" onclick="js.changedate(this)" placeholder="申请日期" readonly class="form-control datesss" id="dt1_{rand}" >
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<input style="width:110px" onclick="js.changedate(this)" readonly class="form-control datesss" id="dt2_{rand}" >
	</td>
	

	<td  style="padding-left:10px">
		<input class="form-control" style="width:130px" id="key_{rand}"   placeholder="单号/处理人/姓名/部门">
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  width="80%" style="padding-left:10px">
		
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default" id="state{rand}_" click="changlx," type="button">全部状态</button>
		<button class="btn btn-default" id="state{rand}_0" click="changlx,0" type="button">待审核</button>
		<button class="btn btn-default" id="state{rand}_1" style="color:green" click="changlx,1" type="button">已审核</button>
		<button class="btn btn-default" id="state{rand}_2" style="color:red" click="changlx,2" type="button">未通过</button>
		<button class="btn btn-default" id="state{rand}_5" style="color:#888888" click="changlx,5" type="button">已作废</button>
		<button class="btn btn-default" id="state{rand}_6" style="color:#ff6600" click="changlx,6" type="button">待提交</button>
		</div>	
	</td>
	
	
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>