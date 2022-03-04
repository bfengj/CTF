<?php
error_reporting(0);
require 'vendor/autoload.php';
$latte = new Latte\Engine;
$latte->setTempDirectory('tempdir');
$policy = new Latte\Sandbox\SecurityPolicy;
$policy->allowMacros(['block', 'if', 'else','=']);
$policy->allowFilters($policy::ALL);
$policy->allowFunctions(['trim', 'strlen']);
$latte->setPolicy($policy);
$latte->setSandboxMode();
$latte->setAutoRefresh(false);

if(isset($_FILES['file'])){
    $uploaddir = '/var/www/html/tempdir/';
    $filename = basename($_FILES['file']['name']);
    if(stristr($filename,'p') or stristr($filename,'h') or stristr($filename,'..')){
        die('no');
    }
    $file_conents = file_get_contents($_FILES['file']['tmp_name']);
    if(strlen($file_conents)>28 or stristr($file_conents,'<')){
        die('no');
    }
    $uploadfile = $uploaddir . $filename;
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        $message = $filename ." was successfully uploaded.";
    } else {
        $message = "error!";
    }

    $params = [
        'message' => $message,
    ];
    $latte->render('tempdir/index.latte', $params);
}
else if($_GET['source']==1){
    highlight_file(__FILE__);
}
else{
    $latte->render('tempdir/index.latte', ['message'=>'Hellow My Glzjin!']);
}
