
<?php
   include_once("config.php");
   $juese = $_SESSION['juese'];
   $sql="select value from juese where id='$juese'";
   $requ=mysqli_query($con,$sql);
   $rs=mysqli_fetch_array($requ);
   $value=explode(",",$rs['value']);
   if (in_array('17',$value)== false) 
   {
    die('您没有权限访问该页面！！！');
   }

   $afile = uploadfile();
   echo($afile);
   function uploadfile($path="uploads",
    $allowExt = array('png','jpg','jpeg','gif','bmp'),
    $maxSize=5240880,$imgFlag=true){

	$result_arr = array();

    if (! file_exists($path)) {
        mkdir($path,0777,true);
    }
    $i = 0;
    $infoArr = buildInfo();
    foreach ($infoArr as $val) {
        if ($val['error'] === UPLOAD_ERR_OK) {
			$h = false;
            $ext = getExt($val['name']);
            for($j=0;$j<count($allowExt);$j++){
                if($ext == $allowExt[$j]){
                    //$m = "此文件适合上传标准";
                    $h = true;
                }else {
                    //$m = "此文件不可以被上传";
                }
            }
            if($h){
                $mes = "文件格式正确";
            }else{
                $mes = "文件格式错误";
				array_push($result_arr,array("status"=>-1,"msg"=>"文件格式错误"));
                continue; 
            }
            if($val['size']>$maxSize){
                $mes = "文件太大了";
				array_push($result_arr,array("status"=>-2,"msg"=>"文件太大了"));
                continue; 
            }
            if($imgFlag){
                $result = getimagesize($val['tmp_name']);
                if(!$result){
                    $mes = "您上传的不是一个真正图片";
					array_push($result_arr,array("status"=>-3,"msg"=>"不是图片"));
                    continue; 
                }
            }
            if(!is_uploaded_file($val['tmp_name'])){
               $mes = "不是通过httppost传输的";
			   array_push($result_arr,array("status"=>-4,"msg"=>"非法访问"));
               continue; 
            }

            $realName = md5($val['tmp_name'].microtime()).".".$ext;
            $destination = $path."/".$realName;
            if(move_uploaded_file($val['tmp_name'], $destination)){
                $val['name'] = $realName;
                unset($val['error'],$val['tmp_name'],$val['size'],$val['type']);

                //$uploadedFiles[$i]=$val;//
				array_push($result_arr,array("status"=>1,"msg"=>"上传成功","file"=>$realName));
                $i++;
            }
        }else {
            switch ($val['error']) {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $mes = "超过配置文件中上传文件的大小";
					$zt = -5;
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $mes = "超过表单中配置文件的大小";
					$zt = -6;
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $mes = "文件被部分上传";
					$zt = -7;
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $mes = "没有文件被上传";
					$zt = -8;
                    break;
                case 6: // UPLOAD_ERR_NO_TMP_DIR
                    $mes = "没有找到临事文件目录";
					$zt = -9;
                    break;
                case 7: // UPLOAD_ERR_CANT_WRITE
                    $mes = "文件不可写";
					$zt = -10;
                    break;
                case 8: // UPLOAD_ERR_EXTENSION
                    $mes = "php扩展程序中断了上传程序";
					$zt = -11;
                    break;
            }
            array_push($result_arr,array("status"=>$zt,"msg"=>$mes));
        }
    }

    return json_encode($result_arr);
}

   
   
   function buildInfo(){
//     $info = $_FILES;
    $i = 0;
    foreach ($_FILES as $v){//三维数组转换成2维数组
        if(is_string($v['name'])){ //单文件上传
            $info[$i] = $v;
            //$i++;
        }else{ // 多文件上传
            foreach ($v['name'] as $key=>$val){//2维数组转换成1维数组
                //取出一维数组的值，然后形成另一个数组
                //新的数组的结构为：info=>i=>('name','size'.....)
                $info[$i]['name'] = $v['name'][$key];
                $info[$i]['size'] = $v['size'][$key];
                $info[$i]['type'] = $v['type'][$key];
                $info[$i]['tmp_name'] = $v['tmp_name'][$key];
                $info[$i]['error'] = $v['error'][$key];
                $i++;
            }
        }
    }
    return $info;
}


/**
 * 得到文件的扩展名
 * @param unknown $fileName
 * @return string
 */
function getExt($fileName){
	$a=explode('.',$fileName);
	$b=end($a);
	$c=strtolower($b);
    return $c;
    /**
     * strtolower() 函数把字符串转换为小写。
     * end()输出数组中最后一个元素的值
     * explode(),拆分字符串
     */
}

?>