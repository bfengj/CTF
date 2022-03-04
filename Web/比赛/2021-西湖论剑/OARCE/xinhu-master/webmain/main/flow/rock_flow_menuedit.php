<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id,mid=params.setid;
	if(!id)id = 0;
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'flow_menu',
		url:publicsave('{mode}','{dir}'),
		params:{otherfields:'setid='+mid+''},
		submitfields:'name,statusname,statuscolor,type,statusvalue,actname,wherestr,upgcont,explain,status,num,iszs,issm,islog,fields',
		requiredfields:'name',
		success:function(){
			closenowtabs();
			try{guanflowmenulist.reload();}catch(e){}
		},
		submitcheck:function(d){
			if(d.type==7 && !d.upgcont)return '请在更新内容上写对应方法名';
			if(d.type==5 && !d.upgcont)return '请在更新内容上写打开地址';
			return {
				wherestr:jm.base64encode(d.wherestr),
				upgcont:jm.base64encode(d.upgcont),
			}
		}
	});
	h.forminit();


	if(id>0){
		var d=guanflowmenulist.changedata;
		
		h.setValues(d);
		if(!isempt(d.wherestr)){
			h.setValue('wherestr',jm.base64decode(d.wherestr));
		}
		if(!isempt(d.upgcont)){
			h.setValue('upgcont',jm.base64decode(d.upgcont));
		}
	}
});

</script>

<div align="center">
<div  style="padding:10px;width:600px">
	
	
	<form name="form_{rand}">
	
		<input name="id" value="0" type="hidden" />
		
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		
		<tr>
			<td  align="right" >编号：</td>
			<td class="tdinput"><input name="num" maxlength="20" onblur="this.value=strreplace(this.value)" class="form-control"></td>
			
			
			
		</tr>
		
		<tr>
			
			<td  align="right" >类型：</td>
			<td class="tdinput"><select name="type" class="form-control"><option value="1">弹出填写说明</option><option value="0">直接操作</option><option value="2">人员选择(单人)</option><option value="3">人员选择(多人)</option><option value="6">人员选择(多选部门人员组)</option><option value="4">更新字段</option><option value="5">打开新窗口</option><option value="7">自定义方法(需开发)</option></select></td>
			<td  align="right" >字段名称：</td>
			<td class="tdinput"><input name="fields" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" ><font color=red>*</font> 显示名称：</td>
			<td class="tdinput"><input name="name" maxlength="20" onblur="this.value=strreplace(this.value)" class="form-control"></td>
			
			<td  align="right" >动作名称：</td>
			<td class="tdinput"><input name="actname" maxlength="20" onblur="this.value=strreplace(this.value)" class="form-control"></td>
		</tr>
		
	
		<tr>
			<td  align="right" width="15%" nowrap >状态名称：</td>
			<td width="35%"  class="tdinput"><input maxlength="20" onblur="this.value=strreplace(this.value)" name="statusname" class="form-control"></td>
			
			<td  width="15%" align="right" nowrap>对应状态值：</td>
			<td width="35%"  class="tdinput"><select name="statusvalue" class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option></select></td>
		</tr>
		
		<tr>
			<td align="right">状态名颜色：</td>
			<td class="tdinput">
			<select name="statuscolor" class="form-control"><option value="">-无-</option>
			<option style="background-color:red" value="red">red</option>
			<option style="background-color:green" value="green">green</option>
			<option style="background-color:blue" value="blue">blue</option>
			<option style="background-color:magenta" value="magenta">magenta</option>
			<option style="background-color:yellow" value="yellow">yellow</option>
			<option style="background-color:chocolate" value="chocolate">chocolate</option>
			<option style="background-color:gray" value="gray">gray</option>
			<option style="background-color:aquamarine" value="aquamarine">aquamarine</option>
			<option style="background-color:fuchsia" value="fuchsia">fuchsia</option>
			<option style="background-color:brass" value="brass">brass</option>
			<option style="background-color:brown" value="brown">brown</option>
			<option style="background-color:deeppink" value="deeppink">deeppink</option>
			<option style="background-color:copper" value="copper">copper</option>
			<option style="background-color:orange" value="orange">orange</option>
			</select>
			</td>
			<td align="right">排序号：</td>
			<td class="tdinput"><input name="sort" value="0" maxlength="3" type="number"  onfocus="js.focusval=this.value" onblur="js.number(this)" class="form-control"></td>
		</tr>
	
		<tr>
			<td align="right">条件：</td>
			<td class="tdinput" colspan="3"><textarea  name="wherestr" style="height:60px" class="form-control"></textarea><font color=#888888>为空或者条件满足时显示菜单</font></td>
		</tr>
		
		<tr>
			<td align="right" id="upcont_{rand}">更新内容：</td>
			<td class="tdinput" colspan="3"><textarea  name="upgcont" style="height:60px" class="form-control"></textarea><font color=#888888>当触发时同时更新对应记录为这个内容,{cname}选择的人,{cnameid}选择人id,{uid}当前用户id，当类型是[打开新窗口]时，这里填写Url地址，如:add|work|def_projectid={id},也就是:新增|模块编号|默认参数</font></td>
		</tr>
	
		<tr>
			<td align="right">说明：</td>
			<td class="tdinput" colspan="3"><textarea  name="explain" style="height:60px" class="form-control"></textarea></td>
		</tr>
	
		
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" colspan="3">
				<label><input name="status" value="1" checked type="checkbox"> 启用</label>&nbsp; &nbsp; 
				<label><input name="islog" value="1" checked type="checkbox"> 写入日志</label>&nbsp; &nbsp; 
				<label><input name="issm" value="1" checked type="checkbox"> 必须填写说明</label>&nbsp; &nbsp; 
				<label><input name="iszs" value="1" type="checkbox"> 显示在详情页</label>&nbsp; &nbsp; 
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
