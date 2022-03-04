<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var bools = false,type = params.type,typename='入库',mid=params.mid;
	if(type==1)typename='出库';
	if(!mid)mid=0;
	var a = $('#view_{rand}').bootstable({
		tablename:'goods',celleditor:true,fanye:true,params:{'type':type,'mid':mid},
		url:publicstore('{mode}','{dir}'),storebeforeaction:'beforeshow',storeafteraction:'aftershow',
		columns:[{
			text:'<font color=red>*</font>要'+typename+'数量',dataIndex:'shu',renderer:function(v,d){
				var maxss = d.maxcount;
				if(!maxss)maxss='99999999';
				var sv = '<input name="shu_{rand}" valid="'+d.id+'" style="width:70px;text-align:center" onfocus="js.focusval=this.value" min="0" max="'+maxss+'" onblur="js.number(this)" type="number" value="" class="form-control">';
				if(d.maxcount)sv+=' 还可'+typename+'数'+d.maxcount+'';
				return sv;
			}
		},{
			text:'总库存',dataIndex:'stock',sortable:true
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'名称',dataIndex:'name'
		},{
			text:'分类',dataIndex:'typeid'
		},{
			text:'单价',dataIndex:'price',sortable:true
		},{
			text:'单位',dataIndex:'unit'
		},{
			text:'规格',dataIndex:'guige'
		},{
			text:'型号',dataIndex:'xinghao'
		},{
			text:'ID',dataIndex:'id'	
		}],
		itemdblclick:function(d){
			openxiang('goods',d.id);
		},
		load:function(d){
			if(!bools){
				if(params.kindname){
					d.typearr = [{'name':params.kindname,'value':params.kind}];
				}
				js.setselectdata(get('type_{rand}'),d.typearr,'value');
				js.setselectdata(get('depotid_{rand}'),d.depotarr,'id');
			}
			bools=true;
		}
	});
	var c = {
		save:function(o1){
			var d={dt:$('#dt1_{rand}').val(),'type':type,kind:get('type_{rand}').value,sm:get('explain_{rand}').value,'depotid':get('depotid_{rand}').value,'mid':mid};
			var msgid='msgview_{rand}';
			d.cont = c.getshul();
			if(d.cont==''){
				js.setmsg('没有输入'+typename+'数量','', msgid);
				return;
			}
			if(d.dt==''){
				js.setmsg('请选择日期','', msgid);
				return;
			}
			if(d.kind==''){
				js.setmsg('请选择'+typename+'类型','', msgid);
				return;
			}
			if(d.depotid==''){
				js.setmsg('请选择'+typename+'的仓库','', msgid);
				return;
			}
			js.setmsg(''+typename+'中...','', msgid);
			o1.disabled=true;
			js.ajax(js.getajaxurl('chukuopt','{mode}','{dir}'),d,function(s){
				if(s=='success'){
					js.setmsg(''+typename+'成功','green', msgid);
					a.reload();
				}else{
					js.setmsg(s,'', msgid);
				}
				o1.disabled=false;
			},'post');
		},
		getshul:function(){
			var o = $("input[name='shu_{rand}']"),i,s='',o1,val;
			for(i=0;i<o.length;i++){
				o1=$(o[i]);
				val=o1.val();
				if(!isempt(val)){
					val=parseFloat(val);
					if(val>0)s+=','+o1.attr('valid')+'|'+val+'';
				}
			}
			if(s!='')s=s.substr(1);
			return s;
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};
	

	js.initbtn(c);
	
	$('#showte{rand}').html('<b>'+typename+'</b>操作');
	$('#dt1_{rand}').val(js.now());
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>
		<h4 id="showte{rand}">入库操作</h4>
	</td>
	<td  style="padding-left:10px">
		<div class="input-group" style="width:250px">
			<input class="form-control" id="key_{rand}"   placeholder="名称/编号/型号">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
	</td>
	
	<td width="80%"></td>
	<td align="right" nowrap>
		
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="blank20"></div>
<table width="500">
	<tr>
	<td width="120" align="right" ><font color=red>*</font>日期：</td>
	<td class="tdinput">
		<div style="width:200px"  class="input-group">
			<input readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	</tr>
	<tr>
	<td align="right" ><font color=red>*</font>类型：</td>
	<td class="tdinput">
		<select  id="type_{rand}" style="width:200px" class="form-control"><option value="">-请选择-</option></select>
	</td>
	</tr>
	<tr>
	<td align="right" ><font color=red>*</font>选择仓库：</td>
	<td class="tdinput">
		<select  id="depotid_{rand}" style="width:200px" class="form-control"><option value="">-请选择-</option></select>
	</td>
	</tr>
	<tr>
	<td align="right" >说明：</td>
	<td class="tdinput">
		<textarea id="explain_{rand}" class="form-control" style="height:60px"></textarea>
	</td>
	</tr>
	
	<tr>
		<td  align="right"></td>
		<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;确认提交</button>&nbsp; <span id="msgview_{rand}"></span>
	</td>
	</tr>
</table>
<div class="blank10"></div>
