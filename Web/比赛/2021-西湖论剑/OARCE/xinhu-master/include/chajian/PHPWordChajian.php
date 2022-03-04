<?php 
/**
*	PHPWord类
*/

class PHPWordChajian extends Chajian{
	
	private $vendorbool=false;
	
	protected function initChajian()
	{
		$path = ''.ROOT_PATH.'/include/vendor/autoload.php';
		if(file_exists($path)){
			require_once($path);
			$this->vendorbool = true;
		}
	}
	
	public function isbool()
	{
		return $this->vendorbool;
	}
	
	public function test()
	{
		if(!$this->vendorbool)return;
		
		\PhpOffice\PhpWord\Settings::loadConfig();
		
		\PhpOffice\PhpWord\Settings::setPdfRenderer(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF, ''.ROOT_PATH.'/include/vendor/dompdf/dompdf');
		\PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
		
		$languageEnGb = new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::EN_GB);

		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->getSettings()->setThemeFontLang($languageEnGb);
		
		
		$section = $phpWord->addSection();
		// Adding Text element to the Section having font styled by default...
		$section->addText(
			'"Learn from yesterday, live for today, hope for tomorrow. '
				. 'The important thing is信呼 not to stop questioning." '
				. '(Albert Einstein)'
		);
		//$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		//$objWriter->save('wfewew.docx');
		$phpWord->save('helloWorld'.time().'.pdf','PDF');
		
		
		
	}
	
	/**
	*	只能替换.docx
	*/
	public function replaceWord($path, $data=array(), $npath='')
	{
		if(!$this->vendorbool)return returnerror('未安装插件');
		
		$PhpWord = new \PhpOffice\PhpWord\TemplateProcessor($path);
		foreach($data as $k=>$v)$PhpWord->setValue($k, $v);

		if($npath=='')$npath = str_replace('.docx',''.rand(1000,9999).'.docx', $path);
		$PhpWord->saveAs($npath);
		
		return returnsuccess($npath);
	}
}                         