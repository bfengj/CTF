<?php
//flag is /flag
$path=$_POST['path'];
$time=(isset($_GET['time'])) ? urldecode(date(file_get_contents('php://input'))) : date("Y/m/d H:i:s");
$name="/var/www/tmp/".time().rand().'.txt';
$black="f|ht|ba|z|ro|;|,|=|c|g|da|_";
$blist=explode("|",$black);
foreach($blist as $b){
    if(strpos($path,$b) !== false){
        die('111');
    }
}
if(file_put_contents($name, $time)){
    echo "<pre class='language-html'><code class='language-html'>logpath:$name</code></pre>";
}
$check=preg_replace('/((\s)*(\n)+(\s)*)/i','',file_get_contents($path));
if(is_file($check)){
    echo "<pre class='language-html'><code class='language-html'>".file_get_contents($check)."</code></pre>";
}
