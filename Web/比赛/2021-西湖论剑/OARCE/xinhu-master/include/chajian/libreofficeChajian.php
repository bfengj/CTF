<?php
/**
*	用libreoffice相关linux和win都可以用
*/
class libreofficeChajian extends Chajian{
	
	/**
	*	转pdf
	*/
	public function filetopdf($fileid)
	{
		$path = getconfig('libreoffice_path');
		if(isempt($path))return returnerror('未配置libreoffice的路径');
		
		$frs 		= m('file')->getone($fileid);
		if(!$frs)return returnerror('文件不存在');
		$filepath 	= $frs['filepath'];
		$pdfpath 	= $frs['pdfpath'];
		if(!isempt($pdfpath) && file_exists($pdfpath))return returnerror('已经转化过了');;
		
		
		$fpath 		= ''.ROOT_PATH.'/'.$filepath.'';
		$outdir		= substr($fpath,0, strripos($fpath,'/'));
		if(contain(PHP_OS,'WIN')){
			$cmd 	= '"'.str_ireplace('LibreOffice','libreoffice',$path).'\program\soffice.exe"';
			$fpath 	= str_replace('/','\\', $fpath);
			$outdir = str_replace('/','\\', $outdir);
		}else{
			$cmd = $path;
		}
		$cmd .=' --headless --invisible --convert-to pdf:writer_pdf_Export "'.$fpath.'" --outdir "'.$outdir.'"';
		
		return c('rockqueue')->pushcmd($cmd);
	}
}