<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'wxtx_renlian',celleditor:true,fanye:true,statuschange:true,
		url:publicstore('{mode}','{dir}'),storebeforeaction:'beforeuserdshow',storeafteraction:'aftereuserdshow',
		columns:[{
			text:'姓名',dataIndex:'personname',sortable:true
		},{
			text:'人员库ID',dataIndex:'personid'
		},{
			text:'人脸图片数',dataIndex:'imgshu'
		},{
			text:'启用',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		},{
			text:'关联OA用户ID',dataIndex:'uid',editor:true,type:'number'
		},{
			text:'关联OA用户姓名',dataIndex:'name'
		},{
			text:'关联OA用户部门',dataIndex:'deptallname'
		}],
		itemclick:function(){
			get('delbtn{rand}').disabled=false;
		},
		beforeload:function(){
			get('delbtn{rand}').disabled=true;
		}
	});
	
	
	
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		},
		getlist:function(){
			js.msg('wait','获取中...');
			js.ajax(js.getajaxurl('reloaduser','{mode}', '{dir}'),{}, function(d){
				if(d.success){
					js.msg('success', d.data);
					a.reload();
				}else{
					js.msg('msg', d.msg);
				}
			},'get,json');
		},
		delaluser:function(){
			a.del({url:js.getajaxurl('delrenlian','{mode}','{dir}')});
		},
		adduser:function(){
			var h = $.bootsform({
				title:'用户人脸',height:400,width:500,
				tablename:'wxtx_renlian',isedit:0,
				url:js.getajaxurl('createurenlian','{mode}','{dir}'),
				submitfields:'uid,personname',
				items:[{
					name:'uid',type:'hidden'
				},{
					labelText:'对应用户',type:'changeuser',changeuser:{
						type:'user',idname:'uid',title:'选择uid'
					},name:'personname',clearbool:true,required:true
				},{
					labelText:'人脸图片地址',name:'imgurl',blankText:'系统目录下图片，不能超过1M，60x60像素以上'
				}],
				success:function(){
					a.reload();
				}
			});
			
		}
	};

	js.initbtn(c);
});
</script>


<table width="100%">
<tr>
<td style="padding-right:10px"><button class="btn btn-primary" click="adduser" type="button"><i class="icon-plus"></i> 创建人员</button></td>
<td style="padding-right:10px"><button class="btn btn-default" click="getlist" type="button">获取人脸用户库上人员</button></td>
<td>
	<div class="input-group" style="width:250px;">
		<input class="form-control" id="key_{rand}"   placeholder="姓名/关联用户部门">
		<span class="input-group-btn">
			<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
		</span>
	</div>
</td>
<td width="90%" style="padding-left:10px">
	
</td>
<td align="right" nowrap>
	<button class="btn btn-danger" disabled id="delbtn{rand}" click="delaluser" type="button">删除</button>
</td>
</tr>
</table>
<div class="blank10"></div>
<div id="view_{rand}"></div>