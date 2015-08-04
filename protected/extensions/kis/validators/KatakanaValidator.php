<?php
class KatakanaValidator extends CValidator
{	
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	
	//public $pattern='/^[\x{30A0}-\x{30FF}]+$/u';
	public $pattern='/^([\x{30A0}-\x{30FF}]|[０-９ａ-ｚＡ-Ｚ]|[ー―－])+$/u';//#5200	111114
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;			
		$order   = array("\n", "\r");
		if(!preg_match($this->pattern,str_replace($order, '', "$value"))){
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be a katakana.');
			$this->addError($object,$attribute,$message);
		}
	}
}
?>