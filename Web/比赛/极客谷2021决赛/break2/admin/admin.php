<?php

include 'head.php';

$act = $_GET["act"];

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

		document.myform.action="?act=delart";

		if(confirm("确定要删除选中的记录吗？本操作不可恢复！"))

		    return true;

		else

			return false;

	}
	
	else if(document.myform.Action.value=="export_code"){

	  document.myform.action="?act=export_code";

	

	}
	else if(document.myform.Action.value=="exportall_code"){

	  document.myform.action="?act=exportall_code";

	

	}

}

</SCRIPT>



<?php		

        $code_list = array();

		$bianhao = trim($_REQUEST["bianhao"]);

		$product = trim($_REQUEST['product']);

		$zd1     = trim($_REQUEST['zd1']);

		$zd2     = trim($_REQUEST['zd2']);

		$h       = trim($_REQUEST["h"]);

		$pz      = trim($_REQUEST['pz']);

		$sql="select * from tgs_code where 1";		

		if($bianhao!=""){

		 $sql.=" and bianhao like '%$bianhao%'";

		}

		if($product != ""){

		 $sql.=" and product like '%$product%'";

		}

		if($zd1!=""){

		 $sql.=" and zd1 like '%$zd1%'";

		}

		if($zd2!=""){

		 $sql.=" and zd2 like '%$zd2%'";

		}

		if($h == "1"){

		$sql.=" order by hits desc,id desc";

		}elseif($h=="0"){

		$sql.=" order by hits asc,id desc";

		}else{

		$sql.=" order by id desc";

		}

		///echo $sql;

		$result = mysql_query($sql);



	   if($pz == ""){

         $pagesize = $cf['list_num'];//每页所要显示的数据个数。

		 $pz       = $cf['list_num'];

	   }else{

	     $pagesize = $pz;

	   }

       $total    = mysql_num_rows($result); 	

       $filename = "?bianhao=".$bianhao."&product=".$product."&zd1=".$zd1."&zd2=".$zd2."&h=".$h."&pz=".$pz."";

    

      $currpage  = intval($_REQUEST["page"]);

      if(!is_int($currpage))

	    $currpage=1;

	  if(intval($currpage)<1)$currpage=1;

      if(intval($currpage-1)*$pagesize>$total)$currpage=1;



	  if(($total%$pagesize)==0)

	   {

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

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">防伪码管理</a> <span class="c-gray en">&gt;</span> 防伪码列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_list_98">

  <tr>

    <td valign="top">

		

		<table cellpadding="3" cellspacing="0" class="table_98">

		 <form action="?" method="post" name="form1">

		  <tr>

			<td >防伪码：<input style="width:100px" class="input-text" type="text" name="bianhao" size="20" value="<?=$bianhao?>" />产品类型：
             <span class="select-box inline"> <select class="select"   name="product" >
           <option value="">不限制产品</option>
  <?php

		 $sql9 = "select * from tgs_product order by jibie DESC";

		 $res9 = mysql_query($sql9);


		 while($arr9 = mysql_fetch_array($res9)){

		?>

     <option value="<?php echo $arr9["id"];?>"><?php echo $arr9["proname"];?></option>



		<?php

		}




		?>

            </select></span>
            保留字段1：<input style="width:100px" class="input-text" type="text" name="zd1" size="10" value="<?=$zd1?>">保留字段2：<input style="width:100px" class="input-text" type="text" name="zd2" size="10" value="<?=$zd2?>" />

			  <input type="hidden" name="pz" id="pz" value="<?=$pz?>" />

			  <input class="btn btn-success" name="submit" type="submit" id="submit" value="查找"> </td>

		  </tr>

		 </form>

		</table>	

	<form method="post" name="myform" id="myform" action="?" onsubmit="return ConfirmDel();">	

	<input type="hidden" name="bianhao" value="<?=$bianhao?>" />

	<input type="hidden" name="product" value="<?=$product?>" />

	<input type="hidden" name="zd1" value="<?=$zd1?>" />

	<input type="hidden" name="zd2" value="<?=$zd2?>" />

	<input type="hidden" name="h" value="<?=$h?>" />

	<table cellpadding="3" cellspacing="0" class="table_98">

        <tr>

          <td height="20"><input name="check" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete'" >

		  <input name="check1" type='submit' value='导出选定的记录' onclick="document.myform.Action.value='export_code'" >
          <input name="check1" type='submit' value='导出所有防伪码' onclick="document.myform.Action.value='exportall_code'" >

		  </td>

		  <td align="right" style="text-align:right !important">

		     显示条数 <input type="text" name="pz" id="pz" value="<?=$pagesize?>" size="8" onchange="javascript:submit()" /> &nbsp;&nbsp;&nbsp;&nbsp;

		     

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

          <td width="7%"><INPUT TYPE="checkbox" NAME="chkAll" id="chkAll" title="全选"  onclick="CheckAll(this.form)">&nbsp;全选</td>

          <td width="5%">本页序号</td>

		  <td width="6%">记录号</td>

		  <td width="11%">防伪码</td>

          <td width="14%">产品名称</td>

          <td width="12%">有效日期</td>

		  <td width="13%">保留字段1</td>

		  <td width="7%">保留字段2</td>
		  <td width="8%">二维码</td>

		  <td width="15%">

		  <?php

		  if($_GET["h"]==1){

		  ?>

		  <a href="?bianhao=<?=$bianhao?>&product=<?=$product?>&zd1=<?=$zd1?>&zd2=<?=$zd2?>&h=0&pz=<?=$pz?>&page=<?=$currpage?>">查询次数</a>

		  <?php }else{ ?>

		  <a href="?bianhao=<?=$bianhao?>&product=<?=$product?>&zd1=<?=$zd1?>&zd2=<?=$zd2?>&h=1&pz=<?=$pz?>&page=<?=$currpage?>">查询次数</a>

		  <?php

		  }

		  ?>

		  </td>

		</tr>

		<?php for($i=0;$i<count($code_list);$i++){?>

        <tr >

          <td><input name="chk[]" type="checkbox" id="chk[]" value="<?php echo $code_list[$i]["id"];?>"></td>

		  <td><?=$i+1?></td>

		  <td><?=$code_list[$i]['id']?></td>

          <td><a href="?act=edit&id=<?php echo $code_list[$i]["id"];?>" title="编辑本条防伪码"><?php echo $code_list[$i]["bianhao"];?></a></td>

          <td>
          
            <?php
		 $proid = $code_list[$i]["product"];
		 $sql="select * from tgs_product where id='$proid' limit 1";
$result=mysql_query($sql);
$arr=mysql_fetch_array($result);
echo $arr['proname'];

?>

</td>

          <td><?php echo $code_list[$i]["riqi"]?></td>

          

          <td><?php echo $code_list[$i]["zd1"]?>&nbsp;</td>

          <td><?php echo $code_list[$i]["zd2"]?>&nbsp;</td>
          <td><a href="#" data-reveal-id="<?php echo $code_list[$i]["bianhao"]?>" >显示</a>&nbsp;<a href="<?=$cf['site_url']?>search_fw.php?bianhao=<?php echo $code_list[$i]["bianhao"]?>" target="_blank" >查看</a></td>
          <div id="<?php echo $code_list[$i]["bianhao"]?>" class="reveal-modal"> <span style="color: #009933">防伪码：<?php echo $code_list[$i]["bianhao"]?></span><br><br><img src="url_qrcode.php?url=<?=$cf['site_url']?>search_fw.php?bianhao=<?php echo $code_list[$i]["bianhao"]?>" alt="二维码" width="200px"><br><br> <span style="color: #FF0000">右击二维码另存图片到本地</span>

		<a class="close-reveal-modal">&#215;</a>

		</div>    

          <td><?php echo $code_list[$i]["hits"];?>&nbsp;</td>

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

			  <input name="check3" type='submit' value='导出选定的记录' onclick="document.myform.Action.value='export_code'" >

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



	  <table align="center" cellpadding="3" cellspacing="0" class="table_98">

		  <tr>

			<td>



			

			 </td>

		  </tr>



		</table>

    

	</td>

  </tr>

</table>
    </div></div>






<?php

}





////防伪码导出，可选择导出项

if($act == "export_code")

{

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">防伪码管理</a> <span class="c-gray en">&gt;</span> 添加/生成防伪码 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>商品防伪码导出提示&#28304;&#30721;&#26377;&#21319;&#32423;&#25991;&#20214;&#104;&#116;&#116;&#112;&#115;&#58;&#47;&#47;&#119;&#119;&#119;&#46;&#97;&#51;&#121;&#109;&#46;&#99;&#111;&#109;</b></td>

  </tr>

  <tr>

    <td>

	<ul class="exli">

	 <li>1、“导出”方式直接生成CSV格式文档。</li>

	 <li>2、请注意导入的文档编码，支持“ANSI简体中文”和“UTF-8”编码两种文档，请使用Ms Excel、 Notepad++、 EditPlus等软件打开和编辑文档。</li>

	 <li>3、csv文档均以英文逗号做为分隔符。</li>

	 <li>4、如果你是备份防伪码，下边的选项请全部选择。</li>

	 </ul>

	</td>

  </tr>

  <tr>

    <td><form name="form1" enctype="multipart/form-data" method="post" action="export.php?act=export_code" target="_blank">

      <label>

        <input type="hidden" name="chk" id="chk" value="<?=implode(",",$_POST['chk'])?>" />

		<input type="checkbox" name="field_bianhao" id="field_bianhao" value="1" checked="checked" />防伪码

		<input type="checkbox" name="field_product" id="field_product" value="1" checked="checked" />产品类型

		<input type="checkbox" name="field_riqi" id="field_riqi" value="1" checked="checked" />有效日期

		<input type="checkbox" name="field_zd1" id="field_zd1" value="1" checked="checked" />保留字段1

		<input type="checkbox" name="field_zd2" id="field_zd2" value="1" checked="checked" />保留字段2

        </label>



		,文档编码：

		<label>

		<select name="file_encoding">

			<option value="gbk">简体中文</option>

			<option value="utf8">UTF-8</option>

		</select>

		</label>

      <label>

      <input type="submit" name="Submit" value=" 导出防伪码 ">

      </label>

    </form>

    </td>

  </tr>

  

</table>
</article>



<?php

}

////防伪码导出全部记录，可选择导出项

if($act == "exportall_code")

{

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">防伪码管理</a> <span class="c-gray en">&gt;</span> 导出所有防伪码 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>商品防伪码导出提示</b></td>

  </tr>

  <tr>

    <td>

	<ul class="exli">

	 <li>1、“导出”方式直接生成CSV格式文档。</li>

	 <li>2、请注意导入的文档编码，支持“ANSI简体中文”和“UTF-8”编码两种文档，请使用Ms Excel、 Notepad++、 EditPlus等软件打开和编辑文档。</li>

	 <li>3、csv文档均以英文逗号做为分隔符。</li>

	 <li>4、如果你是备份防伪码，下边的选项请全部选择。</li>

	 </ul>

	</td>

  </tr>

  <tr>

    <td><form name="form1" enctype="multipart/form-data" method="post" action="export.php?act=exportall_code" target="_blank">

      <label>

        <input type="hidden" name="chk" id="chk" value="<?=implode(",",$_POST['chk'])?>" />

		<input type="checkbox" name="field_bianhao" id="field_bianhao" value="1" checked="checked" />防伪码

		<input type="checkbox" name="field_product" id="field_product" value="1" checked="checked" />产品类型

		<input type="checkbox" name="field_riqi" id="field_riqi" value="1" checked="checked" />有效日期

		<input type="checkbox" name="field_zd1" id="field_zd1" value="1" checked="checked" />保留字段1

		<input type="checkbox" name="field_zd2" id="field_zd2" value="1" checked="checked" />保留字段2

        </label>



		,文档编码：

		<label>

		<select name="file_encoding">

			<option value="gbk">简体中文</option>

			<option value="utf8">UTF-8</option>

		</select>

		</label>

      <label>

      <input type="submit" name="Submit" value=" 导出防伪码 ">

      </label>

    </form>

    </td>

  </tr>

  

</table>
</article>



<?php

}
////防伪码导入///////////////////////////////////

if($act =="import"){

?>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">防伪码管理</a> <span class="c-gray en">&gt;</span> 添加/生成防伪码 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>商品防伪码导入提示</b></td>

  </tr>

  <tr>

    <td>

	<ul class="exli">

	<li>1、“导入”方式支持 XLS、CSV、TXT三种格式文档，请按：<b><a href="../data/exemple/xls_product_list.xls"><span class="red">XLS格式文件</span></a></b>、<b><a href="../data/exemple/csv_product_list.csv"><span class="red">CSV格式文件</span></a></b>、<b><a href="../data/exemple/txt_product_list.txt"><span class="red">TXT格式文件</span></a></b>，制作合适导入的标准文档,如果下载文档时是打开网页那请使用“右键另存为”下载文档。</li>

	<li>2、上述三个文档均为 “ANSI” 简体中文编码文档，在“导入”时选择“文档编码”为"UTF－8"导入时会有乱码。</li>

	<li>3、csv和txt文档均以英文逗号做为分隔符。</li>

	<li>4、程序对上传的文件大小不做限制，但一般空间都会有一个默认限制，一般为2M，所以上传的文件尽量小于2M,新生成的防伪码尽量分批上传。建议每次上传1000条。</li>

	<li>5、三个格式文档第一行的标题栏请不要删除，程序在导入过程中自动省略第一行。 </li>

	<li>6、如何批量生成防伪码？</li>

	<li>答：</li>

	<li>(1)、可使用Excel中的自动生成防伪码功能，生成新的防伪码，然后导入到系统中。</li>

	<li>(2)、使用“商品防伪码添加”中的“批量生成防伪码”，自动批量生成。</li>

	<li>7、如果用之前“导出选定的记录”导出的文档且是标准五项参数的文档，可直接导入。</li>

	</ul>

	   </td>

  </tr>

  <tr>

    <td><form name="form1" enctype="multipart/form-data" method="post" action="?act=save_add">

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

      <input type="submit" name="Submit" value="上传防伪码">

      </label>

    </form>

    </td>

  </tr>

  

</table>
</article>


<?php

}



if($act == "save_add"){



	if($_FILES['file']['size']>0 && $_FILES['file']['name']!="")

	{

	    $file_size_max    = 3072000; //3000k

		$store_dir        = "../upload/";

		$ext_arr          = array('csv','xls','txt');

		$accept_overwrite = true;

		$date1            = date("YmdHis");

		$file_type        = extend($_FILES['file']['name']);

		$newname          = $date1.".".$file_type;

		//判断格式		

		if (in_array($file_type,$ext_arr) === false){

		  echo "<script>alert('上传的文件格式错误，请按要求的文件格式上传');history.back()</script>";

		  exit;

	   }

	    //判断文件的大小

		if ($_FILES['file']['size'] > $file_size_max) {

		  echo "<script>alert('对不起，你上传的文件大于3000k');history.back()</script>";

		  exit;

		}

		

		if (file_exists($store_dir.$_FILES['file']['name'])&&!$accept_overwrite)

		{

		  echo "<script>alert('文件已存在，不能新建');history.back()</script>";

		  exit;

		}

		if (!move_uploaded_file($_FILES['file']['tmp_name'],$store_dir.$newname)) {

		  echo "<script>alert('复制文件失败');history.back()</script>";

		  exit;

		}

	  $filepath = $store_dir.$newname;

	  

	 }else{

	   $filepath = "";

	   

	 }

	 if($filepath == ""){



	    echo "<script>alert('请先选择要上传的文件');history.back()</script>";

		exit;

	 }

	

	$file_encoding = $_POST["file_encoding"];



	if($file_type == "xls"){



		// ExcelFile($filename, $encoding);

		$data = new Spreadsheet_Excel_Reader();

		// Set output Encoding.

		$data->setOutputEncoding('utf-8');

		$data->read($filepath);

		//error_reporting(E_ALL ^ E_NOTICE);

		for ($i = 2; $i < $data->sheets[0]['numRows']; $i++) {

		    //判断上传的是否有重复

			$sql = "select id from tgs_code where bianhao='".$data->sheets[0]['cells'][$i][1]."' limit 1";	

			$res = mysql_query($sql);	

			$arr = mysql_fetch_array($res);	

			if(mysql_num_rows($res)>0){

			echo "<script>alert('该防伪码已存在，请修正你的防伪码！');location.href='?act=edit&id=".$arr["id"]."'</script>";

			exit;

			}

			$sql = "INSERT INTO tgs_code (bianhao,riqi,product,zd1,zd2)VALUES('".

			$data->sheets[0]['cells'][$i][1]."','".

			$data->sheets[0]['cells'][$i][2]."','".

			$data->sheets[0]['cells'][$i][3]."','".

			$data->sheets[0]['cells'][$i][4]."','".

			$data->sheets[0]['cells'][$i][5]."')";

		    mysql_query($sql);

		}

	  

	  $k=$i-2;



    ////导入csv文件///////////////////////////

	}elseif($file_type == "csv"){	   

	  setlocale(LC_ALL, 'zh_CN.UTF-8');

	   $file  = fopen($filepath,"r");  

	   $k     = 1;

	   while(!feof($file) && $data = __fgetcsv($file))

	   {

		 $result = array();  

		   if($k>1 && !empty($data))

		   {  

			  for($i=0;$i<5;$i++)

			  {

				  array_push($result,$data[$i]);

			  }			  

		      if($file_encoding == "gbk"){			   

			   $result_1 = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $result_2 = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $result_3 = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $result_4 = iconv("gbk", "utf-8"."//IGNORE", $result[4]);			  

			  }else{			  

			   $result_1 = $result[1];

			   $result_2 = $result[2];

			   $result_3 = $result[3];

			   $result_4 = $result[4];

			  }  			  

			  //判断上传的是否有重复

			$sql = "select id from tgs_code where bianhao='".$result[0]."' limit 1";	

			$res = mysql_query($sql);	

			$arr = mysql_fetch_array($res);	

			if(mysql_num_rows($res)>0){

			echo "<script>alert('该防伪码已存在，请修正你的防伪码！');location.href='?act=edit&id=".$arr["id"]."'</script>";

			exit;

			}else{

			$sql = "insert into tgs_code (bianhao,riqi,product,zd1,zd2) values ('".$result[0]."','".$result_1."','".$result_2."','".$result_3."','".$result_4."')";

			mysql_query($sql) or die("ERR:".$sql);

			}

		  }  

		 $k++;  

		 }

		 $k=$k-2;

		 fclose($file);

		 

    ///导入txt文件//////////////////////////////

	}elseif($file_type == "txt"){	    

		$row = file($filepath); //读出文件中内容到一个数组当中

		$k   = 1;//统计表中的记录数

		for ($i=1;$i<count($row);$i++)//开始导入记录 

		{ 

			$result = explode(",",$row[$i]);//读取数据到数组中，以英文逗号为分格符

			if($file_encoding == "gbk"){			   

			   $result_1 = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $result_2 = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $result_3 = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $result_4 = iconv("gbk", "utf-8"."//IGNORE", $result[4]);			  

		    }else{			  

			   $result_1 = $result[1];

			   $result_2 = $result[2];

			   $result_3 = $result[3];

			   $result_4 = $result[4];

		    }  

			//当只有防伪码时，下面的数值跟随保存到数据库

			if($result_1 ==""){

				$result_1="2020-12-30";

			}

			if($result_2 ==""){

				$result_2="产品名称";

			}

			if($result_3 ==""){

				$result_3="备用A";

			}

			if($result_4 ==""){

				$result_4="备用B";

			}

			//判断上传的是否有重复

			$sql = "select id from tgs_code where bianhao='".$result[0]."' limit 1";	

			$res = mysql_query($sql);	

			$arr = mysql_fetch_array($res);	

			if(mysql_num_rows($res)>0){

			echo "<script>alert('该防伪码已存在，请修正你的防伪码！');location.href='?act=edit&id=".$arr["id"]."'</script>";

			exit;

			}else{

			$sql = "insert into tgs_code (bianhao,riqi,product,zd1,zd2) values ('".$result[0]."','".$result_1."','".$result_2."','".$result_3."','".$result_4."')";

			mysql_query($sql);

			$k++;

			}

		}

		$k=$k-1;

		fclose($row);

	}

	$msg= "上传成功".$k."条记录";

	@unlink($filepath);

	echo "<script>alert('".$msg."');location.href='?'</script>";

	exit;

}



////////////////////

if($act == "add"){



?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">防伪码管理</a> <span class="c-gray en">&gt;</span> 添加/生成防伪码 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
<table align="center" cellpadding="0" cellspacing="0"  class="table_98">

  <tr>

    <td valign="top">

	<form name="form1" method="post" action="?act=save_create">

        <table cellpadding="3" cellspacing="1"  class="table_50">

          <tr>

            <td colspan="2" align="left"><b>批量生成防伪码</b></td>

		  </tr>

          <tr >

            <td width="20%"> 防伪码长度：</td>

            <td width="80%"><input style="width:300px" class="input-text" name="code_length" type="text" id="code_length" size="20" value="12">（建议8-18以内）</td>

          </tr>

          <tr >

            <td>防伪码前缀：</td>

            <td><input style="width:300px" class="input-text" type="text" name="code_pre" value="" maxlength="4">（建议2-4位） </td>

          </tr>

		  <tr >

            <td>防伪码形式：</td>

            <td><select name="code_type">

			    <option value="0">前缀+数字和字母</option>

				<option value="1">前缀+字母(不限大小写)</option>

				<option value="2">前缀+数字</option>

				<option value="3">前缀+字母(大写)</option>

				</select></td>

          </tr>



		  <tr>

            <td>生成数量：</td>

            <td><input style="width:300px" class="input-text" type="text" name="code_count" value="50">（一次过多可能会造成数据库处理变慢，建议1000条以内）</td>

          </tr>



		  <tr >

            <td>产品类型：</td>

            <td>  <select   name="product" >

        <?php

		 $sql = "select * from tgs_product order by jibie DESC";

		 $res = mysql_query($sql);


		 while($arr = mysql_fetch_array($res)){

		?>

     <option value="<?php echo $arr["id"];?>"><?php echo $arr["proname"];?></option>



		<?php

		}




		?>

            </select></td>

          </tr>

		  

		  <tr>

            <td>有效日期：</td>

            <td><input style="width:300px" class="input-text" type="text" name="riqi" value=""></td>

          </tr>

		   <tr >

            <td>保留字段1：</td>

            <td><input style="width:300px" class="input-text" type="text" name="zd1" value=""></td>

          </tr>

		  <tr >

            <td>保留字段2：</td>

            <td><input style="width:300px" class="input-text" type="text" name="zd2" value=""></td>

          </tr>



          

          <tr >

            <td>&nbsp;</td>

            <td><input type="submit" name="Submit" value=" 批 量 生 成 " ></td>

          </tr>

        </table>

      

	  </form>

	  <br />

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add2">	  

        <table cellpadding="3" cellspacing="1"  class="table_50">

          <tr>

            <td colspan="2" align="left"><b>添加单个防伪码</b></td>

		  </tr>

          <tr >

            <td width="20%"> 防伪码：</td>

            <td width="80%"><input style="width:300px" class="input-text" name="bianhao" type="text" id="bianhao" value=""></td>

          </tr>

          <tr >

            <td>有期日期：</td>

            <td><input style="width:300px" class="input-text" type="text" name="riqi" value=""></td>

          </tr>

		  <tr >

            <td> 产品类型：</td>

            <td><input style="width:300px" class="input-text" name="product" type="text" value=""></td>

          </tr>



		  <tr >

            <td>保留字段1：</td>

            <td><input style="width:300px" class="input-text" type="text" name="zd1" value="<?php echo $zd1?>"></td>

          </tr>

		  <tr >

            <td>保留字段2：</td>

            <td><input style="width:300px" class="input-text" type="text" name="zd2" value="<?php echo $zd2?>"></td>

          </tr>

          

          <tr >

            <td>&nbsp;</td>

            <td><input style="width:300px" class="input-text" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
</article>



<?php

}

////单条添加

if($act == "save_add2"){   

    $bianhao      = trim($_REQUEST["bianhao"]);	

	$riqi         = trim($_REQUEST["riqi"]);

	$product      = strreplace(trim($_REQUEST["product"]));

	$zd1          = strreplace($_REQUEST["zd1"]);

	$zd2          = strreplace($_REQUEST["zd2"]);

	if($bianhao=="")

	{

	  echo "<script>alert('防伪码不能为空');location.href='?act=add'</script>";

	  exit;

	}

	$sql = "select id from tgs_code where bianhao='".$bianhao."' limit 1";

	$res = mysql_query($sql);

	$arr = mysql_fetch_array($res);

	if(mysql_num_rows($res)>0){



	  echo "<script>alert('防伪码已存在');location.href='?act=edit&id=".$arr["id"]."'</script>";

	  exit;

	}

    if($product == "")

	{

	  $product = "产品名称";

	}

	if($riqi == "")

	{

	  $riqi = "2020-12-31";

	}

	if($zd1 == "")

	{

	  $zd1 = "备注A";

	}

	if($zd2 == "")

	{

	  $zd2 = "备注B";

	}

	$sql="insert into tgs_code (bianhao,riqi,product,zd1,zd2)values('$bianhao','$riqi','$product','$zd1','$zd2')";

	//echo $sql;

	mysql_query($sql);

	echo "<script>alert('添加成功');location.href='?'</script>";

	exit;



}

/////保存批量生成的防伪码

if($act == "save_create")

{

    $code_length = trim($_POST['code_length']);//长度

	$code_pre    = trim($_POST['code_pre']);//前缀

	$code_type   = $_POST['code_type'];//形式

	$code_count  = trim($_POST['code_count']);//数量	

	$riqi        = trim($_POST['riqi']);//有效日期

	$product     = strreplace(trim($_POST['product']));//产品

	$zd1         = strreplace($_POST['zd1']);//备注1

	$zd2         = strreplace($_POST['zd2']);//备注2

	if(strlen($code_pre)>= $code_length)

	{

	  echo "<script>alert('防伪码前缀的长度不能大于等于防伪码长度');location.href='?act=add'</script>";

	  exit;

	}	

	if(!is_numeric($code_length))

	{

	  echo "<script>alert('防伪码长度请输入数字');location.href='?act=add'</script>";

	  exit;

	}

	if($code_length<4)

	{

	  echo "<script>alert('防伪码长度最少为4位');location.href='?act=add'</script>";

	  exit;

	}

	/*

	if($code_pre == "")

	{

	  echo "<script>alert('建议输入防伪码前缀！');location.href='?act=add'</script>";

	  exit;

	}*/

	if($product == "")

	{

	  $product = "产品名称";

	}

	if($riqi == "")

	{

	  $riqi = "2020-12-31";

	}

	if($zd1 == "")

	{

	  $zd1 = "备注A";

	}

	if($zd2 == "")

	{

	  $zd2 = "备注B";

	}

	

	$new_code_length = $code_length-strlen($code_pre);//防伪码长度

	

	for($i=1;$i<=$code_count;$i++)

	{

	   $bianhao  = $code_pre.genRandomString($new_code_length,$code_type);

	   $sql = "insert into tgs_code set bianhao = '".$bianhao."',product='".$product."',riqi='".$riqi."',zd1='".$zd1."',zd2='".$zd2."'";

	   mysql_query($sql);

	}

	

	echo "<script>alert('批量生成".$code_count."成功');location.href='?'</script>";

	exit;

}



////编辑

if($act == "edit"){

   

       $editid = $_GET["id"];

		$sql="select * from tgs_code where id='$editid' limit 1";

		//echo $sql;

		$result=mysql_query($sql);

		$arr=mysql_fetch_array($result);

		

		$bianhao    = $arr["bianhao"];

		$riqi       = $arr["riqi"];

		$product    = $arr["product"];

		$zd1        = $arr["zd1"];

		$zd2        = $arr["zd2"];		

		$rn         = "修改商品防伪码";

?>



<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit">    

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="left"><?php echo $rn?>

            <input name="editid" type="hidden" id="editid" value="<?php echo $editid?>"></td></tr>

          <tr >

            <td width="20%"> 防伪码：</td>

            <td width="80%" ><input name="bianhao" type="text" id="bianhao" value="<?php echo $bianhao?>"></td>

          </tr>

          <tr >

            <td>有效日期：</td>

            <td><input type="text" name="riqi" value="<?php echo $riqi?>"></td>

          </tr>

		  <tr >

            <td> 产品ID：</td>

            <td><input type="text" name="product" value="<?php echo $product?>" /></td>

          </tr>

		  <tr >

            <td>保留字段１：</td>

            <td><input type="text" name="zd1" value="<?php echo $zd1?>"></td>

          </tr>

		  <tr >

            <td>保留字段2：</td>

            <td><input type="text" name="zd2" value="<?php echo $zd2?>"></td>

          </tr>   

          <tr >

            <td>&nbsp;</td>

            <td><input type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>  

	  </td>

  </tr>

</table>

<?php

}



//////////////////////////////////////////

if($act == "save_edit"){



    $editid     = $_REQUEST["editid"]; 

    $bianhao    = trim($_REQUEST["bianhao"]);

	

	$riqi          = trim($_REQUEST["riqi"]);

	$product       = strreplace(trim($_REQUEST["product"]));	

	$zd1           = strreplace($_REQUEST["zd1"]);

	$zd2           = strreplace($_REQUEST["zd2"]);	



	if($editid == "")

	{

	  echo "<script>alert('ID参数有误');location.href='?'</script>";

	  exit;

	}

	if($bianhao=="")

	{

	  echo "<script>alert('防伪码不能为空');location.href='?act=edit&id=".$editid."'</script>";

	  exit;

	}



	$sql="update tgs_code set bianhao='$bianhao',riqi='$riqi',product='$product',zd1='$zd1',zd2='$zd2' where id='$editid' limit 1";

	//echo $sql;

	mysql_query($sql);



	echo "<script>alert('修改成功');location.href='?'</script>";

	exit; 



}



 /////多选或全选删除功能////////////////////////////////////////////

if($act == "delart"){



	$chk = $_REQUEST["chk"];

	if(count($chk)>0){



	  $countchk = count($chk);

		for($i=0;$i<=$countchk;$i++)  

		{  

		 //echo  $chk[$i]."<br>"; 

		  mysql_query("delete from tgs_code where id='$chk[$i]' limit 1");  

		  

		} 

		echo "<script>alert('删除成功');location.href='?'</script>";

	}

}

//查询历史信息

if($act == "query_record")

{ 

  $code_list = array();

  $key       = trim($_REQUEST["key"]);

  $qupz        = trim($_REQUEST['qupz']);

  $sql="select * from tgs_history where 1";

  if($key != ""){

    $sql.=" and keyword like '%$key%'";

  }  

  $sql.=" order by id desc";

  ///echo $sql;

  $result=mysql_query($sql); 

  if($qupz == ""){ 

    $pagesize = $cf['list_num'];//每页所要显示的数据个数。

	$qupz       = $cf['list_num'];

  }else{

	$pagesize = $qupz;

  }

  $total    = mysql_num_rows($result); 	

  $filename = "?act=query_record&keyword=".$key."&qupz=".$qupz."";

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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">防伪码管理</a> <span class="c-gray en">&gt;</span> 防伪查询记录 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_list_98">

  <tr>

    <td valign="top">

		

		<table cellpadding="3" cellspacing="0" class="table_98">

		 <form action="?act=query_record" method="post" name="form1">

		  <tr>

			<td>查询记录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 关键字：<input type="text" name="key"> <input name="submit" type="submit" id="submit" value="查找"> </td>

		  </tr>

		 </form>

		</table>

	

	<form method="post" name="myform" id="myform" action="?act=query_record" onsubmit="return ConfirmDel();">

	<input type="hidden" name="key" value="<?=$key?>" />

	<table cellpadding="3" cellspacing="0" class="table_98">

        <tr>

          <td height="20"><input name="check" type='submit' value='删除选定的记录' onclick="document.myform.Action.value='delete_history'" ><span class='red'>(*请定期清理查询记录)</span></td>

		  <td align="right" style="text-align:right !important">

		      显示条数 <input type="text" name="qupz" id="qupz" value="<?=$pagesize?>" size="8" onchange="javascript:submit()" /> &nbsp;&nbsp;&nbsp;&nbsp;

		     

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

		   <td  align="right" style="text-align:right !important">

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

		  mysql_query("delete from tgs_history where id='$chk[$i]' limit 1");		  

		} 

		echo "<script>alert('删除成功');location.href='?act=query_record'</script>";

	}

}

?>

<?php

////系统相关设置

if($act == "config"){  

?>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_config">    

		<table cellpadding="3" cellspacing="1" class="table_98">

          <tr>

            <td colspan="3" align="center" bgcolor="#CCCCCC">配置信息</td></tr>

          <tr >

            <td width="10%"> 系统名称：</td>

            <td width="40%" ><input style="width:300px" class="input-text" name="cf[site_name]" type="text" id="cf[site_name]" size="50" value="<?php echo $cf['site_name']?>"></td>

			<td width="50%" > </td>

          </tr>

          <tr >

            <td>系统网址：</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[site_url]" value="<?php echo $cf['site_url']?>" size="50"></td>

			<td >请输入完整的网站域名 例：http://abc.com.cn/cx/&#28304;&#30721;&#26377;&#21319;&#32423;&#25991;&#20214;&#104;&#116;&#116;&#112;&#115;&#58;&#47;&#47;&#119;&#119;&#119;&#46;&#97;&#51;&#121;&#109;&#46;&#99;&#111;&#109;</td>

          </tr>
          
           <tr >

            <td>代理前台模板：</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[agent_themes]" value="<?php echo $cf['agent_themes']?>" size="50"></td>

			<td >请输入模板文件夹名，模板路径在themes 目录下 </td>

          </tr>
           <tr >

            <td>防伪前台模板：</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[site_themes]" value="<?php echo $cf['site_themes']?>" size="50"></td>

			<td >请输入模板文件夹名 </td>

          </tr>
          
            <tr >

            <td>代理编号前缀：</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[agentqz]" value="<?php echo $cf['agentqz']?>" size="50"></td>

			<td >请输入代理编号前缀，如 WS20198734642  则输入 WS </td>

          </tr>
         

		  <tr >

            <td> 首页网页关键字(keywords)：</td>

            <td><textarea name="cf[page_keywords]" cols="65" rows="5"><?php echo $cf['page_keywords']?></textarea></td>

			<td > </td>

          </tr>

		  <tr >

            <td> 首页网页描述(description)：</td>

            <td><textarea name="cf[page_desc]" cols="65" rows="5"><?php echo $cf['page_desc']?></textarea></td>

			<td > </td>

          </tr>	
            <tr >

            <td> 底部版权：</td>

            <td><textarea name="cf[copyrighta]" cols="65" rows="5"><?php echo $cf['copyrighta']?></textarea></td>

			<td > </td>

          </tr>	  

		

		   <tr >

            <td>默认每页显示数量</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[list_num]" id="list_num" value="<?=$cf['list_num']?>" /></td>

			<td></td>

          </tr>

		  <tr>

		   <td width="10%">系统时区：</td>

			<td><select name="cf[timezone]">

					<option value="-12" <?php if($cf['timezone']=='-12') echo "selected='selected'";?>>(GMT -12:00) Eniwetok, Kwajalein</option>

					<option value="-11" <?php if($cf['timezone']=='-11') echo "selected='selected'";?>>(GMT -11:00) Midway Island, Samoa</option>

					<option value="-10" <?php if($cf['timezone']=='-10') echo "selected='selected'";?>>(GMT -10:00) Hawaii</option>

					<option value="-9" <?php if($cf['timezone']=='-9') echo "selected='selected'";?>>(GMT -09:00) Alaska</option>

					<option value="-8" <?php if($cf['timezone']=='-8') echo "selected='selected'";?>>(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana</option>

					<option value="-7" <?php if($cf['timezone']=='-7') echo "selected='selected'";?>>(GMT -07:00) Mountain Time (US &amp; Canada), Arizona</option>

					<option value="-6" <?php if($cf['timezone']=='-6') echo "selected='selected'";?>>(GMT -06:00) Central Time (US &amp; Canada), Mexico City</option>

					<option value="-5" <?php if($cf['timezone']=='-6') echo "selected='selected'";?>>(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>

					<option value="-4" <?php if($cf['timezone']=='-4') echo "selected='selected'";?>>(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz</option>

					<option value="-3.5" <?php if($cf['timezone']=='-3.5') echo "selected='selected'";?>>(GMT -03:30) Newfoundland</option>

					<option value="-3" <?php if($cf['timezone']=='-3') echo "selected='selected'";?>>(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is</option>

					<option value="-2" <?php if($cf['timezone']=='-2') echo "selected='selected'";?>>(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena</option>

					<option value="-1" <?php if($cf['timezone']=='-1') echo "selected='selected'";?>>(GMT -01:00) Azores, Cape Verde Islands</option>

					<option value="0" <?php if($cf['timezone']=='0') echo "selected='selected'";?>>(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>

					<option value="1" <?php if($cf['timezone']=='1') echo "selected='selected'";?>>(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>

					<option value="2" <?php if($cf['timezone']=='2') echo "selected='selected'";?>>(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa</option>

					<option value="3" <?php if($cf['timezone']=='3') echo "selected='selected'";?>>(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi</option>

					<option value="3.5" <?php if($cf['timezone']=='3.5') echo "selected='selected'";?>>(GMT +03:30) Tehran</option>

					<option value="4" <?php if($cf['timezone']=='4') echo "selected='selected'";?>>(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi</option>

					<option value="4.5" <?php if($cf['timezone']=='4.5') echo "selected='selected'";?>>(GMT +04:30) Kabul</option>

					<option value="5" <?php if($cf['timezone']=='5') echo "selected='selected'";?>>(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>

					<option value="5.5" <?php if($cf['timezone']=='5.5') echo "selected='selected'";?>>(GMT +05:30) Bombay, Calcutta, Madras, New Delhi</option>

					<option value="5.75" <?php if($cf['timezone']=='5.75') echo "selected='selected'";?>>(GMT +05:45) Katmandu</option>

					<option value="6" <?php if($cf['timezone']=='6') echo "selected='selected'";?>>(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk</option>

					<option value="6.5" <?php if($cf['timezone']=='6.5') echo "selected='selected'";?>>(GMT +06:30) Rangoon</option>

					<option value="7" <?php if($cf['timezone']=='7') echo "selected='selected'";?>>(GMT +07:00) Bangkok, Hanoi, Jakarta</option>

					<option value="8" <?php if($cf['timezone']=='8') echo "selected='selected'";?>>(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei</option>

					<option value="9" <?php if($cf['timezone']=='9') echo "selected='selected'";?>>(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>

					<option value="9.5" <?php if($cf['timezone']=='9.5') echo "selected='selected'";?>>(GMT +09:30) Adelaide, Darwin</option>

					<option value="10" <?php if($cf['timezone']=='10') echo "selected='selected'";?>>(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok</option>

					<option value="11" <?php if($cf['timezone']=='11') echo "selected='selected'";?>>(GMT +11:00) Magadan, New Caledonia, Solomon Islands</option>

					<option value="12" <?php if($cf['timezone']=='12') echo "selected='selected'";?>>(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island</option>

					<option value="13" <?php if($cf['timezone']=='13') echo "selected='selected'";?>>(GMT +13:00) Nukualofa</option>

				  </select>			 </td>

			<td></td>

		  </tr>

		  <tr>

		   <td>系统时间格式：</td>

		   <td><input style="width:300px" class="input-text" name="cf[time_format]" type="text" size="12" value="<?php echo $cf['time_format'];?>"></td>

		   <td>服务器时间：<?=date($cf['time_format'],time());?><br /> 程序时间:<?=$GLOBALS['tgs']['cur_time'];?></td>

		  </tr>

		  <tr >

            <td colspan="3" align="center" bgcolor="#CCCCCC">防伪码信息配置<a name="fw"></a></td>

          </tr>

		  <tr >

            <td>防伪码查询结果为真时：<br /></td>

            <td><textarea name="cf[notice_1]" id="cf[notice_1]" cols="65" rows="5"><?php echo ($cf['notice_1'])?></textarea></td>

			<td rowspan="2" style="line-height:25px; font-size:14px; padding:5px 8px;"> (内容可自由编辑成您要的文字，其中编号：{{bianhao}},产品名称：{{product}},产品简介：{{saytext}},其它：{{riqi}},{{hits}},{{zd1}},{{zd2}}等"系统类字符串"可自由组合，如保留一定要是完整“系统类字符串”) </td>			

          </tr>

		  <tr >

            <td>防伪码查询结果为真且非第一次查询时：</td>

            <td><textarea name="cf[notice_2]" id="cf[notice_2]" cols="65" rows="5"><?php echo $cf['notice_2']?></textarea>		</td>

          </tr>

		  <tr >

            <td>防伪码查询结果为空时：</td>

            <td><textarea name="cf[notice_3]" id="cf[notice_3]" cols="65" rows="5"><?php echo ($cf['notice_3'])?></textarea>         </td>

		 <td > 

		(内容可自由编辑成您要的文字，其中仅用到了“{{bianhao}}系统类字符串")			</td>

          </tr>

		  <tr >

            <td>防伪码使用说明：</td>

            <td><textarea name="cf[notices]" id="cf[notices]" cols="65" rows="5"><?php echo $cf['notices']?></textarea>		</td>

		<td > 

		(内容可自由编辑成您要的文字。)			</td>

          </tr> 

		   <tr >

            <td colspan="3" align="center" bgcolor="#CCCCCC">代理商信息设置<a name="agent"></a></td>

          </tr>

		  <tr >

            <td>代理商查询结果为真时：<br /></td>

            <td><textarea name="cf[agent_1]" id="cf[agent_1]" cols="65" rows="5"><?php echo ($cf['agent_1'])?></textarea></td>

			<td rowspan="2"  style="line-height:25px; font-size:14px; padding:5px 8px;"> (内容可自由编辑成您要的文字，其中：代理商编号：{{agentid}}，代理产品：{{product}}，代理等级：{{dengji}}，身份证：{{idcardd}}，代理区域：{{quyu}}，个人/公司：{{shuyu}}，代理渠道：{{qudao}}，网址：{{url}}，代理商介绍：{{about}}，开始代理时间：{{addtime}}，代理结束时间：{{jietime}}，姓名：{{name}}，电话：{{tel}}，传真：{{fax}}，手机：{{phone}}，单位：{{danwei}}，邮箱：{{email}}，QQ：{{qq}}，微信：{{weixin}}，旺旺：{{wangwang}}，拍拍：{{paipai}}，邮编：{{zip}}，地址：{{dizhi}}，备注：{{beizhu}}等"系统类字符串"可自由组合，如保留一定要是完整“系统类字符串”) </td>			

          </tr>

		  <tr >

            <td>黑名单说明：</td>

            <td><textarea name="cf[agent_2]" id="cf[agent_2]" cols="65" rows="5"><?php echo $cf['agent_2']?></textarea>		</td>

          </tr>

		  <tr >

            <td>代理商查询结果为空时：</td>

            <td><textarea name="cf[agent_3]" id="cf[agent_3]" cols="65" rows="5"><?php echo ($cf['agent_3'])?></textarea>         </td>

		 <td > 

		(内容可自由编辑成您要的文字，其中仅用到了“{{keyword}}系统类字符串")			</td>

          </tr>

		  <tr >

            <td>代理商使用说明：</td>

            <td><textarea name="cf[agents]" id="cf[agents]" cols="65" rows="5"><?php echo $cf['agents']?></textarea>		</td>

		<td > 

		(内容可自由编辑成您要的文字。)			</td>

          </tr>

          <tr >
            
            <td>&nbsp;</td>
            
            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 保 存 " ></td>
            
            <td></td>
            
          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
</div>
    


<?php

}

//////////////////////////////////////////

if($act == "save_config"){

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
		$sitelogo      = $destination_folder . $fname;
		$sql = "UPDATE tgs_config SET code_value = '$sitelogo' WHERE code ='site_logo' limit 1";

                        mysql_query($sql);
    }
	
}


    $arr = array();

    $sql = "SELECT id, code_value FROM tgs_config";

    $res = mysql_query($sql);

    while($row = mysql_fetch_array($res))

    {

        $arr[$row['id']] = $row['code_value'];

    }

	 foreach ($_POST['cf'] AS $key => $val)

    {

        if($arr[$key] != $val)

        { 

		  ///变量格式化

		  if($key=='notices' or $key=='notice_1' or $key == 'notice_2' or $key=='notice_3' or $key=='agents' or $key=='agent_1' or $key=='agent_2' or $key=='agent_3'){

              $val = strreplace($val);

		  }

		  if($key=='site_close_reason'){

              $val = strreplace($val);

		  }



	      $sql="update tgs_config set code_value='".trim($val)."' where code='".$key."' limit 1";

		  mysql_query($sql) or die("err:".$sql);
		  
		  

		}

	}



	/* 处理上传文件 */
	

    $file_var_list = array();

    $sql = "SELECT * FROM tgs_config WHERE parentid > 0 AND type = 'file'";

	$res = mysql_query($sql);



	while($row = mysql_fetch_array($res))

    {

        $file_var_list[$row['code']] = $row;

    }

	foreach ($_FILES AS $code => $file)

    {

	
	   
	    if ((isset($file['error']) && $file['error'] == 0) || (!isset($file['error']) && $file['tmp_name'] != 'none'))

        {   

			

			$file_size_max    = 307200; //300k

			$accept_overwrite = true;

			$ext_arr          = array('gif','jpg','png');//定义允许上传的文件扩展名

			$add              = true;

			$ext              = extend($file['name']);

			

			//检查扩展名

			if (in_array($ext,$ext_arr) === false) {

				   $msg .= $_LANG["page"]["_you_upload_pic_type_"]."<br />";

				   

			}else if ($file['size'] > $file_size_max) {

				  $msg .= $_LANG["page"]["_you_upload_pic_larger_than_300k_"]."<br />";

				  

			}else{

				

				if($code == 'site_logo'){

					$date1       =  "logo".date("His");

					$store_dir   = "../upload/logo/";

					$newname     = $date1.".".$ext;



					if (!move_uploaded_file($file['tmp_name'],$store_dir.$newname)) {

					  $msg .= $_LANG['page']['_Copy_file_failed_']."<br />";

					  

					}else{

						///删除原图

						if (file_exists($store_dir.$file_var_list[$code]['value']))

                        {

                          

						  @unlink($store_dir.$file_var_list[$code]['value']);

                        }



						$sql = "UPDATE tgs_config SET code_value = '$newname' WHERE code = '$code' limit 1";

                        mysql_query($sql);

					}



				}

			}

		}



	}

	   echo "<script>window.location.href='?act=config'</script>";

	   exit; 

}

////管理员设置

if($act == "superadmin"){

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="20%">管理员帐户</td>

          <td width="20%">操作</td>          

		</tr>

		<?php

		 $sql = "select * from tgs_admin order by id asc";

		 $res = mysql_query($sql);

		 while($arr = mysql_fetch_array($res)){		

		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_superadmin&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["username"];?></a></td>
          <td>
          <a title="编辑" href="?act=edit_superadmin&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; <a title="删除" href="?act=delete_superadmin&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
          
         </td>

        </tr>

		<?php

		}

		?>

		</table>

    

	</td>

  </tr>

</table>

<br />

<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_superadmin">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">填加管理员帐户</td></tr>

          <tr >

            <td width="20%"> 管理员帐户：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="username" type="text" id="username" size="20" value=""></td>

          </tr>

          <tr >

            <td>管理密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="password" value="" />(密码长度不能少于4位)</td>

          </tr>

		  <tr >

            <td>确认管理密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="repassword" value="" /></td>

          </tr>

          

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确定添加 " ></td>

          </tr>

        </table>

      

	  </form>

	  

	  </td>

  </tr>

</table>
    </div></div>
    






<?php

}



//////////////////////////////////////////

if($act == "save_add_superadmin"){



       $username   = trim($_POST["username"]);

	   $password   = trim($_POST["password"]);

	   $repassword = trim($_POST["repassword"]);

	   $a          = 0;



	   if($username==""){

	      echo "<script>alert('管理员帐户不能为空');window.location.href='?act=superadmin'</script>";

		   exit;

	   }	  

		   if(strlen($password)<4){

			   echo "<script>alert('密码长度不能小于4位');window.location.href='?act=superadmin'</script>";

			   exit;

		   }

		   if($password != $repassword)

		   {

			   echo "<script>alert('两次输入的密码不一致');window.location.href='?act=superadmin'</script>";

			   exit;

		   }



	   $sql="insert into tgs_admin set username='".$username."', password='".md5($password)."'";

	   mysql_query($sql) or die("err:".$sql);

	   

       echo "<script>alert('管理帐户添加成功');</script>";

	   echo "<script>window.location.href='?act=superadmin'</script>";

	   exit; 

}



////编辑管理员

if($act == "edit_superadmin"){ 

 $id  = $_GET['id'];

 $sql = "select * from tgs_admin where id=".$id." limit 1";

 $res = mysql_query($sql);

 $arr = mysql_fetch_array($res);

 $username  = $arr['username'];

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_superadmin">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑管理员帐户</td></tr>

          <tr >

            <td width="20%"> 管理帐户：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="username" type="text" id="username" size="20" value="<?php echo $username?>"></td>

          </tr>

          <tr >

            <td>管理密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="password" value="" />(如不修改密码则无需添写,密码长度不能少于4位)</td>

          </tr>

		  <tr >

            <td>确认管理密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="repassword" value="" /></td>

          </tr>

          

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
    </div></div>



<?php

}



////保存编辑的管理员帐户//////////////////////////////////////

if($act == "save_edit_superadmin"){



       $id         = $_POST['id'];

	   $username   = trim($_POST["username"]);

	   $password   = trim($_POST["password"]);

	   $repassword = trim($_POST["repassword"]);

	   $a          = 0;

	   if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }

	   if($username!=""){

	      $sql="update tgs_admin set username='".$username."' where id=".$id." limit 1";

	      mysql_query($sql) or die("err:".$sql);

		  $a = 1;

	   }

	   if($password != ""){

		   if(strlen($password)<4){

			   echo "<script>alert('密码长度不能小于4位');window.location.href='?act=superadmin'</script>";

			   exit;

		   }

		   if($password != $repassword)

		   {

			   echo "<script>alert('两次输入的密码不一致');window.location.href='?act=superadmin'</script>";

			   exit;

		   }



		   $sql="update tgs_admin set password='".md5($password)."' where id=".$id." limit 1";

	       mysql_query($sql) or die("err:".$sql);

		   $a= 1;

	   }



	   if($a == 1){

         echo "<script>alert('管理帐户更新成功');</script>";

	   }else{

	     echo "<script>alert('管理帐户信息失败!!');</script>";

	   }

	   echo "<script>window.location.href='?act=superadmin'</script>";



	   exit; 



}



////删除管理员帐户//////////////////////////////////////

if($act == "delete_superadmin"){



      $id         = $_GET['id'];

	   

	  if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }



	  

	  $sql="delete from tgs_admin where id=".$id." limit 1";

	  mysql_query($sql) or die("err:".$sql);

		 

	   

      echo "<script>alert('管理帐户删除成功');</script>";

	  echo "<script>window.location.href='?act=superadmin'</script>";

	  exit; 



}




////等级设置

if($act == "dengji"){

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>





<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="10%">等级名称</td>
          <td width="10%">级别</td>
          <td width="10%">审核权限</td>
          <td width="10%">升级审核</td>
          <td width="10%">编辑权限</td>
          <td width="10%">删除权限</td>

          <td width="20%">操作</td>          

		</tr>
       
        <?php

		 $sqldj = "select djname from tgs_dengji where djname<>'' order by jibie DESC";

		 $resdj = mysql_query($sqldj);
		 
         
		 
		 while($arr = mysql_fetch_array($resdj)){	
		$djname1 .= $arr['djname'].',';
		 }
		$djname2 =$djname1;
		$djname = substr($djname2,0,strlen($djname2)-1); 
		//echo $djname;
		
		?>
        
        
        

		<?php

		 $sql = "select * from tgs_dengji where djname<>'' order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_dengji&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["djname"];?></a></td>
          <td><?php echo $arr["jibie"];?></td>
          <td>
		  
		  <?php 
		  
		   if($arr["checkper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
               <td> <?php 
		  
		   if($arr["sjcheckper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
          <td> <?php 
		  
		   if($arr["editper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
          <td><?php 
		  
		   if($arr["delper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
          <td>
            <a title="编辑" href="?act=edit_dengji&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; &nbsp;&nbsp; <a title="删除" href="?act=delete_dengji&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
            
          </td>

        </tr>
        
        

		<?php

		}
		
		

	
		?>
 <tr >

          <td colspan="7"> <a href="dengji.php?dldj=<?=$djname?>" class="btn btn-danger radius"><font color="#ffffff">同步等级设置</font> </a> 菜单修改或添加完成后，请点击同步等级设置按钮，完成代理等级同步 </td>

          </tr>
		</table>

    

	</td>

  </tr>

</table>

<br />

<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_dengji">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">增加等级</td></tr>

          <tr >

            <td width="20%"> 等级名称：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="djname" type="text" id="djname" size="20" value=""></td>

          </tr>

          <tr >

            <td>级别：</td>

            <td><input style="width:300px" class="input-text" name="jibie" type="text" id="jibie" size="20" value="">(请输入1-10之间的数字，数字越大，等级越高)</td>

          </tr>

		  <tr >

            <td>审核权限：</td>

            <td><input name="checkper" type="radio" value="1"/>有
           
           <input name="checkper" type="radio" value="0" checked="checked"/>无</td>

          </tr>
          
            <tr >

            <td>审核权限：</td>

            <td><input name="sjcheckper" type="radio" value="1"/>有
           
           <input name="sjcheckper" type="radio" value="0" checked="checked"/>无</td>

          </tr>
          
            <tr >

            <td>编辑权限：</td>

            <td><input name="editper" type="radio" value="1"/>有
           
           <input name="editper" type="radio" value="0" checked="checked"/>无</td>

          </tr>
           <tr >

            <td>删除权限：</td>

            <td><input name="delper" type="radio" value="1"/>有
           
           <input name="delper" type="radio" value="0" checked="checked"/>无</td>

          </tr>

          

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确定添加 " ></td>

          </tr>

        </table>

      

	  </form>

	  

	  </td>

  </tr>

</table>
    </div></div>
    






<?php

}

//////////////////////////////////////////

if($act == "save_add_dengji"){



       $djname   = trim($_POST["djname"]);
	   $jibie   = trim($_POST["jibie"]);
	   $checkper   = trim($_POST["checkper"]);
	   $sjcheckper   = trim($_POST["sjcheckper"]);
	   $editper   = trim($_POST["editper"]);
	   $delper   = trim($_POST["delper"]);

	  

	   $a          = 0;



	    

		   


	   $sql="insert into tgs_dengji set djname='".$djname."',delper='".$delper."', jibie='".$jibie."',checkper='".$checkper."',sjcheckper='".$sjcheckper."',editper='".$editper."'";

	   mysql_query($sql) or die("err:".$sql);

	   

       echo "<script>alert('添加等级成功');</script>";

	   echo "<script>window.location.href='?act=dengji'</script>";

	   exit; 

}





////编辑等级权限

if($act == "edit_dengji"){ 

 $id  = $_GET['id'];

 $sql = "select * from tgs_dengji where id=".$id." limit 1";

 $res = mysql_query($sql);

 $arr = mysql_fetch_array($res);

 $jibie  = $arr['jibie'];
 $djname  = $arr['djname'];
 $checkper  = $arr['checkper'];
 $sjcheckper  = $arr['sjcheckper'];
 $editper  = $arr['editper'];
 $delper  = $arr['delper'];
 

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_dengji">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑等级权限</td></tr>

          <tr >

            <td width="20%"> 等级名称：</td>

            <td width="80%" ><input name="djname" type="text" class="input-text" id="djname" style="width:300px" value="<?php echo $djname?>" size="20"></td>

          </tr>
       <tr >

            <td width="20%"> 别级：</td>

            <td width="80%" ><input name="jibie" type="text" class="input-text" id="jibie" style="width:300px" value="<?php echo $jibie?>" size="20">请输入1-10之间的数字，数字越大，等级越高</td>

          </tr>
          <tr >

            <td>审核权限：</td>

            <td>  <input name="checkper" type="radio" value="1" 
		   <?php 
		   if($checkper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="checkper" type="radio" value="0"  <?php 
		   if($checkper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>

 <tr >

            <td>升级审核权限：</td>

            <td>  <input name="sjcheckper" type="radio" value="1" 
		   <?php 
		   if($sjcheckper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="sjcheckper" type="radio" value="0"  <?php 
		   if($sjcheckper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>
		  <tr >

            <td>编辑权限：</td>

            <td>  <input name="editper" type="radio" value="1" 
             <?php 
		   if($editper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="editper" type="radio" value="0"  <?php 
		   if($editper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>

            <tr >

            <td>删除权限：</td>

            <td>  <input name="delper" type="radio" value="1" 
             <?php 
		   if($delper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="delper" type="radio" value="0"  <?php 
		   if($delper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
    </div></div>



<?php

}



////保存编辑的等级权限信息//////////////////////////////////////

if($act == "save_edit_dengji"){



       $id         = $_POST['id'];

	   $djname   = trim($_POST["djname"]);
	   $jibie   = trim($_POST["jibie"]);

	   $checkper   = trim($_POST["checkper"]);
	   $sjcheckper   = trim($_POST["sjcheckper"]);

	   $editper = trim($_POST["editper"]);
	   
	   $delper = trim($_POST["delper"]);

	   $a          = 0;

	   if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=dengji'</script>";

			   exit;

	  }

	  

	   
		



		   $sql="update tgs_dengji set djname='".$djname."',jibie='".$jibie."',checkper='".$checkper."',sjcheckper='".$sjcheckper."',delper='".$delper."',editper='".$editper."' where id=".$id." limit 1";

	       mysql_query($sql) or die("err:".$sql);

		   $a= 1;

	


	   if($a == 1){

         echo "<script>alert('更新等级权限成功');</script>";

	   }else{

	     echo "<script>alert('更新等级权限失败!!');</script>";

	   }

	   echo "<script>window.location.href='?act=dengji'</script>";



	   exit; 



}

////删除等级//////////////////////////////////////

if($act == "delete_dengji"){



      $id         = $_GET['id'];

	   

	  if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }



	  

	  $sql="delete from tgs_dengji where id=".$id." limit 1";

	  mysql_query($sql) or die("err:".$sql);

		 

	   

      echo "<script>alert('等级删除成功！');</script>";

	  echo "<script>window.location.href='?act=dengji'</script>";

	  exit; 



}

////产品设置

if($act == "product"){

?>




<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">产品管理</a> <span class="c-gray en">&gt;</span> 产品设置 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>





<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="10%">产品名称</td>
          <td width="10%">顺序</td>
          <td width="10%">图片</td>

          <td width="20%">操作</td>          

		</tr>
       
        <?php

		 $sqldj = "select proname from tgs_product order by jibie DESC";

		 $resdj = mysql_query($sqldj);
		 
         
		 
		 while($arr = mysql_fetch_array($resdj)){	
		$proname .= $arr['proname'].' ';
		 }
		$proname =$proname;
		
		?>
        
        
        

		<?php

		 $sql = "select * from tgs_product order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_product&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["proname"];?></a></td>
          <td><?php echo $arr["jibie"];?></td>
          
            <td><img src="<?php echo $arr["proimg"];?>" width="200" height="100" alt=""/></td>
            
          <td>
            <a title="编辑" href="?act=edit_product&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; &nbsp;&nbsp; <a title="删除" href="?act=delete_product&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
            
          </td>

        </tr>
        
        

		<?php

		}
		
		

	
		?>

		</table>

    

	</td>

  </tr>

</table>

<br />

<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_product">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">增加产品</td></tr>

          <tr >

            <td width="20%"> 产品名称：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="proname" type="text" id="proname" size="20" value=""></td>

          </tr>

          <tr >

            <td>顺序：</td>

            <td><input style="width:300px" class="input-text" name="jibie" type="text" id="jibie" size="20" value="">(数字越大，排名越前)</td>

          </tr>
       <tr >

            

	<td>产品图片：</td>

           

	 <td><input style="width:300px" class="input-text" name="proimg" type="text" id="proimg" size="20" value="">

	 

     <input name="PrUpload" id="PrUpload" type="button" style="width:80px;" value="上传图片">                            



	 </td>

     </tr>
     
       <tr >

            <td>详细介绍：</td>
	
            <td><textarea name="content1" style="width:700px;height:200px;visibility:hidden;"><?php echo htmlspecialchars($htmlData); ?></textarea></td>
          


          </tr>


		  
          

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确定添加 " ></td>

          </tr>

        </table>

      

	  </form>

	  

	  </td>

  </tr>

</table>
    </div></div>
    






<?php

}

//////////////////////////////////////////

if($act == "save_add_product"){



       $proname   = trim($_POST["proname"]);
	   $jibie   = trim($_POST["jibie"]);
	   $proimg   = trim($_POST["proimg"]);
	   $htmlData = '';
	   if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
	}

	  

	   $a          = 0;



	    

		   


	   $sql="insert into tgs_product set proname='".$proname."',jibie='".$jibie."',proimg='".$proimg."',saytext='".$htmlData."'";

	   mysql_query($sql) or die("err:".$sql);

	   echo "<script>alert('添加产品成功');location.href='admin.php?act=product'</script>";

      

	 

	   exit; 

}



////编辑产品

if($act == "edit_product"){ 

 $id  = $_GET['id'];

 $sql = "select * from tgs_product where id=".$id." limit 1";

 $res = mysql_query($sql);

 $arr = mysql_fetch_array($res);

 $jibie  = $arr['jibie'];
 $proimg  = $arr['proimg'];
 $saytext  = $arr['saytext'];
 $proname  = $arr['proname'];

 

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">产品设置</a> <span class="c-gray en">&gt;</span> 编辑产品 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_product">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑产品名称</td></tr>

          <tr >

            <td width="20%"> 产品名称：</td>

            <td width="80%" ><input name="proname" type="text" class="input-text" id="proname" style="width:300px" value="<?php echo $proname?>" size="20"></td>

          </tr>
       <tr >

            <td width="20%"> 别级：</td>

            <td width="80%" ><input name="jibie" type="text" class="input-text" id="jibie" style="width:300px" value="<?php echo $jibie?>" size="20">请输入1-10之间的数字，数字越大，排名越高</td>

          </tr>
          
            <tr >

            

	<td>产品图片：</td>

           

	 <td><input style="width:300px" class="input-text" name="proimg" type="text" id="proimg" size="20" value="<?php echo $proimg;?>">

	 

     <input name="PrUpload" id="PrUpload" type="button" style="width:80px;" value="上传图片">                            



	 </td>

     </tr>
     
       <tr >

            <td>详细介绍：</td>
	
            <td><textarea name="content1" style="width:700px;height:200px;visibility:hidden;"><?php echo $saytext;?></textarea></td>
          


          </tr>
         

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
    </div></div>



<?php

}



////保存编辑的等级权限信息//////////////////////////////////////

if($act == "save_edit_product"){



       $id         = $_POST['id'];

	   $proname   = trim($_POST["proname"]);
	   $proimg   = trim($_POST["proimg"]);
	   $jibie   = trim($_POST["jibie"]);

	    $htmlData = '';
	   if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
	}


	   $a          = 0;

	   if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=dengji'</script>";

			   exit;

	  }

	  

	   
		



		   $sql="update tgs_product set proname='".$proname."',jibie='".$jibie."',proimg='".$proimg."',saytext='".$htmlData."' where id=".$id." limit 1";

	       mysql_query($sql) or die("err:".$sql);

		   $a= 1;

	


	   if($a == 1){

         echo "<script>alert('更新产品成功');</script>";

	   }else{

	     echo "<script>alert('更新产品失败!!');</script>";

	   }

	   echo "<script>window.location.href='?act=product'</script>";



	   exit; 



}

////删除等级//////////////////////////////////////

if($act == "delete_product"){



      $id         = $_GET['id'];

	   

	  if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }



	  

	  $sql="delete from tgs_product where id=".$id." limit 1";

	  mysql_query($sql) or die("err:".$sql);

		 

	   

      echo "<script>alert('删除成功！');</script>";

	  echo "<script>window.location.href='?act=product'</script>";

	  exit; 



}


////csv读取函数

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