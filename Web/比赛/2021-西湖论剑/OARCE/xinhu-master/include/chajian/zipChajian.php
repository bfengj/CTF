<?php 
class zipChajian extends Chajian{

	/**
	*	解压zip文件
	*/
	public function unzip($filename, $path){
		if(!function_exists('zip_open'))return 'php未开启zip模块';
		if(!file_exists($filename))return '文件不存在';
		@$resource = zip_open($filename);
		if(!$resource)return '无法打开文件';
		while ($dir_resource = zip_read($resource)){
			if(zip_entry_open($resource,$dir_resource)){
				$file_name = $path.zip_entry_name($dir_resource);
				$file_path = substr($file_name,0,strrpos($file_name, "/"));
				if(!is_dir($file_path))mkdir($file_path,0777,true);
				if(!is_dir($file_name)){
					$file_size 		= zip_entry_filesize($dir_resource);
					$file_content 	= zip_entry_read($dir_resource, $file_size);
					$bos 			= $this->writefile($file_name, $file_content);
					if(!$bos)return '无权限写入文件:'.$file_name.'';
				}
				zip_entry_close($dir_resource);
			}
		}
		zip_close($resource); 
		return 'ok';
	}
	
	private function writefile($file_name, $file_content)
	{
		$oldcont 		= '';
		if(file_exists($file_name)){
			$oldcont 	= file_get_contents($file_name);
			if($oldcont != $file_content){
				$barfile = ''.UPDIR.'/upage/'.$file_name.'';
				$this->rock->createdir($barfile);
				copy($file_name, $barfile);
			}
		}
		@$bos 			= file_put_contents($file_name,$file_content);
		return $bos;
	}
	
	/**
	*	获取zip上文件
	*/
	public function zipget($filename){
		if(!function_exists('zip_open'))return 'php未开启zip模块';
		if(!file_exists($filename))return '文件不存在';
		@$resource = zip_open($filename);
		if(!$resource)return '无法打开文件';
		$farr = array();
		while ($dir_resource = zip_read($resource)){
			if(zip_entry_open($resource,$dir_resource)){
				$file_name = zip_entry_name($dir_resource);
				$file_path = substr($file_name,0,strrpos($file_name, "/"));
				if(!is_dir($file_name) && substr($file_name,-1)!='/'){
					$file_size 		= zip_entry_filesize($dir_resource);
					$file_content 	= zip_entry_read($dir_resource, $file_size);
					if(substr($file_name,0,1)=='/')$file_name = substr($file_name,1);
					$farr[] = array(
						'filepath' => $file_name,
						'filesize' => $file_size,
						'filecontent' => base64_encode($file_content),
					);
				}
				zip_entry_close($dir_resource);
			}
		}
		zip_close($resource); 
		return $farr;
	}
	
	
	private function addFileToZip($path, $zip, $wz){
		$handler = opendir($path); 
		while(($filename=readdir($handler))!==false){
			if($filename != '.' && $filename != '..'){
				$addfile = $path.'/'.$filename;
				if(is_dir($addfile)){
					$this->addFileToZip($addfile, $zip, $wz);
				}else{
					$localwz = substr($addfile, $wz);
					$zip->addFile($addfile, $localwz);
				}
			}
		}
		@closedir($path);
	}
	
	/**
	*	zip打包
	*/
	public function packzip($path, $topath)
	{
		$zip	=	new ZipArchive();
		if($zip->open($topath, ZipArchive::OVERWRITE)=== TRUE){
			$this->addFileToZip($path, $zip, strlen($path));
			$zip->close();
		}
		if(!file_exists($topath))$topath='';
		return $topath;
	}
}