<?php
class logLatValidator extends CValidator
{	
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $attributeName;

	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->isEmpty($value))
			return;			
		
		if($attributeName==NULL) {	
			$message=$this->message!==null?$this->message:Yii::t('yii','正しい住所を入力してください。');
			$this->addError($object,$attribute,$message);
		}
	}
}
?>