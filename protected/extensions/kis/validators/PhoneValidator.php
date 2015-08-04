<?php
class PhoneValidator extends CValidator
{	
	/**
	 * Apply for phone & fax rules
   * number + symbol
	 */
	 
	public $allowEmpty = true;
	
	private function isValid($str)
  {
    $pattern = '/^[0-9]+([0-9-.\ ])*$/i';
    
    if (preg_match($pattern, $str))
      return true;
    else
      return false;
  }
	
	protected function validateAttribute($object, $attribute)
  {
		$value = $object->$attribute;
		
    if($this->allowEmpty && $this->isEmpty($value))
			return;
		
		if(! $this->isValid($value))
    {
			$message = $this->message!==null ? $this->message : Yii::t('yii','{attribute} is incorrect');
			$this->addError($object, $attribute, $message);
		}
	}
}