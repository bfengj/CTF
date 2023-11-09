<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
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
            background-image: url("pinkk.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<?php
include "config.inc.php";

$file = $_FILES["file"];
if ($file["error"] == 0) {
    if($_FILES["file"]['size'] > 0 && $_FILES["file"]['size'] < 102400) {
        $typeArr = explode("/", $file["type"]);
        $imgType = array("png","jpg","jpeg");
        if(!$typeArr[0]== "image" | !in_array($typeArr[1], $imgType)){
            exit("type error");
        }
        $blackext = ["php", "php5", "php3", "html", "swf", "htm","phtml"];
        $filearray = pathinfo($file["name"]);
        $ext = $filearray["extension"];
        if(in_array($ext, $blackext)) {
            exit("extension error");
        }
        $imgname = md5(time()).".".$ext;
        if(move_uploaded_file($_FILES["file"]["tmp_name"],"./".$imgname)) {
            array_push($userfile, $imgname);
            setcookie("userfile", serialize($userfile), time() + 3600*10);
            $msg = e("file: {$imgname}");
            echo $msg;
        } else {
            echo "upload failed!";
        }
    }
}else{
    exit("error");
}
?>






