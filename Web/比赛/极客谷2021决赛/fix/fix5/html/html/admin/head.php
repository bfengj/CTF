<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
require('../data/reader.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?=$cf['site_name']?></title>
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<script type="text/javascript" src="style/js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" src="style/js/jquery.reveal.js"></script>

<link rel="stylesheet" href="style/js/reveal.css">	

<link href="style/js/style.css" rel="stylesheet" type="text/css">



<link href="../data/css/style.css" rel="stylesheet" type="text/css">


<link rel="stylesheet" href="../editor/themes/default/default.css" />
<script charset="utf-8" src="../editor/kindeditor-all.js"></script>
	
	
    
    

<script charset="utf-8" src="../editor/lang/zh-CN.js"></script>

	<link rel="stylesheet" href="../editor/plugins/code/prettify.css" />

	<script charset="utf-8" src="../editor/plugins/code/prettify.js"></script>
      
  
<script>

KindEditor.ready(function(K) {

	var editor = K.editor({

		uploadJson : '../editor/php/upload_json.php?TableField=web_article',

		fileManagerJson : '../editor/php/file_manager_json.php',

		showRemote : true,

		allowFileManager : true,

	});

	K('#PrUpload').click(function(){

		editor.loadPlugin('image', function(){

			editor.plugin.imageDialog({

				imageUrl : K('#proimg').val(),

				clickFn : function(url, title, width, height, border, align){

					K('#proimg').val(url);

					K('#prpicDetail').html('<img src="'+url+'" />');

					editor.hideDialog();

				}

			});

		});

	});


var editor1 = K.create('textarea[name="content1"]', {
				cssPath : '../editor/plugins/code/prettify.css',
				uploadJson : '../editor/php/upload_json.php',
				fileManagerJson : '../editor/php/file_manager_json.php',
				allowFileManager : true,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
				}
			});
			prettyPrint();
})

</script>

</head>
<body>

<?php
 ///判断安装文件 
 if (file_exists("../install.php"))
    {
        echo "<br><br><br><span class='red'><center><b>请删除或更改防伪程序的安装文件  install.php</b></center></span>";
		exit;
    }
?>



