
<?php

include 'head.php';

$act = $_GET["act"];






?>

<?php

////系统相关设置

if($act == "config"){  

?>

<link rel="stylesheet" href="../editor/themes/default/default.css" />

<script charset="utf-8" src="../editor/kindeditor-all.js"></script>
<script charset="utf-8" src="../editor/lang/zh-CN.js"></script>



<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '../editor/php/upload_json.php?TableField=web_article',
		fileManagerJson : '../editor/php/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#LogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Logo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Logo').val(url);
					K('#LogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ShareLogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ShareLogo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ShareLogo').val(url);
					K('#ShareLogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
		K('#ReplyImgUpload1').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ReplyImgPath1').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ReplyImgPath1').val(url);
					K('#ReplyImgDetail1').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ReplyImgUpload2').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ReplyImgPath2').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ReplyImgPath2').val(url);
					K('#ReplyImgDetail2').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ReplyImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ReplyImgPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ReplyImgPath').val(url);
					K('#ReplyImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
<style type="text/css">
#config_form img{width:100px; height:100px;}

</style>



<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">模板风格管理</a> <span class="c-gray en">&gt;</span> 常用图片管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_config">   
    
    <table width="100%" border="1">
  <tbody>
    <tr>
      <td align="center">默认LOGO</td>
      <td align="left"><div id="card_style">
                            <div class="file">
                                <span class="fc_red">（请上传png透明格式）</span><br />
                                <span class="tips">&nbsp;&nbsp;尺寸建议：400*100px</span><br /><br />
                                <input name="LogoUpload" id="LogoUpload" type="button" style="width:80px;" value="上传图片" /><br /><br />
                               
								<input type="hidden" id="Logo" name="cf[site_logo]" value="<?php echo $cf['site_logo']?>" />
                            </div>
                            <div class="clear"></div>
                        </div></td>
      <td align="center"> <span class="fc_red">（请上传png透明格式）</span><br />
                                <span class="tips">&nbsp;&nbsp;尺寸建议：400*100px<br>
 所有模板通用（默认模板、申请注册页面LOGO）<br>
 <div class="img" id="LogoDetail">
                                <img src="<?php echo $cf['site_logo']?>" />								
                                </div></span><br />
                               
        <br /></td>
    </tr>
    <tr>
      <td>代理页面LOGO</td>
      <td align="center"><div id="card_style">
                            <div class="file">
                                <span class="tips">&nbsp;&nbsp;尺寸建议：400*220px</span><br><br>
                                <input name="ShareLogoUpload" id="ShareLogoUpload" type="button" style="width:80px;" value="上传图片"><br><br>
                              
								<input type="hidden" id="ShareLogo" name="cf[agent_logo]" value="<?php echo $cf['agent_logo']?>">
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                        </td>
      <td align="center" bgcolor="#cccccc"><div class="img" id="ShareLogoDetail">
                                <img src="<?php echo $cf['agent_logo']?>" height="100px" />								
                                全站通用</div> </td>
    </tr>
    <tr>
      <td>防伪页面LOGO</td>
      <td align="center"><div id="card_style">
                            <div class="file">
                                <span class="tips">&nbsp;&nbsp;尺寸建议：400*220px</span><br><br>
                                <input name="ReplyImgUpload" id="ReplyImgUpload" type="button" style="width:80px;" value="上传图片"><br><br>
                              
								<input type="hidden" id="ReplyImgPath" name="cf[fw_logo]" value="<?php echo $cf['fw_logo']?>">
                            </div>
                            <div class="clear"></div>
                        </div></td>
      <td align="center" bgcolor="#cccccc"><div class="img" id="ReplyImgDetail">
                                <img src="<?php echo $cf['fw_logo']?>" height="100px" />								
                                全站通用</div></td>
    </tr>
    <tr>
      <td>PC 端自定义背景</td>
      <td align="center"><div id="card_style">
                            <div class="file">
                                <span class="tips">&nbsp;&nbsp;尺寸建议：1920*1024px</span><br><br>
                                <input name="ReplyImgUpload1" id="ReplyImgUpload1" type="button" style="width:80px;" value="上传图片"><br><br>
                              
								<input type="hidden" id="ReplyImgPath1" name="cf[bgpic]" value="<?php echo $cf['bgpic']?>">
                            </div>
                            <div class="clear"></div>
                        </div></td>
      <td align="center"><div class="img" id="ReplyImgDetail1">
                                <img src="<?php echo $cf['bgpic']?>" height="100px" />								
                                仅适用于可自定义背景的模板</div></td>
    </tr>
    <tr>
      <td>手机端自定义背景</td>
      <td align="center"><div id="card_style">
                            <div class="file">
                                <span class="tips">&nbsp;&nbsp;尺寸建议：500*800px</span><br><br>
                                <input name="ReplyImgUpload2" id="ReplyImgUpload2" type="button" style="width:80px;" value="上传图片"><br><br>
                              
								<input type="hidden" id="ReplyImgPath2" name="cf[mbgpic]" value="<?php echo $cf['mbgpic']?>">
                            </div>
                            <div class="clear"></div>
                        </div></td>
      <td align="center"><div class="img" id="ReplyImgDetail2">
                                <img src="<?php echo $cf['mbgpic']?>" height="100px" />								
                仅适用于可自定义背景的模板</div></td>
    </tr>
    
      <tr >
        
        <td>&nbsp;</td>
        
        <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 保 存 " ></td>
        
        <td></td>
        
      </tr>

  </tbody>
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





?>

</body>

</html>










                    
                        
</body>
</html>