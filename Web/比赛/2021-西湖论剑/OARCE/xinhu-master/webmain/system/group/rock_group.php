<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var gid = 0,companyinfoall=[];
	var a = $('#veiw_{rand}').bootstable({
		tablename:'group',celleditor:true,url:publicstore('{mode}','{dir}'),storeafteraction:'groupafter',
		modenum:'group',sort:'sort',dir:'asc',
		columns:[{
			text:'组名',dataIndex:'name',editor:true
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'所属单位',dataIndex:'companyname'
		},{
			text:'人员数',dataIndex:'utotal'
		},{
			text:'ID',dataIndex:'id'	
		}],
		itemclick:function(d){
			var bo=false;
			if(companymode && adminid>1 && d.companyid=='0')bo=true;
			btn(bo);
		},
		itemdblclick:function(ad,oi,e){
			$('#downshow_{rand}').html('组<b>['+ad.name+']</b>下的人员');
			gid=ad.id;
			at.setparams({gid:gid},true);
		},
		load:function(d1){
			companyinfoall = d1.carr.companyinfoall;
		},
		beforeload:function(){
			btn(true);
		}
	});
	var alluserid = '';
	var at = $('#veiwuser_{rand}').bootstable({
		tablename:'admin',sort:'sort',dir:'asc',
		url:publicstore('{mode}','{dir}'),
		autoLoad:false,storebeforeaction:'groupusershow',
		columns:[{
			text:'用户名',dataIndex:'user',sortable:true
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'部门',dataIndex:'deptname',sortable:true
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'操作',dataIndex:'opt',renderer:function(v,d){
				return '<a href="javascript:" onclick="return deluserr{rand}('+d.id+')"><i class="icon-trash"> 删</a>';
			}
		}],
		load:function(da){
			get('add_{rand}').disabled=false;
			alluserid = '';
			for(var i=0;i<da.rows.length;i++){
				alluserid+=','+da.rows[i].id+'';
			}
			if(alluserid!='')alluserid = alluserid.substr(1);
		},
		beforeload:function(){
			alluserid = '';
		}
	});
	
	var c = {
		del:function(){
			a.del({check:function(lx){if(lx=='yes')btn(true)}});
		},
		clickwin:function(o1,lx){
			var items = [{
				labelText:'组名',name:'name',required:true
			},{
				labelText:'序号',name:'sort',type:'number',value:'0'
			}],les='';
			if(companymode){
				var store = [];
				if(adminid==1)store.push({'id':'0','name':'全部单位'});
				for(var i=0;i<companyinfoall.length;i++)store.push(companyinfoall[i]);
				items.push({
					labelText:'所属单位',name:'companyid',type:'select',value:'0',valuefields:'id',displayfields:'name',store:store
				});
				les=',companyid';
			}
			
			var h = $.bootsform({
				title:'组',height:400,width:400,
				tablename:'group',isedit:lx,
				url:js.getajaxurl('publicsave','group','system'),
				params:{int_filestype:'sort',add_otherfields:'indate={now}'},
				submitfields:'name,sort'+les+'',
				items:items,
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('name').focus();
		},
		refresh:function(){
			a.reload();
			if(gid>0)at.reload();
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
			if(sid!=''){
				js.msg('wait','保存中...');
				js.ajax(js.getajaxurl('saveuser','{mode}','{dir}'),{sid:sid,gid:gid},function(){
					js.msg('success','保存成功');
					at.reload();
					a.reload();
				},'post');
			}
		},
		delusers:function(uid){
			js.msg('wait','删除中...');
			js.ajax(js.getajaxurl('deluser','{mode}','{dir}'),{sid:uid,gid:gid},function(){
				js.msg('success','删除成功');
				at.reload();
				a.reload();
			},'post');
		}
	};
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
	
	deluserr{rand}=function(uid){
		js.confirm('确定要删除组下的人员吗？',function(lx){
			if(lx=='yes'){
				c.delusers(uid);
			}
		});
	}
});
</script>

<table width="100%">
<tr valign="top">
<td width="45%">
	
	
	<div>
	<ul class="floats">
		<li class="floats50">
			<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增组</button>&nbsp; 
			<button class="btn btn-default" click="refresh,0" type="button">刷新</button>
		</li>
		<li class="floats50" style="text-align:right">
			<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
			<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
		</li>
	</ul>
	</div>
	<div class="blank10"></div>
	<div id="veiw_{rand}"></div>
	<div class="tishi">在组的ID列下双击，查看组下的人员，在添加组下人员</div>
</td>
<td width="10"></td>
<td>
	
	<div>
	<ul class="floats">
		<li class="floats50">
			<span id="downshow_{rand}">&nbsp;</span>
		</li>
		<li class="floats50" style="text-align:right">
			<button class="btn btn-primary" click="addguser,0" id="add_{rand}" disabled type="button"><i class="icon-plus"></i> 添加组下人员</button>
		</li>
	</ul>
	</div>
	<div class="blank10"></div>
	<div id="veiwuser_{rand}"></div>	
	
</td>
</tr>
</table>
