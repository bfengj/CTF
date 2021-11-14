<?php
error_reporting(0);
class Unzip{
  public function __construct(){
    //init code here...
    header("content-type:text/html;charset=utf8");
  }


  public function unzip($src_file, $dest_dir){
  
	  $unzip="unzip -o $src_file -d $dest_dir";
	  exec($unzip);
    return true;
  }

}