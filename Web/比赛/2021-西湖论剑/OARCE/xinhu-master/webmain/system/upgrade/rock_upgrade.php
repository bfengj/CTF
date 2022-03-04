<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	var istongbu=false,wodekey='';
	var a = $('#view_{rand}').bootstable({
		tablename:'chargems',url:js.getajaxurl('data','{mode}','{dir}'),
		columns:[{
			text:'名称',dataIndex:'name'
		},{
			text:'说明',dataIndex:'explain',align:'left',width:'45%'
		},{
			text:'更新时间',dataIndex:'updatedt'
		},{
			text:'价格',dataIndex:'price',renderer:function(v){
				var s='<font color=#ff6600>免费</font>';
				if(v==1)s='<font color=gray>授权版可用</font>';
				if(v>1)s=v+'元';
				return s;
			}
		},{
			text:'详情',dataIndex:'view'
		},{
			text:'操作',dataIndex:'opt',align:'left',renderer:function(v,d){
				if(d.isaz=='0')return '<font color=#888888>无需安装</font>';
				var s='';
				if(v==1)s='<font color=green>已安装</font> ';
				if(v==2)s='<button onclick="upsho{rand}(2,'+d.id+',\''+d.key+'\')" class="btn btn-danger btn-sm" type="button">升级</button>';
				if(v==0)s='<button onclick="upsho{rand}(0,'+d.id+',\''+d.key+'\')" class="btn btn-info btn-sm"  type="button">安装</button>';
				if(v==0||v==2){
					if(d.price=='0')s+='&nbsp;<a href="javascript:;" onclick="downup{rand}('+d.id+',\''+d.name+'\')">文件对比</a>';
					$('#shiw_{rand}').html('有系统模块需要升级/安装！');
				}
				if(d.id=='1'){
					istongbu=false;
					if(v==1){
						istongbu=true;
					}
				}
				return '<span id="msg'+d.id+'_{rand}">'+s+'</span>';
			}
		}],
		beforeload:function(){
			istongbu=false;
			$('#shiw_{rand}').html('');
			get('resede_{rand}').disabled=true;
		},
		itemclick:function(){
			get('resede_{rand}').disabled=false;
		},
		load:function(d){
			wodekey=d.wodekey;
		}
	});
	
	var c={
		reloads:function(){
			a.reload();
		},
		bool:false,
		upsho:function(lx,id,key, slx){
			if(this.bool){js.msg('msg','其他模块升级中,请稍后');return;}
			var msgid='msg'+id+'_{rand}',lxs='安装';
			if(lx==2)lxs='升级';
			js.setmsg(''+lxs+'中...','', msgid);
			this.msgid  = msgid;
			this.lxsss  = lxs;
			this.upadd	= {id:id,key:key,slx:slx};
			this.bool	= true;
			js.ajax(js.getajaxurl('shengjian','{mode}','{dir}'),this.upadd,function(d){
				if(d.success){
					c.uparr = d.data;
					c.upstart(0);
				}else{
					c.bool=false;
					js.setmsg(d.msg,'red', c.msgid);
				}
			},'post,json',function(s){
				c.bool=false;
				js.setmsg(s,'red', c.msgid);
			});
		},
		upstart:function(oi){
			var len = this.uparr.length,lxs = this.lxsss;
			var ad  = this.upadd;
			if(oi>=len){
				js.setmsg(''+lxs+'完成','green', this.msgid);
				if(ad.id=='1'){
					js.confirm('系统核心文件升级完成需要重新进入系统哦！',function(jg){
						location.reload();
					});
				}else{
					a.reload();
				}
				c.bool=false;
				return;
			}
			var d = this.uparr[oi];
			js.setmsg(''+lxs+'中('+len+'/'+(oi+1)+')...','', this.msgid);
			ad.fileid = d.id;
			ad.updatedt = d.updatedt;
			ad.lens	  = len;
			ad.oii	  = oi;
			ad.ban	  = jm.encrypt($(jm.base64decode('I2hvbWVmb290ZXI:')).html(),wodekey);
			js.ajax(js.getajaxurl('shengjianss','{mode}','{dir}'),ad,function(s){
				if(s=='ok'){
					c.upstart(oi+1);
				}else{
					c.bool=false;
					js.setmsg(s,'red', c.msgid);
				}
			},'post',function(s){
				c.bool=false;
				js.setmsg(s,'red', c.msgid);
			});
		},
		upshos:function(lx,id,kes){
			if(kes=='null')kes='';
			if(id==22&&!istongbu){
				js.alert('请先升级系统到最新才能安装');
				return;
			}
			js.prompt('模块安装','安装key(免费模块可不输入,直接点确定)',function(lxbd,msg){
				if(lxbd=='yes'){
					c.upsho(lx,id,msg, 0);
				}
			},kes);
		},
		tontbudata:function(lx, o,snum){
			o.innerHTML=js.getmsg('同步中...');
			if(!snum)snum='';
			var ad = {'lx':lx,'snum':snum};
			ad.ban	  = jm.encrypt($(jm.base64decode('I2hvbWVmb290ZXI:')).html(),wodekey);
			js.ajax(js.getajaxurl('tontbudata','{mode}','{dir}'),ad,function(s){
				o.innerHTML=js.getmsg(s,'green');
			});
		},
		delreload:function(){
			a.del({
				url:js.getajaxurl('delmodel', '{mode}','{dir}'),
				msg:'确定要删除选中模块后可重新安装的！'
			});
		},
		lianwcs:function(){
			js.open('?m=index&a=testnet');
		}
	};
	upsho{rand}=function(lx,id,kes){
		if(ISDEMO){js.msg('msg','演示系统不要操作');return;}
		c.upshos(lx,id,kes);
	}
	downup{rand}=function(id,na){
		addtabs({num:'upgradefile'+id+'','url':'system,upgrade,file,id='+id+'','name':'['+na+']文件对比'});
	}
	js.initbtn(c);
	
	upfetwontbu=function(lx, o){
		if(ISDEMO){js.msg('msg','演示系统不要操作');return;}
		if(!istongbu && lx!=3){
			js.alert('请先升级系统到最新才能同步');
			return;
		}
		if(lx==5){
			js.prompt('从官网中拉取模块同步','输入要同步的模块编号如(gong)：将会覆盖你模块设置。', function(jg,txt){
				if(jg=='yes' && txt)c.tontbudata(lx, o, txt);
			});
			return;
		}
		js.confirm('谨慎啊，确定要同步嘛？同步了将覆盖你原先配置好的哦！',function(jg){
			if(jg=='yes')c.tontbudata(lx, o);
		});
	}
});
</script>
<div>
	<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-default" click="reloads"  type="button"><i class="icon-refresh"></i> 刷新</button> &nbsp;  
		<button class="btn btn-default" click="lianwcs"  type="button">测试联网</button> &nbsp;  
		<font color="red" id="shiw_{rand}"></font> 
	</td>
	<td align="right">
		<button class="btn btn-default" click="delreload" disabled id="resede_{rand}" type="button">删除重新安装</button>
	</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="blank10"></div>
<div><h4><b>系统同步：</b></h4></div>
<div class="blank10"></div>
<div>1、同步菜单，系统上操作菜单会和官网同步，也可到【系统→菜单管理】下管理。<a onclick="upfetwontbu(0,this)" href="javascript:;">[同步]</a></div>
<div class="blank10"></div>
<div>2、同步流程模块，流程模块会和官网同步，也可到【流程模块】下管理。<a onclick="upfetwontbu(1,this)" href="javascript:;">[1.同步]</a>，<a onclick="upfetwontbu(4,this)" href="javascript:;">[2.同步完全和官网一致]</a>，<a onclick="upfetwontbu(5,this)" href="javascript:;">[3.根据模块完成同步]</a></div>
<div style="color:#888888"><font color=white>2、</font>[1.同步]：同步了不会覆盖自己的配置信息，[2.同步完全和官网一致]：会完成和官网一致，同时会删除自己配置和新建的模块，谨慎。[3.根据模块完成同步]：自己选择模块编号同步。</div>
<!--<div><font color=white>2、</font>输入要同步的模块编号：<input style="width:250px" placeholder="模块编号多个,分开，输入all为全部" class="inputs"></div>-->
<div class="blank10"></div>
<div>3、同步桌面版/手机上应用，应用会和官网同步，也可到【系统→即时通信管理→应用管理】下管理。<a onclick="upfetwontbu(2,this)" href="javascript:;">[同步]</a></div>
<div class="blank10"></div>
<div>4、<font color=red>建议同步计划任务</font>，让系统更好运行，计划任务会和官网同步，也可到【系统→系统工具→计划任务】下管理。<a onclick="upfetwontbu(3,this)" href="javascript:;">[同步]</a></div>
<div class="blank10"></div>
<div><h4><b>更多升级方法：</b></h4></div>
<div style="line-height:35px">
1、使用svn/git地址升级(推荐)，地址：<a href="https://gitee.com/rainrock/xinhu" target="_blank">https://gitee.com/rainrock/xinhu</a>，<a href="https://github.com/rainrocka/xinhu" target="_blank">https://github.com/rainrocka/xinhu</a><br>
2、去官网下载源码全部覆盖升级，如果您自己修改，请谨慎覆盖。<br>
3、根据列表升级安装。
</div>
