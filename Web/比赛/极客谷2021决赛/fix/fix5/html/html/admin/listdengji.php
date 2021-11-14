<?php

include 'head.php';

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
<html><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=3.0, user-scalable=yes">

<meta content="yes" name="apple-mobile-web-app-capable">

<meta content="black" name="apple-mobile-web-app-status-bar-style">

<meta content="telephone=no" name="format-detection">

<title>

	数据统计

</title>

<style>
.tabobxx {
	width: 300px;

	padding-top: 100px;
	
    padding-left:200px;
}
</style>

</head>

<body> 

  <div class="tabobxx">

    <div id="section_container">

        <section id="index_section" class="active">


           <div class="scroll-area-list" id="codelListArea">
  <div class="weinxPlane">
  
      <table width="70%" border="0">
  <tbody>
    <tr>
      <td><strong>等级名称</strong></td>
      <td><strong>统计</strong></td>
      <td>&nbsp;</td>
    </tr>
   

      
      
      <?php

		 $sqldj = "select djname from tgs_dengji where djname<>'' order by jibie DESC";

		 $resdj = mysql_query($sqldj);
		 
         
		 
		 while($arr = mysql_fetch_array($resdj)){	
		$djname1 .= $arr['djname'].' ';
		 }
		$djname =$djname1;
		
		?>
        
        
        

		<?php

		 $sql = "select * from tgs_dengji where djname<>'' order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

     
          <tr>
      <td><?php echo $arr["djname"];?></td>
      <td><?php
$sql="select * from tgs_agent where sjdl in ($chaxunc) and dengji='".$arr["djname"]."'";

$query=mysql_query($sql);
$total=mysql_num_rows($query);
echo $total;
?></td>
      <td>&nbsp;</td>
    </tr>
        

		<?php

		}
		
		

	
		?>
      
        
        <tr>
      <td>我的直接代理</td>
      <td>  <?php
$sql="select * from tgs_agent where sjdl in ($chaxunc) and sjdl='".$aac."'";

$query=mysql_query($sql);
$total=mysql_num_rows($query);
echo $total;
?></td>
      <td>&nbsp;</td>
    </tr>
    
     <tr>
      <td colspan="3"><strong><a href="agent.php?">返回</a></strong></td>
      </tr>
   
  </tbody>
</table>
  </div>
</div>

 

            

            



        </section>





    </div> 

    







</div>



</body></html>