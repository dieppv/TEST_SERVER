<?php
class ExistValidator extends CValidator
{
	/**
	 * @var string the ActiveRecord class name that should be used to
	 * look for the attribute value being validated. Defaults to null,
	 * meaning using the ActiveRecord class of the attribute being validated.
	 * You may use path alias to reference a class name here.
	 * @see attributeName
	 */
	public $className;
	/**
	 * @var string the ActiveRecord class attribute name that should be
	 * used to look for the attribute value being validated. Defaults to null,
	 * meaning using the name of the attribute being validated.
	 * @see className
	 */
	public $attributeName;
	/**
	 * @var array additional query criteria. This will be combined with the condition
	 * that checks if the attribute value exists in the corresponding table column.
	 * This array will be used to instantiate a {@link CDbCriteria} object.
	 * @since 1.0.8
	 */
	public $criteria=array();
	
	public $with=array();
	
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	
	public $paramCount = 0;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object, $attribute)
	{
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;

		$className=$this->className===null?get_class($object):Yii::import($this->className);
		$attributeName=$this->attributeName===null?$attribute:$this->attributeName;
		$finder=CActiveRecord::model($className);
		
		$table=$finder->getTableSchema();
		
		if(($column=$table->getColumn($attributeName))===null)
			throw new CException(Yii::t('yii','Table "{table}" does not have a column named "{column}".',
				array('{column}'=>$attributeName,'{table}'=>$table->name)));

		$criteria=array('condition'=>$column->rawName.'=:vp','params'=>array(':vp'=>$value));
		
		foreach($this->with as $w){
			if($column=$table->getColumn($w['attributeName'])){
				$attr = $w['attribute'];
				$criteria['condition'] .= " {$w['operator']} {$column->rawName} =:vp".$this->paramCount;
				$criteria['params'][':vp'.$this->paramCount++] = $object->$attr;
			}				
		}	
		
		if($this->criteria!==array())
		{
			$criteria=new CDbCriteria($criteria);
			$criteria->mergeWith($this->criteria);
		}
		
		if(!$finder->exists($criteria))
		{
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} "{value}" is invalid.');
			$this->addError($object,$attribute,$message,array('{value}'=>$value));
		}		
	}
}

