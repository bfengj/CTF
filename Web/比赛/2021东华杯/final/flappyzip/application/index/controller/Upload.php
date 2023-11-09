<?php
namespace app\index\controller;
class Upload
{
    public function index(){
        $file = request()->file("zipfile");
        if($file){
            $fileData = $file->get();
            //echo  substr($fileData,0,2);
            if (preg_match("/<\?|php|flag|filter/i", $fileData) || !$file->checkFile() ){
                die("error");
            }
            $info = $file->validate(['ext'=>'zip'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                echo $info->getSaveName()."\n";
                echo "upload success";
            }else{
                echo $file->getError();
            }
        }else{
            echo "only zip file";
        }
    }
}