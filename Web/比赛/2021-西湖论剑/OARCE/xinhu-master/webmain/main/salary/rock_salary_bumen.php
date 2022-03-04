<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var modenum = 'hrsalary';
	
	
	function editorbefore(d){
		if(d.isturn=='1'){
			js.msg('msg','已提交不能在修改，请在操作列下处理');
			return false;
		}else{
			return true;
		}
	}
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,params:{'atype':'dept'},fanye:true,modenum:modenum,modedir:'{mode}:{dir}',statuschange:false,celleditor:true,storeafteraction:'bumenafter',checked:true,
		columns:[{
			text:'部门',dataIndex:'deptallname',sortable:true
		},{
			text:'人员',dataIndex:'uname',sortable:true
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'月份',dataIndex:'month',sortable:true
		},{
			text:'基本工资',dataIndex:'base',sortable:true
		},{
			text:'实发工资',dataIndex:'money',sortable:true
		},{
			text:'核算人',dataIndex:'optname'
		},{
			text:'绩效打分',dataIndex:'jxdf',editor:true,editorbefore:editorbefore,type:'number'
		},{
			text:'计件工资',dataIndex:'jiansr',editor:true,editorbefore:editorbefore,type:'number'
		},{
			text:'其他增加',dataIndex:'otherzj',editor:true,editorbefore:editorbefore,type:'number'
		},{
			text:'其他减少',dataIndex:'otherjs',editor:true,editorbefore:editorbefore,type:'number'
		}/*,{
			text:'',dataIndex:'opted',renderer:function(v,d,i){
				var s = '&nbsp;';
				if(d.status=='0')s='<a href="javascript:;" onclick="hesuan{rand}('+i+')">核算</a>';
				return s;
			}
		}*/,{
			text:'是否核算',dataIndex:'isturnss'
		},{
			text:'发放',dataIndex:'ispay',sortable:true
		},{
			text:'状态',dataIndex:'statustext'
		},{
			text:'',dataIndex:'caozuo',callback:'callback2{rand}'
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		},
		itemdblclick:function(){
			c.view();
		},
		load:function(d){
			get('daochu{rand}').disabled= (!d.isdaochu);
		}
	});
	
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	hesuan{rand}=function(oi){
		c.hesuan(oi);
	}
	
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('薪资',modenum,d.id);
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value,
				dt:get('dt2_{rand}').value
			},true);
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'薪资',
				'modenum':modenum,
				'btnobj':o1
			});
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('薪资', modenum,id,'callback{rand}');
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			a.setparams({isturn:lx});
			this.search();
		},
		hesuan:function(oi){
			var d = a.getData(oi);
			var h = $.bootsform({
				title:'人员['+d.uname+','+d.month+']的核算录入',height:400,width:400,
				tablename:modenum,isedit:1,
				submitfields:'jxdf,jiansr,otherzj,otherjs',
				items:[{
					labelText:'绩效打分',name:'jxdf',type:'number'
				},{
					labelText:'计件工资',name:'jiansr',type:'number'
				},{
					labelText:'其他增加',name:'otherzj',type:'number'
				},{
					labelText:'其他减少',name:'otherjs',type:'number'
				}],
				success:function(){
					a.reload();
				},
				submitcheck:function(da, o1){
					o1.close();
					c.hesuansj(da);
					return '&nbsp;';
				}
			});
			h.setValues(d);
			return h;
		},
		hesuansj:function(oi){
			var id  	 = this.arrzong[oi];
			this.chulici = oi;
			var url = 'index.php?a=lu&m=input&d=flow&num=hrsalary&mid='+id+'&callback=callback{rand}&actlx=hesuan';
			js.loading('核算中('+this.arrzong.length+'/'+(oi+1)+')...');
			hesuaniframe{rand}.location.href=url;
		},
		plturn:function(){
			var s = a.getchecked();
			if(s==''){js.msg('msg','没有选中记录');return;}
			this.arrzong = s.split(',');
			this.hesuansj(0);
		},
		wancheng:function(){
			var len = this.arrzong.length;
			js.msg();
			if(this.chulici+1>=len){
				js.msgok('核算完成');
				a.reload();
				return;
			}
			this.hesuansj(this.chulici+1);
		}
	};
	js.initbtn(c);
	callback{rand}=function(){
		c.wancheng();
	}
	callback2{rand}=function(){
		a.reload();
	}
});

</script>
<div>
	<table width="100%">
	<tr>
	<td style="padding-right:10px">
		<button class="btn btn-primary" click="plturn" type="button">批量选中提交</button>
	</td>
	<td>
		<input class="form-control" style="width:180px" id="key_{rand}"  placeholder="部门/姓名/职位">
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>	
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td width="80%" style="padding-left:10px">
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_" click="changlx," type="button">全部</button>
		<button class="btn btn-default" id="state{rand}_0" style="color:red" click="changlx,0" type="button">待核算</button>
		<button class="btn btn-default" id="state{rand}_1" style="color:green" click="changlx,1" type="button">已核算</button>
		</div>		
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" disabled id="daochu{rand}" type="button">导出 <i class="icon-angle-down"></i></button>  
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<iframe style="display:none" name="hesuaniframe{rand}" width="100%" height="400px">