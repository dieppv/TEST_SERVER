<?php
class UrlTypeValidator extends CValidator
{	
	/**	 
	 * http://php.net/manual/en/function.mb-detect-encoding.php
	 */
	public $allowEmpty=true;	
	
        public $pattern= '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i';
        
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;	
		
        if(!preg_match($this->pattern,"$value"))
        {
            $message=$this->message!==null?$this->message:Yii::t('msg','please.input.url.type', array('{0}'=>'{attribute}'));
			$this->addError($object,$attribute,$message);
        }
}
}
?>