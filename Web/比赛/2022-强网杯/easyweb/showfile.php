<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>神奇的照片墙</title>
    <!-- css -->
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            background-color: rgba(0,0,0,0.71);
        }
        img {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50% , -50%);
        }
        p.tip {
            padding-top: 10%;
            text-align: center;
            color: blanchedalmond;
            font-size: 3em;
            font-family: Papyrus, serif;
        }
    </style>
</head>

<body>
<?php
error_reporting(0);
require_once('class.php');
$filename = $_GET['f'];


if(preg_match("/http|https|bzip2|gopher|dict|zlib|data|input|%00/i", $filename)){
    die("nop");
}
else{
    if(isset($_SESSION)){
        $show = new AdminShow($filename);
        $show->show();
    }else{
        if(preg_match('/guest|demo/i',$filename)) {
            $show = new GuestShow($filename);
            $show->show();
        }else{
            die("<p class='tip'>no permission, you can only see string 'demo' and 'guest'</p>");
        }
    }
}
?>
