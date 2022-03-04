<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id,mid=params.setid;
	if(!id)id = 0;
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'flow_todo',
		url:publicsave('{mode}','{dir}'),
		params:{otherfields:'setid='+mid+''},
		submitfields:'explain,status,num,name,whereid,changefields,changecourse,boturn,boedit,bochang,bodel,bozuofei,botong,bobutong,bozhui,receid,recename,setid,toturn,tocourse,tosuper,bofinish,bozhuan,todofields,summary,botask,boping',
		success:function(){
			closenowtabs();
			try{guanflowtodolist.reload();}catch(e){}
		},
		submitcheck:function(d){
			if(d.botask=='1' && d.whereid=='0')return '计划任务的类型必须选择触发条件';
			if(d.botask=='1' && !d.summary)return '计划任务的类型通知内容摘要必须写';
			return {
				changefields:c.getsleval('changefields'),
				changecourse:c.getsleval('changecourse')
			}
		}
	});
	h.forminit();
	js.setselectdata(h.form.whereid,guanflowtodowherelist[0],'id');
	js.setselectdata(h.form.changefields,guanflowtodowherelist[1],'fields');
	js.setselectdata(h.form.changecourse,guanflowtodowherelist[2],'id');
	if(id>0){
		var d=guanflowtodolist.changedata;	
		h.setValues(d);
		js.setselectval(h.form.changefields,d.changefields);
		js.setselectval(h.form.changecourse,d.changecourse);
	}
	
	var c = {
		changcourse:function(o1){
			var bo = o1.checked;
			setTimeout(function(){o1.checked = bo;c.changcourses();},10);

		},
		changcourses:function(){
			if(h.form.botong.checked || h.form.bobutong.checked){
				$('#changecourse{rand}').show();
			}else{
				$('#changecourse{rand}').hide();
			}
		},
		changfields:function(o1){
			var bo = o1.checked;
			setTimeout(function(){o1.checked = bo;},10);
			if(bo){
				$('#changefields{rand}').show();
			}else{
				$('#changefields{rand}').hide();
			}
		},
		getsleval:function(fv){
			return js.getselectval(h.form[fv]);
		},
		getdists:function(o1, lx){
			var cans = {
				nameobj:h.form.recename,
				idobj:h.form.receid,
				type:'deptusercheck',
				title:'通知给'
			};
			js.getuser(cans);
		},
		removes:function(){
			h.form.recename.value='';
			h.form.receid.value='';
		}
	};
	js.initbtn(c);
	c.changcourses();
	c.changfields(h.form.bochang);
});

</script>

<div align="center">
<div  style="padding:10px;width:650px">
	
	
	<form name="form_{rand}">
	
		<input name="id" value="0" type="hidden" />
		
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		
		<tr>
			<td  align="right"  width="15%">编号：</td>
			<td class="tdinput"  width="35%"><input name="num" maxlength="20" onblur="this.value=strreplace(this.value)" class="form-control"></td>
			
			
			
		</tr>
		
		
		<tr>
			<td  align="right"  width="15%">通知标题：</td>
			<td class="tdinput"  colspan="3"><input name="name" maxlength="30" onblur="this.value=strreplace(this.value)" placeholder="主表变量{字段}格式" class="form-control"></td>
			
			
		</tr>
		
		<tr>
			<td  align="right" >触发条件：</td>
			<td class="tdinput"><select class="form-control" name="whereid"><option value="0">无条件</option></select></td>
			<td colspan="2">满足时。</td>
		</tr>
		<tr>
			<td  align="right" ></td>
			<td colspan="3" style="padding-bottom:10px"><font color=#888888>在【流程模块条件】上添加，满足此条件才触发通知</font></td>
		</tr>
		
	
		<tr>
			<td  align="right" nowrap >触发类型：</td>
			<td colspan="3" class="tdinput">
			<label><input name="boturn" type="checkbox" value="1">提交时</label>&nbsp; 
			<label><input name="boedit" type="checkbox" value="1">编辑时</label>&nbsp; 
			<label><input name="bochang" click="changfields" disabled type="checkbox" value="1">字段改变时(未开发)</label>&nbsp; 
			<label><input name="bodel" type="checkbox" value="1">删除时</label>&nbsp; 
			<label><input name="bozuofei" type="checkbox" value="1">作废时</label>&nbsp; 
			<label><input  name="botong" click="changcourse" type="checkbox" value="1">步骤处理通过时</label>&nbsp; 
			<label><input  name="bobutong"  click="changcourse" type="checkbox" value="1">步骤处理不通过时</label>&nbsp; 
			<label><input  name="bofinish" type="checkbox" value="1">处理完成时</label>&nbsp; 
			<label><input  name="bozhuan" type="checkbox" value="1">转办时</label>&nbsp; 
			<label><input  name="bozhui" type="checkbox" value="1">追加说明时</label>&nbsp; 
			<label><input  name="botask" type="checkbox" value="1">计划任务</label>&nbsp; 
			<label><input  name="boping" type="checkbox" value="1">评论时</label>&nbsp; 
			</td>
		</tr>
		
		
		
		<tr id="changefields{rand}" style="display:none">
			<td  align="right" nowrap >变化字段(可多选)：<br><font color="#888888">来自[表单元素管理]</font><font color=white>：</font></td>
			<td class="tdinput"><select multiple name="changefields" size="8" class="form-control"></select></td>
		</tr>
		<tr id="changecourse{rand}" style="display:none">
			<td  align="right" nowrap >处理的步骤(可多选)：<br><font color="#888888">来自[流程审核步骤]</font><font color=white>：</font></td>
			<td class="tdinput"><select multiple name="changecourse" size="5" class="form-control"></select></td>
		</tr>
		
		<tr>
			<td  colspan="4"><div class="inputtitle">通知给如下人员</div></td>
		</tr>
		
		<tr>
			<td  align="right" nowrap >通知给：</td>
			<td class="tdinput" colspan="3">
			<label><input name="toturn" type="checkbox" value="1">提交人</label>&nbsp; 
			<label><input name="tocourse" type="checkbox" value="1">流程所有参与人</label>&nbsp; 
			<label><input name="tosuper" type="checkbox" value="1">直属上级</label>&nbsp; 
			</td>
		</tr>
		<tr>
			<td  align="right" nowrap >通知给：</td>
			<td class="tdinput" colspan="3">
				<div style="width:100%" class="input-group">
					<input readonly class="form-control"  name="recename" >
					<input type="hidden" name="receid" >
					<span class="input-group-btn">
						<button class="btn btn-default" click="removes" type="button"><i class="icon-remove"></i></button>
						<button class="btn btn-default" click="getdists,1" type="button"><i class="icon-search"></i></button>
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td  align="right" nowrap >通知给单据字段上：</td>
			<td class="tdinput" colspan="3">
				<input name="todofields" placeholder="写主表上的字段，必须是保存人员ID的字段" class="form-control">
			</td>
		</tr>
	
		<tr>
			<td align="right">通知内容摘要：</td>
			<td class="tdinput" colspan="3"><textarea name="summary" style="height:60px" class="form-control"></textarea></td>
		</tr>
	<tr>
			<td align="right">说明：</td>
			<td class="tdinput" colspan="3"><textarea  name="explain" style="height:60px" class="form-control"></textarea></td>
		</tr>
		
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" colspan="3">
				<label><input name="status" value="1" checked type="checkbox"> 启用</label>
			</td>
		</tr>

		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button  class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
	
</div>
</div>
