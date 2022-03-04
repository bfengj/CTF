<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var gid = 0;
	var changdata={},idboog=false;
	var a = $('#veiw_{rand}').bootstable({
		tablename:'im_group',celleditor:false,url:publicstore('{mode}','{dir}'),storeafteraction:'groupafter',keywhere:'and type<>2',modenum:'huihua',
		columns:[{
			text:'头像',dataIndex:'face',renderer:function(v, d){
				return '<img src="images/group.png" height="24" width="24">';
			}
		},{
			text:'名称',dataIndex:'name',editor:true
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'人员数',dataIndex:'utotal'
		},{
			text:'组织结构id',dataIndex:'deptid'
		},{
			text:'创建人',dataIndex:'createname'	
		},{
			text:'ID',dataIndex:'id'	
		},{
			text:'',dataIndex:'optdt',renderer:function(s,d){
				return '<button onclick="openchat('+d.id+',1)" class="btn btn-primary btn-xs"><i class="icon-comment-alt"></i> 发消息</button>';
			}
		}],
		itemclick:function(){
			btn(false);
		},
		itemdblclick:function(ad,oi,e){
			$('#downshow_{rand}').html('<b>['+ad.name+']</b>下的人员');
			gid=ad.id;
			changdata = ad;
			at.setparams({gid:gid},true);
		}
	});
	var alluserid = '';
	var at = $('#veiwuser_{rand}').bootstable({
		tablename:'admin',sort:'sort',dir:'asc',fanye:true,
		url:publicstore('{mode}','{dir}'),
		autoLoad:false,storebeforeaction:'groupusershow',
		columns:[{
			text:'用户名',dataIndex:'user',sortable:true
		},{
			text:'部门',dataIndex:'deptname',sortable:true
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'职位',dataIndex:'ranking',sortable:true
		},{
			text:'操作',dataIndex:'opt',renderer:function(v,d){
				var s = '&nbsp;';
				if(isempt(changdata.deptid)||changdata.deptid=='0')s = '<a href="javascript:;" onclick="return deluserr{rand}('+d.id+')"><i class="icon-trash"> 删</a>';
				return s;
			}
		}],
		load:function(da){
			var bo = false;
			if(!isempt(changdata.deptid))bo=true;
			if(changdata.deptid=='0')bo=false;
			get('add_{rand}').disabled=bo;
			
			alluserid = '';
			for(var i=0;i<da.rows.length;i++){
				alluserid+=','+da.rows[i].id+'';
			}
			if(alluserid!='')alluserid = alluserid.substr(1);
		}
	});
	
	var c = {
		del:function(){
			a.del({check:function(lx){if(lx=='yes')btn(true)}});
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'会话',height:400,width:400,
				tablename:'im_group',isedit:lx,
				url:js.getajaxurl('publicsave','imgroup','main'),
				params:{int_filestype:'sort,type'},aftersaveaction:'savegroupafter',
				submitfields:'name,sort,type,explain,deptid,createid,createname',
				items:[{
					labelText:'会话名称',type:'changeuser',changeuser:{
						type:'deptcheck',idname:'deptid',title:'选择所属部门'
					},name:'name',clearbool:true,required:true
					
				},{
					name:'deptid',type:'hidden',value:'0'
				},{
					name:'createid',type:'hidden',value:'0'
				},{
					labelText:'创建人',type:'changeuser',changeuser:{
						type:'user',idname:'createid',title:'选择创建人'
					},name:'createname'
					
				},{
					labelText:'序号',name:'sort',type:'number',value:'0'
				},{
					labelText:'说明',name:'explain',type:'textarea',height:'60'
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('name').focus();
			h.getField('name').readOnly=false;
		},
		refresh:function(){
			a.reload();
		},
		addguser:function(){
			var cans = {
				type:'usercheck',
				title:'选择人员',
				changerangeno:alluserid,
				callback:function(sna,sid){
					c.savedist(sid);
				}
			};
			js.getuser(cans);
			return false;
		},
		savedist:function(sid){
			if(sid != ''){
				js.msg('wait','保存中...');
				js.ajax(js.getajaxurl('saveuser','{mode}','{dir}'),{sid:sid,gid:gid},function(){
					js.msg('success','保存成功');
					at.reload();
				},'post');
			}
		},
		delusers:function(uid){
			js.msg('wait','删除中...');
			js.ajax(js.getajaxurl('deluser','{mode}','{dir}'),{sid:uid,gid:gid},function(){
				js.msg('success','删除成功');
				at.reload();
			},'post');
		},
		relaodss:function(){
			js.msg('wait','刷新中...');
			js.ajax(js.getajaxurl('reloadall','{mode}','{dir}'),false,function(){
				js.msg();
				a.reload();
			});
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
	
	deluserr{rand}=function(uid){
		js.confirm('确定要删除对应会话下的人员吗？',function(lx){
			if(lx=='yes'){
				c.delusers(uid);
			}
		});
	}
});
</script>

<table width="100%">
<tr valign="top">
<td width="50%">
	
	
	<div>
	<ul class="floats">
		<li class="floats50">
			<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>&nbsp;&nbsp;
			<button class="btn btn-default" click="relaodss" type="button">刷新</button>
		</li>
		<li class="floats50" style="text-align:right">
			<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
			<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
		</li>
	</ul>
	</div>
	<div class="blank10"></div>
	<div id="veiw_{rand}"></div>
	<div class="tishi">双击查看对应人员，有组织结构id会自动添加删除会话里的人员。</div>
</td>
<td width="10"></td>
<td>
	
	<div>
	<ul class="floats">
		<li class="floats50">
			<span id="downshow_{rand}">&nbsp;</span>
		</li>
		<li class="floats50" style="text-align:right">
			<button class="btn btn-primary" click="addguser,0" id="add_{rand}" disabled type="button"><i class="icon-plus"></i> 添加对应人员</button>
		</li>
	</ul>
	</div>
	<div class="blank10"></div>
	<div id="veiwuser_{rand}"></div>	
	
</td>
</tr>
</table>
