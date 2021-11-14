<?php
function extend($file_name) 
{ 
	$retval = ""; 
	$pt     = strrpos($file_name, ".");
	if ($pt) $retval=substr($file_name, $pt+1, strlen($file_name) - $pt); 
	return ($retval); 
}

///内容转换后插入表中,单引号双引号转化为特殊字符均可插入内容中
function strreplace($content) 
{ 
	$conntent = str_replace("<","&lt;",$content);
	$conntent = str_replace(">","&gt;",$content);
	$content  = str_replace("\"","&quot;",$content);
	$content  = str_replace("'","‘",$content);
	
	return ($content); 
}

///内容转换后显示,特殊字符转化为标准html
function unstrreplace($content) 
{ 
	$conntent = str_replace("&lt;","<",$content);
	$conntent = str_replace("&gt;",">",$content);
	$content  = str_replace("&quot;","\"",$content);
	$content  = str_replace("‘","'",$content);	
	return ($content); 
}

///判断后输出是或否
function boolean($c) 
{ 
	if ($c=="1"){
	   $co = "是";
	}else{
	  $co = "否";
	}
	return ($co); 
} 

//清除特定字符 ///查找$string 字符串里面有没有 $pattern 这个数组里的东西,如果有就替换成$replacement 
function clear($string) {
    $pattern = array("'","\"","","or","&&","!=");
    $replacement = "";
    return preg_replace($pattern,$replacement,$string);
}//


///随机字符串，字母＋数字
function genRandomString($len,$t=0) 
{ 
    $chars = array( 
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
        "l", "m", "n", "p", "q", "r", "s", "t", "u", "v",  
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",  
        "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R",  
        "S", "T", "U", "V", "W", "X", "Y", "Z", "2",  
        "3", "4", "5", "6", "7", "8", "9" 
    ); 

	$chars1= array( 
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
        "l", "m", "n", "p", "q", "r", "s", "t", "u", "v",  
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",  
        "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R",  
        "S", "T", "U", "V", "W", "X", "Y", "Z"
    ); 

	$chars2 = array( 
        "1", "2", "0", 
        "3", "4", "5", "6", "7", "8", "9" 
    );
	$chars3 = array( 
        "A", "B", "C", "D", "E", "F", "G", "O" ,
        "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R",  
        "S", "T", "U", "V", "W", "X", "Y", "Z"
    );
	
	if($t==1){

        $charsLen = count($chars1) - 1; 
	 
		shuffle($chars1);    // 将数组打乱 
		 
		$output = ""; 
		for ($i=0; $i<$len; $i++) 
		{ 
			$output .= $chars1[mt_rand(0, $charsLen)]; 
		} 

	}elseif($t==2){
       
	   $charsLen = count($chars2) - 1; 
	 
		shuffle($chars2);    // 将数组打乱 
		 
		$output = ""; 
		for ($i=0; $i<$len; $i++) 
		{ 
			$output .= $chars2[mt_rand(0, $charsLen)]; 
		} 

    }elseif($t==3){
       
	   $charsLen = count($chars3) - 1;
	 
		shuffle($chars3);    // 将数组打乱 
		 
		$output = ""; 
		for ($i=0; $i<$len; $i++) 
		{ 
			$output .= $chars3[mt_rand(0, $charsLen)]; 
		} 

	}else{

		$charsLen = count($chars) - 1; 
	 
		shuffle($chars);    // 将数组打乱 
		 
		$output = ""; 
		for ($i=0; $i<$len; $i++) 
		{ 
			$output .= $chars[mt_rand(0, $charsLen)]; 
		} 
	}
	
    return $output; 
 
}

/**
 * 文件或目录权限检查函数
 *
 * @access          public
 * @param           string  $file_path   文件路径
 * @param           bool    $rename_prv  是否在检查修改权限时检查执行rename()函数的权限
 *
 * @return          int     返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
 *                          返回值在二进制计数法中，四位由高到低分别代表
 *                          可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
 */
function file_mode_info($file_path)
{
    /* 如果不存在，则不可读、不可写、不可改 */
    if (!file_exists($file_path))
    {
        return false;
    }
    $mark = 0;
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    {
        /* 测试文件 */
        $test_file = $file_path . '/cf_test.txt';
        /* 如果是目录 */
        if (is_dir($file_path))
        {
            /* 检查目录是否可读 */
            $dir = @opendir($file_path);
            if ($dir === false)
            {
                return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
            }
            if (@readdir($dir) !== false)
            {
                $mark ^= 1; //目录可读 001，目录不可读 000
            }
            @closedir($dir);
            /* 检查目录是否可写 */
            $fp = @fopen($test_file, 'wb');
            if ($fp === false)
            {
                return $mark; //如果目录中的文件创建失败，返回不可写。
            }
            if (@fwrite($fp, 'directory access testing.') !== false)
            {
                $mark ^= 2; //目录可写可读011，目录可写不可读 010
            }
            @fclose($fp);
            @unlink($test_file);
            /* 检查目录是否可修改 */
            $fp = @fopen($test_file, 'ab+');
            if ($fp === false)
            {
                return $mark;
            }
            if (@fwrite($fp, "modify test.\r\n") !== false)
            {
                $mark ^= 4;
            }
            @fclose($fp);
            /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false)
            {
                $mark ^= 8;
            }
            @unlink($test_file);
        }
        /* 如果是文件 */
        elseif (is_file($file_path))
        {
            /* 以读方式打开 */
            $fp = @fopen($file_path, 'rb');
            if ($fp)
            {
                $mark ^= 1; //可读 001
            }
            @fclose($fp);
            /* 试着修改文件 */
            $fp = @fopen($file_path, 'ab+');
            if ($fp && @fwrite($fp, '') !== false)
            {
                $mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
            }
            @fclose($fp);
            /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false)
            {
                $mark ^= 8;
            }
        }
    }
    else
    {
        if (@is_readable($file_path))
        {
            $mark ^= 1;
        }
        if (@is_writable($file_path))
        {
            $mark ^= 14;
        }
    }
    return $mark;
}

///获取网站配置参数
/// return array
function get_site_config($g = ""){
    
	$arr = array();
	$sql5 = "SELECT code,code_value FROM tgs_config where 1";
	if($g === ""){
     $sql5.=" and parentid>0";
	}else{
      $sql5.=" and parentid=".$g."";
	}
	$res5 = mysql_query($sql5);
    
	while($row5 = mysql_fetch_array($res5))
    {
        $arr[$row5['code']] = $row5['code_value'];
    }

	/* 对变量进行格式化处理 */
	//$arr['points_method']             = intval($arr['points_method']);
    //$arr['piclist_open']              = intval($arr['piclist_open']) >0 ? intval($arr['piclist_open']) :0;

	$arr['yzm_status']              = intval($arr['yzm_status']);

	$arr['notice_1']              = ($arr['notice_1']);
	$arr['notice_2']              = ($arr['notice_2']);
	$arr['notice_3']              = ($arr['notice_3']);
	$arr['notices']               = ($arr['notices']);
	
	$arr['agent_1']              = ($arr['agent_1']);
	$arr['agent_2']              = ($arr['agent_2']);
	$arr['agent_3']              = ($arr['agent_3']);
	$arr['agents']               = ($arr['agents']);

	/*部分变量进行全局化*/
	$GLOBALS['cfg']['site_name']      = $arr['site_name'];
	$GLOBALS['cfg']['site_url']       = $arr['site_url'];

	$GLOBALS['cfg']['timezone']       = $arr['cf_timezone'];
	$GLOBALS['cfg']['time_format']    = $arr['time_format'];

	return $arr;
}


?>