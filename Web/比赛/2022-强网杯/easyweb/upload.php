<?php
error_reporting(0);
require_once('class.php');

if(isset($_SESSION)){
    if(isset($_GET['fname'])?!empty($_GET['fname']):FALSE){
        $_FILES["file"]["name"] = $_GET['fname'];
    }
    $upload = new Upload();
    $upload->upload();
}else {
    die("<p class='tip'>guest can not upload file</p>");
}
?>