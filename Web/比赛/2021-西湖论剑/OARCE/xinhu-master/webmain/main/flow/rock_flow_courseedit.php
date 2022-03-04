<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;var setid=params.setid,statusstr='';
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'flow_course',
		url:publicsave('{mode}','{dir}'),beforesaveaction:'coursesavebefore',
		params:{otherfields:'optdt={now}'},
		submitfields:'setid,name,num,checktype,checktypeid,checktypename,checkfields,sort,where,whereid,explain,status,courseact,checkshu,recename,receid,mid,iszf,isqm,nid,coursetype,zshtime,zshstate,zbrangeame,zbrangeid,smlx,wjlx,isxgfj,cslx,csfwname,csfwid',
		requiredfields:'name',
		success:function(){
			closenowtabs();
			try{guanflowcourselist.reload();}catch(e){}
		},
		load:function(a){
			js.setselectdata(h.form.whereid,a.wherelist,'id');
			statusstr=a.statusstr;
		},
		loadafter:function(a){
			c.changetype(0);
			if(a.data){
				h.form.where.value=jm.base64decode(a.data.where);
				if(a.data.iszf>0)$('#zbdiv_{rand}').show();
				if(a.data.cslx>0)$('#csdiv_{rand}').show();
			}
		},
		submitcheck:function(d){
			if(d.checktype=='user'&&d.checktypeid=='')return '请选择人员';
			if(d.checktype=='rank'&&d.checktypename=='')return '请输入职位';
			if(d.checktype=='cname'&&d.checktypeid=='')return '请选择审核人员组';
			if(d.checktype=='field'&&d.checktypeid=='')return '请选择主表元素';
			if(d.cslx=='3'&&d.csfwid=='')return '请选择抄送人员';
			return {
				where:jm.base64encode(d.where)
			};
		}
	});
	h.forminit();
	h.load(js.getajaxurl('loaddatacourse','{mode}','{dir}',{id:id,setid:setid}));
	var c = {
		getdist:function(o1, lx){
			var val = h.form.checktype.value;
			if(val=='rank'){
				$.selectdata({
					title:'选择职位',
					url:js.getajaxurl('getrank','admin','system'),
					checked:false,
					nameobj:h.form.checktypename
				});
				return;
			}
			if(val=='cname'){
				$.selectdata({
					title:'选审核人员组',
					url:js.getajaxurl('getcname','{mode}','{dir}'),
					checked:false,
					nameobj:h.form.checktypename,
					idobj:h.form.checktypeid,
				});
				return;
			}
			if(val=='field'){
				$.selectdata({
					title:'选择主表元素',
					url:js.getajaxurl('getfields','{mode}','{dir}',{'setid':setid}),
					checked:true,
					nameobj:h.form.checktypename,
					idobj:h.form.checktypeid,
				});
				return;
			}
			if(val=='change'){
				var cans = {
					nameobj:h.form.checktypename,
					idobj:h.form.checktypeid,
					value:h.form.checktypeid.value,
					type:'deptusercheck',
					title:'选择指定人范围'
				};
				js.getuser(cans);
				return;
			}
			var cans = {
				nameobj:h.form.checktypename,
				idobj:h.form.checktypeid,
				value:h.form.checktypeid.value,
				type:'usercheck',
				title:'选择人员'
			};
			js.getuser(cans);
		},
		clears:function(){
			h.form.checktypename.value='';
			h.form.checktypeid.value='';
		},
		changetype:function(lx){
			var v=h.form.checktype.value;
			$('#checktext_{rand}').html('');
			$('#checkname_{rand}').hide();
			if(lx==1){
				h.form.checktypename.value='';
				h.form.checktypeid.value='';
			}
			if(v=='rank'){
				$('#checktext_{rand}').html('请输入职位：');
				$('#checkname_{rand}').show();
			}
			if(v=='user'){
				$('#checktext_{rand}').html('请选择人员：');
				$('#checkname_{rand}').show();
			}
			if(v=='cname'){
				$('#checktext_{rand}').html('审核人员组：');
				$('#checkname_{rand}').show();
			}
			if(v=='field'){
				$('#checktext_{rand}').html('选择主表上元素：');
				$('#checkname_{rand}').show();
			}
			if(v=='change'){
				$('#checktext_{rand}').html('指定人范围：');
				$('#checkname_{rand}').show();
			}
		},
		reloadhweil:function(){
			h.form.whereid.length = 1;
			h.load(js.getajaxurl('loaddatacourse','{mode}','{dir}',{id:id,setid:setid}));
		},
		getdists:function(o1, lx){
			var cans = {
				nameobj:h.form.recename,
				idobj:h.form.receid,
				type:'deptusercheck',
				title:'选择适用对象'
			};
			js.getuser(cans);
		},
		getzbrangeame:function(o1, lx){
			var cans = {
				nameobj:h.form.zbrangeame,
				idobj:h.form.zbrangeid,
				type:'deptusercheck',
				title:'选择转办范围'
			};
			if(lx==2){
				cans.nameobj = h.form.csfwname;
				cans.idobj = h.form.csfwid;
				cans.title = '选择抄送范围';
			}
			js.getuser(cans);
		},
		getzbraben:function(o1,lx){
			if(lx==1){
				h.form.zbrangeame.value='本部门';
				h.form.zbrangeid.value='dept';
			}
			if(lx==2){
				h.form.csfwname.value='本部门';
				h.form.csfwid.value='dept';
			}
		},
		getzbrabens:function(o1,lx){
			if(lx==1){
				h.form.zbrangeame.value='本部门(含下级部门)';
				h.form.zbrangeid.value='deptall';
			}
			if(lx==2){
				h.form.csfwname.value='本部门(含下级部门)';
				h.form.csfwid.value='deptall';
			}
		},
		getzbraremoves:function(o1,lx){
			if(lx==1){
				h.form.zbrangeame.value='';
				h.form.zbrangeid.value='';
			}
			if(lx==2){
				h.form.csfwname.value='';
				h.form.csfwid.value='';
			}
		},
		csxuanze:function(){
			var s1 = h.form.csfwname.value,s2=h.form.csfwid.value;
			if(s1){
				s1+=',审批人直属上级';
				s2+=',super';
			}else{
				s1='审批人直属上级';
				s2='super';
			}
			h.form.csfwname.value=s1;
			h.form.csfwid.value=s2;
		},
		allqt:function(o1,lx){
			h.form.recename.value='全体人员';
			h.form.receid.value='all';
		},
		removes:function(){
			h.form.recename.value='';
			h.form.receid.value='';
		},
		
		setstatus:function(){
			var val = h.form.courseact.value;
			var sha = [],vala;
			if(val)sha = val.split(',');
			var str = '<table width="100%"><tr><td align="center"  height="30" nowrap>动作值</td><td>动作名</td><td>动作颜色</td><td>处理后状态</td></tr>';
			if(isempt(statusstr))statusstr='待处理,已完成,不通过';
			var ztarr = statusstr.replace(/\?/g,'').split(',');
			for(var i=0;i<=6;i++){
				var na='',col='',naa,sel='',ove='';
				if(sha[i]){
					naa = sha[i].split('|');
					na  = naa[0];
					if(naa[1])col=naa[1];
					if(naa[2])ove=naa[2];
				}
				str+='<tr><td width="20%" align="center">'+(i+1)+'</td><td width="25%"><input maxlength="10" value="'+na+'" id="abc_xtname'+i+'" style="color:'+col+'" class="form-control"></td><td width="25%"><input class="form-control" maxlength="7" style="color:'+col+'" value="'+col+'"  id="abc_xtcol'+i+'"></td><td width="30%">';
				str+='<select class="form-control" id="abc_xscol'+i+'" value="'+col+'">';
				str+='<option value=""></option>';
				for(var j=0;j<ztarr.length;j++){
					sel=(ove!='' && ove==j)?'selected':'';
					str+='<option '+sel+' value="'+j+'">'+ztarr[j]+'</option>';
				}
				str+='</select></td></tr>';
			}
			str+='</table>';
			
			js.tanbody('sttts','['+h.form.name.value+']的状态设置',400,300,{
				html:'<div style="height:300px;overflow:auto;padding:5px">'+str+'</div>',
				btn:[{text:'确定'}]
			});
			$('#sttts_btn0').click(function(){
				c.setstatusok();
			});
		},
		setstatusok:function(){
			var str = '';
			for(var i=0;i<=6;i++){
				var na=get('abc_xtname'+i+'').value,col=get('abc_xtcol'+i+'').value,zts=get('abc_xscol'+i+'').value;
				if(!na)break;
				str+=','+na+'';
				if(col){
					str+='|'+col+'';
					if(zts)str+='|'+zts+'';
				}else{
					if(zts)str+='||'+zts+'';
				}
			}
			if(str!='')str=str.substr(1);
			h.form.courseact.value=str;
			js.tanclose('sttts');
		},
		setwhere:function(){
			js.setwhere(params.setid,'backsheowe{rand}');
		}
	};
	js.initbtn(c);
	
	if(id==0){
		h.form.setid.value=setid;
		h.form.mid.value=params.mid;	
	}
	
	$(h.form.checktype).change(function(){
		c.changetype(1);
	});
	
	$(h.form.changezbsseas).change(function(){
		var o1= this.options[this.selectedIndex];
		h.form.zbrangeame.value=o1.text;
		h.form.zbrangeid.value=this.value;
	});
	$(h.form.changezbsseas1).change(function(){
		var o1= this.options[this.selectedIndex];
		h.form.csfwname.value=o1.text;
		h.form.csfwid.value=this.value;
	});
	
	$(h.form.iszf).change(function(){
		if(this.value>0){
			$('#zbdiv_{rand}').show();
		}else{
			$('#zbdiv_{rand}').hide();
			c.getzbraremoves(false, 1);
		}
	});
	$(h.form.cslx).change(function(){
		if(this.value>0){
			$('#csdiv_{rand}').show();
		}else{
			$('#csdiv_{rand}').hide();
			c.getzbraremoves(false,2);
		}
	});
	
	//替换的返回
	backsheowe{rand}=function(s1,s2){
		h.setValue('where',s1);
		h.setValue('explain',s2);
	}
});

</script>

<div align="center">
<div  style="padding:10px;width:700px">
	
	
	<form name="form_{rand}">
	
		<input name="id" value="0" type="hidden" />
		<input name="setid" value="0" type="hidden" />
		
		
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
			<td  align="right"  width="15%"><font color=red>*</font> 步骤名称：</td>
			<td class="tdinput"  width="35%"><input name="name" onblur="this.value=strreplace(this.value)" class="form-control"></td>
			<td  align="right"   width="15%">编号：</td>
			<td class="tdinput" width="35%"><input onblur="this.value=strreplace(this.value)" name="num" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" nowrap >步骤适用对象：</td>
		
			<td class="tdinput" colspan="3">
				<div style="width:100%" class="input-group">
					<input readonly class="form-control" placeholder="不选就适用全体人员" name="recename" >
					<input type="hidden" name="receid" >
					<span class="input-group-btn">
						<button class="btn btn-default" click="removes" type="button"><i class="icon-remove"></i></button>
						<button class="btn btn-default" click="getdists,1" type="button"><i class="icon-search"></i></button>
					</span>
				</div>
			</td>
		</tr>
		
		<tr>
			<td  align="right" nowrap ><a href="<?=URLY?>view_checklx.html" target="_blank">?审核人员类型</a>：</td>
			<td class="tdinput"><select class="form-control" name="checktype"><option value="">-类型-</option><option value="super">直属上级</option><option value="optsuper">上次处理的直属上级</option><option value="superall">直属上级逐级审批</option><option value="rank">职位</option><option value="user">指定人员</option><option value="dept">部门负责人</option><option value="auto">自定义(写代码上)</option><option value="apply">申请人</option><option value="opt">操作人</option><option value="change">由上步指定</option><option value="cname">审核人员组</option><option value="field">主表上元素</option></select></td>
			
			<td align="right" id="checktext_{rand}" nowrap></td>
			<td class="tdinput" id="checkname_{rand}" style="display:none">
				<div class="input-group" style="width:100%">
					<input class="form-control"  name="checktypename" >
					<input type="hidden" name="checktypeid" >
					<span class="input-group-btn">
						<button class="btn btn-default" click="clears" type="button">×</button>
						<button class="btn btn-default" click="getdist,1" type="button"><i class="icon-search"></i></button>
					</span>
				</div>
				
			</td>
		</tr>
		
		
		
		<tr>
			<td  align="right" >手写签名设置：</td>
			<td class="tdinput"><select class="form-control" name="isqm"><option value="0">不需要手写签名</option><option value="1">需要手写签名</option><option value="2">通过才需要手写签名</option><option value="3">不通过才需要手写签名</option></select></td>
			<td  align="right" >上级步骤ID：</td>
			<td class="tdinput">
				<table>
				<tr>
					<td><input name="mid" class="form-control" value="0" type="number" /></td>
					<td nowrap>&nbsp;下级步骤ID：</td>
					<td><input name="nid" style="width:70px" class="form-control" value="0" type="number" /></td>
				</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td  align="right" >审核条件：</td>
			<td class="tdinput"><select class="form-control" name="whereid"><option value="0">无条件</option></select></td>
			<td colspan="2"><font color=#888888>在【流程模块条件】上添加，满足此条件才需要此步骤</font><a click="reloadhweil" href="javascript:;">[刷新]</a></td>
		</tr>
		
		<tr>
			<td  align="right" >审核条件：<br><a click="setwhere" href="javascript:;">[设置条件]</a>&nbsp;&nbsp;</td>
			<td colspan="3"  class="tdinput"><textarea placeholder="写SQL条件，条件成立才需要此步骤，标准SQL条件" name="where" style="height:50px" class="form-control"></textarea></td>
		</tr>
		

		<tr>
			<td  align="right" >审核动作：</td>
			<td class="tdinput" colspan="3"><input name="courseact" placeholder="默认是：同意,不同意。多个,分开" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" >审核处理表单：</td>
			<td class="tdinput" colspan="3"><input name="checkfields" placeholder="写主表字段名，不支持子表字段" class="form-control"><div style="padding-top:0px" class="tishi">需要处理表单元素必须在【表单元素管理】上，输入字段名如：title,dt|stitle，其中格式：必填字段|选填字段</div></td>
		</tr>
		
		
		<tr>
			<td  align="right" >说明：</td>
			<td class="tdinput" colspan="3"><textarea  name="explain" style="height:50px" class="form-control"></textarea></td>
		</tr>
		
		<tr>
			
			<td  align="right" nowrap >审核人数：</td>
			<td class="tdinput"><select class="form-control" name="checkshu"><option value="0">需全部审核</option><option value="1" selected>至少一人</option><option value="2">至少2人</option></select></td>
			
			<!--
			<td  align="right" nowrap >审批方式：</td>
			<td class="tdinput"><select class="form-control" name="coursetype"><option value="0">顺序审批</option><option value="1">前置审批(前面有审批后面出现就跳过)</option><option value="2">后置审批(如后面步骤有出现就跳过)</option></select></td>
			-->
		</tr>
		
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" colspan="3">
				超过<input class="input" type="number" id="shijian_{rand}" onfocus="js.focusval=this.value" value="0" onblur="js.number(this)" min="0"  style="width:70px" name="zshtime">分钟自动审核<select name="zshstate"><option value="1">通过</option><option value="2">不通过</option><option value="3">作废单据</option><option value="4">删除单据</option><option value="5">催办提醒</option></select>,0不限制。<select onchange="$('#shijian_{rand}').val(this.value)" name="lbztxs"><option value="0">不限制</option><option value="30">30分钟</option><option value="120">2小时</option><option value="360">6小时</option><option value="1440">1天</option><option value="2880">2天</option><option value="10080">7天</option><option value="21600">15天</option><option value="43200">30天</option></select>
			</td>
		</tr>
		
		<tr>
			<td align="right">排序号：</td>
			<td class="tdinput"><input name="sort" value="0" maxlength="3" type="number"  onfocus="js.focusval=this.value" onblur="js.number(this)" class="form-control"></td>
		</tr>
	<tr>
			<td align="right">处理时：</td>
			<td class="tdinput" colspan="3"><label><input name="isxgfj" value="1" type="checkbox">可直接编辑附件(客户端需要安装<a href="<?=URLY?>view_editword.html" target="_blank">在线编辑文档</a>)</label>&nbsp;</td>
		</tr>
		
		<tr>
			<td align="right">转办类型：</td>
			<td class="tdinput">
				<select class="form-control" name="iszf">
				<option value="0">不可转办</option>
				<option value="1">可转办多人</option>
				<option value="2">可转办单人</option>
				</select>
			</td>
		</tr>
		
		<tr id="zbdiv_{rand}" style="display:none">
			<td  align="right" nowrap >转办的范围：</td>
		
			<td class="tdinput" colspan="3">
				<div  class="input-group">
					<input readonly class="form-control" placeholder="不选就可转办给任何人" name="zbrangeame" >
					<input type="hidden" name="zbrangeid" >
					<span class="input-group-btn">
						<select class="btn btn-default" name="changezbsseas" style="width:150px">
						<option value="">-选择-</option>
						<option value="dept">本部门</option>
						<option value="deptall">本部门(含下级部门)</option>
						<option value="down">直属下级</option>
						<option value="downall">直属下级(含下级的下级)</option>
						</select>
						<button class="btn btn-default" click="getzbraremoves,1" type="button"><i class="icon-remove"></i></button>
						<button class="btn btn-default" click="getzbrangeame,1" type="button"><i class="icon-search"></i></button>
					</span>
				</div>
			</td>
		</tr>
		
		<tr>
			<td align="right">处理说明：</td>
			<td class="tdinput">
				<select class="form-control" name="smlx">
				<option value="0">默认不同意才需要填写</option>
				  <option value="1" >都必须填写</option>
				  <option value="2" >都可以不写</option>
				  <option value="3" >不显示说明栏</option>

				</select>
			</td>
			<td align="right">处理文件：</td>
			<td class="tdinput">
				<select class="form-control" name="wjlx">
				<option value="0">默认(可选上传)</option>
				<option value="1" >必须上传</option>
				<option value="2" >仅同意时需上传</option>
				<option value="3" >不显示文件栏</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td align="right">抄送类型：</td>
			<td class="tdinput">
				<select class="form-control" name="cslx">
				<option value="0">不用抄送</option>
				<option value="1">可选抄送</option>
				<option value="2">同意时必须选抄送</option>
				<option value="3">同意时抄送给固定人</option>
				</select>
			</td>
		</tr>
		<tr id="csdiv_{rand}" style="display:none">
			<td  align="right" nowrap >抄送的范围：</td>
		
			<td class="tdinput" colspan="3">
				<div class="input-group">
					<input readonly class="form-control" placeholder="不选就可抄送给任何人" name="csfwname" >
					<input type="hidden" name="csfwid" >
					<span class="input-group-btn">
						<select class="btn btn-default" name="changezbsseas1" style="width:150px">
						<option value="">-选择-</option>
						<option value="dept">本部门</option>
						<option value="deptall">本部门(含下级部门)</option>
						<option value="down">直属下级</option>
						<option value="downall">直属下级(含下级的下级)</option>
						</select>
						<button class="btn btn-default" click="getzbraremoves,2" type="button"><i class="icon-remove"></i></button>
						<button class="btn btn-default" click="getzbrangeame,2" type="button"><i class="icon-search"></i></button>
					</span>
				</div>
				<div><a href="javascript:;" click="csxuanze,2">审批人直属上级</a></div>
			</td>
		</tr>
		
		
			
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" colspan="3">
				<label><input name="status" value="1" checked type="checkbox"> 启用</label>&nbsp;
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button disabled class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>&nbsp;<a href="<?=URLY?>view_course.html" target="_blank">[看帮助]</a>
		</td>
		</tr>
		
		</table>
		</form>
</div>
</div>
