<?php
class DbCriteria extends CDbCriteria
{	
	const PARAM_PREFIX=':ycp';
	
	public $from;
	
	public function __construct($data=array())
	{
		foreach($data as $name=>$value)
			$this->$name=$value;
	}
	
	public function addSearch($column, $keyword, $right = true, $escape=true, $operator='AND', $like='LIKE')
	{
		if($keyword=='')
			return $this;
		if($escape)
			$keyword=($right?'':'%').strtr($keyword,array('%'=>'\%')).($right?'%':'');
		$condition=$column." $like ".self::PARAM_PREFIX.self::$paramCount;
		$this->params[self::PARAM_PREFIX.self::$paramCount++]=$keyword;
		return $this->addCondition($condition, $operator);
	}
		
	public function sql($count = false){
		$query=array();
		foreach(array('distinct', 'select', 'from', 'join', 'where'=>'condition', 'group', 'having', 'order', 'limit', 'offset') as $key=>$name){
			$value = $this->$name;
			if(!empty($value)){
				$query[is_numeric($key)?$name:$key] = $value; 
			}			                
		}		
		if($count){
			$sql = Yii::app()->db->createCommand()->buildQuery($query);
			return "SELECT COUNT(*) FROM ({$sql}) AS t";
		}else
			return Yii::app()->db->createCommand()->buildQuery($query);
	}
}
?>