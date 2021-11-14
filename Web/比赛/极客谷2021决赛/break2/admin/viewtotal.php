<?php

error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
require('../data/reader.php');

//树形获取
function get_str($id = 0) {
    global $str;
    $sql = "select * from tgs_agent where sjdl= $id";
    $result = mysql_query($sql);//查询pid的子类的分类
    if($result && mysql_affected_rows()){//如果有子类

        while ($row = mysql_fetch_array($result)) { //循环记录集

            $str .= "" . $row['sjdl'] . ","; //构建字符串
            get_str($row['id']); //调用get_str()，将记录集中的id参数传入函数中，继续查询下级
        }

    }
    return $str;

}



	$aac      = $_GET[id];
	$chaxuna=get_str($aac);
	$chaxunb=1000000;
	$chaxunc ="$chaxuna$chaxunb";



?>
<?php
$sql="select * from tgs_agent where sjdl in ($chaxunc)";

$query=mysql_query($sql);
$total=mysql_num_rows($query);
?>
document.write('<?=$total?>');
