<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id,mid=params.mid;
	if(!id)id = 0;
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'flow_element',
		url:publicsave('{mode}','{dir}'),
		params:{otherfields:'mid='+mid+''},
		submitfields:'name,fields,fieldstype,xiaoshu,dev,savewhere,placeholder,sort,istj,ispx,isalign,issou,islu,islb,isbt,iszs,data,iszb,attr,lens,gongsi,isonly,isdr',
		requiredfields:'name,fields,fieldstype,lens',aftersaveaction:'elemensavefields',beforesaveaction:'elemensavefieldsbefore',
		success:function(){
			closenowtabs();
			try{guanelementedit.reload();}catch(e){}
		},
		submitcheck:function(d){
			if(d.fieldstype.indexOf('change')==0){
				if(d.data=='')return '此字段元素类型时，数据源必须填写用来存储选择来的Id，如填写为：'+d.fields+'id';
			}
			if(d.islu=='1' && d.fields=='id')return 'id字段是不可以做录入项字段';
		}
	});
	h.forminit();

	var farr = zzzfieldsarr[params.table];
	js.setselectdata(h.form.fieldss,farr,'id');
	js.setselectdata(h.form.fieldstype,fieldstypearr,'value');
	if(id>0){
		var d=guanelementedit.changedata;
		h.setValues(d);
		h.form.fieldss.value=d.fields;
	}
	$(h.form.fieldss).change(function(){
		h.form.fields.value=this.value;
		var txt = this.options[this.selectedIndex].text;
		var as1 = txt.split(']');if(as1[1])h.form.name.value=as1[1];
	});
	$(h.form.fields).blur(function(){
		var val = this.value;
		val = val.replace(/[^a-zA-Z0-9+\_]/gi,'');
		this.value = strreplace(val);
	});
	$(h.form.fieldstype).change(function(){
		var val = this.value;
		c.changetype();
	});
	blursehs{rand}=function(o1){
		o1.value = o1.value.replace('select ','[SQL]');
	}
	
	var c = {
		xuanchang:function(){
			var val = h.form.fieldstype.value;
			if(val.indexOf('change')==0){
				var cans1 = {
					idobj:h.form.gongsi,
					type:'deptusercheck',
					title:'选择范围'
				};
				js.getuser(cans1);
			}else{
				js.msg('msg','元素类型不是选择人员部门的');
			}
		},
		changetype:function(){
			var val = h.form.fieldstype.value;
			if(val=='number'){
				$('#div_number{rand}').show();
			}else{
				$('#div_number{rand}').hide();
			}
		}
	};
	js.initbtn(c);
	if(id>0){
		c.changetype();
	}
});

</script>

<div align="center">
<div  style="padding:10px;width:700px">
	
	
	<form name="form_{rand}" autocomplete="off">
	
		<input name="id" value="0" type="hidden" />
		
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
			<td  align="right" ><font color=red>*</font> 名称：</td>
			<td class="tdinput"><input name="name" class="form-control"></td>
			
		</tr>
		
		<tr>
		
			<td  align="right"  ><font color=red>*</font> 对应字段：</td>
			<td class="tdinput" colspan="3">
				<table><tr>
					<td width="220"><input name="fields" class="form-control"></td>
					<td width="220"><select name="fieldss" class="form-control"><option value="">-字段-</option></select></td>
				</tr></table>
			</td>
		</tr>
		
		<tr>
			<td  align="right" width="15%" nowrap ><font color=red>*</font> <a target="_blank" href="<?=URLY?>view_element.html">?字段元素类型</a>：</td>
			<td width="35%"  class="tdinput"><select name="fieldstype" class="form-control"><option value="">-字段-</option></select>
			<div id="div_number{rand}" style="display:none">小数点位数：<input type="text" onfocus="js.focusval=this.value" onblur="js.number(this)" min="0" max="6" name="xiaoshu" class="input" style="width:60px" value="0"></div>
			</td>
			
			<td  width="15%" align="right" nowrap>默认值：<br><a target="_blank" href="<?=URLY?>view_xinhudev.html">看帮助</a>&nbsp;&nbsp;</td>
			<td width="35%"  class="tdinput"><input name="dev" placeholder="不会设置?看帮助吧" class="form-control"></td>
		</tr>
		
		<tr>
			<td align="right">字段分类：</td>
			<td class="tdinput"><select name="iszb" class="form-control"><option value="0">主表字段</option><option value="1">第1个多行子表字段</option><option value="2">第2个多行子表字段</option><option value="3">第3个多行子表字段</option><option value="4">第4个多行子表字段</option><option value="5">第5个多行子表字段</option><option value="6">第6个多行子表字段</option><option value="7">第7个多行子表字段</option><option value="8">第8个多行子表字段</option><option value="9">第9个多行子表字段</option>
			<option value="10">第10个多行子表字段</option>
			<option value="11">第11个多行子表字段</option>
			<option value="12">第12个多行子表字段</option>
			<option value="13">第13个多行子表字段</option>
			<option value="14">第14个多行子表字段</option>
			<option value="15">第15个多行子表字段</option>
			<option value="16">第16个多行子表字段</option>
			<option value="17">第17个多行子表字段</option>
			<option value="18">第18个多行子表字段</option>
			<option value="19">第19个多行子表字段</option>
			<option value="20">第20个多行子表字段</option>
			<option value="21">用到20多个子表，真厉害，建议拆分多个模块</option>
			</select></td>
			<td align="right">字段长度：</td>
			<td class="tdinput"><input name="lens" value="0" maxlength="4" type="number"  onfocus="js.focusval=this.value" onblur="js.number(this)" class="form-control"></td>
		</tr>
	
		<tr>
			<td align="right">数据源：<br><a target="_blank" href="<?=URLY?>view_element.html">看帮助</a>&nbsp;&nbsp;</td>
			<td class="tdinput" colspan="3"><textarea placeholder="数据选项编号,自定义方法等" name="data" style="height:60px" onblur="blursehs{rand}(this)" class="form-control"></textarea></td>
		</tr>
		
		<tr>
			<td align="right">属性：</td>
			<td class="tdinput" colspan="3"><input name="attr" placeholder="如果只读填写：readonly" class="form-control"></td>
		</tr>
		<tr>
			<td align="right">提示内容：</td>
			<td class="tdinput" colspan="3"><input name="placeholder" placeholder="" class="form-control"></td>
		</tr>
		<tr>
			<td align="right">计算公式：</td>
			<td class="tdinput" colspan="3"><textarea  name="gongsi" style="height:60px" class="form-control"></textarea><font color=#888888>如：{price}*{shu}，更多公式详见<a target="_blank" href="<?=URLY?>view_gongsi.html">[帮助]</a>哦，字段元素类型为选择人员部门时这个可以设置要<a href="javascript:;" click="xuanchang">选择范围</a>。</font></td>
		</tr>
		
		<tr>
			<td align="right">保存条件：</td>
			<td class="tdinput" colspan="3"><textarea  name="savewhere" style="height:60px" class="form-control"></textarea><font color=#888888>如截止时间比较大于开始：gt|{startdt}|提示，多个,分开。符号说明gt大于，egt大于等于，lt小于，elt小于等于，eg等于，neg不等于，{now}当前时间，{date}当前日期</font></td>
		</tr>
		
		<tr>
			<td align="right">排序号：</td>
			<td class="tdinput"><input name="sort" value="0" maxlength="3" type="number"  onfocus="js.focusval=this.value" onblur="js.number(this)" class="form-control"></td>
			<td align="right">对齐方式：</td>
			<td class="tdinput"><select name="isalign" class="form-control"><option value="0">居中</option><option value="1">居左</option><option value="2">居右</option></select></td>
		</tr>
	
		
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" colspan="3">
				<label><input name="islu" value="1" checked type="checkbox"> 录入列?</label>&nbsp; &nbsp; 
				<label><input name="isbt" value="1" checked type="checkbox"> 是否必填</label>&nbsp; &nbsp; 
				<label><input name="iszs" value="1" checked type="checkbox"> 展示列</label>&nbsp; &nbsp; 
				<label><input name="islb" value="1" checked type="checkbox"> 列表列</label>&nbsp; &nbsp; 
				<label><input name="ispx" value="0" type="checkbox"> 列表列排序</label>&nbsp; &nbsp; 
				<label><input name="issou" value="0" type="checkbox"> 可搜索筛选</label>&nbsp; &nbsp; 
				<label><input name="istj" value="0" type="checkbox"> 可统计</label>&nbsp; &nbsp; 
				<label><input name="isonly" value="0" type="checkbox"> 唯一值</label>&nbsp; &nbsp; 
				<label><input name="isdr" value="0" type="checkbox"> 导入字段</label>&nbsp; &nbsp; 
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
