<?php
require_once './zipzip.php';
header("Content-type:text/html;charset=utf-8");
 $dir='/tmp';
 $listpath='/uploads/';
 $path=$dir.$listpath;
  if (!file_exists($path)){
            mkdir ($path,0777,true);
   }
 $tmpname=$_FILES['file']['tmp_name'];
 $filename=$_FILES['file']['name'];
 if(preg_match('/^\w+\.(zip)$/', $filename)===0){
	 exit("æä»¶åæ ¼å¼éè¯¯ï¼$filename");
 }
 
 $filepath=$path.'/'.$filename; 
 
 if(move_uploaded_file($tmpname,$filepath)){
    $z = new Unzip();
    $z->unzip($filepath,$path);
     $result['status'] = 1;
     $result['message'] = "æä»¶ä¸ä¼ æå";
	 $result['path'] = $path;
 }else{
      $result['status'] = 0;
      $result['message'] = "æä»¶ä¸ä¼ å¤±è´¥";
 }
 echo json_encode($result);