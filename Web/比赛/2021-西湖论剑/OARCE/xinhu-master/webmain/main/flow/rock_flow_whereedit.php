<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var id = params.id;
	if(!id)id = 0;
	var h = $.bootsform({
		window:false,rand:'{rand}',tablename:'flow_where',url:publicsave('{mode}','{dir}'),
		submitfields:'setid,name,wheresstr,whereustr,wheredstr,pnum,explain,recename,status,islb,receid,nrecename,nreceid,num,sort',requiredfields:'name',
		success:function(){
			if(id>0){
				closenowtabs();
			}else{
				js.msg('success','新增成功，继续保存可持续新增');
			}
			try{guanflowwherelist.reload();}catch(e){}
		},
		loadafter:function(a){
			if(a.data){
				h.form.wheresstr.value=jm.base64decode(a.data.wheresstr);
				h.form.whereustr.value=jm.base64decode(a.data.whereustr);
				h.form.wheredstr.value=jm.base64decode(a.data.wheredstr);
			}
		},
		submitcheck:function(d){
			if(d.islb==1&&d.num=='')return '请设置一个编号';
			return {
				wheresstr:jm.base64encode(d.wheresstr),
				whereustr:jm.base64encode(d.whereustr),
				wheredstr:jm.base64encode(d.wheredstr)
			};
		}
	});
	h.forminit();
	h.load(js.getajaxurl('loaddatawhere','{mode}','{dir}',{id:id,setid:params.setid}));
	var c = {
		setwhere:function(){
			js.setwhere(params.setid,'backsheowe{rand}');
		},
		clears:function(o1,lx){
			if(lx==1){
				h.setValue('recename','');
				h.setValue('receid','');
			}
			if(lx==2){
				h.setValue('nrecename','');
				h.setValue('nreceid','');
			}
		},
		getdist:function(o1, lx){
			var cans = {
				nameobj:h.form.recename,
				idobj:h.form.receid,
				type:'deptusercheck',
				title:'选择包含人员'
			};
			if(lx==2){
				var cans = {
					nameobj:h.form.nrecename,
					idobj:h.form.nreceid,
					type:'deptusercheck',
					title:'选择除外人员'
				};
			}
			cans.value=cans.idobj.value,
			js.getuser(cans);
		},
		setdab:function(ov,lx){
			var a = ['{read}','{unread}','{weekfirst}'];
			this.addwhewe(a[lx]);
		},
		addwhewe:function(ss){
			if(!ss)return;
			var o1 = h.form.wheresstr;
			if(ss=='uid'){o1.value='`uid`={uid}';return;}
			if(ss=='optid'){o1.value='`optid`={uid}';return;}
			if(o1.value!='')ss='and '+ss+'';
			o1.value+=' '+ss;
		},
		changessvs:function(ss){
			if(!ss)return;
			var fid = get('weherecss{rand}').value;
			if(fid==''){js.msg('msg','没有输入字段');return;}
			ss = ss.replace('uid', fid);
			c.addwhewe(ss);
		}
	};
	js.initbtn(c);
	if(id==0)h.form.setid.value=params.setid;
	backsheowe{rand}=function(s1,s2){
		h.setValue('wheresstr',s1);
		h.setValue('explain',s2);
	}
	$('#weherecs{rand}').change(function(){
		c.changessvs(this.value);
	});
});

</script>

<div align="center">
<div  style="padding:10px;width:700px">
	
	
	<form name="form_{rand}">
	
		<input name="id" value="0" type="hidden" />
		<input name="setid" value="0" type="hidden" />
		
		<table cellspacing="0" border="0" width="100%" align="center" cellpadding="0">
		<tr>
			<td  align="right"  width="25%"><font color=red>*</font> 名称：</td>
			<td class="tdinput"  width="25%"><input name="name" class="form-control"></td>
			<td  align="right"   width="15%">编号：</td>
			<td class="tdinput" width="30%"><input name="num"  maxlength="30" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" ></td>
			<td  align="right" >分组编号：</td>
			<td class="tdinput"><input name="pnum"  maxlength="30" class="form-control"></td>
		</tr>
		

		<tr>
			<td  align="right" >主表字段条件：</td>
			<td class="tdinput" colspan="3"><textarea  name="wheresstr" style="height:60px" class="form-control"></textarea><div class="tishi" style="padding-top:0px">对应主表上字段条件，字段必须用``包含，如：`uid`={uid},<a click="setwhere" href="javascript:;">[设置条件]</a><br>字段:<input style="width:60px"  id="weherecss{rand}" class="input" value="uid">中<select class="input" id="weherecs{rand}"><option value="">-选择条件-</option><option value="uid">我申请的uid</option><option value="optid">我操作的optid</option><option value="{uid,uidin}">包含当前用户</option><option value="{uid,down}">直属下级</option><option value="{uid,downall}">全部直属下级</option><option value="{uid,dept}">同级部门人员</option><option value="{uid,deptall}">同级部门人员(含子部门)</option><option value="{receid}">receid字段包含当前用户</option><option value="{read}">已读记录</option><option value="{unread}">未读记录</option><option value="{uid,receall}">字段中用户部门包含当前用户(含为空时)</option><option value="{uid,recenot}">字段中用户部门包含当前用户</option><option value="{uid,company}">所属单位</option></select><br>用{}的变量会在文件webmain/model/whereModel.php中的getstrwhere方法替换可自己查看。<a href="<?=URLY?>view_flowwhere.html" target="_blank">[看帮助介绍]</a></div></td>
		</tr>
		
		<tr>
			<td  align="right" >数据上人员包含条件：</td>
			<td class="tdinput" colspan="3">
			<div style="width:100%" class="input-group">
				<input readonly class="form-control"  name="recename" >
				<input type="hidden" name="receid" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clears,1" type="button"><i class="icon-remove"></i></button>
					<button class="btn btn-default" click="getdist,1" type="button"><i class="icon-search"></i></button>
				</span>
			</div>
			<textarea  name="whereustr" style="height:40px" class="form-control"></textarea><div class="tishi" style="padding-top:0px">不选默认全部人员</div>
			</td>
		</tr>
		
		<tr>
			<td  align="right" >除了这些人员外：</td>
			<td class="tdinput" colspan="3">
			<div style="width:100%" class="input-group">
				<input readonly class="form-control"  name="nrecename" >
				<input type="hidden" name="nreceid" >
				<span class="input-group-btn">
					<button class="btn btn-default" click="clears,2" type="button"><i class="icon-remove"></i></button>
					<button class="btn btn-default" click="getdist,2" type="button"><i class="icon-search"></i></button>
				</span>
			</div>
			<textarea  name="wheredstr" style="height:40px" class="form-control"></textarea></div>
			</td>
		</tr>
		
		
		
		<tr>
			<td  align="right" >说明：</td>
			<td class="tdinput" colspan="3"><textarea  name="explain" style="height:60px" class="form-control"></textarea></td>
		</tr>
		
		
		<tr>
			<td align="right">排序号：</td>
			<td class="tdinput"><input name="sort" value="0" maxlength="3" type="number"  onfocus="js.focusval=this.value" onblur="js.number(this)" class="form-control"></td>
	
			
		</tr>
		
		<tr>
			<td  align="right" ></td>
			<td class="tdinput" colspan="3">
				<label><input name="status" value="1" checked type="checkbox"> 启用?</label>&nbsp; &nbsp; 
				<label><input name="islb" value="0" type="checkbox"> 列表页显示</label>&nbsp; &nbsp; 
			</td>
		</tr>

		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button disabled class="btn btn-success" id="save_{rand}" type="button"><i class="icon-save"></i>&nbsp;保存</button>&nbsp; <span id="msgview_{rand}"></span>
		</td>
		</tr>
		
		</table>
		</form>
		<div align="left" class="tishi">也可根据编号从程序代码上自定义返回条件，文件：webmain\model\flow下对应模块编号文件</div>
</div>
</div>
