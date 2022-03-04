<?php 
/**
*	PHPExcel类
*/
class PHPExcelChajian extends Chajian{
	
	public  $excel				= null;
	public  $sheetObj;
	public	$headlen			= 0;

	public  $headbgcolor		= 'CDF79E';
	public  $bordercolor		= '000000';
	public  $headfontcolor		= '';
	public	$headfontbold		= false;
	public 	$borderbool			= true;
	
	public 	$title				= '';
	public 	$titlebool			= true;
	public 	$titlebgbool		= false;
	
	public 	$sheettitle			= '';
	public 	$headArr			= array();
	public 	$rows				= array();
	
	
	public 	$titleboolArray		= array();
	public  $titleArray			= array();
	public  $headArrArray		= array();
	public  $rowsArray			= array();
	
	public  $pageCode			= 'utf-8';
	public  $code				= 'utf-8';
	public 	$createbool			= false;
	
	public function initChajian()
	{
		$this->A	= explode(',','A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,AS,AT,AU,AV,AW,AX,AY,AZ,BA,BB,BC,BD,BE,BF,BG,BH,BI,BJ,BK,BL,BM,BN,BO,BP,BQ,BR,BS,BT,BU,BV,BW,BX,BY,BZ,CA,CB,CC,CD,CE,CF,CG,CH,CI,CJ,CK,CL,CM,CN,CO,CP,CQ,CR,CS,CT,CU,CV,CW,CX,CY,CZ');
		$this->headWidth= array();
		$pastr = ROOT_PATH.'/include/PHPExcel.php';
		if(file_exists($pastr)){
			include_once($pastr);
			$this->excel 	= new PHPExcel();
		}
	}
	
	public function isBool()
	{
		if($this->excel==null){
			return false;
		}else{
			return true;
		}
	}
	
	/**
		设置表头
	*/
	private function setHead($sheet=0)
	{
		$arrh	= $this->headArr;
		$title	= $this->sheettitle;
		if($title=='')$title = $this->title;
		$this->headWidth	 = array();
		if($sheet>0)$this->excel->createSheet();

		$this->excel->setActiveSheetIndex($sheet);//设置当前的sheet工作簿
		$this->sheetObj	= $this->excel->getActiveSheet();
		$this->sheetObj->setTitle($title);//设置sheet的工作簿标题

		$k	= 0;
		if($sheet == 0){
			$this->excel->getProperties()->setCreator('rock'); 		//创建者
			$this->excel->getProperties()->setLastModifiedBy('rock');  //最后修改
			$this->excel->getProperties()->setTitle('rock');  			//设置标题
			$this->excel->getProperties()->setSubject('rock');  		//设置备注
			$this->excel->getProperties()->setDescription('rock');  	//设置描述
			$this->excel->getProperties()->setKeywords('rock');  		//设置关键字 | 标记
			$this->excel->getProperties()->setCategory('rock');  		//设置类别
		}
		
		$this->headlen	= -1;
		foreach($arrh as $_arrh)$this->headlen++;
		$this->rowslen	= 0;
		if(is_array($this->rows))$this->rowslen=count($this->rows);//长度
		if($this->headlen==-1)return false;

		//整体添加边框颜色，居中
		$zta		= ($this->titlebool)?2:1;
		$getStyle	= $this->sheetObj->getStyle('A1:'.$this->A[$this->headlen].''.($this->rowslen+$zta).'');
		$getStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
		$getStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);//水平居中
		
		if($this->borderbool){
			$getStyle->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);//边框
			if($this->bordercolor!='000000')$getStyle->getBorders()->getAllborders()->getColor()->setARGB('FF'.$this->bordercolor.'');//边框颜色
		}

		//设置头部标题
		if($this->titlebool){
			$this->sheetObj->mergeCells('A1:'.$this->A[$this->headlen].'1');  //合并单元格
			$this->sheetObj->setCellValue('A1', $title);
			$this->sheetObj->getRowDimension(1)->setRowHeight(30); //设置行高
			$getStyle	= $this->sheetObj->getStyle('A1');
			$getStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$getStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直剧中
			$getStyle->getFont()->setBold(true);
			$getStyle->getFont()->setSize(16);
			//标题背景颜色
			if($this->titlebgbool){
				$getStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$getStyle->getFill()->getStartColor()->setARGB('FF'.$this->headbgcolor.'');
			}
		}
		
		//设置表头列标题
		if($this->headfontbold)$this->sheetObj->getStyle('A'.$zta.':'.$this->A[$this->headlen].''.$zta.'')->getFont()->setBold(true);//标题是否加粗
		foreach($arrh as $key=>$_arrh){
			$name			= $_arrh;
			$xlsfontcolor	= $this->headfontcolor;
			$xlsbgcolor		= $this->headbgcolor;
			$xlsbordercolor	= $this->bordercolor;
			$xlsfontsize	= '';//字体大小
			$xlswidth		= 0;		//宽度
			$xlsalign		= 'center';	//对齐方式
			$xlsbold		= false;	//是否加粗
			if(is_array($_arrh)){
				if(isset($_arrh['xlsfontcolor']))$xlsfontcolor=$_arrh['xlsfontcolor'];
				if(isset($_arrh['xlsbgcolor']))$xlsbgcolor=$_arrh['xlsbgcolor'];
				if(isset($_arrh['xlsbordercolor']))$xlsbordercolor=$_arrh['xlsbordercolor'];
				if(isset($_arrh['xlswidth']))$xlswidth=$_arrh['xlswidth'];
				if(isset($_arrh['xlsalign']))$xlswidth=$_arrh['xlsalign'];
				if(isset($_arrh['xlsbold']))$xlsbold=$_arrh['xlsbold'];
				if(isset($_arrh['xlsfontsize']))$xlsfontsize=$_arrh['xlsfontsize'];
				$name= $_arrh['name'];
			}
			$this->headWidth[$key] = array(strlen($name), $xlswidth);	//设置宽度
			//设置样式
			$vk	= ''.$this->A[$k].'2';
			
			if(!$this->titlebool)$vk = ''.$this->A[$k].'1';
			$getStyle	= $this->sheetObj->getStyle($vk);
			
			//边框
			if($xlsbordercolor!='000000')$getStyle->getBorders()->getAllborders()->getColor()->setARGB('FF'.$xlsbordercolor.''); 
				
			
			//设置背景色
			$getStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$getStyle->getFill()->getStartColor()->setARGB('FF'.$xlsbgcolor.'');
			
			//设置字体颜色加粗，大小
			if($xlsfontcolor!='')$getStyle->getFont()->getColor()->setARGB('FF'.$xlsfontcolor.'');
			if($xlsbold)$getStyle->getFont()->setBold(true);
			if($xlsfontsize!='')$getStyle->getFont()->setSize($xlsfontsize);
			
			//设置对齐方式
			if($xlsalign!='center')$getStyle->getAlignment()->setHorizontal($xlsalign);
			
			$this->sheetObj->setCellValue($vk, $name);
			$k++;
		}
	}
	
	
	/**
		添加数据
	*/
	private function setData()
	{
		$rows	= $this->rows;
		if(!is_array($rows))return false;
		$arrh	= $this->headArr;
		$zta	= ($this->titlebool)?3:2;
		//添加数据
		foreach($rows as $r=>$rs){
			if(!is_array($rs))continue;
			$k			= 0;
			$xlsmerge	= '';
			$xlsbgcolor	= '';
			$xua		= $r+$zta;
			if(isset($rs['xlsmerge']))$xlsmerge=$rs['xlsmerge'];
			if(isset($rs['xlsbgcolor']))$xlsbgcolor=$rs['xlsbgcolor'];
			
			//整行背景色
			if($xlsbgcolor!=''){
				$this->sheetObj->getStyle('A'.$xua.':'.$this->A[$this->headlen].''.$xua.'')
				->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()->setARGB('FF'.$xlsbgcolor.'');
			}
			
			//判断是否有合并单元格的(A:D)
			if($xlsmerge!=''){
				$ncta	= explode(':',$xlsmerge);
				$this->sheetObj->mergeCells(''.$ncta[0].''.$xua.':'.$ncta[1].''.$xua.'');
			}
			
			foreach($arrh as $key=>$_arrh){
				if(!isset($rs[$key])){
					$k++;
					continue;
				}
				$vk	= ''.$this->A[$k].''.$xua.'';
				$xlsfontcolor	= $xlsbgcolor = '';
				$xlsbordercolor	= $this->bordercolor;
				$xlsunderline	= '';
				$xlsfontsize	= '';
				$xlsalign		= 'center';	//对齐方式
				$xlsbold		= false;	//是否加粗
				$xlsitalic		= false;	//是否斜体
				$val			= $rs[$key];
				$vallen			= strlen(''.$val.'');
				if($this->headWidth[$key][0]<$vallen)$this->headWidth[$key][0]=$vallen;
				if(is_array($_arrh)){
					if(isset($_arrh['xlsfontcolor']))$xlsfontcolor=$_arrh['xlsfontcolor'];
					if(isset($_arrh['xlsbgcolor']))$xlsbgcolor=$_arrh['xlsbgcolor'];
					if(isset($_arrh['xlsbordercolor']))$xlsbordercolor=$_arrh['xlsbordercolor'];
					if(isset($_arrh['xlsalign']))$xlsalign=$_arrh['xlsalign'];
				}
				
				if(isset($rs['xlsfontcolor']))$xlsfontcolor=$rs['xlsfontcolor'];
				if(isset($rs['xlsbordercolor']))$xlsbordercolor=$rs['xlsbordercolor'];
				if(isset($rs['xlsalign']))$xlsalign=$rs['xlsalign'];
				if(isset($rs['xlsbold']))$xlsbold=$rs['xlsbold'];
				if(isset($rs['xlsfontsize']))$xlsfontsize=$rs['xlsfontsize'];
				if(isset($rs['xlsunderline']))$xlsunderline=$rs['xlsunderline'];
				
				if(isset($rs[''.$key.'xlsfontcolor']))$xlsfontcolor=$rs[''.$key.'xlsfontcolor'];
				if(isset($rs[''.$key.'xlsbgcolor']))$xlsbgcolor=$rs[''.$key.'xlsbgcolor'];
				if(isset($rs[''.$key.'xlsbordercolor']))$xlsbordercolor=$rs[''.$key.'xlsbordercolor'];
				if(isset($rs[''.$key.'xlsalign']))$xlsalign=$rs[''.$key.'xlsalign'];
				if(isset($rs[''.$key.'xlsbold']))$xlsbold=$rs[''.$key.'xlsbold'];
				if(isset($rs[''.$key.'xlsfontsize']))$xlsfontsize=$rs[''.$key.'xlsfontsize'];
				if(isset($rs[''.$key.'xlsitalic']))$xlsitalic=$rs[''.$key.'xlsitalic'];
				if(isset($rs[''.$key.'xlsunderline']))$xlsunderline=$rs[''.$key.'xlsunderline'];
				
				$getStyle	= $this->sheetObj->getStyle($vk);
				//设置背景色
				if($xlsbgcolor!=''){
					$getStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$getStyle->getFill()->getStartColor()->setARGB('FF'.$xlsbgcolor.'');
				}
				
				//字体颜色
				if($xlsfontcolor!='')$getStyle->getFont()->getColor()->setARGB('FF'.$xlsfontcolor.'');
				if($xlsbold)$getStyle->getFont()->setBold(true);
				if($xlsitalic)$getStyle->getFont()->setItalic(true);//斜体
				if($xlsfontsize!='')$getStyle->getFont()->setSize($xlsfontsize);
				if($xlsunderline!='')$getStyle->getFont()->setUnderline($xlsunderline);//下划线情况double,双下划线、doubleAccounting,整个单元格双下划线、single,单下划线、singleAccounting,整个单元格单下划线
				
				//设置边框颜色
				if($xlsbordercolor!='000000')$getStyle->getBorders()->getAllborders()->getColor()->setARGB('FF'.$xlsbordercolor.''); 
				
				//对齐方式
				if($xlsalign!='center')$getStyle->getAlignment()->setHorizontal($xlsalign);
				
				$this->sheetObj->setCellValue($vk, $val);
				$k++;
			}
			
			//设置行高
			$xlsrowheight	= 0;
			if(isset($rs['xlsrowheight']))$xlsrowheight=$rs['xlsrowheight'];
			if($xlsrowheight != 0)$this->sheetObj->getRowDimension($r+$zta)->setRowHeight($xlsrowheight); 
		}
	}
	
	/**
		设置列宽
	*/
	private function setColwidth()
	{
		$k=0;
		foreach($this->headWidth as $key=>$v){
			$w	= $v[1];
			if($w<=0){
				$w= $v[0] * 1.2;
			}else{
				$w= $w/70*12;
			}
			if($w>0)$this->sheetObj->getColumnDimension($this->A[$k])->setWidth($w);
			$k++;
		}		
	}

	/**
		创建数据
	*/
	public function createData()
	{
		$len1	= count($this->titleArray);
		$len2	= count($this->headArrArray);
		$len3	= count($this->rowsArray);
		
		if($len1==$len2 && $len2==$len3 && $len1>0){
			for($i=0; $i<$len1; $i++){
				$this->sheettitle	= $this->titleArray[$i];
				$this->headArr		= $this->headArrArray[$i];
				$this->rows			= $this->rowsArray[$i];
				$this->titlebool	= true;
				if(isset($this->titleboolArray[$i]))$this->titlebool=$this->titleboolArray[$i];
				
				$this->setHead($i);
				$this->setData();
				$this->setColwidth();
			}
			$this->excel->setActiveSheetIndex(0);
		}else{
			$this->setHead(0);
			$this->setData();
			$this->setColwidth();
		}
		$this->createbool	= true;
	}
	
	/**
		输出显示
		@param	string  $ext   	输出类型 xls,xlsx
		@param	string  $type   是否直接下载
		@return string 文件名
	*/
	public function display($ext='xls', $type='down')
	{
		if(!$this->createbool){
			$this->createData();
		}
		
		$title	= $this->title;

		//有随机数
		$rand		= '';
		if(contain($type,'rand'))$rand	= date('YmdHis');
		$filename	= ''.$title.''.$rand.'.'.$ext.'';

		//输出
		if($ext!='xlsx'){
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		}else{
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); //保存excel—2007格式或者
		}
		$backfile = 'down';
		
		//保存文件
		if(contain($type,'savelogs')){
			$dir	= 'logs';
			$path	= ''.ROOT_PATH.'/'.UPDIR.'/'.$dir.'/';
			if(!is_dir($path))mkdir($path);
			$savefile	= $path.''.$this->iconvstr($filename).'';
			$objWriter->save($savefile); //是否保存本地
			$backfile	= ''.UPDIR.'/'.$dir.'/'.$filename.'';
		}
		
		//下载
		if(contain($type, 'down')){
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.iconv('utf-8', 'gbk', $filename).'"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
		
		return $backfile;
	}
	
	
	/**
		编码转化
	*/	
	private function iconvstr($str)
	{
		if($this->pageCode=='utf-8')$str	= iconv('utf-8', 'gbk', $str);
		return $str;
	}
	
	
	/**
		合并单元格
	*/
	public function mergeCells($cel1, $cel2, $val=null, $sheet=-1)
	{
		if($sheet != -1)$this->excel->setActiveSheetIndex($sheet);
		$this->excel->getActiveSheet()->mergeCells(''.$cel1.':'.$cel2.'');
		if($val != null)$this->sheetObj->setCellValue($cel1, $val);
	}
}                         