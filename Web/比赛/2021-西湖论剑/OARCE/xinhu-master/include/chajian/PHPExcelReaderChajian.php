<?php 
/**
*	PHPExcel 读取插件类
*/
class PHPExcelReaderChajian extends Chajian{
	
	public $A;
	public $AT;
	
	protected function initChajian()
	{
		$this->Astr	= 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,AS,AT,AU,AV,AW,AX,AY,AZ,BA,BB,BC,BD,BE,BF,BG,BH,BI,BJ,BK,BL,BM,BN,BO,BP,BQ,BR,BS,BT,BU,BV,BW,BX,BY,BZ,CA,CB,CC,CD,CE,CF,CG,CH,CI,CJ,CK,CL,CM,CN,CO,CP,CQ,CR,CS,CT,CU,CV,CW,CX,CY,CZ';
		$this->A	= explode(',', $this->Astr);
		$this->AT	= array('A'=>0,'B'=>1,'C'=>2,'D'=>3,'E'=>4,'F'=>5,'G'=>6,'H'=>7,'I'=>8,'J'=>9,'K'=>10,'L'=>11,'M'=>12,'N'=>13,'O'=>14,'P'=>15,'Q'=>16,'R'=>17,'S'=>18,'T'=>19,'U'=>20,'V'=>21,'W'=>22,'X'=>23,'Y'=>24,'Z'=>25,'AA'=>26,'AB'=>27,'AC'=>28,'AD'=>29,'AE'=>30,'AF'=>31,'AG'=>32,'AH'=>33,'AI'=>34,'AJ'=>35,'AK'=>36,'AL'=>37,'AM'=>38,'AN'=>39,'AO'=>40,'AP'=>41,'AQ'=>42,'AR'=>43,'AS'=>44,'AT'=>45,'AU'=>46,'AV'=>47,'AW'=>48,'AX'=>49,'AY'=>50,'AZ'=>51,'BA'=>52,'BB'=>53,'BC'=>54,'BD'=>55,'BE'=>56,'BF'=>57,'BG'=>58,'BH'=>59,'BI'=>60,'BJ'=>61,'BK'=>62,'BL'=>63,'BM'=>64,'BN'=>65,'BO'=>66,'BP'=>67,'BQ'=>68,'BR'=>69,'BS'=>70,'BT'=>71,'BU'=>72,'BV'=>73,'BW'=>74,'BX'=>75,'BY'=>76,'BZ'=>77,'CA'=>78,'CB'=>79,'CC'=>80,'CD'=>81,'CE'=>82,'CF'=>83,'CG'=>84,'CH'=>85,'CI'=>86,'CJ'=>87,'CK'=>88,'CL'=>89,'CM'=>90,'CN'=>91,'CO'=>92,'CP'=>93,'CQ'=>94,'CR'=>95,'CS'=>96,'CT'=>97,'CU'=>98,'CV'=>99,'CW'=>100,'CX'=>101,'CY'=>102,'CZ'=>103);
	}
	
	public function reader($filePath=null, $index=2)
	{
		if(file_exists(ROOT_PATH.'/include/PHPExcel/Reader/Excel2007.php'))include_once(ROOT_PATH.'/include/PHPExcel/Reader/Excel2007.php');
if(file_exists(ROOT_PATH.'/include/PHPExcel/Reader/Excel5.php'))include_once(ROOT_PATH.'/include/PHPExcel/Reader/Excel5.php');
		$help = c('xinhu')->helpstr('phpexcel');
		if(!class_exists('PHPExcel_Reader_Excel2007'))return '没有安装PHPExcel插件'.$help.'';
		if($filePath==null)$filePath = $_FILES['file']['tmp_name'];
		$PHPReader = new PHPExcel_Reader_Excel2007();
		if(!$PHPReader->canRead($filePath)){
			$PHPReader = new PHPExcel_Reader_Excel5();
			if(!$PHPReader->canRead($filePath)){
				return '不是正规的Excel文件'.$help.'';
			}
		}
		
		$PHPExcel 	= $PHPReader->load($filePath);
		$rows		= array();
		$sheet 		= $PHPExcel->getSheet(0); //第一个表
		$allColumn 	= $sheet->getHighestColumn();
		$allRow 	= $sheet->getHighestRow();
		$allCell	= $this->AT[$allColumn];

		for($row = $index; $row <= $allRow; $row++){
			$arr = array();	
			for($cell= 0; $cell<= $allCell; $cell++){
				$val = $sheet->getCellByColumnAndRow($cell, $row)->getValue();
				$arr[$this->A[$cell]] = $val;
			}
			$rows[] = $arr;
		}
		
		return $rows;
	}
	
	/**
		导入到表
	*/
	public function importTable($table, $rows, $fields)
	{
		
	}
	
	public function ExcelToDate($lx, $val)
	{
		if($lx=='date')$lx = 'Y-m-d';
		if($lx=='datetime')$lx = 'Y-m-d H:i:s';
		return date($lx, PHPExcel_Shared_Date::ExcelToPHP($val)-8*3600);
	}
}                         