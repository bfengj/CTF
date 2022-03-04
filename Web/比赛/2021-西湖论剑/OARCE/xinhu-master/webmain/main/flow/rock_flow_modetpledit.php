<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id,mid=params.mid;
	if(!id)id = 0;
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'flow_modetpl',
		url:publicsave('{mode}','{dir}'),
		params:{otherfields:'setid='+mid+''},
		submitfields:'tplname,tplnum,sort,explain,status,recename,receid,fieldsluru,fieldsbitian',
		requiredfields:'tplname,tplnum',aftersaveaction:'modetpl_savefieldsafter',beforesaveaction:'modetpl_savefieldsbefore',
		success:function(){
			closenowtabs();
			try{guanmodetpledit.reload();}catch(e){}
		},
		submitcheck:function(d){
			
		}
	});
	h.forminit();
	
	var a = $('#viewzd{rand}').bootstable({
		tablename:'flow_element',celleditor:true,
		params:{mid:mid,sid:id},
		url:publicstore('{mode}','{dir}'),storeafteraction:'modetpledit_after',storebeforeaction:'modetpledit_before',
		columns:[{
			text:'名称',dataIndex:'name'
		},{
			text:'字段',dataIndex:'fields'
		},{
			text:'类型',dataIndex:'fieldstype'
		},{
			text:'默认值',dataIndex:'dev'
		},{
			text:'录入项',dataIndex:'islu',renderer:function(v,d){
				var ceck = (v=='1')? 'checked' : '';
				return '<input type="checkbox" '+ceck+' value="'+d.fields+'" name="fieldsluru[]">';
			}
		},{
			text:'必填',dataIndex:'isbt',renderer:function(v,d){
				var ceck = (v=='1')? 'checked' : '';
				return '<input type="checkbox" '+ceck+' value="'+d.fields+'" name="fieldsbitian[]">';
			}
		}],
		load:function(a){
			if(a.data){
				h.setValues(a.data);
			}
		}
	});
	
	
	var c = {
		getdist:function(o1, lx){
			var cans = {
				nameobj:h.form.recename,
				idobj:h.form.receid,
				type:'deptusercheck',
				title:'选择针对人员'
			};
			js.getuser(cans);
		},
		allqt:function(){
			h.form.recename.value='全体人员';
			h.form.receid.value='';
		}
	};
	js.initbtn(c);
});

</script>

<div align="center">
<div  style="padding:10px;width:750px">
	
	
	<form name="form_{rand}">
	
		<input name="id" value="0" type="hidden" />
		
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
			<td  align="right" ><font color=red>*</font> 模版名称：</td>
			<td class="tdinput"><input name="tplname" class="form-control"></td>
			<td  align="right" ><font color=red>*</font> 模版编号：</td>
			<td class="tdinput"><input name="tplnum" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" >适用对象：</td>
			<td class="tdinput" colspan="3">
				<div class="input-group" style="width:100%">
					<input readonly class="form-control"  name="recename" >
					<input type="hidden" name="receid" >
					<span class="input-group-btn">
						<button class="btn btn-default" click="allqt" type="button">全体人员</button>
						<button class="btn btn-default" click="getdist,1" type="button"><i class="icon-search"></i></button>
					</span>
				</div>
			</td>
			
		</tr>
		
		<tr>
			<td  align="right" >说明：</td>
			<td class="tdinput" colspan="3"><textarea  name="explain" style="height:60px" class="form-control"></textarea></td>
		</tr>
		
		
		<tr>
			<td align="right">排序号：</td>
			<td class="tdinput"><input name="sort" value="0" maxlength="3" type="number"  onfocus="js.focusval=this.value" onblur="js.number(this)" class="form-control"></td>
			<td align="right"></td>
			<td class="tdinput"><label><input name="status" value="1" checked type="checkbox">启用</label>&nbsp; &nbsp; </td>
		</tr>
	
		
		<tr>
			<td colspan="4" id="viewzd{rand}">
				
			</td>
		</tr>

		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button disabled class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
</div>
</div>
