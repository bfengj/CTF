<div class="foot">
	<div class="pw"><?php echo webinfo('web_powerby');?></div>
	<div class="lang">
		<?php 
 $fun_return=lang();if(isset($fun_return)&&is_array($fun_return)){
foreach($fun_return as $v){?>
		<a href="<?php echo $v['url'];?>" <?php echo $v['class'];?> <?php echo $v['target'];?>><?php echo $v['lang_name'];?></a>
		<?php 
}
}?>
	</div>

</div>

</div>