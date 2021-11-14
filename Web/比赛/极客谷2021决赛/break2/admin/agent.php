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

		document.myform.action="action.php?act=delagent";

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

		$quyu     = trim($_REQUEST['quyu']);

		$shuyu     = trim($_REQUEST['shuyu']);
		$weixin     = trim($_REQUEST['weixin']);
		$phone     = trim($_REQUEST['phone']);
		$name     = trim($_REQUEST['name']);

		$dengji     = trim($_REQUEST['dengji']);
		$sqdengji     = trim($_REQUEST['sqdengji']);
		$shzt     = trim($_REQUEST['shzt']);
		$sjdl     = trim($_REQUEST['sjdl']);

    $udengji = trim($_REQUEST["udengji"]);
    $ucheck = trim($_REQUEST["ucheck"]);
    $usjdl = trim($_REQUEST["usjdl"]);

		$h       = trim($_REQUEST["h"]);

		$pz      = trim($_REQUEST['pz']);

		$sql="select * from tgs_agent where 1";

		if($agentid!=""){

		 $sql.=" and agentid like '%$agentid%'";

		}

		if($phone != ""){

		 $sql.=" and phone like '%$phone%'";

		}

		if($weixin!=""){

		 $sql.=" and weixin like '%$weixin%'";

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

		if($dengji!=""){

		 $sql.=" and dengji like '%$dengji%'";

		}

		if($sqdengji!=""){

		 $sql.=" and sqdengji<>''";

		}
		if($shzt!=""){

		 $sql.=" and shzt like '%$shzt%'";

		}

		if($sjdl!=""){

		 $sql.=" and sjdl = '$sjdl'";

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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 代理商管理 <span class="c-gray en">&gt;</span> 代理商列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table cellpadding="3" cellspacing="0" class="table_98">

		 <form action="?" method="post" name="form1">

		  <tr>

			<td >代理商编号：<input type="text" style="width:100px" class="input-text" name="agentid" size="20" value="<?=$agentid?>" />手机号：<input type="text" style="width:100px" class="input-text" name="phone" size="10"  value="<?=$phone?>">微信号：<input style="width:100px" class="input-text" type="text" name="weixin" size="10" value="<?=$weixin?>">姓名：<input style="width:100px" class="input-text" type="text" name="name" size="10" value="<?=$name?>" />

            上级代理ID：<input style="width:60px" class="input-text" type="text" name="sjdl" size="10" value="<?=$sjdl?>" />

            代理等级：<span class="select-box inline">
<select  name="dengji" id="dengji" class="select" >
<option value="">不限</option>
                   <?php
foreach ($dengjiOpt as $key => $value) {
  echo '<option value="'.$value.'">'.$value.'</option>';
}
?>



            </select></span>

                      审核状态：<span class="select-box inline">
<select  name="shzt" id="shzt" class="select" >

<option value="">不限</option>
<option value="2">未审核</option>
<option value="1">已审核</option>
 </select></span>

                     升级处理：<span class="select-box inline">
<select  name="sqdengji" id="sqdengji" class="select" >

<option value="">不限</option>
<option value="2">待处理</option>
 </select></span>
			  <input type="hidden" name="pz" id="pz" value="<?=$pz?>" />

			  <input name="submit" class="btn btn-success" type="submit" id="submit" value="查找"> </td>

		  </tr>

		 </form>

	  </table>


	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"> <a href="agent.php?act=add"  class="btn btn-primary radius" data-title="添加代理商"><i class="Hui-iconfont"><font color="#ffffff">&#xe600;</font></i><font color="#ffffff">添加代理商</font> </a></span> <span class="r"> </span> </div>
	<div class="mt-20">




        <table align="center" cellpadding="0" cellspacing="0" class="table_98" >

  <tr>

    <td valign="top">





	<form method="post" name="myform" id="myform" action="?" onsubmit="return ConfirmDel();">

	<input type="hidden" name="agentid" value="<?=$agentid?>" />

	<input type="hidden" name="product" value="<?=$product?>" />

	<input type="hidden" name="quyu" value="<?=$quyu?>" />

	<input type="hidden" name="shuyu" value="<?=$shuyu?>" />

	<input type="hidden" name="h" value="<?=$h?>" />

	<table cellpadding="3" cellspacing="0">

        <tr>

          <td height="20"><input class="Hui-iconfont" name="check" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete'" >

		  <input name="check1" type='submit' value='导出选定的记录' onclick="document.myform.Action.value='export_agent'" >

		  </td>

		  <td align="right" style="text-align:right !important">

		    显示条数 <input style="width:50px" class="input-text" type="text" name="pz" id="pz" value="<?=$pagesize?>" size="8" onchange="javascript:submit()" /> &nbsp;&nbsp;&nbsp;&nbsp;

		      当前第<?=$currpage?>页, 共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

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
<span class="select-box inline">
			  <select name='page' size='1' id="page" class="select" onchange='javascript:submit()'>

			  <?php

			  for($i=1;$i<=$totalpage;$i++)

			  {

			  ?>

			   <option value="<?php echo $i; ?>" <?php if ($currpage==$i) echo "selected"; ?>> 第<?php echo $i;?>页</option>

			   <?php }?>

			   </select></span>

			  </td>

        </tr>

    </table>

      <table cellpadding="3" cellspacing="1"  class="table table-border table-bordered table-bg table-hover table-sort" >

		<tr>

          <td width="5%"><strong>
            <INPUT TYPE="checkbox" NAME="chkAll" id="chkAll" title="全选"  onclick="CheckAll(this.form)">
            &nbsp;全选</strong></td>

          <td width="4%"><strong>序号</strong></td>
          <td width="2%"><strong>ID </strong></td>

		  <td width="7%"><strong>代理商编号</strong></td>
		  <td width="7%"><strong>姓名</strong></td>
        <td width="7%"><strong>统计</strong></td>
		  <td width="7%"><strong>手机</strong></td>
		  <td width="7%"><strong>微信号</strong></td>

          <td width="7%"><strong>代理等级</strong></td>

           <td width="9%"><strong>上级代理</strong></td>


		  <td width="7%"><strong>代理结束时间</strong></td>
		  <td width="7%"><strong>代理升级</strong></td>
		  <td width="7%"><strong>状态</strong></td>
		  <td width="7%">

		    <strong>
		    <?php

		  if($_GET["h"]==1){

		  ?>

		    <a href="?bianhao=<?=$bianhao?>&product=<?=$product?>&zd1=<?=$zd1?>&zd2=<?=$zd2?>&h=0&pz=<?=$pz?>&page=<?=$currpage?>">查询次数</a>

		    <?php }else{ ?>

		    <a href="?bianhao=<?=$bianhao?>&product=<?=$product?>&zd1=<?=$zd1?>&zd2=<?=$zd2?>&h=1&pz=<?=$pz?>&page=<?=$currpage?>">查询次数</a>

		    <?php

		  }

		  ?>

		    </strong></td>
		  <td width="9%"><strong>操作</strong></td>

		</tr>

		<?php for($i=0;$i<count($code_list);$i++){?>

        <tr >

          <td><input name="chk[]" type="checkbox" id="chk[]" value="<?php echo $code_list[$i]["id"];?>"></td>

		  <td><?=$i+1?></td>
		  <td><?=$code_list[$i]['id']?></td>

          <td><a href="?act=edit&id=<?php echo $code_list[$i]["id"];?>" title="编辑该代理商"><?php echo $code_list[$i]["agentid"];?></a></td>
          <td><?php echo $code_list[$i]["name"]?></td>

  <td><?php
$sql3="select * from tgs_agent where sjdl ='".$code_list[$i]["id"]."'";
$query3=mysql_query($sql3);
$totala3=mysql_num_rows($query3);
?>
    下级：（<a href="?usjdl=<?php echo $code_list[$i]["id"]?>"><?php echo $totala3; ?>
    </a>)</br>
    团队：（<a href="listdengji.php?id=<?=$code_list[$i]['id']?>">统计
    </a>)</td>
          <td><?php echo $code_list[$i]["phone"]?></td>
          <td><?php echo $code_list[$i]["weixin"]?></td>

          <td><?php echo $code_list[$i]["dengji"]?></td>
 <td>

  <?php
   $sjdlaa=$code_list[$i]["sjdl"];
$sql="select * from tgs_agent where id='$sjdlaa' limit 1";

	//echo $sql;

	$result=mysql_query($sql);

	$arra=mysql_fetch_array($result);

	$sjdlname      = $arra["name"];
	$sjdlid      = $arra["id"];
	$sjdlweixin      = $arra["weixin"];

?>
         微信号：<?php echo $sjdlweixin?>     <br />
姓名：<?php echo $sjdlname?>     ID：<?php echo $sjdlid?>
 </td>
		  <td><?php echo $code_list[$i]["jietime"]?>


		    <?php
		  $zero1=date("y-m-d h:i:s");
$zero2=$code_list[$i]["jietime"];
if(strtotime($zero2)<strtotime($zero1)){
echo "<strong><font color='#990000'>已过期</font></strong>";

 }
		  ?>
		    </td>
		  <td> <?php
		if ($code_list[$i]["dkpic"]<>'')
		{
		$dkpicaa=$code_list[$i]["dkpic"];
		echo "<a href=\"../mblogin/$dkpicaa\" target=\"_blank\">查看打款截图</a>";
			}
		else {
			echo 未上传打款图;
			}

		 ?><br />


          <?php
		   if ($code_list[$i]["sqdengji"]<>'')
{?>

  申请时间：<?=$code_list[$i]["sqtime"]?><br />
         申请等级：<?=$code_list[$i]["sqdengji"]?><br />
 <a href="action.php?act=save_sqdengji&sqdj=<?php echo $code_list[$i]["sqdengji"]?>&editid=<?php echo $code_list[$i]["id"];?>" title="确认升级"> 确认升级</a><br><br>

    <a href="action.php?act=del_sqdengji&sqdj=<?php echo $code_list[$i]["sqdengji"]?>&editid=<?php echo $code_list[$i]["id"];?>" title="取消升级"> 取消升级</a><br>
<?php }
 ?>


       </td>
		  <td><?php
		$shnr='<span class="label label-success radius">未审核</span>';
		$ashzt='1';
		if ($code_list[$i]["shzt"]==1)
		{$shnr='<span class="label label-success radius">已审核</span>';
		$ashzt='2';
			}


		 ?>
		    <?php
		$hmda='';
		if ($code_list[$i]["hmd"]==1)
		{$hmda='<strong></br>黑名单</srong>';

			}

		 ?>
		    <a href="action.php?act=save_agentsh&amp;shzta=<?php echo $ashzt;?>&amp;editid=<?php echo $code_list[$i]["id"];?>"><?php echo $shnr;?></a>&nbsp;<?php echo $hmda;?><br /></td>
		  <td><?php echo $code_list[$i]["hits"];?></td>
		<td class="f-14 td-manage">


         <a href="?act=edit&id=<?php echo $code_list[$i]["id"];?>" title="编辑该代理商"><i class="Hui-iconfont">&#xe6df;</i></a> &nbsp;&nbsp; <a target="_blank" href="../zs.php?act=query&keyword=<?php echo $code_list[$i]["weixin"]?>&submit=查询&search=&yzm_status=0" title="下载证书"><i class="Hui-iconfont">&#xe638;</i></a></td>

        </tr>

		<?php

		}

		?>

		</table>



		<table cellpadding="3" cellspacing="0" class="table_98">

		<tr><td >

		<INPUT TYPE="checkbox" NAME="chkAll2" id="chkAll2" title="全选"  onclick="CheckAll2(this.form)">&nbsp;全选

			  <input name="check2" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete'" >

			  <input name="Action" type="hidden" id="Action" value="">

			  <input name="check3" type='submit' value='导出选定的记录' onclick="document.myform.Action.value='export_agent'" >

	       </td>

		   <td align="right" style="text-align:right !important">



			  当前第<?=$currpage?>页,&nbsp;共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

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
  </div>
</div>






<?php

}

/////导出/////

if($act == "export_agent")

{

?>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 代理商管理 <span class="c-gray en">&gt;</span> 导出提示 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>商品代理商导出提示</b></td>

  </tr>

  <tr>

    <td>

	<ul class="exli">

	 <li>1、“导出”方式直接生成CSV格式文档。</li>

	 <li>2、请注意导入的文档编码，支持“ANSI简体中文”和“UTF-8”编码两种文档，请使用Ms Excel、 Notepad++、 EditPlus等软件打开和编辑文档。</li>

	 <li>3、csv文档均以英文逗号做为分隔符。</li>

	 <li>4、如果你是备份代理商信息，下边的选项请全部选择。</li>

	 </ul>

	</td>

  </tr>

  <form name="form1" enctype="multipart/form-data" method="post" action="export.php?act=export_agent" target="_blank">

  <tr>

    <td style="line-height:30px;">代理信息：

        <input type="hidden" name="chk" id="chk" value="<?=implode(",",$_POST['chk'])?>" />

		<input type="checkbox" name="field_agentid" id="field_agentid" value="1" checked="checked" />代理商编号

		<input type="checkbox" name="field_name" id="field_name" value="1" checked="checked" />姓名

		<input type="checkbox" name="field_idcard" id="field_idcard" value="1" checked="checked" />身份证号

		<input type="checkbox" name="field_weixin" id="field_weixin" value="1" checked="checked" />微信

		<input type="checkbox" name="field_phone" id="field_phone" value="1" checked="checked" />手机

		<input type="checkbox" name="field_qq" id="field_qq" value="1" checked="checked" />QQ

		<input type="checkbox" name="field_quyu" id="field_quyu" value="1" checked="checked" />代理区域

		<input type="checkbox" name="field_dengji" id="field_dengji" value="1" checked="checked" />代理等级
		<input type="checkbox" name="field_sjdl" id="field_sjdl" value="1" checked="checked" />上级代理

		<input type="checkbox" name="field_addtime" id="field_addtime" value="1" checked="checked" />代理开始时间

		<input type="checkbox" name="field_jietime" id="field_jietime" value="1" checked="checked" />代理结束时间
		<input type="checkbox" name="field_beizhu" id="field_beizhu" value="1" checked="checked" />备注

	</td>

    </tr>



   <tr>

   <td>文档编码

   <select name="file_encoding">

	<option value="gbk">简体中文</option>

	<option value="utf8">UTF-8</option>

   </select>

   <input type="submit" name="Submit" value=" 导出代理 "> （导出会转行）

   </td>

   </tr>

   </form>

</table>
</article>


<?php

}

///////导入////////////

if($act =="import"){

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 代理商管理 <span class="c-gray en">&gt;</span> 编辑代理商 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>导入代理商提示</b></td>

  </tr>

  <tr>

    <td>

	<ul class="exli">

	<li>1、“导入”方式支持 XLS、CSV、TXT三种格式文档，请按：<b><a href="../data/exemple/xls_agent_list.xls"><span class="red">XLS格式文件</span></a></b>、<b><a href="../data/exemple/csv_agent_list.csv"><span class="red">CSV格式文件</span></a></b>、<b><a href="../data/exemple/txt_agent_list.txt"><span class="red">TXT格式文件</span></a></b>，制作合适导入的标准文档,如果下载文档时是打开网页那请使用“右键另存为”下载文档。</li>

	<li>2、上述三个文档均为 “ANSI” 简体中文编码文档，在“导入”时选择“文档编码”为"UTF－8"导入时会有乱码。</li>

	<li>3、csv和txt文档均以英文逗号做为分隔符。</li>

	<li>4、程序对上传的文件大小不做限制，但一般空间都会有一个默认限制，一般为2M，所以上传的文件尽量小于2M,新生成的防伪码尽量分批上传。建议每次上传1000条。</li>

	<li>5、三个格式文档第一行的标题栏请不要删除，程序在导入过程中自动省略第一行。 </li>

	<li>6、如果用之前“导出选定的记录”导出的文档且是标准五项参数的文档，可直接导入。</li>

	</ul>

	</td>

  </tr>

  <tr>

    <td><form name="form1" enctype="multipart/form-data" method="post" action="action.php?act=save_uplod">

        文档编码：

		<label>

		<select name="file_encoding">

			<option value="gbk">简体中文</option>

			<option value="utf8">UTF-8</option>

		</select>

		</label>



		<label>

		<input type="file" name="file">

        </label>

      <label>

      <input class="btn btn-primary radius"  type="submit" name="Submit" value="上传代理">



      </label>

    </form>

    </td>

  </tr>

</table>
</article>




<?php

}



////////添加////////////

if($act == "add"){

?>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 代理商管理 <span class="c-gray en">&gt;</span> 添加代理商 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>


<article class="page-container">

<table align="center" cellpadding="0" cellspacing="0"  class="table_98">

  <tr>

    <td valign="top">

	<form class="form form-horizontal" name="formagent" method="post" enctype="multipart/form-data" action="action.php?act=Addagent">

       <table cellpadding="3" cellspacing="1"  class="table_50">

          <tr>

            <td colspan="2" align="center"><strong>添加代理商信息</strong></td>

		  </tr>

          <tr>

            <td width="20%"> 代理编号：</td>

            <td width="80%">

              <input type="hidden" name="hmd" size="50" value="2">

             <input type="hidden" name="shzt" size="50" value="1">
              <?php
$dingdanhao = date("i-s");
$dingdanhao = str_replace("-","",$dingdanhao);
$dingdanhao .= rand(1000,999999);
?>


          <input style="width:300px" class="input-text" name="agentid" type="text" id="agentid" size="50" value="<?=$cf['agentqz']?><?php echo $dingdanhao ?>">
*</td>

          </tr>
 <tr>

            <td>姓 名：</td>

            <td><input style="width:300px" class="input-text" type="text" name="name" size="50" value="">
              *</td>

          </tr>
          <tr>

            <td>身份证号：</td>

            <td><input style="width:300px" class="input-text" type="text" name="idcard" size="50" value=""></td>

          </tr>
           <tr>

            <td>微 信：</td>

            <td><input style="width:300px" class="input-text" type="text" name="weixin" size="50">
* 经销商登录名为微信号</td>

          </tr>
           <tr>

            <td>密 码：</td>

            <td><input style="width:300px" class="input-text" type="text" name="password" value="12345678" size="50">
* </td>

          </tr>

           <tr >

            <td>手 机：</td>

            <td><input style="width:300px" class="input-text" type="text" name="phone" size="50" value="">
</td>

          </tr>
           <tr>

            <td>Q Q：</td>

            <td><input style="width:300px" class="input-text" type="text" name="qq" size="50" value=""></td>

          </tr>
          <tr>

            <td> 代理产品：</td>

            <td>
            <span style="width:300px" class="select-box"> <select   name="product" class="select">

        <?php

		 $sql = "select * from tgs_product order by jibie DESC";

		 $res = mysql_query($sql);


		 while($arr = mysql_fetch_array($res)){

		?>

     <option value="<?php echo $arr["proname"];?>"><?php echo $arr["proname"];?></option>



		<?php

		}




		?>

            </select></span>

            </td>

          </tr>
          <tr>

            <td> 代理区域：</td>

            <td><input style="width:300px" class="input-text" name="quyu" type="text" size="50" value=""></td>

          </tr>




<tr>

            <td>上级代理微信号：</td>

            <td>

          <input style="width:300px" class="input-text" type="text" name="sjdl" size="30" value="">
           </td>

          </tr>
		  <tr>

            <td>代理等级：</td>

            <td>
            <span style="width:300px" class="select-box"> <select   name="dengji" class="select">
         <?php
foreach ($dengjiOpt as $key => $value) {
  echo '<option value="'.$value.'">'.$value.'</option>';
}
?>
            </select></span>
           </td>

          </tr>

		  <tr>

            <td>开始代理日期：</td>

            <td><input style="width:300px" class="input-text" type="text" name="addtime" value="<?php echo date('Y-m-d',time());?>">
            <input  type="hidden" name="applytime" value="<?php echo date('Y-m-d h:s',time());?>">
*</td>

          </tr>

		  <tr>

            <td>代理结束日期：</td>
<?php
$time = time(); //当前时间戳
$datea = date('Y',$time) + 1 . '-' . date('m-d');//一年后日期
?>


            <td><input style="width:300px" class="input-text" type="text" name="jietime" value="<?php echo $datea;?>">
*</td>

          </tr>

		  <tr>

            <td>备注：</td>

            <td><textarea style="width:400px" class="textarea" name="beizhu" id="beizhu" cols="50" rows="5"></textarea></td>

          </tr>

          <tr>

            <td>&nbsp;</td>

            <td>

            <input class="btn btn-primary radius" type="submit" name="Submit" value=" 保存并提交" ></td>

          </tr>

        </table>

	  </form>

	  </td>

  </tr>

</table>



</article>




<?php

}

////为编辑获取数据

if($act == "edit"){

    $editid = $_GET["id"];

	$sql="select * from tgs_agent where id='$editid' limit 1";

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


<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 代理商管理 <span class="c-gray en">&gt;</span> 编辑代理商 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>


<article class="page-container">

<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	<form name="form1" method="post" enctype="multipart/form-data" action="action.php?act=save_agentedit">

        <table cellpadding="3" cellspacing="1"  class="table_50">

		  <tr>

            <td colspan="2" align="left"><strong><?php echo $rn?></strong>

            <input name="editid" type="hidden" id="editid" value="<?php echo $editid?>"></td>

		  </tr>

          <tr>

            <td width="20%"> 代理编号：</td>

            <td width="80%"><input style="width:300px" class="input-text" name="agentid" type="text" id="agentid" size="50" value="<?php echo $agentid?>" ></td>

          </tr>

           <tr>

            <td>姓名：</td>

            <td><input style="width:300px" class="input-text" type="text" name="name" size="50" value="<?php echo $name?>"></td>

          </tr>
           <tr>

            <td>身份证号：</td>

            <td><input style="width:300px" class="input-text" type="text" name="idcard" size="50" value="<?php echo $idcard?>" autocomplete="off"></td>

          </tr>
  <tr>

            <td>微信：</td>

            <td><input style="width:300px" class="input-text" type="text" name="weixin" size="50" value="<?php echo $weixin?>" autocomplete="off"> 开通后代理将使用微信号登录</td>

          </tr>
            <tr>

            <td>登录密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="password" size="50" value="<?php echo $password?>" autocomplete="off"> 如无需修改请不要改动，初始密码为12345678</td>

          </tr>
           <tr >

            <td>手机：</td>

            <td><input style="width:300px" class="input-text" type="text" name="phone" size="50" value="<?php echo $phone?>" autocomplete="off"></td>

          </tr>

          <tr>

            <td> 代理产品：</td>

            <td><input style="width:300px" class="input-text" name="product" type="text" size="50" value="<?php echo $product?>" autocomplete="off"></td>

          </tr>
           <tr>

            <td> 代理区域：</td>

            <td><input style="width:300px" class="input-text" name="quyu" type="text" size="50" value="<?php echo $quyu?>"></td>

          </tr>

		  <tr>

            <td>QQ：</td>

            <td><input  style="width:300px" class="input-text" type="text" name="qq" size="50" value="<?php echo $qq?>"></td>

          </tr>
           <tr>

            <td>代理等级：</td>

            <td>

               <span style="width:300px" class="select-box">  <select name="dengji" id="dengji" >


<?php
foreach ($dengjiArr as $key => $value) {
	$aselect="";
	if($dengji==$value) {

		$aselect="selected='selected'";

		};

		echo $value;

  echo '<option '.$aselect.' value="'.$value.'">'.$value.'</option>';
}
?>


                    </select></span></td>

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
            <input style="width:300px" class="input-text" type="text" name="sjdl" size="50" value="<?php echo $sjdlweixin ?>">
             姓名：<?php echo $sjdlname?>     ID：<?php echo $sjdlid?> </td>

          </tr>


		  <tr>

            <td>开始代理日期：</td>

            <td><input style="width:300px" class="input-text" type="text" name="addtime" size="50" value="<?php echo $addtime?>"></td>

          </tr>

		  <tr>

            <td>代理结束日期：</td>

            <td><input style="width:300px" class="input-text" type="text" name="jietime" size="50" value="<?php echo $jietime?>"></td>

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

            <td><textarea style="width:400px" class="textarea" name="beizhu" id="beizhu" cols="50" rows="5"><?php echo $beizhu?></textarea></td>

          </tr>

          <tr>

            <td>&nbsp;</td>

            <td> <input class="btn btn-primary radius" type="submit" name="Submit" value=" 保存修改" ></td>

          </tr>

        </table>

	  </form>

	  </td>

  </tr>

</table>
</article>
<?php

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

	if(document.myform.Action.value=="delete_history")

	{

		document.myform.action="?act=delete_history";

		if(confirm("确定要删除选中的记录吗？本操作不可恢复！"))

		    return true;

		else

			return false;

	}

}



</SCRIPT>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 代理商管理 <span class="c-gray en">&gt;</span> 防伪查询记录 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_list_98">

  <tr>

    <td valign="top">



		<table cellpadding="3" cellspacing="0" class="table_98">

		 <form action="action.php?act=query_record" method="post" name="form1">

		  <tr>

			<td>查询记录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>

		  </tr>

		 </form>

		</table>

	<form method="post" name="myform" id="myform" action="action.php?act=query_record" onsubmit="return ConfirmDel();">

	<input type="hidden" name="key" value="<?=$key?>" />

	<table cellpadding="3" cellspacing="0" class="table table-border table-bordered table-bg table-hover table-sort">

        <tr>

          <td height="20"><input name="check" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete_history'" ><span class='red'>(*请定期清理查询记录)</span></td>

		  <td align="right" style="text-align:right !important">





		      当前第<?=$currpage?>页, 共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

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

			  <select name='page' size='1' id="page" onchange='javascript:submit()'>

			  <?php

			  for($i=1;$i<=$totalpage;$i++)

			  {

			  ?>

			   <option value="<?php echo $i; ?>" <?php if ($currpage==$i) echo "selected"; ?>> 第<?php echo $i;?>页</option>

			   <?php }?>

			   </select>

			 </td>

        </tr>

    </table>



      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg table-hover table-sort">



		<tr>

          <td width="10%"><INPUT TYPE="checkbox" NAME="chkAll" id="chkAll" title="全选"  onclick="CheckAll(this.form)">&nbsp;全选</td>

		  <td width="10%">序号</td>

          <td width="20%">搜索关键字</td>

          <td width="20%">搜索日期</td>

          <td width="20%">搜索IP</td>

		</tr>

		<?php for($i=0;$i<count($code_list);$i++){?>

        <tr >

          <td><input name="chk[]" type="checkbox" id="chk[]" value="<?php echo $code_list[$i]["id"];?>">&nbsp;</td>

		  <td><?php echo $i+1;?></td>

          <td><?php echo $code_list[$i]["keyword"];?></td>

          <td><?php echo $code_list[$i]["addtime"]?></td>

          <td>来自：<script src="http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=<?php echo $code_list[$i]["addip"]?>"></script>

		  <script>document.write(remote_ip_info.country + ' - ' + remote_ip_info.province + ' - ' + remote_ip_info.city);</script> IP地址：

		  <a href="http://ip.taobao.com/ipSearch.php?ipAddr=<?php echo $code_list[$i]["addip"]?>" title="点击查看地区" target="_blank"><?php echo $code_list[$i]["addip"]?></a></td>

        </tr>

		<?php

		}

		?>

		</table>



		<table cellpadding="3" cellspacing="0" class="table_98">

		<tr><td >

		      <INPUT TYPE="checkbox" NAME="chkAll2" id="chkAll2" title="全选"  onclick="CheckAll2(this.form)">&nbsp;全选

			  <input name="check" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete_history'" >

			  <input name="Action" type="hidden" id="Action" value="">

	       </td>

		   <td align="right" style="text-align:right !important">

			  当前第<?=$currpage?>页,&nbsp;共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

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
    </div></div>



<?php

}



/////删除查询记录

if($act == "delete_history")

{

	$chk = $_REQUEST["chk"];

	if(count($chk)>0){

	  $countchk = count($chk);

		for($i=0;$i<=$countchk;$i++)

		{

		 //echo  $chk[$i]."<br>";

		  mysql_query("delete from tgs_hisagent where id='$chk[$i]' limit 1");

		}

		echo "<script>alert('删除成功');location.href='?act=query_record'</script>";

	}

}

?>

<?php

//csv读取函数

function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"')

{

      $d = preg_quote($d);

      $e = preg_quote($e);

      $_line = "";

      $eof   = false;

      while ($eof != true)

      {

         $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));

         $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);

         if ($itemcnt % 2 == 0)

            $eof = true;

      }

      $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));      $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';

      preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

      $_csv_data = $_csv_matches[1];

      for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++)

      {       $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);

         $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);

      }

      return empty($_line) ? false : $_csv_data;

}

?>

</body>

</html>
