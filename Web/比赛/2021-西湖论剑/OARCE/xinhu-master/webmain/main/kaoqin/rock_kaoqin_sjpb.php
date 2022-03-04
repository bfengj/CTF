<?php defined('HOST') or die ('not access');?>
<script>
$(document).ready(function(){
	{params}
	var atype=params.atype,columna=[],chagnedtarr={},lbob=false,pblx=0;if(atype)atype='';
	var column = [{
		text:'部门',dataIndex:'deptname',align:'left',sortable:true
	},{
		text:'姓名',dataIndex:'name',sortable:true
	}];
	for(var i=1;i<=28;i++){
		columna.push({
			text:''+i+'',
			dataIndex:'day'+i+'',
		});
	}
	var a = $('#view_{rand}').bootstable({
		tablename:'admin',fanye:true,url:publicstore('{mode}','{dir}'),storeafteraction:'pbkqdistafter',storebeforeaction:'pbkqdistbefore',params:{'pblx':0,'atype':atype},
		columns:[].concat(column,columna),
		itemclick:function(d,oi, e){
			var e1 = e.target;
			if(e1.nodeName.toLowerCase()=='td'){
				var o1 = $(e1),row = o1.attr('row'),cell = parseFloat(o1.attr('cell'));
				if(cell>=2){
					var ke = 'a_'+row+'_'+cell+'';
					if(chagnedtarr[ke]){
						o1.css('background','');
						chagnedtarr[ke] = false;
					}else{
						o1.css('background','#94DDFC');
						chagnedtarr[ke] = o1;
					}
				}
			}
		},
		loadbefore:function(d){
			var cs = [],i;
			for(i in column)cs.push(column[i]);
			var warr=['日','一','二','三','四','五','六'],w=parseFloat(d.week),tsa;
			for(i=1;i<=d.maxjg;i++){
				if(i>1)w++;
				if(w>6)w=0;
				tsa = ''+i+'<br>('+warr[w]+')';
				if(w==0||w==6)tsa='<font color="#ff6600">'+tsa+'</font>';
				cs.push({
					text:tsa,
					dataIndex:'day'+i+''
				});
			}
			a.setColumns(cs);
		},
		load:function(d){
			var str='提示：其中空白为休息日，0：未设置',gzrows=d.gzrows;
			
			var rda = [{
				name:'设置为休息日',lx:0,id:0
			},{
				name:'取消休息日',lx:1,id:0
			},{
				name:'设置为工作日',lx:2,id:0
			},{
				name:'取消工作日',lx:3,id:0
			}];
			for(var i=0;i<gzrows.length;i++){
				str+='，'+gzrows[i].id+'：'+gzrows[i].name+'';
				rda.push({name:'设置规则为：'+gzrows[i].name+'',lx:4,id:gzrows[i].id});
			}
			rda.push({name:'取消规则',id:5,lx:5});
			$('#guistr_{rand}').html(str);
			
			if(!lbob){
				$('#downbtn_{rand}').rockmenu({
					width:230,top:35,donghua:false,
					data:rda,
					itemsclick:function(d, i){
						c.setdownss(d);
					}
				});
			}
		},
		beforeload:function(){
			chagnedtarr={};
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt1:get('dt1_{rand}').value},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(){
			a.exceldown('考勤时间排班('+get('dt1_{rand}').value+')');
		},
		xuanzeq:function(){
			for(var i in chagnedtarr){
				if(chagnedtarr[i])chagnedtarr[i].css('background','');
			}
			chagnedtarr={};
		},
		//保存
		setdownss:function(d){
			if(pblx=='0'){js.msg('msg','请先选择根据组/人员来设置');return;}
			var str='',i,j,kes,o,row,cell,kesa,da,can=[],mon=get('dt1_{rand}').value,type=d.lx;
			for(kes in chagnedtarr){
				o = chagnedtarr[kes];
				if(o){
					kesa = kes.split('_');
					row = parseFloat(kesa[1]);
					cell = parseFloat(kesa[2]);
					da = a.getData(row);
					can.push({receid:da.id,dt:mon+'-'+(cell-1)+'',plx:pblx,type:type,mid:d.id});
				}
			}
			var cans={},len=can.length;
			if(len==0){js.msg('msg','没有选中单元格来设置');return;}
			for(i=0;i<len;i++){
				for(j in can[i])cans[''+j+'_'+i+'']=can[i][j];
			}
			cans.len = len;
			js.ajax(js.getajaxurl('setpaiban','{mode}','{dir}'),cans, function(s){
				a.reload();
			},'post',false,'标识中,标识成功');
		},
		changeplx:function(){
			var val = get('plx_{rand}').value;
			pblx = val;
			get('downbtn_{rand}').disabled=(pblx=='0')?true:false;
			a.setparams({key:'','pblx':val},true);
		}
	};
	
	$('#dt1_{rand}').val(js.now('Y-m'));
	js.initbtn(c);
	
	$('#plx_{rand}').change(function(){
		c.changeplx();
	});
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>月份&nbsp;</td>
	<td nowrap>
		<div style="width:120px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<select class="form-control" style="width:170px" id="plx_{rand}">
		<option value="0">查看人员排班情况</option>
		<option value="1">根据组来排班(设置)</option>
		<option value="2">根据人员来排班(设置)</option>
		</select>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px">
		
	</td>
	<td  style="padding-left:5px">
		
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="downbtn_{rand}" disabled  type="button">选中标识为 <i class="icon-angle-down"></i></button>&nbsp;&nbsp;
		<button class="btn btn-default" click="xuanzeq" type="button">取消选择</button>&nbsp;&nbsp;
		<button class="btn btn-default" click="daochu" type="button">导出</button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}" style="cursor:default"></div>
<div class="tishi" id="guistr_{rand}">提示：</div>