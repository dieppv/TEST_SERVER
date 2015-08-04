<?php
class JapaneseModeValidator extends CValidator
{	
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	
	public $pattern='/^[A-z0-9]+$/i';
	public $check=true;
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;			
		
		if($this->check==false) $this->pattern='/[A-z0-9]/i';
		if(!preg_match($this->pattern,"$value")){
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} could not be Japanese.');
			$this->addError($object,$attribute,$message);
		}
	}
}
?>