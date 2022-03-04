<?php 
/**
*	桌面首页项(底部版权提示)
*	我们系统的开源，你们可以直接使用，给点面子不，版权信息就留着吧不要去掉；当然拉，你不给面子可以去掉或修改。
*	欢迎来官网www.rockoa.com下咨询，去掉了就无法在线升级了。
*/
defined('HOST') or die ('not access');

?>

<div class="tishi" align="center">Copyright &copy;<?=date('Y')?>  &nbsp;<?=substr(URLY,7,-1)?> &nbsp;<?=TITLE?>v<?=VERSION?> &nbsp;版权所有：<a href="<?=URLY?>" id="homefooter" target="_blank">信呼开发团队</a></div>