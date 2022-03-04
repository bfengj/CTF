var isedit = 1,qmimgstr='',isxiang=1,alldata={},data={};
function othercheck(){}

//函数触发
function oninputblur(name,zb,obj){};

function initbody(){
	js.inittabs();
	$('body').click(function(){
		$('.menullss').hide();
	});
	$('body').keydown(c.onkeydown);
	$('#showmenu').click(function(){
		$('.menullss').toggle();
		return false;
	});
	$('.menullss li').click(function(){
		c.mencc(this);
	});
	if(document.myform && form('fileid')){
		if(typeof(FormData)=='function'){
			f.fileobj = $.rockupload({
				autoup:false,
				fileview:'filedivview',
				allsuccess:function(){
					check(1);
				}
			});
		}else{
			$('#filedivview').parent().html('<font color="#888888">当前浏览器不支持上传</font>');
		}
		//初始化微信jssdk
		if(js.jssdkinit){
			js.jssdkinit();
			js.jssdkcall=function(bo){
				if(bo)c.initRecord();//可以录音
			}
		}
	}
	js.tanstyle=1;
	if(document.myform && get('modelujs')){
		js.importjs('webmain/flow/input/inputjs/input_two.js?'+Math.random()+'', function(){
			for(var oi in inputtwo)c[oi]=inputtwo[oi];
			if(typeof(initbodys)=='function')initbodys();
			c.initinput();
			//检查是否有编辑器
			var hobj = $("span[fieldstype='htmlediter']");
			if(hobj.length>0)js.importjs('mode/kindeditor/kindeditor-min.js', function(){
				for(var i=0;i<hobj.length;i++)c.htmlediter($(hobj[i]).attr('fieidscheck'));
			});
		});
	}
	
	if(receiptrs){
		var s = '<div style="position:fixed;top:40%;right:5px;padding:10px;border-radius:4px;z-index:5px;background:#555555;color:white" id="receiptrsdiv"><div>此单据需要回执确认<br>请将页面拉到最后</div><div style="margin-top:5px"><input type="button"  onclick="c.receiptque()" value="回执确认" class="webbtn btn-danger"></div></div>';
		$('body').append(s);
	}
	
	$('#contentshow img[onclick=""]').click(function(){c.showviews(this)});
}
function showchayue(opt, st){
	alert('总查阅:'+st+'次\n最后查阅：'+opt+'');
}
function geturlact(act,cns){
	var url=js.getajaxurl(act,'mode_'+modenum+'|input','flow',cns);
	return url;
}

var f={
	change:function(o1){
		f.fileobj.change(o1);
	}
};

//拨打电话
function callPhone(o1){
	if(appobj1('callPhone',$(o1).text())){
		return false;
	}else{
		return true;
	}
}

//选择人员前处理
js.changeuser_before=function(na){
	if(na=='sys_nextcoursename'){
		var fw = '',o = form('sys_nextcourseid');
		if(o){
			var o1= o.options[o.selectedIndex];
			fw = $(o1).attr('changerange');
			return {'changerange':fw};
		}
	}
	return c.changeuser_before(na);
}

//提交处理
function check(lx){
	var sm = form('check_explain')?form('check_explain').value:'';
	var da = {'sm':sm,'tuiid':'0','fileid':'','mid':mid,'modenum':modenum,'zt':_getaolvw('check_status'),'qmimgstr':qmimgstr};
	if(form('fileid'))da.fileid=form('fileid').value;
	if(form('check_tuiid'))da.tuiid=form('check_tuiid').value;
	var smlx = form('check_smlx').value,wjlx=form('check_wjlx').value,cslx=0;
	if(form('bzcslx'))cslx = form('bzcslx').value;
	js.setmsg();
	if(da.zt==''){
		js.setmsg('请选择处理动作');
		return;
	}
	if(((smlx=='0' && da.zt=='2') || (smlx=='1')) && isempt(da.sm)){
		js.setmsg('此动作必须填写说明');
		return;
	}
	
	if($('#filedivview').html()=='' && ((wjlx=='1') || (wjlx=='2' && da.zt=='1') )){
		js.setmsg('此动作必须选择上传相关文件');
		return;
	}
	
	var isqm = form('isqianming').value;
	var qbp  = true;
	
	if(form('zhuanbanname')){
		da.zyname 	= form('zhuanbanname').value;
		da.zynameid = form('zhuanbannameid').value;
	}
	
	if(form('bzchaosongname')){
		da.csname 	= form('bzchaosongname').value;
		da.csnameid = form('bzchaosongnameid').value;
	}
	
	if(cslx==2 && da.zt=='1' && !da.csnameid){
		js.setmsg('此动作必须选择抄送');return;
	}
	
	//手写签名判断
	if(isqm=='1' && qmimgstr=='')qbp=false;
	if(isqm=='2' && da.zt=='1' && qmimgstr=='')qbp=false;
	if(isqm=='3' && da.zt=='2' && qmimgstr=='')qbp=false;
	if(!qbp && !da.zynameid){js.setmsg('此动作必须手写签名');return;}
	
	
	if(form('nextnameid') && da.zt=='1' && !da.zynameid){
		da.nextname 	= form('nextname').value;
		da.nextnameid 	= form('nextnameid').value;
		if(da.nextnameid==''){
			js.setmsg('请选择下一步处理人');return;
		}
	}
	
	//自由流程处理的
	if(da.zt=='1' && form('sys_nextcourseid') && !da.zynameid){
		da.sys_nextcourseid 	= form('sys_nextcourseid').value;
		da.sys_nextcoursename 	= form('sys_nextcoursename').value;
		da.sys_nextcoursenameid = form('sys_nextcoursenameid').value;
		if(da.sys_nextcourseid==''){
			js.setmsg('请选择下步处理步骤');
			return;
		}
		if(da.sys_nextcourseid>0 && da.sys_nextcoursenameid=='' && c.changenextbool){
			js.setmsg('请选择下步处理人');
			return;
		}
	}
	
	//加签
	if(da.zt=='25' || da.zt=='26'){
		if(!form('sys_yushenname')){
			js.setmsg('无效使用加签');
			return;
		}
		da.sys_yushenname   = form('sys_yushenname').value;
		da.sys_yushennameid = form('sys_yushenname_id').value;
		da.sys_yushennamezt = form('sys_yushennamezt').value;
		if(da.sys_yushennameid==''){
			js.setmsg('请选择加签处理人');
			return;
		}
	}
	
	if(!da.zynameid && da.zt!='2' && da.zt!='25' && da.zt!='26'){
		var fobj=$('span[fieidscheck]'),i,fid,flx,fiad,val,isbt;
		var subdat = js.getformdata();
		for(i=0;i<fobj.length;i++){
			fiad = $(fobj[i]);
			fid	 = fiad.attr('fieidscheck');
			isbt = fiad.attr('isbt');
			val  = subdat[fid];
			if(c.editorobj[fid])val=c.editorobj[fid].html();
			da['cfields_'+fid]=val;
			if(val=='' && isbt=='1'){js.setmsg(''+fiad.text()+'不能为空');return;}
		}
	}
	var ostr=othercheck(da);
	if(typeof(ostr)=='string'&&ostr!=''){js.setmsg(ostr);return;}
	if(typeof(ostr)=='object')for(var csa in ostr)da[csa]=ostr[csa];
	js.setmsg('处理中...');
	
	var o1 = get('check_btn');
	o1.disabled = true;
	if(lx==0 && f.fileobj && f.fileobj.start()){
		return js.setmsg('上传相关文件中...');//有上传相关文件
	}
	var url = c.gurl('check');
	js.ajax(url,da,function(a){
		if(a.success){
			js.setmsg(a.data,'green');
			if(da.zt=='26'){
				js.alert('后加签成功，请继续审批','', function(){
					js.reload();
				});
			}else{
				c.callback(a.data);
				if(get('autocheckbox'))if(get('autocheckbox').checked)c.close();
			}
		}else{
			js.setmsg(a.msg);
			o1.disabled = false;
		}
	},'post,json',function(estr){
		js.setmsg('处理失败:'+estr+'');o1.disabled = false;
	});
}
function _getaolvw(na){
	var v = '',i,o=$("input[name='"+na+"']");
	for(i=0;i<o.length;i++)if(o[i].checked)v=o[i].value;
	return v;
}

/**
*	nae记录名称 
*	zt状态名称 
*	ztid 状态id 
*	ztcol 状态颜色 
*	ocan 其他参数
*	las 说明字段Id默认other_explain
*/
function _submitother(nae,zt,ztid,ztcol,ocan,las){
	if(!las)las='other_explain';
	if(!nae||!get(las)){js.setmsg('sorry;不允许操作','','msgview_spage');return;}
	var sm=$('#'+las+'').val();
	if(!ztcol)ztcol='';
	if(!zt)zt='';if(!ocan)ocan={};
	if(!ztid){js.setmsg('没有选择状态','','msgview_spage');return;}
	if(!sm){js.setmsg('没有输入备注/说明','','msgview_spage');return;}
	var da = js.apply({'name':nae,'mid':mid,'modenum':modenum,'ztcolor':ztcol,'zt':zt,'ztid':ztid,'sm':sm},ocan);
	js.setmsg('处理中...','','msgview_spage');
	js.ajax(c.gurl('addlog'),da,function(s){
		js.setmsg('处理成功','green', 'msgview_spage');
		$('#spage_btn').hide();
	},'post',function(s){
		js.setmsg(s,'','msgview_spage');
	});
	return false;
}
var c={
	callback:function(cs,cbo){
		var nowli= js.getoption('nowListener');
		if(nowli)js.sendevent('reload',nowli);
		if(ismobile==1 && js.msgok)js.msgok(cs, function(){js.back()},1);
		var calb = js.request('callback');
		if(!calb)return;
		try{parent[calb](cs);}catch(e){}
		try{opener[calb](cs);}catch(e){}
		try{parent.js.tanclose('openinput');}catch(e){}
		if(cbo)this.close();
	},
	changeuser_before:function(){},
	gurl:function(a){
		var url=js.getajaxurl(a,'flowopt','flow');
		return url;
	},
	editorobj:{},
	showtx:function(msg){
		js.setmsg(msg);
		if(ismobile==1)js.msg('msg', msg);
	},
	close:function(){
		var ofrom = js.request('ofrom');
		if(ofrom=='deskclient'){
			js.cliendsend('closenowtabs');
		}else{
			window.close();
			try{parent.js.tanclose('winiframe');}catch(e){}
		}
	},
	other:function(nae,las){
		_submitother(nae,'','1','',las);
	},
	others:function(nae,zt,ztid,ztcol,ocan,las){
		_submitother(nae,zt,ztid,ztcol,ocan,las);
	},
	mencc:function(o1){
		var lx=$(o1).attr('lx');
		if(lx=='2')c.delss();
		if(lx=='3')c.close();
		if(lx=='4')location.reload();
		if(lx=='0')c.clickprint(false);
		if(lx=='6')c.clickprint(true);
		if(lx=='5')c.daochuword();
		if(lx=='7')c.savetoimg();
		if(lx=='10')c.savetopdf();
		if(lx=='8')js.location('?a=t&num='+modenum+'&mid='+mid+'');
		if(lx=='9')js.location('?a=p&num='+modenum+'&mid='+mid+'');
		if(lx=='1'){
			var url='index.php?a=lu&m=input&d=flow&num='+modenum+'&mid='+mid+'';
			js.location(url);
		}
	},
	clickprint:function(bo){
		c.hideoth();
		if(bo){
			$('#recordss').remove();
			$('#checktablediv').remove();
			$('#recordsss').remove();
		}
		window.print();
	},
	savetoimg:function(){
		this.hideoth();
		js.loading();
		js.importjs('js/html2canvas.js', function(){
			html2canvas($('#maindiv'),{
				onrendered: function(canvas){
					var imgbase64 = canvas.toDataURL().split(',')[1];
					c.showviews({src:canvas.toDataURL()});
					js.unloading();
				}
			});
		});
	},
	savetopdf:function(){
		this.hideoth();
		js.loading();
		js.importjs('js/html2canvas.js', function(){
			html2canvas($('#maindiv'),{
				onrendered: function(canvas){
					var imgbase64 = canvas.toDataURL().split(',')[1];
					js.ajax(c.gurl('savetopdf'),{imgbase64:imgbase64},function(ret){
						js.unloading();
						if(!ret.success){
							js.msgerror(ret.msg);
						}else{
							js.msgok('导出成功');
						}
					},'post,json');
				}
			});
		});
	},
	daochuword:function(){
		var url='task.php?a='+js.request('a')+'&num='+modenum+'&mid='+mid+'&stype=word';
		js.location(url);
	},
	hideoth:function(){
		$('.menulls').hide();
		$('.menullss').hide();
		$('#pinglunview').hide();
		$('a[temp]').remove();
	},
	delss:function(){
		js.confirm('删除将不能恢复，确定要<font color=red>删除</font>吗？',function(lx){
			if(lx=='yes')c.delsss();
		});
	},
	delsss:function(){
		var da = {'mid':mid,'modenum':modenum,'sm':''};
		js.ajax(c.gurl('delflow'),da,function(a){
			js.msg('success','单据已删除,3秒后自动关闭页面,<a href="javascript:;" onclick="c.close()">[关闭]</a>');
			c.callback();
			setTimeout('c.close()',3000);
		},'post');
	},
	onkeydown:function(e){
		var code	= e.keyCode;
		if(code==27){
			c.close();
			return false;
		}
		if(e.altKey){
			if(code == 67){
				c.close();
				return false;
			}
		}
	},
	changeshow:function(lx){
		$('#showrecord'+lx+'').toggle();
	},
	loacdis:false,
	showviews:function(o1){
		this.loadicons();
		$.imgview({'url':o1.src,'ismobile':ismobile==1});
	},
	loadicons:function(){
		if(!this.loacdis){
			$('body').append('<link rel="stylesheet" type="text/css" href="web/res/fontawesome/css/font-awesome.min.css">');
			this.loacdis= true;
		}
	},
	showfilestr:function(d){
		var flx = js.filelxext(d.fileext);
		var s = '<img src="web/images/fileicons/'+flx+'.gif" align="absmiddle" height=16 width=16> <a href="javascript:;" onclick="js.downshow('+d.id+')">'+d.filename+'</a> ('+d.filesizecn+')';
		return s;
	},
	//撤回操作
	chehui:function(){
		js.prompt('确定撤回吗？','要撤回上一步处理结果说明(选填)',function(jg,txt){
			if(jg=='yes')c.chehuito(txt);
		});
	},
	chehuito:function(sm){
		js.msg('wait','撤回中...');
		js.ajax(c.gurl('chehui'),{'mid':mid,'modenum':modenum,'sm':sm},function(a){
			if(a.success){
				js.msg('success', '撤回成功');
				location.reload();
			}else{
				js.msg('msg', a.msg);
			}
		},'post,json',function(s){
			js.msg('msg','操作失败');
		});
	},
	
	//预览文件
	downshow:function(id, ext,pts, fnun){
		this.loadicons();
		if(!isempt(fnun)){
			js.fileopt(id,0);
		}else{
			js.yulanfile(id, ext,pts,'','','xq');
		}
		return false;
	},
	changecheck_status:function(o1){
		var zt = _getaolvw('check_status');
		if(zt=='2'){
			$('#tuihuidiv').show();
		}else{
			$('#tuihuidiv').hide();
		}
		if(zt=='1'){
			$('#zhuangdiv').show();
			$('#nextxuandiv').show();
			if(get('sys_nextcoursediv0')){
				$('#sys_nextcoursediv0').show();
			}
		}else{
			$('#zhuangdiv').hide();
			$('#nextxuandiv').hide();
			if(get('sys_nextcoursediv0')){
				form('sys_nextcourseid').value='';
				js.changeclear('changesys_nextcoursename');
				$('#sys_nextcoursediv0').hide();
				$('#sys_nextcoursediv1').hide();
			}
		}
		if(zt=='25' || zt=='26'){
			$('#sys_yushennamediv').show();
			$('#sys_yushennamediv1').show();
		}else{
			$('#sys_yushennamediv').hide();
			$('#sys_yushennamediv1').hide();
		}
	},
	changenextbool:true,
	changenextcourse:function(o,lx){
		var o1= o.options[o.selectedIndex];
		var clx = $(o1).attr('checktype');
		this.changenextbool=true;
		js.changeclear('changesys_nextcoursename');
		if(o.value>0){
			if(lx==3 || (lx==4 && clx=='change')){
				$('#sys_nextcoursediv1').show();
			}else{
				$('#sys_nextcoursediv1').hide();
				this.changenextbool=false;
			}
		}else{
			$('#sys_nextcoursediv1').hide();
		}
	},
	//手写签名
	qianming:function(o1){
		this.qianmingbo=false;
		js.tanbody('qianming','请在空白区域写上你的姓名',300,200,{
			html:'<div data-width="280" data-height="120" data-border="1px dashed #cccccc" data-line-color="#000000" data-auto-fit="true" id="qianmingdiv" style="margin:10px;height:120px;cursor:default"></div>',
			btn:[{text:'确定签名'},{text:'重写'}]
		});
		$('#qianmingdiv').jqSignature().on('jq.signature.changed', function() {
			c.qianmingbo=true;
		});
		
		if(ismobile==1)get('qianmingdiv').addEventListener('touchmove',function(e){
			e.preventDefault();
		},false);
	
		$('#qianming_btn0').click(function(){
			c.qianmingok();
		});
		$('#qianming_btn1').click(function(){
			$('#imgqianming').remove();
			$('#qianmingdiv').jqSignature('clearCanvas');
			c.qianmingbo = false;
			qmimgstr	 = '';
		});
	},
	qianmingok:function(){
		if(!this.qianmingbo)return;
		$('#imgqianming').remove();
		var dataUrl = $('#qianmingdiv').jqSignature('getDataURL');
		var s = '<br><img id="imgqianming" src="'+dataUrl+'"  height="90">';
		qmimgstr = dataUrl;
		$('#qianmingshow').append(s);
		js.tanclose('qianming');
	},
	qianyin:function(){
		js.msg('wait','引入中...');
		js.ajax(c.gurl('qianyin'),{},function(a){
			if(a.success){
				js.msg('success', '引入成功');
				$('#imgqianming').remove();
				var dataUrl = a.data;
				var s = '<br><img id="imgqianming" src="'+dataUrl+'"  height="90">';
				qmimgstr = dataUrl;
				$('#qianmingshow').append(s);
			}else{
				js.msg('msg', a.msg);
			}
		},'get,json',function(s){
			js.msg('msg','操作失败');
		});
	},
	optmenu:function(o1){
		var o = $(o1);
		var issm = o.attr('issm'),optmenuid = o.attr('optmenuid');
		var smts = (issm=='1') ? '(必填)' : '(选填)';
		var d  = {'modenum':modenum,'mid':mid,'name':o1.value,'issm':issm,'optmenuid':optmenuid};
		js.prompt(d.name,'请输入['+d.name+']说明'+smts+'：',function(jg,text){
			if(jg=='yes'){
				if(!text && d.issm==1){
					js.msg('msg','没有输入['+d.name+']说明');
					return true;
				}else{
					o1.disabled=true;
					o1.style.background='#888888';
					c.optmenusubmit(d, text);
				}
			}
		});
	},
	optmenusubmit:function(d,sm){
		d.sm = sm;
		js.msg('wait','处理中...');
		js.ajax(js.getajaxurl('yyoptmenu','flowopt','flow'),d,function(ret){
			if(ret.code==200){
				js.msg('success','处理成功');
			}else{
				js.msg('msg',ret.msg);
			}
		},'post,json');	
	},
	
	inputblur:function(o1,zb){
		var nae = o1.name;
		oninputblur(nae,zb,o1);
	},
	getselobj:function(fv){
		var o = form(fv);
		if(!o)return;
		var o1= o.options[o.selectedIndex];
		return o1;
	},
	getseltext:function(fv){
		var o1 = this.getselobj(fv);
		if(!o1)return '';
		return o1.text;
	},
	getselattr:function(fv,art){
		var o1 = this.getselobj(fv);
		if(!o1)return '';
		return $(o1).attr(art);
	},
	
	//评论
	pinglun:function(o1){
		js.setmsg('','','pinglun_spage');
		var sm = get('pinglun_explain').value;
		if(!sm){js.setmsg('请输入评论内容','','pinglun_spage');return;}
		js.setmsg('提交中...','','pinglun_spage');
		js.ajax(c.gurl('pinglun'),{'sm':sm,'name':'评论','mid':mid,'modenum':modenum},function(s){
			var msg = '提交评论成功';
			js.setmsg(msg,'green','pinglun_spage');
			js.msgok(msg);
			get('pinglun_explain').disabled=true;
			$(o1).remove();
		},'post',function(s){
			js.setmsg(s,'','pinglun_spage');
		});
		return false;
	},
	
	//回执确认
	receiptque:function(){
		$('#receiptrsdiv').remove();
		js.prompt('回执确认','确认说明(选填)', function(jg,txt){
			if(jg=='yes'){
				c.receiptqueok(txt);
			}
		});
	},
	receiptqueok:function(sm){
		js.msg('wait','回执确认确认提交中...');
		var da = {'mid':mid,'modenum':modenum,'sm':sm,'receiptid':receiptrs.id};
		js.ajax(c.gurl('receiptcheck'),da,function(a){
			js.msg('success','回执确认提交成功');
		},'post');
	},
	
	initRecord:function(){
		$('#filedivviewfile').prepend('<input onclick="js.wxRecord.startLuyin(this)" type="button" class="webbtn" style="padding:5px 8px;border-radius:5px" value="录音">&nbsp;');
		js.wxRecord.success=function(ret){
			f.fileobj.fileallarr.push(ret);
			var str='<div style="padding:3px;font-size:14px;border-bottom:1px #dddddd solid">录音:'+ret.filename+'('+ret.filesizecn+')</div>';
			$('#filedivview').append(str);
		}
	},
	
	showeditcont:function(optdt,uid){
		js.tanbody('editcont','修改记录',(ismobile==1) ? winWb()-10 : 600,300, {
			html:'<div style="height:300px;overflow:auto"><div id="editcontview" class="wrap" style="padding:5px">'+js.getmsg('加载中...')+'</div></div>'
		});
		js.ajax(c.gurl('editcont'),{optdt:optdt,uid:uid,mid:mid,modenum:modenum},function(ret){
			$('#editcontview').html(ret);
		},'get');
	},
	
	//审核表单中可重写的方法，录入js写用到
	onselectdata:{},
	onselectdataall:function(){},
	changeuser_before:function(){},
	onselectdatabefore:function(){},
	htmlediteritems:function(){},
	uploadback:function(){},
	uploadfileibefore:function(){},
	onselectmap:function(){}
};