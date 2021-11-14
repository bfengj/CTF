
<?php

include 'head.php';
  


$act = $_GET["act"];

//////列表////

if($act == "")

{

?>

<SCRIPT language="javascript">

function CheckAll(form)

  {

  for (var i=0;i<form.elements.length;i++)

    {

    var e = form.elements[i];

    if (e.Name != "chkAll"&&e.disabled==false)

       e.checked = form.chkAll.checked;

    }

  }

function CheckAll2(form)

  {

  for (var i=0;i<form.elements.length;i++)

    {

    var e = form.elements[i];

    if (e.Name != "chkAll2"&&e.disabled==false)

       e.checked = form.chkAll2.checked;

    }

  }   

function ConfirmDel()

{

	if(document.myform.Action.value=="delete")

	{

		document.myform.action="?act=delagent";

		if(confirm("确定要删除选中的记录吗？本操作不可恢复！"))

		    return true;

		else

			return false;

	}else if(document.myform.Action.value=="export_agent"){

	  document.myform.action="?act=export_agent";

	

	}

}

</SCRIPT>


         
   
 
<?php		

        $code_list = array();

		$agentid = trim($_REQUEST["agentid"]);

		$product = trim($_REQUEST['product']);
		$weixin     = trim($_REQUEST['weixin']);
		$phone     = trim($_REQUEST['phone']);
		$name     = trim($_REQUEST['name']);

		$quyu     = trim($_REQUEST['quyu']);

		$shuyu     = trim($_REQUEST['shuyu']);

		$h       = trim($_REQUEST["h"]);

		$pz      = trim($_REQUEST['pz']);
		
		$sql="select * from tgs_agent where sjdl in ($chaxunc) and shzt=2";		

		if($agentid!=""){

		 $sql.=" and agentid like '%$agentid%'";

		}

		if($phone != ""){

		 $sql.=" and phone like '%$phone%'";

		}

		if($name!=""){

		 $sql.=" and name like '%$name%'";

		}

		if($weixin!=""){

		 $sql.=" and weixin like '%$weixin%'";

		}

		if($h == "1"){

		 $sql.=" order by hits desc,id desc";

		}

		elseif($h=="0"){

		 $sql.=" order by hits asc,id desc";

		}

		else{

		 $sql.=" order by id desc";

		}

		///echo $sql;

		$result = mysql_query($sql);



	   if($pz == ""){

         $pagesize = $cf['list_num'];//每页所要显示的数据个数。

		 $pz       = $cf['list_num'];

	   }

	   else{

	     $pagesize = $pz;

	   }

       $total    = mysql_num_rows($result); 	

       $filename = "?agentid=".$agentid."&product=".$product."&quyu=".$quyu."&shuyu=".$shuyu."&h=".$h."&pz=".$pz."";

    

      $currpage  = intval($_REQUEST["page"]);

      if(!is_int($currpage))

	    $currpage=1;

	  if(intval($currpage)<1)$currpage=1;

      if(intval($currpage-1)*$pagesize>$total)$currpage=1;



	  if(($total%$pagesize)==0){

		$totalpage=intval($total/$pagesize); 

	   }

	  else

	    $totalpage=intval($total/$pagesize)+1;

	  if ($total!=0&&$currpage>1)

       mysql_data_seek($result,(($currpage-1)*$pagesize));



       $i=0;

     while($arr=mysql_fetch_array($result)) 

     { 

     $i++;

     if($i>$pagesize)break; 

      $code_list[] = $arr;

	 }

?>



<table align="center" cellpadding="0" cellspacing="0" class="table_list_98">

  <tr>

    <td valign="top">

		

			

	<form method="post" name="myform" id="myform" action="?" onsubmit="return ConfirmDel();">	

	<input type="hidden" name="agentid" value="<?=$agentid?>" />

	<input type="hidden" name="product" value="<?=$product?>" />

	<input type="hidden" name="quyu" value="<?=$quyu?>" />

	<input type="hidden" name="shuyu" value="<?=$shuyu?>" />

	<input type="hidden" name="h" value="<?=$h?>" />

	

      <table width="100%" cellpadding="3" cellspacing="1" class="table_98">        

		<tr>

          <td >序号</td>
          <td >代理信息</td>
          <td >操作</td>
          </tr>
     
		<?php for($i=0;$i<count($code_list);$i++){?>

        <tr >
 

          <td><?=$i+1?></td>
		  <td>
          <?php
          //获取上级代理信息

   $sjdlaa=$code_list[$i]["sjdl"];
$sql="select * from tgs_agent where id='$sjdlaa' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arra=mysql_fetch_array($result);

	$sjdlname      = $arra["name"];
	$sjdlid      = $arra["id"];
	$sjdlweixin      = $arra["weixin"];
	?>
    
    
		    编号：<?php echo $code_list[$i]["agentid"]?><br>
		    姓名：<?php echo $code_list[$i]["name"]?><br>
		    
		    微信：<?php echo $code_list[$i]["weixin"]?><br>
		    
		    手机：<?php echo $code_list[$i]["phone"]?><br>
            
            等级：<?php echo $code_list[$i]["dengji"]?><br>
		    
		    上级：<?php echo $sjdlweixin?>（<?php echo $sjdlname?>）
		    </td>
		  <td>
		
          <a href="agentlist.php?act=edit&id=<? echo $code_list[$i]["id"];?>" title="编辑该代理商"> 编辑/查看</a><br><br>


		  <?php 
		$shnr='已审核';
		$ashzt='2';
		if ($code_list[$i]["shzt"]==2)
		{$shnr='<strong>未审核</srong>';
		$ashzt='1';
			}
		
		 ?>
		    <?php 
		$hmda='';
		if ($code_list[$i]["hmd"]==1)
		{$hmda='<strong></br>黑名单</srong>';
		
			}
		
		 ?>
		    <a href="?act=save_agentsh&shzta=<? echo $ashzt;?>&editid=<? echo $code_list[$i]["id"];?>" title="<? echo $shnr;?>"><? echo $shnr;?></a>&nbsp;<? echo $hmda;?><br>

             <?php 
		  $zero1=date("y-m-d h:i:s");   
$zero2=$code_list[$i]["jietime"];     
if(strtotime($zero2)<strtotime($zero1)){   
echo "<strong><font color='#990000'>已过期</font></strong>";
			   
 }
		  ?></td>
		  </tr>

		<?php

		}

		?>

		</table> 



		<table cellpadding="3" cellspacing="0" class="table_98">

		<tr><td  >
		  
		  <br />

		  
		  共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;
		  
		  
		  <?php if($currpage==1){?>
		  
		  首页&nbsp;上一页&nbsp;
		  
		  <?php } else {?>
		  
		  <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;
		  
		  <?php }

			  if($currpage==$totalpage)

			  {?>
		  
		  下一页&nbsp;尾页&nbsp;
		  
		  <?php }else{?>
		  
		  <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;
		  
		  <?php }?>
		  
		  </td>

	      </tr>		

      </table>	

	  </FORM>



	      

	</td>

  </tr>

</table>



<?php

}







/////审核//////////


if($act == "save_agentsh"){


$shzta = $_GET["shzta"];
    $editid     = $_REQUEST["editid"]; 
	

	$agentid      = trim($_REQUEST["agentid"]);


	

	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='?'</script>";

	  exit;

	}

	if($editid=="")

	{

	  echo "<script>alert('代理商编号不能为空');location.href='?act=edit&id=".$editid."'</script>";

	  exit;

	}

	//$sql="update tgs_code set bianhao='$bianhao',riqi='$riqi',product='$product',zd1='$zd1',zd2='$zd2' where id='$editid' limit 1";

	

	$sql="update tgs_agent set shzt='$shzta' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);

if ($shzta=="2")
{echo "<script>alert('取消审核成功');location.href='?'</script>";}
else {
	
	echo "<script>alert('审核成功');location.href='?'</script>";
	}

	

	exit; 



}




?>

</div> 
 </section>



          

    </div>
</body>

</html>