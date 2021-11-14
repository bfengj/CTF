<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?php echo cateinfo('cate_info_seo');?>">
<meta name="keywords" content="<?php echo cateinfo('cate_key_seo');?>">
<title><?php echo cateinfo('cate_title_seo');?>_<?php echo webinfo('web_name');?></title>
<link href="<?php cmspath('template');?>/images/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php cmspath('template');?>/images/jquery.js"></script>
<script type="text/javascript" src="<?php cmspath('template');?>/images/nav.js"></script>
</head>
<body>
<?php $this->display('head',1,1);?>

<div class="div_lan">
	<a href="">【<?php echo cateinfo('cate_name');?>】内容列表</a>
</div>
<div class="index_pr">
	<ul>
	<?php 
 $fun_return=list_article();if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $v){?>
		<li><a href="<?php echo $v['url'];?>"><img src="<?php echo $v['thumb_pic'];?>" border="0" style="border:1px solid #ddd" /><p><?php echo $v['title'];?></p></a></li>
	<?php 
}
}?>	
	</ul>
	<div class="clear"></div>
</div>

<div class="list_page">
   				<div class="page_fy">
    			<?php echo list_page();?>
   				</div>
  				</div>
				<div class="clear"></div>



<?php $this->display('foot',1,1);?>
</body>
</html>