
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
		
		$sql="select * from tgs_agent where sjdl in ($chaxunc) and  sqdengji <>''";		

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

          <td width="15%" >序号</td>
          <td width="42%" >代理信息</td>
          <td width="43%" >操作</td>
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
		    
		    上级：<?php echo $sjdlweixin?>（<?php echo $sjdlname?>）<br />

            
            等级：<?php echo $code_list[$i]["dengji"]?><br>
            
            申请时间：<?php echo $code_list[$i]["sqtime"]?><br />

        申请等级：<?php echo $code_list[$i]["sqdengji"]?><br />
		    </td>
		  <td>
	
<br>
         <?php 
		if ($code_list[$i]["dkpic"]<>'')
		{
		$dkpicaa=$code_list[$i]["dkpic"];
		echo "<a href=\"../mblogin/$dkpicaa\">查看打款截图</a>";
			}
		else {
			echo 未上传打款图;
			}
		
		 ?>
        <br />
<br />


  <a href="dengjish.php?act=save_sqdengji&sqdj=<?php echo $code_list[$i]["sqdengji"]?>&editid=<? echo $code_list[$i]["id"];?>" title="确认升级"> 确认升级</a><br><br>
  <br />

    <a href="dengjish.php?act=del_sqdengji&sqdj=<?php echo $code_list[$i]["sqdengji"]?>&editid=<? echo $code_list[$i]["id"];?>" title="取消升级"> 取消升级</a><br>

           </td>
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







/////审核代理申请//////////


if($act == "save_sqdengji"){


    $editid     = $_REQUEST["editid"]; 
	
     $sqdj     = $_REQUEST["sqdj"]; 
	 
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

	

	$sql="update tgs_agent set dengji='$sqdj',sqdengji='' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);


	
	echo "<script>alert('确认升级成功');location.href='?'</script>";

	

	exit; 



}



/////删除请求记录//////////


if($act == "del_sqdengji"){


    $editid     = $_REQUEST["editid"]; 
	
     $sqdj     = $_REQUEST["sqdj"]; 
	 
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

	

	$sql="update tgs_agent set sqdengji='' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);


	
	echo "<script>alert('删除请求记录成功');location.href='?'</script>";

	

	exit; 



}

?>

</div> 
 </section>



          

    </div>
</body>

</html>