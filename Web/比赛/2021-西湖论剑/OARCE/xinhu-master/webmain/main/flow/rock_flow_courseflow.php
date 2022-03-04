<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var setid=params.setid;
	
	$('#mainv{rand}').css('height',''+viewheight-20+'px');
});

</script>
<div>
</div>
<div align="center" id="mainv{rand}" style="background:url(images/cropbg.gif);position:relative">
	<div style="left:10px;top:20px" class="rf rf_ract"><div style="padding:10px">图形1</div></div>
	<div style="left:10px;top:100px" class="rf rf_yuan"><div style="padding:10px">图形1</div></div>
	
	<div style="left:80px;top:100px;transform:rotate(deg)" class="rf_shu">
		<div class="rf_shu1"></div>
		<div class="rf_shu2"></div>
	</div>
</div>