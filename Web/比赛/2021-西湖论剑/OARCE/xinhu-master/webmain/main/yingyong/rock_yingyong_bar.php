<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var arr=[];
	var c={
		loadlist:function(){
			js.setmsg('加载中...','','showlistyingyong');
			js.ajax(js.getajaxurl('getdata','{mode}','{dir}'),{}, function(a){
				js.setmsg('nont');
				arr = a;
				arr.push({
					'name':'添加应用',
					'face':'images/jia.png',
					'num' :'add',
					'id'  : 0
				});
				$('#showlistyingyong').html('');
				$('#showlistyingyongtt').html('');
				c.showlist();
			},'get,json');
		},
		showlist:function(){
			var i=0,len=arr.length,s='',hd,s1='';
			for(i=0;i<len;i++){
				hd=1;
				if(arr[i].valid==0)hd='0.3';
				s='';
				s+='<li style="opacity:'+hd+'" onclick="yingyongedit('+i+',0)">';
				s+='<div><img width="50" height="50" src="'+arr[i].face+'"></div>';
				s+='<div>'+arr[i].name+'</div>';
				if(arr[i].id>0)s+='<div style="font-size:10px"><a href="javascript:;" onclick="yingyongedit('+i+',1)">编辑</a> &nbsp; <a href="javascript:;" onclick="yingyongmenu('+i+')">菜单</a></div>';
				s+='</li>';
				if(arr[i].valid==0){
					$('#showlistyingyongtt').append(s);
				}else{
					$('#showlistyingyong').append(s);
				}
				
			}
			
		},
		reload:function(){
			c.loadlist();
		}
	}
	
	yingyongedit=function(i, lx){
		var a=arr[i];
		if(lx==0&&a.id>0)return;
		listyingyongobj = c;
		addtabs({num:'yingyongedit'+a.id+'',url:'main,yingyong,edit,id='+a.id+'',name:'应用['+a.name+']'});
	}
	yingyongmenu=function(i){
		var a=arr[i];
		addtabs({num:'yingyongmenu'+a.id+'',url:'main,yingyong,menu,mid='+a.id+'',name:'应用['+a.name+']菜单'});
	}
	
	c.loadlist();
});
</script>
<style>
.divlisssa li{float:left;padding:10px;text-align:center;width:100px;overflow:hidden;cursor:pointer}
.divlisssa li:hover{ background-color:#f1f1f1}
.divlisssa ul,.divlisssa{display:inline-block;width:100%}
</style>
<div style="padding:0px 10px">
	<h3>已有应用</h3>
	<div class="blank1"></div>
	<div class="divlisssa" style="padding:10px"><ul id="showlistyingyong"></ul></div>
	
	<h3>已停用</h3>
	<div class="blank1"></div>
	<div class="divlisssa" style="padding:10px"><ul id="showlistyingyongtt"></ul></div>
	
	

</div>
