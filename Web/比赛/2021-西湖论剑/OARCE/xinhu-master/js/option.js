/**
*	系统选项分类操作
*/
function optionclass(cans){
	
	var me 		= this;
	this.sspid 	= 0;
	this.typeid = 0;
	
	this.reloadtype = function(){
		this.at.reload();
	}
	this.clicktypeeidt = function(){
		var d = this.at.changedata;
		if(d.id)this.clicktypewin(false, 1, d);
	}

	this.clicktypewin = function(o1, lx, da){
		var h = $.bootsform({
			title:this.title,height:250,width:300,
			tablename:'option',labelWidth:50,
			isedit:lx,submitfields:'name,sort,pid',cancelbtn:false,
			items:[{
				labelText:'名称',name:'name',required:true
			},{
				labelText:'上级id',name:'pid',value:0,type:'hidden'
			},{
				labelText:'排序号',name:'sort',type:'number',value:0
			}],
			success:function(){
				me.reloadtype();
			}
		});
		if(lx==1)h.setValues(da);
		if(lx==0)h.setValue('pid',this.typeid);
		h.form.name.focus();
		return h;
	}
	this.typedel = function(o1){
		this.at.del({
			url:js.getajaxurl('deloption','option','system'),params:{'stable':this.stable}
		});
	}
	
	this.optionmove = function(){
		var d = this.at.changedata;
		if(!d || !d.id){js.msg('msg','没有选中行');return;}
		this.movedata = d;
		js.msg('success','请在5秒内选择其他分类确认移动');
		clearTimeout(this.cmoeefese);
		this.cmoeefese=setTimeout(function(){me.movedata=false;},5000);
	}
	this.ismoveok=function(d){
		var md = this.movedata;
		if(md && md.id && md.id!=d.id){
			js.confirm('确定要将['+md.name+']移动到['+d.name+']下吗？',function(jg){
				if(jg=='yes'){
					me.movetoss(md.id,d.id,0);
				}
			});
		}
	}
	
	this.optionmoveto=function(){
		var d = this.at.changedata;
		if(!d || !d.id || this.sspid==0)return;
		js.confirm('确定要将['+d.name+']移动到顶级吗？',function(jg){
			if(jg=='yes'){
				me.movetoss(d.id,me.sspid,1);
			}
		});
	}
	
	this.movetoss=function(id,toid,lx){
		js.ajax(js.getajaxurl('movetype','option','system'),{'id':id,'toid':toid,'lx':lx},function(s){
			if(s!='ok'){
				js.msg('msg', s);
			}else{
				me.reloadtype();
			}
		},'get',false, '移动中...,移动成功');
		this.movedata=false;
	}
	

	this.createtable=function(){
		this.at = $('#'+this.optionview+'').bootstree({
			url:js.getajaxurl('gettreedata','option','system',{'num':this.optionnum}),
			columns:[{
				text:this.title,dataIndex:'name',align:'left',xtype:'treecolumn',width:'79%'
			},{
				text:'序号',dataIndex:'sort',width:'20%'
			}],
			load:function(d){
				if(me.sspid==0){
					me.sspid  = d.pid;
					me.allshow();
				}
			},
			itemdblclick:function(d){
				me.typeid = d.id;
				me.loadfile(d.id,d.name);
			},
			itemclick:function(d){
				me.ismoveok(d);
			}
		});
	}
	
	this.allshow=function(){
		this.typeid = this.sspid;
		this.at.changedata={};
		this.loadfile('0','所有'+this.title+'');
	}
	
	this.loadfile=function(spd,nsd){
		$('#megss'+this.rand+'').html(nsd);
		this.mobj.setparams({'typeid':spd}, true);
	}
	this.showfooter=function(){
		var s= '<div class="panel-footer"><a href="javascript:;" style="TEXT-DECORATION:none" title="新增" click="clicktypewin,0" onclick="return false"><i class="icon-plus"></i></a>&nbsp; &nbsp;<a href="javascript:;" style="TEXT-DECORATION:none" title="编辑" click="clicktypeeidt" onclick="return false"><i class="icon-edit"></i></a>&nbsp; &nbsp;<a href="javascript:;" style="TEXT-DECORATION:none" title="删除" click="typedel" onclick="return false"><i class="icon-trash"></i></a>&nbsp; &nbsp;<a href="javascript:;" style="TEXT-DECORATION:none" title="刷新" click="reloadtype" onclick="return false"><i class="icon-refresh"></i></a>&nbsp; &nbsp; <a href="javascript:;" style="TEXT-DECORATION:none" title="移动" click="optionmove" onclick="return false"><i class="icon-move"></i></a>&nbsp; &nbsp;<a href="javascript:;" title="移动到顶级" style="TEXT-DECORATION:none" click="optionmoveto" onclick="return false"><i class="icon-arrow-up"></i></a>&nbsp; &nbsp; <a href="javascript:;" style="TEXT-DECORATION:none" title="所有'+this.title+'" click="allshow" onclick="return false"><i class="icon-search"></i></a></div>';
		$('#'+this.optionview+'').after(s);
		return true;
	}
	
	//初始化
	this.init_option=function(){
		this.createtable(); //创建表格
		this.showfooter(); //显示底部
		
		$('#'+this.optionview+'').css('height',''+(viewheight-70)+'px');
	}
	
	for(var oc in cans)this[oc]=cans[oc];
	
	this.init_option();
}