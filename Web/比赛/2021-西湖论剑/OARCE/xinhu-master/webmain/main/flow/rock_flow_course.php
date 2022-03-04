<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var num = params.num,setid,optlx=0,maxpid = 0,courseobj={};
	var at = $('#optionview_{rand}').bootstable({
		tablename:'flow_set',defaultorder:'`sort`',where:'and isflow>0 and status=1',
		modedir:'{mode}:{dir}',storeafteraction:'setcourselistafter',storebeforeaction:'setcourselistbefore',
		columns:[{
			text:'名称',dataIndex:'name'
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'步骤数',dataIndex:'shu'
		},{
			text:'ID',dataIndex:'id'
		}],
		itemdblclick:function(ad,oi,e){
			$('#downshow_{rand}').html('设置<b>['+ad.id+'.'+ad.name+']</b>的流程管理');
			setid=ad.id;
			get('addp_{rand}').disabled=false;
			get('relad_{rand}').disabled=false;
			c.loaddata();
		}
	});
	

	
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_course',celleditor:true,
		autoLoad:false,where:'and setid=-1',tree:true,modedir:'{mode}:{dir}',storeafteraction:'flowcourselistafter',storebeforeaction:'flowcourselistbefore',
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'适用于',dataIndex:'recename'
		},{
			text:'分支条件',dataIndex:'whereid'
		},{
			text:'编号',dataIndex:'num',editor:true
		},{
			text:'审核人类型',dataIndex:'checktype'
		},{
			text:'审核人',dataIndex:'checktypename'
		},{
			text:'排序号',dataIndex:'sort',editor:true
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true
		},{
			text:'下级步骤',dataIndex:'nid',renderer:function(v){
				if(v=='0')v='&nbsp;';
				return v;
			}
		},{
			text:'ID',dataIndex:'id'
		},{
			text:'',dataIndex:'opts',align:'left',renderer:function(v,d,i){
				var s='&nbsp;';
				if(d.id>0){
					s='<a onclick="optess{rand}('+i+',0)" href="javascript:;">改</a>';
					if(d.stotal==0)s+=' <a onclick="optess{rand}('+i+',1)" href="javascript:;">删</a>';
				}
				return s;
			}
		}],
		itemclick:function(){
			get('down_{rand}').disabled=false;
		}
	});
	optess{rand}=function(oi,lx){
		c.optss(oi,lx);
	}
	var c = {
		reload:function(){
			this.loaddata();
		},
		optss:function(oi,lx){
			var d = a.getData(oi);
			if(lx==0){
				this.addflow(d.mid, d.id, d.name);
			}
			if(lx==1){
				this.delflow(d.id);
			}
		},
		adddowns:function(){
			var d = a.changedata;
			this.addflow(d.id, 0, d.name);
		},
		addflow:function(mid, id,tex){
			
			var icon='plus',name='新增['+tex+']下的步骤';
			if(id>0){
				icon='edit';
				name='编辑['+tex+']步骤';
			}
			guanflowcourselist = c;
			addtabs({num:'flowcourse'+id+'',url:'main,flow,courseedit,id='+id+',setid='+setid+',mid='+mid+'',icons:icon,name:name});
		},
		pipei:function(){
			js.ajax(js.getajaxurl('reloadpipei','{mode}','{dir}'),{mid:setid},function(s){
				js.msg('success', s);
			},'get',false,'匹配中...,匹配完成');
		},
		loaddata:function(){
			get('down_{rand}').disabled=true;
			this.inpub = $('#mainv{rand}');
			this.inpub.html('');
			js.ajax(js.getajaxurl('courseflowinit','{mode}','{dir}'),{setid:setid}, function(ret){
				c.loaddatashow(ret);
			},'get,json');
			if(!this.robj)this.robj = $.rockmenu({
				data:[],
				itemsclick:function(d){
					c.rightclick(d);
				}
			});
		},
		loaddatashow:function(ret){
			this.heie = 50;
			this.jianarr=[];
			this.showflow(ret.rows,0, -1, 0,0);
			a.loadData({rows:ret.treedata,totalCount:0});
			this.showflows();
			var ads = courseobj[0],lds,o,ow,o1;
			if(ads.childshu>1){
				lds = ads.children[ads.childshu-1].id;
				o = $('#courseflow'+setid+'_'+lds+'');
				o1= $('#courseflow'+setid+'_0');
				ow= o.width()+ parseInt(o.css('left'));
				ol= ow*0.5 - o1.width()*0.5;
				o1.css('left',''+ol+'px');
			}
			//显示箭头
			var i,len=this.jianarr.length,d;
			for(i=0;i<len;i++){
				d = this.jianarr[i];
				this.jian(d[0], d[1]);
			}
		},
		
		showflows:function(){
			this.inpub.css('height',''+(this.heie+90)+'px');
		},
		
		//lef起始位置 lev级别
		showflow:function(ret, lef,mid,lev){
			$('#mainvss{rand}').show();
			var i=0,len=ret.length,s,d={},l,t,dw,rfys='',sty,sid,o1;
			var jg = 100,oi=0;
			ks  = lef;
			for(i=0;i<len;i++){
				d=ret[i];
				sty = '';
				oi++;
				l = ks;
				if(d.childshu>1)jg=150; //有下级间隔大点
				if(i>0){
					l+=jg;
				}
				t = 120*d.level;
				if(t>this.heie)this.heie=t;
				rfys='rf_ract';
				if(d.childshu>1)rfys='rf_yuan';
				
				if(d.id==0||d.status=='0')sty=';background:#eeeeee;border-color:#cccccc;color:#888888';
				sid='courseflow'+setid+'_'+d.id+'';
				s='<div id="'+sid+'" dataid="'+d.id+'" oncontextmenu="right{rand}(this,event)" level="'+d.level+'" style="left:'+l+'px;top:'+t+'px'+sty+'" class="rf '+rfys+'"><div class="rf_nei">';
				if(!isempt(d.fzsm))s+='<div class="rf-texts">'+d.fzsm+'</div>';
				s+='<div class="rf-text">'+d.id+'.'+d.name+'</div>';
				if(!isempt(d.checktypename))s+='<div class="rf-texts">'+d.checktype+'.'+d.checktypename+'</div>';
				s+='</div></div>';
				this.inpub.append(s);
				if(mid>=0){
					this.jianarr.push([mid, d.id]);
				}
				if(d.childshu==0 && d.nid>0)this.jianarr.push([d.id, d.nid]);
				
				if(d.children){
					this.showflow(d.children, l ,d.id,d.level,0);
				}
				
				d.left = l;
				d.top = t;
				courseobj[d.id]=d;
				ks = l+$('#'+sid+'').width();
			}
			
		},
		jian:function(fid,tid){
			var fo,to,jl,l,t,jw,wh,dw;
			fo = $('#courseflow'+setid+'_'+fid+'');
			to = $('#courseflow'+setid+'_'+tid+'');
			
			var tleft = parseInt(to.css('left')),
				fleft = parseInt(fo.css('left')),
				ttop  = parseInt(to.css('top')),
				ftop  = parseInt(fo.css('top'));
			
			//如果同一条线上
			if(ttop==ftop){
				//→这个方向
				if(tleft>fleft){
					l = fleft + fo.width() + 4;
					t = ftop + fo.height()*0.5+4;
					dw= tleft - l;
					this.getjstr(dw,l,t, -90);
				}else{//←这个方向
					l = fleft-4;
					t = ftop + fo.height()*0.5-4;
					dw= fleft - tleft-to.width()-6;
					this.getjstr(dw,l,t, 90);
				}
				
				return;
			}		
			
			jl = parseInt(to.css('top'))- parseInt(fo.css('top'))-fo.height()-10;
			l  = fleft+fo.width()*0.5-8; //箭头开始位置
			t  = parseInt(fo.css('top'))+fo.height()+5; //箭头结束位置
			
			
			
			jw = tleft - fleft - fo.width() + to.width()*0.5;//两个的位置宽
			//console.log(''+fid+'→'+tid+':'+jw+'');
			var jgs = tleft-fleft;
			
			//判断f中心点 是否在t的区间内
			var fzxd = fleft+fo.width()*0.5;
			var tzxd = tleft+to.width();
			//在中心点就用直线↓
			if(fzxd>=tleft && fzxd<=tzxd){
				jw=0;
			}else if(fleft>tleft){
				jw = fleft - tleft - to.width()+ fo.width()*0.5;
				jw = 0-jw;
				l  = fleft;
				t  = t-5;
			}
			
			
			jh = jl;//两个的位置高
			
			dw = Math.sqrt(jw*jw+jh*jh); //对角线长度(勾股定理)
			
			
			var jds = Math.atan(jw/jh)*180/Math.PI; //旋转角度
			if(jds>0)l = l+fo.width()*0.5+10;
			var jdss= 0-jds;
			this.getjstr(dw,l,t, jdss);
		},
		getjstr:function(h,l,t,jds){
			var s = '<div style="left:'+l+'px;top:'+t+'px;transform:rotate('+jds+'deg);height:'+h+'px" class="rf_shu"><div class="rf_shu1" style="height:'+(h-8)+'px"></div><div class="rf_shu2"></div></div>';
			this.inpub.append(s);
		},
		righmenu:function(o1,e){
			var d = [],o=$(o1);
			var id = parseFloat(o.attr('dataid'));
			var ba = courseobj[id];
			d.push({'name':'新增下级步骤',text:ba.name,mid:0,'id':id,lx:0});
			if(id>0){
				d.push({'name':'编辑',text:ba.name,'id':id,lx:1,mid:ba.mid});
				if(ba.childshu==0)d.push({'name':'<font color=red>删除</font>','id':id,lx:2});
			}
			this.robj.setData(d);
			this.robj.showAt(e.clientX,e.clientY,130);
		},
		rightclick:function(d){
			var lx = d.lx;
			if(lx==0)this.addflow(d.id, 0,d.text);
			if(lx==1)this.addflow(d.mid, d.id,d.text); //编辑
			if(lx==2)this.delflow(d.id);
		},
		delflow:function(id){
			js.confirm('确定要删除此步骤吗？',function(jg){
				if(jg=='yes')c.delflows(id);
			});
		},
		delflows:function(id){
			js.ajax(js.getajaxurl('courseflowdel','{mode}','{dir}'), {id:id}, function(s){
				c.reload();
			},'get',false,'删除中...,删除成功');
		},
		search:function(){
			at.setparams({'key':get('key_{rand}').value}, true);
		}
	};
	js.initbtn(c);
	
	
	right{rand}=function(o1,e){
		c.righmenu(o1,e);
	}
	
	$('#optionview_{rand}').css('height',''+(viewheight-102)+'px');
});
</script>


<table width="100%">
<tr valign="top">
<td width="25%">
	<div class="panel panel-info" style="margin:0px">
	  <div class="panel-heading">
		<h3 class="panel-title">流程模块(双击显示步骤)</h3>
	  </div>
	  <div>
		<div class="input-group" style="width:200px">
			<input class="form-control" id="key_{rand}" placeholder="模块名称/编号">
			<span class="input-group-btn">
				<button class="btn btn-default" click="search" type="button"><i class="icon-search"></i></button>
			</span>
		</div>
	  </div>
	  <div id="optionview_{rand}" style="height:400px;overflow:auto"></div>
	</div>
</td>
<td width="10"></td>
<td>
	<div>
		<table width="100%">
		<tr>
		<td align="left" nowrap>
			<button class="btn btn-default" click="reload" id="relad_{rand}" disabled type="button">刷新</button>&nbsp;&nbsp;
			<button class="btn btn-default" click="pipei" id="addp_{rand}" disabled type="button">匹配流程</button>
		</td>
		<td align="left" width="90%" id="downshow_{rand}" style="padding:0px 10px;">
			
		</td>
		<td align="right" nowrap>
			<button class="btn btn-primary" click="adddowns" id="down_{rand}" disabled type="button">新增下级步骤</button>
		</td>
		</tr>
		</table>
		
	</div>
	
	<div class="blank10"></div>
	<div class="blank1"></div>
	<div class="blank10"></div>
	
	
	<div id="view_{rand}"></div>
	<div class="blank10"></div>
	<div id="mainvss{rand}" style="background:#f1f1f1;border:1px #dddddd solid;border-radius:5px;padding:10px;display:none;">
	<div oncontextmenu="return false" id="mainv{rand}" class="notsel" style="position:relative;height:auto;"></div>
	</div>
</td>
</tr>
</table>