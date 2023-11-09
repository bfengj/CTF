<?php
class Upload {
    public $file;
    public $filesize;
    public $date;
    public $tmp;
    function __construct(){
        $this->file = $_FILES["file"];
    }
    function do_upload() {
        $filename = session_id().explode(".",$this->file["name"])[0].".jpg";
        if(file_exists($filename)) {
            unlink($filename);
        }
        move_uploaded_file($this->file["tmp_name"],md5("2022qwb".$_SERVER['REMOTE_ADDR'])."/".$filename);
        echo 'upload  '."./".md5("2022qwb".$_SERVER['REMOTE_ADDR'])."/".$this->e($filename).' success!';
    }
    function e($str){
        return htmlspecialchars($str);
    }
    function upload() {
        if($this->check()) {
            $this->do_upload();
        }
    }
    function __toString(){
        return $this->file["name"];
    }
    function __get($value){
        $this->filesize->$value = $this->date;
        echo $this->tmp;
    }
    function check() {
        $allowed_types = array("jpg","png","jpeg");
        $temp = explode(".",$this->file["name"]);
        $extension = end($temp);
        if(in_array($extension,$allowed_types)) {
            return true;
        }
        else {
            echo 'Invalid file!';
            return false;
        }
    }
}

class GuestShow{
    public $file;
    public $contents;
    public function __construct($file)
    {

        $this->file=$file;
    }
    function __toString(){
        $str = $this->file->name;
        return "";
    }
    function __get($value){
        return $this->$value;
    }
    function show()
    {
        $this->contents = file_get_contents($this->file);
        $src = "data:jpg;base64,".base64_encode($this->contents);
        echo "<img src={$src} />";
    }
    function __destruct(){
        echo $this;
    }
}


class AdminShow{
    public $source;
    public $str;
    public $filter;
    public function __construct($file)
    {
        $this->source = $file;
        $this->schema = 'file:///var/www/html/';
    }
    public function __toString()
    {
        $content = $this->str[0]->source;
        $content = $this->str[1]->schema;
        return $content;
    }
    public function __get($value){
        $this->show();
        return $this->$value;
    }
    public function __set($key,$value){
        $this->$key = $value;
    }
    public function show(){
        if(preg_match('/usr|auto|log/i' , $this->source))
        {
            die("error");
        }
        $url = $this->schema . $this->source;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        $src = "data:jpg;base64,".base64_encode($response);
        echo "<img src={$src} />";

    }
    public function __wakeup()
    {
        if ($this->schema !== 'file:///var/www/html/') {
            $this->schema = 'file:///var/www/html/';
        }
        if ($this->source !== 'admin.png') {
            $this->source = 'admin.png';
        }
    }
}