<?php
namespace app\index\controller;

use ZipArchive;

class Get
{
    public function index(){
        $zipfile = request()->get("zipfile");
        if(file_exists($zipfile)){
            echo "file exist";
        }else{
            echo "no such file";
        }
    }
}