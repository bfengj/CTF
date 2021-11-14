<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="description" content="<?php echo webinfo('web_description');?>">
<meta name="keywords" content="<?php echo webinfo('web_keywords');?>">
<title><?php if(webinfo('web_index_name')){?><?php echo webinfo('web_index_name');?><?php }else{?><?php echo webinfo('web_name');?><?php }?></title>
<link href="<?php cmspath('template');?>/images/style.css" rel="stylesheet" type="text/css">
<link href="<?php cmspath('template');?>/images/skin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php cmspath('template');?>/images/jquery.js"></script>
<script type="text/javascript" src="<?php cmspath('template');?>/images/nav.js"></script>
<script type="text/javascript" src="<?php cmspath('template');?>/images/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php cmspath('template');?>/images/slides.min.jquery.js"></script>
<script type="text/javascript">

$(function(){
	
	
	
})

</script>

</head>
<body>
<?php $this->display('head',1,1);?>
<div class="flash">
	<?php $flash=get_flash($tpl_id='2');?>
	<div id="focus" class="flash_div">
				<div class="hd">
					<ul></ul>
				</div>
				<div class="bd">
					<ul>
					<?php 
 $fun_return=$flash;if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $v){?>
							<li><a href="<?php echo $v['url'];?>"><img src="<?php echo $v['pic'];?>" /></a></li>
					<?php 
}
}?>
					</ul>
				</div>
			</div>
	<script type="text/javascript">
				TouchSlide({ 
					slideCell:"#focus",
					titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
					mainCell:".bd ul", 
					effect:"left", 
					autoPlay:true,//自动播放
					autoPage:true, //自动分页
					switchLoad:"_src" //切换加载，真实图片路径为"_src" 
				});
			</script>
	
</div>

<div class="div_lan">
	<a href="">关于我们</a>
</div>
<div class="index_about">
	<div class="div_padding">
		<?php echo get_block_content('about_us');?>	
	</div>
	<div class="clear"></div>
</div>
<?php $pr=get_tpl_cate_content($tpl_id='3',$limit='0,6',$order_type='id',$filter='',$pic='no',$order='desc',$lang='');?>
<div class="div_lan">
	<a href="">产品中心</a>
</div>
<div class="index_pr">
	<ul>
	<?php 
 $fun_return=$pr['contents'];if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $v){?>
		<li><a href="<?php echo $v['url'];?>"><img src="<?php echo $v['thumb_pic'];?>" border="0" style="border:1px solid #ddd" /><p><?php echo $v['title'];?></p></a></li>
	<?php 
}
}?>	
	</ul>
	<div class="clear"></div>
</div>
<?php $index_news=get_tpl_cate_content($tpl_id='2',$limit='0,7',$order_type='',$filter='',$pic='no',$order='',$lang='');?>
<div class="div_lan">
	<a href="">最新动态</a>
</div>
<div class="index_news">
	<ul>
	<?php 
 $fun_return=$index_news['contents'];if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $v){?>
		<li><a href="<?php echo $v['url'];?>"><?php echo $v['title'];?></a></li>
		<?php 
}
}?>	
	</ul>
</div>
<div class="div_lan">
	<a href="">联系我们</a>
</div>
<div class="index_about">
	<div class="div_padding">
		<?php echo get_block_content('contact_us');?>
	</div>
	<div class="clear"></div>
</div>

<?php $this->display('foot',1,1);?>
</body>
</html>