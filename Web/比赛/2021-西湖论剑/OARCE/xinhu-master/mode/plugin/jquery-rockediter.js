/**
	编辑器
	caratename：chenxihu
	caratetime：214-09-01 21:40:00
	email:qqqq2900@126.com
	homepage:www.xh829.com
*/
var rockediterarray	= [];
(function ($) {
	
	function rockediter(contid, can){
		
		var me			= this;
		this.selecttext	= '';
		var obj			= get(contid);
		var rand		= js.getrand();
		this.oi			= rockediterarray.length;
		
		this.formatarray={
			'font':['字体样式','FontName', 'down'],
			'fontsize':['字体大小','FontSize', 'down'],
			'forecolor':['字体颜色','ForeColor', 'down'],
			'marks':['插入特殊符号','Marks'],
			'images':['上传图片','Images'],
			'imageslink':['插入图片链接','Imageslink'],
			'backcolor':['字体背景色','BackColor', 'down'],
			'html':['查看源码','HTML'],
			'arrow_out':['全屏',''],
			'emot':['插入表情',''],
			'cut':['剪切','Cut'],
			'paste':['粘贴','Paste'],
			'copy':['复制','Copy'],
			'selectall':['全选','SelectAll'],
			'hr':['添加水平线','InsertHorizontalRule'],
			'link_add':['添加链接','CreateLink'],
			'link_del':['删除链接','UnLink'],
			'bold':['加粗','Bold'],
			'italic':['斜体','Italic'],
			'underline':['下划线','Underline'],
			'strikethrough':['删除线','StrikeThrough'],
			'align_center':['居中对齐','JustifyCenter'],
			'align_justify':['两端对齐','JustifyFull'],
			'align_left':['左对齐','JustifyLeft'],
			'align_right':['右对齐','JustifyRight'],
			'list_bullets':['项目符号','insertunorderedlist'],
			'list_numbers':['数字编号','insertorderedlist'],
			'indent':['增加缩进量','Indent'],
			'indent_remove':['减小缩进量','Outdent'],
			'undo':['返回上一步','Undo'],
			'removeformat':['删除格式','RemoveFormat']
		}
		
		//初始化
		this.init	= function(){
			this.createtbar();
			$('#'+contid+'_tbar a').click(function(event){
				me.toolsclick(event, this);
				return false;
			});
		};
		
		this.createtbar	= function(){
			var a	=	can.tbaricons.split(',');
			var s	= '';
			for(var i=0; i<a.length; i++){
				var s1	= a[i];
				if(s1=='|'){
					s+='<span>|</span>';
				}else{	
					var c	= this.formatarray[s1];
					s+='<a title="'+c[0]+'" href="javascript:" xtype="'+c[1]+'"><img src="images/editer/text_'+s1+'.png" class="icons" align="absmiddle">';
					if(can.showtext)s+=' '+c[0]+'';
					if(c[2] == 'down')s+='<img src="images/editer/desc.gif" style="margin-left:3px" align="absmiddle">';
					s+='</a>';
				}
			}
			$('#'+contid+'_tbar').html(s);
		};
			
		this.toolsclick	= function(event, o1){
			var o		= $(o1);
			var xtype	= o.attr('xtype');
			this.selecttext	= this.getSelText();
			if(xtype == 'Bold'){
				this.strreplace(this.selecttext, '[B]'+this.selecttext+'[/B]');
			}
			if(xtype == 'Italic'){
				this.strreplace(this.selecttext, '[I]'+this.selecttext+'[/I]');
			}
			if(xtype == 'Underline'){
				this.strreplace(this.selecttext, '[U]'+this.selecttext+'[/U]');
			}
			
			if(xtype == 'RemoveFormat'){
				this.removeformat();
			}
			if(xtype == 'CreateLink'){
				this.createlink();
			}
			if(xtype == 'Images'){
				this.uploadImages();
			}
		};
		
		this.createlink	= function(){
			var h	= '<div style="padding:10px;line-height:30px">';
			h+='<div style="padding:5px 0px"><font color="#888888">连接文字</font> <input id="createlinkwindow_text" style="width:50%" class="input" value="'+this.selecttext+'"></div>';
			h+='<div style="padding:5px 0px"><font color="#888888">连接地址</font> <input id="createlinkwindow_texturl" value="http://" class="input" style="width:78%"></div>';
			h+='</div>';
			js.tanbody('createlinkwindow', '添加连接', 400, 200, {html:h,btn:[{text:'确定'}]});	
			get('createlinkwindow_texturl').focus();
			$('#createlinkwindow_btn0').click(function(){
				me.quedingadd();
			});
		};
		this.quedingadd	= function(){
			var text	= $('#createlinkwindow_text').val();
			var url		= $('#createlinkwindow_texturl').val();
			var str1	= '[A,'+url+']'+text+'[/A]';
			if(isempt(text))return false;
			this.strreplace(text,str1);
			js.tanclose('createlinkwindow');
		};
		
		//清除格式
		this.removeformat	= function(){
			var cont	= obj.value;
			cont		= cont.replace(/\[.*?\]/gi, '');
			cont		= cont.replace(/\<.*?\>/gi, '');
			obj.value	= cont;
		};
		
		this.addcont	= function(str){
			obj.value+=str;
		};
		
		this.strreplace	= function(str,str1){
			if(isempt(str) || !get(contid))return false;
			var cont	= obj.value;
			var s		= '';
			if(isIE){
				var patt1	= new RegExp(""+str+"", "gi");
				s			= cont.replace(patt1, str1);
			}else{
				var st	= obj.selectionStart;
				var et	= obj.selectionEnd;
				s	= cont.substr(0, st)+str1;
				s+=cont.substr(et);
			}
			get(contid).value	= s;
			if(!isIE)get(contid).focus();
		};
		this.getSelText	= function(){
			var o	= obj;
			var txt	= '';
			if(isIE){
				txt	= document.selection.createRange().text;
			} else {
				txt	= o.value.substr(o.selectionStart,o.selectionEnd-o.selectionStart);
			}
			return txt;
		};
		
		//删除图片
		this.uploadImages= function(){
			var h	= '<div style="padding:10px;line-height:30px">';
			h+='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
			h+='<tr>';
			h+=' 	<td width="110" height="110" align="center"><img id="createlinkwindow_imagesview" src="images/noface.jpg" width="100" height="100"></td>';
			h+='	<td style="padding:5px 10px;line-height:30px">';
			h+='		<div style="padding:5px 0px"><font color="#888888">图片地址：</font> &nbsp; <a href="javascript:" onclick="return rockediteruploadimage()">↑从本地上传</a></div>';
			h+='		<div style="padding:0px 0px"><input  id="createlinkwindow_images" class="input" style="width:250px"></div>';
			//h+='		<div style="height:20px;overflow:hidden"></div>';
			//h+='		<div style="padding:5px 0px">宽：<input class="inputs" id="createlinkwindow_width" readonly style="width:60px"> &nbsp; 高：<input id="createlinkwindow_height" class="inputs"  readonly style="width:60px"></div>';
			h+='	</td>';
			h+='</tr>';
			h+='</table>';
			h+='</div>';
			js.tanbody('createlinkwindow', '插入图片', 400, 200, {
				html:h,btn:[{text:'确定'}]
			});	
			$('#createlinkwindow_btn0').click(function(){
				me.uploadImagesback();
			});
		};
		this.uploadImagesback= function(){
			var url	= get('createlinkwindow_images').value;
			if(isempt(url))return false;
			this.addcont('[IMG,'+url+']');
			js.tanclose('createlinkwindow');
			return false;
		};
	}
	
	$.rockediter	= function(contid,can){
		var fcan	= js.apply({
			showtext	: true,
			tbaricons	: 'bold,italic,underline,|,link_add,images,removeformat'
		}, can);
		var clsa = new rockediter(contid, fcan);
		clsa.init();
		rockediterarray.push(clsa);
		return clsa;
	};
	
})(jQuery);

function rockediteruploadimage(){
	js.upload('rockediteruploadimageautoclose',{title:escape('本地上传图片'),maxup:1,uptype:'image',maxwidth:600});
	return false;
}
function rockediteruploadimageautoclose(a){
	var d	= a[0];
	//$('#createlinkwindow_width').val(d.width);
	//$('#createlinkwindow_height').val(d.height);
	$('#createlinkwindow_images').val(d.thumbpath);
	get('createlinkwindow_imagesview').src=d.thumbpath;
}