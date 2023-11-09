<?php 
/**
 * @name PHPTree 
 * @author crazymus < QQ:291445576 >
 * @des PHP生成树形结构,无限多级分类
 * @version 1.2.0
 * @Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * @updated 2015-08-26

 */
class PHPTree{	

	protected static $config = array(
		/* 主键 */
		'primary_key' 	=> 'id',
		/* 父键 */
		'parent_key'  	=> 'pId',
		/* 展开属性 */
		'expanded_key'  => 'open',
		/* 叶子节点属性 */
		'leaf_key'      => 'leaf',
		/* 孩子节点属性 */
		'children_key'  => 'children',
		/* 是否展开子节点 */
		'expanded'    	=> false
	);
	
	/* 结果集 */
	protected static $result = array();
	
	/**
	 * @name 生成树形结构
	 * @param array 二维数组
	 * @return mixed 多维数组
	 */
	
	/* 生成线性结构, 便于HTML输出, 参数同上 */
	public static function makeTreeForHtml($data,$options=array()){
		$dataset = self::buildData($data,$options);
		$r = self::makeTreeCore(0,$dataset,'linear');
		return $r;	
	}
	
	/* 格式化数据, 私有方法 */
	private static function buildData($data,$options){
		$config = array_merge(self::$config,$options);
		self::$config = $config;
		extract($config);

		$r = array();
		foreach($data as $item){
			$id = $item[$primary_key];
			$parent_id = $item[$parent_key];
			$r[$parent_id][$id] = $item;
		}
		return $r;
	}
	
	/* 生成树核心, 私有方法  */
	private static function makeTreeCore($index,$data,$type='linear')
	{
		extract(self::$config);
		foreach($data[$index] as $id=>$item)
		{
			if(isset($data[$id])){
				$item[$expanded_key]= self::$config['expanded'];
			}else{
				
			}
			self::$result[] = $item;
			if(isset($data[$id])){
				self::makeTreeCore($id,$data,$type);
			}
			$r = self::$result;
		}
		return $r;
	}
}


?>