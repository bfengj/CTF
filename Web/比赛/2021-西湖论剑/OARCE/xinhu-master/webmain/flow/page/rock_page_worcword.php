<?php
/**
*	模块：文档中心
*/
defined('HOST') or die ('not access');
?>
<script >
$(document).ready(function(){
	
	
	var cqid = 0,typeid = 0,lboss=false,fqarr={},isnotquan=true,isup=false;
	var at = $('#optionview_{rand}').bootstree({
		url:publicmodeurl('worc','getworc'),
		columns:[{
			text:'文档分区',dataIndex:'name',align:'left',xtype:'treecolumn',width:'74%'
		},{
			text:'文件数',dataIndex:'wcount',width:'25%'
		}],
		load:function(d){
			if(!lboss && d.ids)a.setparams({'cqids':d.ids}, true);
			lboss = true;
			for(var i=0;i<d.rows.length;i++)fqarr[d.rows[i].id]=d.rows[i];//记录是否管理权限
		},
		itemdblclick:function(d){
			c.openfenqu(d.id,d.name);
		},
		itemclick:function(d){
			
		}
	});
	
	var a = $('#view_{rand}').bootstable({
		tablename:'word',celleditor:true,autoLoad:false,checked:true,url:publicmodeurl('worc','getfiledata'),fanye:true,
		columns:[{
			text:'',dataIndex:'fileext',renderer:function(v,d){
				if(!isempt(d.thumbpath))return '<img src="'+d.thumbpath+'" width="24" height="24">';
				if(v!='folder'){
					var lxs = js.filelxext(v);
					return '<img src="web/images/fileicons/'+lxs+'.gif">';
				}else{
					return '<img src="images/folder.png" height="24">';
				}
			}
		},{
			text:'名称',dataIndex:'name',editor:true,align:'left',editorbefore:function(d){
				if(isnotquan){
					return false;
				}else{
					return true;
				}
			}
		},{
			text:'大小',dataIndex:'filesizecn',sortable:true
		},{
			text:'创建者',dataIndex:'optname'
		},{
			text:'创建时间',dataIndex:'optdt'
		},{
			text:'共享给',dataIndex:'shate'
		},{
			text:'下载次数',dataIndex:'downci',sortable:true
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				if(d.ishui=='1')return '已删';
				var lxs = ',doc,docx,xls,xlsx,ppt,pptx,';
				if(d.fileext=='folder'){
					return '<a href="javascript:;" onclick="openfolder{rand}('+d.id+','+d.cid+')">打开</a>';
				}else{
					var str = (lxs.indexOf(','+d.fileext+',')>-1 && !isnotquan) ? '&nbsp;<a href="javascript:;" onclick="showvies{rand}('+oi+',3)">编辑</a>' : '';
					return '<a href="javascript:;" onclick="showvies{rand}('+oi+',0)">预览</a>'+str+'&nbsp;<a href="javascript:;" onclick="showvies{rand}('+oi+',1)"><i class="icon-arrow-down"></i></a>';
				}
			}
		}],
		itemclick:function(){
			var bo = true;
			if(!isnotquan)bo=false;
			get('del_{rand}').disabled=bo;
		},
		loadbefore:function(){
			get('del_{rand}').disabled=true;
		},
		load:function(d){
			c.showlaber(d);
		},
		itemdblclick:function(d){
			openxiangs(d.name,'word', d.id);
		}
	});
	
	
	_editfacech{rand}angback=function(a,typeid,pars2,sid){
		c.savefile(typeid, sid);
	};
	openfolder{rand}=function(id,cid){
		c.openfolder(id,cid);
	}
	showvies{rand}=function(oi,lx){
		var d=a.getData(oi);
		if(lx==3){
			//js.sendeditoffice(d.fileid);
			js.fileopt(d.fileid,2);
			return;
		}
		if(lx==2){
			c.openfolder(d.id,d.cid);
			return;
		}
		if(lx==1){
			js.downshow(d.fileid,d.filenum);
		}else{
			js.yulanfile(d.fileid,d.fileext,d.filepath,d.filename,d.filenum);//预览
		}
	}
	var c = {
		reload:function(){
			at.reload();
		},
		uploadfile:function(){
			if(!isup)return;
			var na = at.changedata.name,upte=at.changedata.uptype;
			if(!na)na='文件';
			if(!upte)upte='';
			js.upload('_editfacech{rand}angback',{'title':'上传到'+na+'','uptype':upte,'params1':cqid});	
		},
		openfenqu:function(id,nsd){
			this.openfolder(0,id);
		},
		createfolder:function(){
			if(isnotquan)return;
			js.prompt('创建文件夹','输入文件夹名称',function(jg,txt){
				if(jg=='yes' && txt){
					js.ajax(publicmodeurl('worc','createfolder'),{'cqid':cqid,'typeid':typeid,'name':txt},function(s){
						a.reload();
					},'post',false, '创建中...,创建成功');
				}
			});
		},
		huitop:function(){
			this.openfolder(0,0);
		},
		openfolder:function(id,cid){
			typeid = id;
			cqid = cid;//分区ID 
			var bo = false,upbo=false;//有权限
			if(cqid==0){
				bo=true;
			}else{
				var fqa = fqarr[cqid];
				if(fqa.ismanage!=1)bo=true;
				if(fqa.isup==1)upbo=true;
			}
			isup = upbo;
			isnotquan = bo;//记录是否有权限
			get('upbtn_{rand}').disabled= (!isup);
			get('cretefile_{rand}').disabled=bo;
			get('gong_{rand}').disabled=bo;
			get('gongqx_{rand}').disabled=bo;
			get('move_{rand}').disabled=bo;
			get('del_{rand}').disabled=true;
			get('key_{rand}').value='';
			a.setparams({'typeid':typeid,'cqid':cqid,'key':''}, true);
		},
		showlaber:function(d){
			var s = '所有分区根目录';
			if(d.cprow){
				s='<a href="javascript:;" onclick="openfolder{rand}(0,'+d.cprow.id+')">'+d.cprow.name+'</a>';
				for(var i=0;i<d.patha.length;i++){
					var ds = d.patha[i];
					s+=' / <a href="javascript:;" onclick="openfolder{rand}('+ds.id+','+ds.cid+',1)">'+ds.name+'</a>';
				}
			}
			var zts = '',ysz='';
			if(isnotquan){
				if(isup){
					zts='仅上传';
					ysz= 'warning';
				}else{
					zts='只读';
					ysz= 'default';
				}
			}else{
				if(isup){
					zts='可管理可上传';
					ysz= 'success';
				}else{
					zts='仅管理';
					ysz= 'info';
				}
			}
			
			if(zts)s='<span class="label label-'+ysz+'">'+zts+'</span> '+s;
			$('#megss{rand}').html(s);
		},
		savefile:function(tps, sid){
			if(sid=='')return;
			js.ajax(publicmodeurl('worc','savefile'),{'cqid':cqid,'typeid':typeid,'sid':sid},function(s){
				a.reload();
			},'get',false, '保存中...,保存成功');
		},
		
		openglfe:function(){
			addtabs({name:'文档分区管理',num:'searchworc',url:'flow,page,worc,atype=my',icons:'folder-close'});
		},
		
		del:function(){
			a.del({url:publicmodeurl('worc','delfile')});
		},
		
		sharefile:function(){
			var fid = a.getchecked();
			if(fid==''){js.msg('msg','没有选中');return;}
			var cans = {
				type:'deptusercheck',
				title:'共享给...',
				callback:function(sna,sid){
					if(sid)c.sharefiles(sna,sid, fid);
				}
			}
			js.getuser(cans);
		},
		sharefiles:function(sna,sid, fid){
			if(sid==''||fid=='')return;
			js.ajax(publicmodeurl('worc','sharefile'),{'sid':sid,'sna':sna,'ids':fid},function(s){
				a.reload();
			},'post',false, '共享中...,共享成功');
		},
		qxsharefile:function(){
			var fid = a.getchecked();
			if(fid==''){js.msg('msg','没有选中');return;}
			js.ajax(publicmodeurl('worc','sharefile'),{'ids':fid},function(s){
				a.reload();
			},'post',false, '取消共享中...,取消成功');
		},
		movefile:function(){
			var ids = a.getchecked();
			if(ids==''){js.msg('msg','没有选中');return;}
			
			$.selectdata({
				title:'选择移动的分区/目录',
				url:publicmodeurl('worc','moveget'),
				checked:false,
				onselect:function(sda,sna, sid){
					js.ajax(publicmodeurl('worc','movefile'),{'cqid':sda.cqid,'typeid':sda.typeid,'ids':ids},function(ret){
						if(ret.success){
							a.reload();
						}else{
							js.msg('msg', ret.msg);
						}
					},'get,json',false, '移动中...,移动成功');
				}
			});
		},
		search:function(){
			a.setparams({'key':get('key_{rand}').value}, true);
		}
	};
	js.initbtn(c);
	$('#optionview_{rand}').css('height',''+(viewheight-70)+'px');
});
</script>


<table width="100%">
<tr valign="top">
<td width="220">
	<div style="border:1px #cccccc solid;width:220px">
	  <div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
	  <div  class="panel-footer">
		<a href="javascript:" title="分区管理" click="openglfe" onclick="return false"><i class="icon-cog"></i></a>&nbsp; &nbsp;
		<a href="javascript:" title="回到顶级" click="huitop" onclick="return false"><i class="icon-arrow-up"></i></a>&nbsp; &nbsp;
		<a href="javascript:" title="刷新" click="reload" onclick="return false"><i class="icon-refresh"></i></a>
	  </div>
	</div>  
</td>
<td width="10"></td>
<td>	
	<div>
	<table width="100%"><tr>
		<td nowrap align="left">
			<button class="btn btn-primary" id="upbtn_{rand}" click="uploadfile" disabled type="button"><i class="icon-upload-alt"></i> 上传文件</button>&nbsp; 
			<span id="megss{rand}"></span>
		</td>
		
		<td style="padding-left:10px">
		<input class="form-control" style="width:120px" id="key_{rand}"   placeholder="文件名/创建者">
		</td>
		<td style="padding-left:10px">
			<button class="btn btn-default" click="search" type="button">搜索</button> 
		</td>
		<td width="80%">&nbsp;</td>
		<td nowrap align="right">
			<button class="btn btn-default" id="gong_{rand}" disabled click="sharefile" type="button"><i class="icon-share-alt"></i> 共享</button>&nbsp; 
			<button class="btn btn-default" id="gongqx_{rand}" disabled click="qxsharefile" type="button">取消共享</button>&nbsp; 
			<button class="btn btn-default" id="move_{rand}" disabled click="movefile" type="button">移动</button>&nbsp; 
			<button class="btn btn-default" id="cretefile_{rand}" click="createfolder" disabled type="button"><i class="icon-folder-close-alt"> </i>创建文件夹</button>&nbsp; 
			<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		</td>
	</tr></table>
	</div>
	<div class="blank10"></div>
	<div id="view_{rand}"></div>
	<div class="tishi">←双击左边的分区可对分区下文档进行管理。</div>
	
</td>
</tr>
</table>
