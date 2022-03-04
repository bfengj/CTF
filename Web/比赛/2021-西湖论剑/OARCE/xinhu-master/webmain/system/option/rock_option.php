<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var num = params.num,pid,optlx=0,dpid=0,nowid=0;
	
	var at = $('#optionview_{rand}').bootstable({
		tablename:'option',celleditor:true,sort:'sort',dir:'asc',url:js.getajaxurl('getlist', 'option', 'system'),
		params:{num:num,name:nowtabs.name},
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'ID',dataIndex:'id'
		}],
		itemclick:function(ad,oi,e){
			//c.showmenu(ad,e);
		},
		itemdblclick:function(ad,oi,e){
			c.zhankai(ad);
		},
		load:function(ad){
			pid = ad.pid;
			if(dpid==0)dpid=pid;
		}
	});
	
	var a = $('#view_{rand}').bootstable({
		tablename:'option',celleditor:true,sort:'sort',dir:'asc',modedir:'{mode}:{dir}',storeafteraction:'downshuafter',
		autoLoad:false,where:'and pid=-1',bodyStyle:'height:'+(viewheight-72)+'px;overflow:auto',
		columns:[{
			text:'名称',dataIndex:'name',sortable:true,editor:true
		},{
			text:'对应值',dataIndex:'value',editor:true,sortable:true
		},{
			text:'编号',dataIndex:'num',editor:true
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'下级数',dataIndex:'dcount'
		},{
			text:'启用',dataIndex:'valid',type:'checkbox',editor:true
		},{
			text:'说明',dataIndex:'explain',type:'textarea',editor:true,align:'left'
		},{
			text:'ID',dataIndex:'id'
		},{
			text:'',dataIndex:'optd',renderer:function(v,d,oi){
				var s = '&nbsp;';
				s='<a href="javascript:;" onclick="zhankai{rand}('+oi+')">展开</a>';
				return s;
			}
		}],
		load:function(){
			get('add_{rand}').disabled=false;
			get('del_{rand}').disabled=true;
		},
		itemclick:function(){
			get('del_{rand}').disabled=false;
		},
		beforeload:function(){
			get('del_{rand}').disabled=true;
		}
	});
	zhankai{rand}=function(oi){
		var d=a.getData(oi);
		c.zhankai(d);
	}
	var c = {
		zhankai:function(ad){
			$('#downshow_{rand}').html('<b>['+ad.id+'.'+ad.name+']</b>的下级选项');
			nowid = ad.id;
			a.search("and `pid`="+ad.id+"");
		},
		search:function(){
			var s = get('key_{rand}').value;
			at.setparams({key:s},true);
		},
		showmenu:function(d,e){
			if(!this.menuobj)this.menuobj = $.rockmenu({data:[],width:150,itemsclick:function(d){
				c.cliemmenus(d);
			}});
			this.optdata = d;
			var da = [{name:'打开选项',lx:0},{name:'编辑',lx:3}];
			if(d.stotal>0)da.push({name:'管理下级',lx:1});
			if(d.pid!=dpid)da.push({name:'↑回到上级',lx:2});
			this.menuobj.setData(da);
			setTimeout(function(){c.menuobj.showAt(e.clientX,e.clientY);},0);
		},
		
		cliemmenus:function(d){
			var ad = this.optdata;
			if(d.lx==0){
				$('#downshow_{rand}').html('<b>['+ad.id+'.'+ad.name+']</b>的下级选项');
				a.search("and `pid`="+ad.id+"");
			}
			if(d.lx==1){
				c.xiajili();
			}
			if(d.lx==2){
				this.showdwon(num);
			}
			if(d.lx==3){
				this.clicktypeeidt();
			}
		},
		reload:function(){
			at.reload();
		},
		xiajili:function(){
			if(at.changeid==0)return;
			var bh = at.changedata.num;
			this.showdwon(bh);
		},
		showdwon:function(bh){
			if(isempt(bh))return;
			at.setparams({num:bh},true);
		},
		dingji:function(bh){
			this.showdwon(num);
		},
		clickwin:function(o, lx){
			var a = this.clicktypewin(false, 0);
			optlx = 1;
			a.setValue('pid', nowid);
		},
		clicktypeeidt:function(){
			var d = at.changedata;
			if(d.id)c.clicktypewin(false, 1, d);
		},
		clicktypewin:function(o1, lx, da){
			var h = $.bootsform({
				title:'选项',height:250,width:300,
				tablename:'option',labelWidth:50,
				isedit:lx,
				submitfields:'num,name,sort,pid',cancelbtn:false,
				items:[{
					labelText:'名称',name:'name',required:true
				},{
					labelText:'上级id',name:'pid',value:0,type:'hidden'
				},{
					labelText:'编号',name:'num'
				},{
					labelText:'排序号',name:'sort',type:'number',value:0
				}],
				success:function(){
					if(optlx==0)at.reload();
					if(optlx==1)a.reload();
				}
			});
			if(lx==1)h.setValues(da);
			if(lx==0)h.setValue('pid', pid);
			optlx = 0;
			return h;
		},
		typedel:function(o1){
			at.del({url:js.getajaxurl('deloption','option','system')});
		},
		del:function(){
			a.del({url:js.getajaxurl('deloption','option','system')});
		}
	};
	js.initbtn(c);
	$('#optionview_{rand}').css('height',''+(viewheight-142)+'px');
});
</script>


<table width="100%">
<tr valign="top">
<td width="350">
	<div class="panel panel-info" style="margin:0px">
	  <div class="panel-heading">
		<h3 class="panel-title">选项列表(双击展开)</h3>
	  </div>
	  <div>
		<div class="input-group" style="width:200px">
			<input class="form-control" id="key_{rand}" placeholder="选项名称/编号">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
	  </div>
	  <div id="optionview_{rand}" style="height:400px;overflow:auto"></div>
	  <div  class="panel-footer">
		<a href="javascript:" click="clicktypewin,0" onclick="return false"><i class="icon-plus"></i></a>&nbsp; &nbsp; 
		<a href="javascript:" click="clicktypeeidt" onclick="return false"><i class="icon-edit"></i></a>&nbsp; &nbsp; 
		<a href="javascript:" click="reload" onclick="return false"><i class="icon-refresh"></i></a>
		&nbsp; 
		<a href="javascript:" click="xiajili" onclick="return false">[管理下级]</a>&nbsp; 
		<a href="javascript:" click="dingji" onclick="return false">[回到顶级]</a>
		<a href="javascript:" style="float:right" click="typedel" onclick="return false"><i class="icon-trash"></i></a>
	  </div>
	</div>
</td>
<td width="10"></td>
<td>	
	<div>
	<ul class="floats">
		<li class="floats70">
			<button class="btn btn-primary" click="clickwin,0" disabled id="add_{rand}" type="button"><i class="icon-plus"></i> 新增对应下级</button>&nbsp;&nbsp;
			<span id="downshow_{rand}">双击左边选项列表行打开选项</span>
		</li>
		<li class="floats30" style="text-align:right">
			<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		</li>
	</ul>
	</div>
	<div class="blank10"></div>
	<div id="view_{rand}"></div>
</td>
</tr>
</table>
