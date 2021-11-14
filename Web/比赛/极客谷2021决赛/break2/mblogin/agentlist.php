
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

		$udengji = trim($_REQUEST["udengji"]);
		$ucheck = trim($_REQUEST["ucheck"]);
		$usjdl = trim($_REQUEST["usjdl"]);

		$product = trim($_REQUEST['product']);
		$weixin     = trim($_REQUEST['weixin']);
		$phone     = trim($_REQUEST['phone']);
		$name     = trim($_REQUEST['name']);

		$quyu     = trim($_REQUEST['quyu']);

		$shuyu     = trim($_REQUEST['shuyu']);

		$h       = trim($_REQUEST["h"]);

		$pz      = trim($_REQUEST['pz']);

		$sql="select * from tgs_agent where sjdl in ($chaxunc)";

		if($agentid!=""){

		 $sql.=" and agentid like '%$agentid%'";

		}

		if($phone != ""){

		 $sql.=" and phone like '%$phone%'";

		}
        if($udengji != ""){

		 $sql.=" and dengji like '%$udengji%'";

		}
		if($ucheck != ""){

		 $sql.=" and shzt like '%$ucheck%'";

		}
		if($usjdl != ""){

		 $sql.=" and sjdl = '$usjdl'";

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

          <td ><INPUT TYPE="checkbox" NAME="chkAll" id="chkAll" title="全选"  onclick="CheckAll(this.form)"></td>

          <td >序号</td>
          <td >代理信息</td>
          <td >状态</td>
          </tr>

		<?php for($i=0;$i<count($code_list);$i++){?>

        <tr >


          <td><input name="chk[]" type="checkbox" id="chk[]" value="<?php echo $code_list[$i]["id"];?>"></td>

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

            <strong><?php echo $code_list[$i]["name"]?> (微信: <?php echo $code_list[$i]["weixin"]?>)</strong><br>
		    编号：<?php echo $code_list[$i]["agentid"]?> <a href="agentlist.php?usjdl=<?php echo $code_list[$i]["id"]?>">下级(<font color="red">
            <?php
$sql3="select * from tgs_agent where sjdl ='".$code_list[$i]["id"]."'";
$query3=mysql_query($sql3);
$totala3=mysql_num_rows($query3);
echo $totala3;
?>

            </font>)</a><br>




		    手机：<?php echo $code_list[$i]["phone"]?><br>
            等级：<?php echo $code_list[$i]["dengji"]?><br>

		    上级：<?php echo $sjdlweixin?>（<?php echo $sjdlname?>）
		    </td>
		  <td>



        <?php

		if ($editper==1)
		{

		?>  <a href="?act=edit&id=<?php echo $code_list[$i]["id"];?>" title="编辑该代理商"> 编辑/查看</a><br><br>
        <?php }
	    else {
			?>  <a href="?act=view&id=<?php echo $code_list[$i]["id"];?>"> 浏览</a><br><br>
        <?php
			}

		 ?>

       <span><a href="zs.php?wx=<?php echo $code_list[$i]["weixin"];?>">查看授权</a>
       </sapn>

       <br /><br />



		  <?php
		$shnr='已审核';
		$ashzt='2';
		if ($code_list[$i]["shzt"]==2)
		{$shnr='未审核';
		$ashzt='1';
			}

		 ?>
		    <?php
		$hmda='';
		if ($code_list[$i]["hmd"]==1)
		{$hmda='<strong></br>黑名单</srong>';

			}

		 ?>

          <?php

		if ($checkper==1)
		{

		?> <a href="?act=save_agentsh&shzta=<?php echo $ashzt;?>&editid=<?php echo $code_list[$i]["id"];?>"><span class="label label-success radius"><?php echo $shnr;?></sapn></a>
        <?php }
	    else {
			?> <span class="label label-success radius"><?php echo $shnr;?></sapn>
        <?php
			}

		 ?>

		    <?php echo $hmda;?><br>

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

		<tr><td width="198"  >

		<INPUT TYPE="checkbox" NAME="chkAll2" id="chkAll2" title="全选"  onclick="CheckAll2(this.form)">&nbsp;全选<br />
		<br />
          <?php

		if ($delper==1)
		{

		?> <input class="alertify-button alertify-button-cancel" name="check2" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete'" >
        <?php }
	    else {
			?>
        <?php
			}

		 ?>



			  <input name="Action" type="hidden" id="Action" value=""></td>

		   <td width="305" align="right">



			 共<?=$totalpage?>页/<?php  echo $total;?>个记录<br>


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




////为编辑获取数据

if($act == "edit"){
    $huiyuanmb=$_SESSION["Adminname"];

    $editid = $_GET["id"];

	$sql="select * from tgs_agent where id='$editid' and sjdl in ($chaxunc) limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);


	$agentid      = $arr["agentid"];
	$idcard      = $arr["idcard"];
	$dengji      = $arr["dengji"];

	$product      = $arr["product"];

	$quyu         = $arr["quyu"];

	$shuyu        = $arr["shuyu"];
	$hmd        = $arr["hmd"];

	$sjdl        = $arr["sjdl"];

	$password        = $arr["password"];

	$qudao        = $arr["qudao"];

	$about        = $arr["about"];

	$addtime      = $arr["addtime"];

	$jietime      = $arr["jietime"];

	$name         = $arr["name"];

	$tel          = $arr["tel"];

	$fax          = $arr["fax"];

	$phone        = $arr["phone"];

	$danwei       = $arr["danwei"];

	$email        = $arr["email"];

	$url          = $arr["url"];

	$qq           = $arr["qq"];

	$weixin       = $arr["weixin"];

	$wangwang     = $arr["wangwang"];

	$paipai       = $arr["paipai"];

	$zip          = $arr["zip"];

	$dizhi          = $arr["dizhi"];

	$beizhu       = $arr["beizhu"];

	$rn         = "修改代理商信息";

｝

?>



<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_agentedit">

        <table cellpadding="3" cellspacing="1"  class="table_98">

		  <tr>

            <td colspan="2" align="left"><strong><?php echo $rn?></strong>

            <input name="editid" type="hidden" id="editid" value="<?php echo $editid?>"></td>

		  </tr>

          <tr>

            <td width="20%"> 代理编号：</td>

            <td width="80%"><input name="agentidaa" type="text" disabled="disabled" id="agentidaa" value="<?php echo $agentid?>" size="50" readOnly="true" ></td>

          </tr>

           <tr>

            <td>姓名：</td>

            <td><input type="text" name="name" size="50" value="<?php echo $name?>"></td>

          </tr>
            <tr>

            <td>身份证号：</td>

            <td><input type="text" name="idcard" size="50" value="<?php echo $idcard?>"></td>

          </tr>
  <tr>

            <td>微信：</td>

            <td><input name="weixinaa" type="text" disabled="disabled" value="<?php echo $weixin?>" size="50"><input name="weixin" type="hidden" id="weixin" value="<?php echo $weixin?>"></td>

          </tr>
            <tr >

            <td>手机：</td>

            <td><input type="text" name="phone" size="50" value="<?php echo $phone?>"></td>

          </tr>
            <tr>

            <td>QQ：</td>

            <td><input type="text" name="qq" size="50" value="<?php echo $qq?>"></td>

          </tr>
           <tr>

            <td>代理产品：</td>

            <td><input type="text" name="product" size="50" value="<?php echo $product?>"></td>

          </tr>
           <tr>

            <td>代理区域：</td>

            <td><input type="text" name="quyu" size="50" value="<?php echo $quyu?>"></td>

          </tr>

           <tr>

            <td>登录密码：</td>

            <td><input type="password" name="password" size="50" value="<?php echo $password?>"> 如无需修改请不要改动</td>

          </tr>
            <tr>

            <td>确认密码：</td>

            <td><input type="password" name="repassword" size="50" value="<?php echo $password?>"> 如无需修改请不要改动</td>

          </tr>




		  <tr>

            <td>代理等级：</td>

            <td>

             <?php

   //获取上级代理信息
$sql="select * from tgs_agent where id='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arra=mysql_fetch_array($result);



	$sjdlname      = $arra["name"];
	$sjdlid      = $arra["id"];
	$sjdlweixin      = $arra["weixin"];



    $akey = array_search($adengji, $dengjiArr);
    $adengjiOpt = array_slice($dengjiArr, $akey);

			 ?>





              <select name="dengji" id="dengji" style="width:98%">


<?php
foreach ($adengjiOpt as $akey => $value) {
	$aselect="";
	if($dengji==$value) {

		$aselect="selected='selected'";

		};



  echo '<option '.$aselect.' value="'.$value.'">'.$value.'</option>';
}
?>


                    </select>
            </td>

          </tr>

 <tr>

            <td>上级代理：</td>

            <td>

            <input name="sjdla" type="text" disabled="disabled" value="微信号：<?php echo $sjdlweixin ?> 姓名：<?php echo $sjdlname?>  " size="50"><input name="sjdl" type="hidden" id="sjdl" value="<?php echo $sjdl?>">
            </td>

          </tr>


		  <tr>

            <td>开始代理日期：</td>

            <td><input type="text" name="addtime" size="50" value="<?php echo $addtime?>"></td>

          </tr>

		  <tr>

            <td>代理结束日期：</td>

            <td><input type="text" name="jietime" size="50" value="<?php echo $jietime?>"></td>

          </tr>
 <tr>

            <td>是否黑名单：</td>

            <td>

           <input name="hmd" type="radio" value="1"
		   <?php
		   if($hmd==1)
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>

		     />加入黑名单

           <input name="hmd" type="radio" value="2"  <?php
		   if($hmd==1)
		   {echo "";}
           else   {echo "checked=checked;";}
		   ?> />不在黑名单


          </td>

          </tr>

		  <tr>

            <td>备注：</td>

            <td><textarea name="beizhu" id="beizhu" cols="50" rows="5"><?php echo $beizhu?></textarea></td>

          </tr>

          <tr>

            <td>&nbsp;</td>

            <td><input class="alertify-button alertify-button-cancel" type="submit" name="Submit" value=" 修 改 " ></td>

          </tr>

        </table>

	  </form>

    </td>

  </tr>

</table>



<?php

}

if($act == "view"){
    $huiyuanmb=$_SESSION["Adminname"];

    $editid = $_GET["id"];

	$sql="select * from tgs_agent where id='$editid' and sjdl in ($chaxunc) limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);


	$agentid      = $arr["agentid"];
	$idcard      = $arr["idcard"];
	$dengji      = $arr["dengji"];

	$product      = $arr["product"];

	$quyu         = $arr["quyu"];

	$shuyu        = $arr["shuyu"];
	$hmd        = $arr["hmd"];

	$sjdl        = $arr["sjdl"];
	$shzt        = $arr["shzt"];

	$password        = $arr["password"];

	$qudao        = $arr["qudao"];

	$about        = $arr["about"];

	$addtime      = $arr["addtime"];

	$jietime      = $arr["jietime"];

	$name         = $arr["name"];

	$tel          = $arr["tel"];

	$fax          = $arr["fax"];

	$phone        = $arr["phone"];

	$danwei       = $arr["danwei"];

	$email        = $arr["email"];

	$url          = $arr["url"];

	$qq           = $arr["qq"];

	$weixin       = $arr["weixin"];

	$wangwang     = $arr["wangwang"];

	$paipai       = $arr["paipai"];

	$zip          = $arr["zip"];

	$dizhi          = $arr["dizhi"];

	$beizhu       = $arr["beizhu"];

	$rn         = "浏览代理信息";

｝

?>



<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">



        <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg table-hover table-sort">

		  <tr>

            <td colspan="2" align="left"><strong><?php echo $rn;?></strong>

            <input name="editid" type="hidden" id="editid" value="<?php echo $editid;?>"></td>

		  </tr>

          <tr>

            <td width="20%"> 代理编号：</td>

            <td width="80%"><?php echo $agentid;?></td>

          </tr>

           <tr>

            <td>姓名：</td>

            <td><?php echo $name;?></td>

          </tr>
            <tr>

            <td>身份证号：</td>

            <td><?php echo $idcard;?></td>

          </tr>
  <tr>

            <td>微信：</td>

            <td><?php echo $weixin;?></td>

          </tr>
            <tr >

            <td>手机：</td>

            <td><?php echo $phone;?></td>

          </tr>
            <tr>

            <td>QQ：</td>

            <td><?php echo $qq; ?></td>

          </tr>
           <tr>

            <td>代理产品：</td>

            <td><?php echo $product; ?></td>

          </tr>
           <tr>

            <td>代理区域：</td>

            <td><?php echo $quyu; ?></td>

          </tr>





		  <tr>

            <td>代理等级：</td>

            <td>

            <?php echo $dengji?>
            </td>

          </tr>

             <?php

   //获取上级代理信息
$sql="select * from tgs_agent where id='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arra=mysql_fetch_array($result);



	$sjdlname      = $arra["name"];
	$sjdlid      = $arra["id"];
	$sjdlweixin      = $arra["weixin"];



    $akey = array_search($adengji, $dengjiArr);
    $adengjiOpt = array_slice($dengjiArr, $akey);

			 ?>


 <tr>

            <td>上级代理：</td>

            <td>

            微信号：<?php echo $sjdlweixin ?> 姓名：<?php echo $sjdlname?>
            </td>

          </tr>


		  <tr>

            <td>开始代理日期：</td>

            <td><?php echo $addtime?></td>

          </tr>

		  <tr>

            <td>代理结束日期：</td>

            <td><?php echo $jietime?></td>

          </tr>
 <tr>

            <td>状态：</td>

            <td>

         <?php
		   if($hmd==1)
		   {echo "黑名单";}
           else   {echo "";}
		   ?>

           <?php
		   if($shzt==1)
		   {echo "已审核";}
           else   {echo "未审核";}
		   ?>


          </td>

          </tr>

		  <tr>

            <td>备注：</td>

            <td><?php echo $beizhu?></td>

          </tr>



        </table>



    </td>

  </tr>

</table>



<?php

}


////为编辑获取数据

if($act == "per"){
    $huiyuanmb=$_SESSION["Adminname"];

    $editid = $_GET["id"];

	$sql="select * from tgs_agent where id='$editid' and weixin='$huiyuanmb' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);


	$agentid      = $arr["agentid"];
	$idcard      = $arr["idcard"];
	$dengji      = $arr["dengji"];

	$product      = $arr["product"];

	$quyu         = $arr["quyu"];

	$shuyu        = $arr["shuyu"];
	$hmd        = $arr["hmd"];

	$sjdl        = $arr["sjdl"];

	$password        = $arr["password"];

	$qudao        = $arr["qudao"];

	$about        = $arr["about"];

	$addtime      = $arr["addtime"];

	$jietime      = $arr["jietime"];

	$name         = $arr["name"];

	$tel          = $arr["tel"];

	$fax          = $arr["fax"];

	$phone        = $arr["phone"];

	$danwei       = $arr["danwei"];

	$email        = $arr["email"];

	$url          = $arr["url"];

	$qq           = $arr["qq"];

	$weixin       = $arr["weixin"];

	$wangwang     = $arr["wangwang"];

	$paipai       = $arr["paipai"];

	$zip          = $arr["zip"];

	$dizhi          = $arr["dizhi"];

	$beizhu       = $arr["beizhu"];

	$rn         = "修改个人资料";

｝

?>



<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_peredit">

        <table cellpadding="3" cellspacing="1"  class="table_98">

		  <tr>

            <td colspan="2" align="left"><strong><?php echo $rn?></strong>

            <input name="editid" type="hidden" id="editid" value="<?php echo $editid?>"></td>

		  </tr>

          <tr>

            <td width="20%"> 代理编号：</td>

            <td width="80%"><input name="agentidaa" type="text" disabled="disabled" id="agentidaa" value="<?php echo $agentid?>" size="50" readOnly="true" > <input name="agentid" type="hidden" id="agentid" value="<?php echo $agentid?>"></td>

          </tr>

           <tr>

            <td>姓名：</td>

            <td><input type="text" name="name" size="50" value="<?php echo $name?>"></td>

          </tr>
           <tr>

            <td>身份证号：</td>

            <td><input type="text" name="idcard" size="50" value="<?php echo $idcard?>"></td>

          </tr>
  <tr>

            <td>微信：</td>

            <td><input name="weixin" type="text" disabled="disabled" value="<?php echo $weixin?>" size="50"><input name="weixin" type="hidden" id="weixin" value="<?php echo $weixin?>"></td>

          </tr>
            <tr >

            <td>手机：</td>

            <td><input type="text" name="phone" size="50" value="<?php echo $phone?>"></td>

          </tr>



		  <tr>

            <td>QQ：</td>

            <td><input type="text" name="qq" size="50" value="<?php echo $qq?>"></td>

          </tr>
           <tr>

            <td>登录密码：</td>

            <td><input type="password" name="password" size="50" value="<?php echo $password?>"> 如无需修改请不要改动</td>

          </tr>
           <tr>

            <td>确认密码：</td>

            <td> <input type="password" name="repassword" size="50" value="<?php echo $password?>"></td>

          </tr>

 <tr>

            <td> 代理产品：</td>

            <td><input name="product" type="text" value="<?php echo $product; ?>" size="50" readonly></td>

          </tr>
		  <tr>

            <td> 代理区域：</td>

            <td><input name="quyu" type="text" value="<?php echo $quyu?>" size="50" readonly></td>

          </tr>

		 <tr>

            <td>代理等级：</td>

            <td>

            <input name="dengjiaa" type="text" disabled="disabled" value="<?php echo $dengji?>" size="50"> <input name="dengji" type="hidden" value="<?php echo $dengji?>" size="50"></td>

          </tr>
 <tr>

            <td>上级代理微信：</td>

            <td>
             <?php
$sql="select * from tgs_agent where id='$sjdl' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arra=mysql_fetch_array($result);

	$sjdlname      = $arra["name"];
	$sjdlid      = $arra["id"];
	$sjdlweixin      = $arra["weixin"];

?>
            <input name="sjdl" type="text" disabled="disabled" value="<?php echo $sjdlweixin ?>" size="50"><input name="sjdl" type="hidden" id="sjdl" value="<?php echo $sjdlid?>">
             姓名：<?php echo $sjdlname?>     ID：<?php echo $sjdlid?> </td>

          </tr>


		  <tr>

            <td>开始代理日期：</td>

            <td><input name="addtimeaa" type="text" disabled="disabled" value="<?php echo $addtime?>" size="50"><input name="addtime" type="hidden" value="<?php echo $addtime?>" size="50"></td>

          </tr>

		  <tr>

            <td>代理结束日期：</td>

            <td><input name="jietimeaa" type="text" disabled="disabled" value="<?php echo $jietime?>" size="50">
            <input name="jietime" type="hidden"  value="<?php echo $jietime?>" size="50"></td>

          </tr>




















          <tr>

            <td>&nbsp;</td>

            <td><input type="submit" name="Submit" value=" 修 改 " ></td>

          </tr>

        </table>

	  </form>

    </td>

  </tr>

</table>




<?php

}


/////修改代理信息//////////

if($act == "save_peredit"){

    $password   = trim($_POST["password"]);

	$repassword = trim($_POST["repassword"]);

    $editid     = $_REQUEST["editid"];

	$agentid      = trim($_REQUEST["agentid"]);
	$idcard      = trim($_REQUEST["idcard"]);
	$dengji      = trim($_REQUEST["dengji"]);

	$product      = trim($_REQUEST["product"]);

	$quyu         = trim($_REQUEST["quyu"]);

	$shuyu        = trim($_REQUEST["shuyu"]);

	$url          = strreplace(trim($_REQUEST["url"]));

	$qudao        = trim($_REQUEST["qudao"]);
	$hmd        = trim($_REQUEST["hmd"]);

	$password        = trim($_REQUEST["password"]);

	$about        = trim($_REQUEST["about"]);

	$addtime      = trim($_REQUEST["addtime"]);

	$jietime      = trim($_REQUEST["jietime"]);

	$name         = trim($_REQUEST["name"]);

	$tel          = trim($_REQUEST["tel"]);

	$fax          = trim($_REQUEST["fax"]);

	$phone        = trim($_REQUEST["phone"]);

	$danwei       = trim($_REQUEST["danwei"]);

	$email        = strreplace(trim($_REQUEST["email"]));

	$qq           = trim($_REQUEST["qq"]);

	$weixin       = trim($_REQUEST["weixin"]);

	$wangwang     = trim($_REQUEST["wangwang"]);

	$paipai       = trim($_REQUEST["paipai"]);

	$zip          = trim($_REQUEST["zip"]);

	$dizhi        = strreplace(trim($_REQUEST["dizhi"]));

	$beizhu       = strreplace(trim($_REQUEST["beizhu"]));

	if(strlen($password)<4){

			   echo "<script>alert('密码长度不能小于4位');window.location.href='?act=per&id=".$editid."'</script>";

			   exit;

		   }

		   if($password != $repassword)

		   {

			   echo "<script>alert('两次输入的密码不一致');window.location.href='?act=per&id=".$editid."'</script>";

			   exit;

		   }

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



	$sql="update tgs_agent set password = '".$password."',idcard = '".$idcard."',name='".$name."',phone='".$phone."',qq='".$qq."',dizhi='".$dizhi."' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);



	echo "<script>alert('修改成功');location.href='mbadmin.php'</script>";

	exit;



}

/////申请代理等级//////////



////为编辑申请代理等级获取数据

if($act == "sj"){
    $huiyuanmb=$_SESSION["Adminname"];

    $editid = $_GET["id"];

	$sql="select * from tgs_agent where id='$editid' and weixin='$huiyuanmb' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arr=mysql_fetch_array($result);


	$agentid      = $arr["agentid"];

	$dengji      = $arr["dengji"];

    $weixin       = $arr["weixin"];

	$name         = $arr["name"];

	$sjdl         = $arr["sjdl"];

	$sqdengji         = $arr["sqdengji"];
	$dkpic         = $arr["dkpic"];




	$rn         = "申请代理等级";

｝

?>



<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_sjdengji">

        <table cellpadding="3" cellspacing="1"  class="table_98">

		  <tr>

            <td colspan="2" align="left"><strong><?php echo $rn?></strong>

            <input name="editid" type="hidden" id="editid" value="<?php echo $editid?>"></td>

		  </tr>

          <tr>

            <td width="20%"> 代理编号：</td>

            <td width="80%"><input name="agentidaa" type="text" disabled="disabled" id="agentidaa" value="<?php echo $agentid?>" size="50" readOnly="true" > <input name="agentid" type="hidden" id="agentid" value="<?php echo $agentid?>">
            <input name="sqtime" type="hidden" id="sqtime" value="<?php echo date('y-m-d h:i:s',time());?>"></td>

          </tr>

           <tr>

            <td>姓名：</td>

            <td><input name="name" type="text" disabled="disabled" value="<?php echo $name?>" size="50"></td>

          </tr>
  <tr>

            <td>微信：</td>

            <td><input name="weixin" type="text" disabled="disabled" value="<?php echo $weixin?>" size="50"><input name="weixin" type="hidden" id="weixin" value="<?php echo $weixin?>"></td>

          </tr>

		 <tr>

            <td>目前等级：</td>

            <td>

            <input name="dengjiaa" type="text" disabled="disabled" value="<?php echo $dengji?>" size="50"> <input name="dengji" type="hidden" value="<?php echo $dengji?>" size="50"></td>

          </tr>

          <tr>

            <td>申请等级：</td>

            <td>



                <?php

   //获取上级代理信息
$sql="select * from tgs_agent where id='$sjdl' limit 1";


	$result=mysql_query($sql);

	$arra=mysql_fetch_array($result);

	$sjdldengji      = $arra["dengji"];





    $bkey = array_search($sjdldengji, $dengjiArr);
    $bdengjiOpt = array_slice($dengjiArr, $bkey);
//echo $sjdldengji;


			 ?>





              <select name="sqdengji" id="sqdengji" style="width:98%">


				<?php
foreach ($bdengjiOpt as $bkey => $value) {


  echo '<option value="'.$value.'">'.$value.'</option>';
}
?>


                    </select>

                     </td>

          </tr>

           <tr>

            <td>打款截图：</td>

            <td>

          <input type="file" name="upfile" id="fileField" />
          </td>

          </tr>




















          <tr>

            <td>&nbsp;</td>

            <td><input class="alertify-button alertify-button-cancel" type="submit" name="Submit" value=" 提交申请 " ></td>

          </tr>

        </table>

	  </form>

    </td>

  </tr>

</table>




<?php

}


/////申请代理等级//////////

if($act == "save_sjdengji"){

	 include 'test_upload_pic.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //判断是否有上传文件
    if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
        $upfile = $_FILES['upfile'];

        $name = $upfile['name'];    //文件名
        $type = $upfile['type']; //文件类型
        $size = $upfile['size']; //文件大小
        $tmp_name = $upfile['tmp_name'];  //临时文件
        $error = $upfile['error']; //出错原因
        if ($max_file_size < $size) { //判断文件的大小
            echo '上传文件太大';
            exit ();
        }
        if (!in_array($type, $uptypes)) {        //判断文件的类型
            echo '上传文件类型不符' . $type;
            exit ();
        }
        if (!file_exists($destination_folder)) {
            mkdir($destination_folder);
        }
        if (file_exists("upload/" . $_FILES["file"]["name"])) {

        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
            echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
        }
        $pinfo = pathinfo($name);
        $ftype = $pinfo['extension'];
        $destination = $destination_folder . time() . "." . $ftype;
        if (file_exists($destination) && $overwrite != true) {
            echo "同名的文件已经存在了";
            exit ();
        }
        if (!move_uploaded_file($tmp_name, $destination)) {
            echo "移动文件出错";
            exit ();
        }
        $pinfo = pathinfo($destination);
        $fname = $pinfo[basename];


        if ($watermark == 1) {
            $iinfo = getimagesize($destination, $iinfo);
            $nimage = imagecreatetruecolor($image_size[0], $image_size[1]);
            $white = imagecolorallocate($nimage, 255, 255, 255);
            $black = imagecolorallocate($nimage, 0, 0, 0);
            $red = imagecolorallocate($nimage, 255, 0, 0);
            imagefill($nimage, 0, 0, $white);
            switch ($iinfo[2]) {
                case 1 :
                    $simage = imagecreatefromgif($destination);
                    break;
                case 2 :
                    $simage = imagecreatefromjpeg($destination);
                    break;
                case 3 :
                    $simage = imagecreatefrompng($destination);
                    break;
                case 6 :
                    $simage = imagecreatefromwbmp($destination);
                    break;
                default :
                    die("不支持的文件类型");
                    exit;
            }
            imagecopy($nimage, $simage, 0, 0, 0, 0, $image_size[0], $image_size[1]);
            imagefilledrectangle($nimage, 1, $image_size[1] - 15, 80, $image_size[1], $white);
            switch ($watertype) {
                case 1 : //加水印字符串
                    imagestring($nimage, 2, 3, $image_size[1] - 15, $waterstring, $black);
                    break;
                case 2 : //加水印图片
                    $simage1 = imagecreatefromgif("xplore.gif");
                    imagecopy($nimage, $simage1, 0, 0, 0, 0, 85, 15);
                    imagedestroy($simage1);
                    break;
            }
            switch ($iinfo[2]) {
                case 1 :
                    //imagegif($nimage, $destination);
                    imagejpeg($nimage, $destination);
                    break;
                case 2 :
                    imagejpeg($nimage, $destination);
                    break;
                case 3 :
                    imagepng($nimage, $destination);
                    break;
                case 6 :
                    imagewbmp($nimage, $destination);
                    //imagejpeg($nimage, $destination);
                    break;
            }
            //覆盖原上传文件
            imagedestroy($nimage);
            imagedestroy($simage);
        }
  $dkpic      = $destination_folder . $fname;
    }

}


 
   $sqdengji      = trim($_REQUEST["sqdengji"]);

    $sqtime      = trim($_REQUEST["sqtime"]);


    $editid     = $_REQUEST["editid"];


	$agentid      = trim($_REQUEST["agentid"]);

	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='?'</script>";

	  exit;

	}




	$sql="update tgs_agent set sqdengji = '".$sqdengji."',sqtime = '".$sqtime."',dkpic = '".$dkpic."' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);



	echo "<script>alert('申请提交成功，请耐心等待上级代理审核');location.href='mbadmin.php'</script>";

	exit;



}



/////申请代理等级结束//////////

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


/////修改代理信息//////////

if($act == "save_agentedit"){

 $password   = trim($_POST["password"]);

	$repassword = trim($_POST["repassword"]);

    $editid     = $_REQUEST["editid"];

	$agentid      = trim($_REQUEST["agentid"]);
	$idcard      = trim($_REQUEST["idcard"]);
	$dengji      = trim($_REQUEST["dengji"]);

	$product      = trim($_REQUEST["product"]);

	$quyu         = trim($_REQUEST["quyu"]);

	$shuyu        = trim($_REQUEST["shuyu"]);

	$url          = strreplace(trim($_REQUEST["url"]));

	$qudao        = trim($_REQUEST["qudao"]);
	$hmd        = trim($_REQUEST["hmd"]);

	$password        = trim($_REQUEST["password"]);

	$about        = trim($_REQUEST["about"]);

	$addtime      = trim($_REQUEST["addtime"]);

	$jietime      = trim($_REQUEST["jietime"]);

	$name         = trim($_REQUEST["name"]);

	$tel          = trim($_REQUEST["tel"]);

	$fax          = trim($_REQUEST["fax"]);

	$phone        = trim($_REQUEST["phone"]);

	$danwei       = trim($_REQUEST["danwei"]);

	$email        = strreplace(trim($_REQUEST["email"]));

	$qq           = trim($_REQUEST["qq"]);

	$weixin       = trim($_REQUEST["weixin"]);

	$wangwang     = trim($_REQUEST["wangwang"]);

	$paipai       = trim($_REQUEST["paipai"]);

	$zip          = trim($_REQUEST["zip"]);

	$dizhi        = strreplace(trim($_REQUEST["dizhi"]));

	$beizhu       = strreplace(trim($_REQUEST["beizhu"]));

	if(strlen($password)<4){

			   echo "<script>alert('密码长度不能小于4位');window.location.href='?act=edit&id=".$editid."'</script>";

			   exit;

		   }

		   if($password != $repassword)

		   {

			   echo "<script>alert('两次输入的密码不一致');window.location.href='?act=edit&id=".$editid."'</script>";

			   exit;

		   }

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



	$sql="update tgs_agent set password = '".$password."',idcard = '".$idcard."',dengji = '".$dengji."',hmd = '".$hmd."',product='".$product."',quyu='".$quyu."',shuyu='".$shuyu."',qudao='".$qudao."',about='".$about."',addtime='".$addtime."',jietime='".$jietime."',name='".$name."',tel='".$tel."',fax='".$fax."',phone='".$phone."',danwei='".$danwei."',email='".$email."',url='".$url."',qq='".$qq."',weixin='".$weixin."',wangwang='".$wangwang."'where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);



	echo "<script>alert('修改成功');location.href='?'</script>";

	exit;



}



 /////多选或全选删除功能//////////////

if($act == "delagent"){

	$chk = $_REQUEST["chk"];

	if(count($chk)>0){

	  $countchk = count($chk);

		for($i=0;$i<=$countchk;$i++)

		{

		 //echo  $chk[$i]."<br>";

		  mysql_query("delete from tgs_agent where id='$chk[$i]' limit 1");

		}

		echo "<script>alert('删除成功');location.href='?'</script>";

	}

}



/////查询记录////////

if($act == "query_record")

{

  $code_list = array();

  $key       = trim($_REQUEST["key"]);

  $agpz        = trim($_REQUEST['agpz']);

  $sql="select * from tgs_hisagent where 1";

  if($key != ""){

    $sql.=" and keyword like '%$key%'";

  }

  $sql.=" order by id desc";

  ///echo $sql;

  $result=mysql_query($sql);

  if($agpz == ""){

    $pagesize = $cf['list_num'];//每页所要显示的数据个数。

	$agpz       = $cf['list_num'];

  }else{

	$pagesize = $agpz;

  }

  $total    = mysql_num_rows($result);

  $filename = "?act=query_record&keyword=".$key."&agpz=".$agpz."";

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


<?php

}



?>

</div>
 </section>





    </div>
</body>

</html>
