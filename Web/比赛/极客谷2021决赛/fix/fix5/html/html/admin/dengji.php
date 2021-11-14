<?php

include 'head.php';


?>

<?php

$dldj     = trim($_REQUEST["dldj"]);







	
 $sql="update tgs_config set code_value='".$dldj."' where code='dldj' limit 1";


	mysql_query($sql);

	echo "<script>alert('同步等级成功！');location.href='admin.php?act=dengji'</script>";

	exit;
?>