<?php
class NumericIncludeSpecialVarchar extends CValidator
{	
	/**	 
	 * http://php.net/manual/en/function.mb-detect-encoding.php
	 */
	public $allowEmpty=true;	
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;	
		
        if (preg_match('/[a-zA-Z]/', $value))
        {
            $message=$this->message!==null?$this->message:Yii::t('msg','please.input.number.1byte', array('{0}'=>'{attribute}'));
			$this->addError($object,$attribute,$message);
        }
	}
}
?>