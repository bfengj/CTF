<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?php echo content('info');?>">
<meta name="keywords" content="<?php echo content('keywords');?>">
<title><?php echo content('small_title');?>_<?php echo cateinfo('cate_title_seo');?>_<?php echo webinfo('web_name');?></title>
<link href="<?php cmspath('template');?>/images/style.css" rel="stylesheet" type="text/css">
<link href="<?php cmspath('template');?>/images/MagicZoom.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php cmspath('template');?>/images/mz-packed.js"></script>
<script type="text/javascript" src="<?php cmspath('template');?>/images/jquery.js"></script>
<script type="text/javascript" src="<?php cmspath('template');?>/images/nav.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.q_body ul li').click(function(){
		$index=$('.q_body ul li').index(this);
		$(this).addClass('focus').siblings().removeClass('focus');
		$('.show_q_body').find('#body').eq($index).show().siblings().hide();
	});
});
</script>
</head>
<body>
<?php $this->display('head',1,1);?>

<div class="div_lan">
	<a href="">【<?php echo content('title');?>】详细内容</a>
</div>
<div class="content_div">
	
	 <div class="arc_body">
	  <?php 
 $fun_return=album('pics');if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $v){?>
	 <p><img src="<?php echo $v['pic'];?>"/></p>
	  <?php 
}
}?>
 <table width='100%'>
	<tr><td><?php echo content('content');?></td></tr>
  </table>
  </div>
  <div class="page_fy" style="float:none;margin-left:300px;">
    <?php echo body_pages();?>
  </div>

 <div class="arc_link" style="clear:both">
 <?php $prev=content('prev');?>
 <div class="arc_link_div"><?php if($prev){?><span>上一篇：<a href="<?php echo $prev['url'];?>"><?php echo $prev['title'];?></a></span><?php }else{?><span>上一篇：没有了</span><?php }?></div>
  <?php $next=content('next');?>
  <div class="arc_link_div"><?php if($next){?><span>下一篇：<a href="<?php echo $next['url'];?>"><?php echo $next['title'];?></a></span><?php }else{?><span>下一篇：没有了</span><?php }?></div>
 </div>

</div>
<?php $pr=get_tpl_cate_content($tpl_id='3',$limit='0,4',$order_type='id',$filter='',$pic='no',$order='desc',$lang='');?>
<div class="div_lan">
	<a href="">推荐产品</a>
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



<?php $this->display('foot',1,1);?>
</body>
</html>