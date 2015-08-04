<?php
class HiraganaValidator extends CValidator
{	
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	
	public $pattern='/^[\x{3040}-\x{309F}]+$/u';
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;			
		
		if(!preg_match($this->pattern,"$value")){
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be a hiragana.');
			$this->addError($object,$attribute,$message);
		}
	}
}
?>