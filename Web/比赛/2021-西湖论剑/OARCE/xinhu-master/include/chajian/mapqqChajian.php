<?php 
class mapqqChajian extends Chajian{
	
	private $mapqqkey 	= 'I3EBZ-TYP6F-RGZJI-J3W3V-ERKDT-PTBK4';
	
	public function geocoder($location)
	{
		$url 	= 'http://apis.map.qq.com/ws/geocoder/v1/?location='.$location.'&coord_type=5&key='.$this->mapqqkey.'';
		$result = c('curl')->getcurl($url);
		
		echo $result;
	}
}