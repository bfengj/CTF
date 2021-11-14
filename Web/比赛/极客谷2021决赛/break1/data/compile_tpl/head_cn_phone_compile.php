<div class="ph_contain">

<div class="head">
	<a href="<?php cmspath('index');?>"><img src="<?php cmspath('cms');?>upload/<?php echo webinfo('web_logo');?>" border="0"/></a>
</div>
<div class="head_nav">
	<ul>
		<li class="<?php echo get_web_param('index_focus');?>"><a href="<?php cmspath('index');?>">首页</a></li>
		<?php 
 $fun_return=nav_middle();if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $nav_child){?> 
		<li class="<?php echo $nav_child['class'];?>"><a href="<?php echo $nav_child['url'];?>" <?php echo $nav_child['target'];?>><?php echo $nav_child['cate_name'];?></a></li>
		<?php 
}
}?>
	</ul>
	<div class="clear"></div>
</div>
