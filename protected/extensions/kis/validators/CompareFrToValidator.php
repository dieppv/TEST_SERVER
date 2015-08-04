<?php
class CompareFrToValidator extends CValidator {

	public $compareAttribute;
	public $compareValue;
	public $operator='=';

	protected function validateAttribute($object,$attribute){
		$compareAttr = $this->compareAttribute;
		if(!empty($object->$attribute) && !empty($object->$compareAttr)){
			$cValidate = CValidator::createValidator('compare', 
														$object, 
														$attribute, 
														array(	'message'=>$this->message,
																'operator'=>$this->operator,
																'compareAttribute'=>$this->compareAttribute));
			$cValidate->validateAttribute($object, $attribute);
		}
	}	
}
?>