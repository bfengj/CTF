<?php 
/**
*	win下将word转html
*/
class officeChajian extends Chajian{


	public function tohtml($path)
	{
		if(!class_exists('COM'))return '没有开启COM组件';
		$word = new COM('Word.Application');
		$word->Visible = true; //可看见
		$word->Documents->Open($path);
		 
		$word->Documents[1]->SaveAs('upload/abc.html',8);
	}
}