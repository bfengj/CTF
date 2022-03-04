<?php
include_once "flag.php";
include_once "log.php";

if(file_exists(@$_GET["file"])){
    echo "file exist!";
}else{
    echo "file not exist!";
}

?>