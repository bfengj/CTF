<?php
/**
*	语言包
*/
class langChajian extends Chajian{
	
	//支持的语言包
	private $langArray	= array('zh-CN','en-US','zh-TW','jp');
	private $locallang	= 'zh-CN'; //默认的语言包
	
	/**
	*	初始化语言包
	*/
	public function initLang()
	{
		$moren= getconfig('locallang', $this->locallang);
		$lang = $this->rock->get('locallang', $moren);
		if(!in_array($lang, $this->langArray))$lang = $moren;
		if(!defined('LANG'))define('LANG', $lang);
		$langs 	= str_replace('-','_', $lang);
		$langr 	= str_replace('-','_', $moren);
		
		$obj  	= c('lang_'.$langs.'');
		$objmr  = c('lang_'.$langr.'');
		$data[$moren] 	= method_exists($objmr, 'getLang') ? $objmr->getLang() : array();
		$data[$lang] 	= method_exists($obj, 'getLang') ? $obj->getLang() : $data[$moren];
		$GLOBALS['langdata'] = $data;
	}
	
}