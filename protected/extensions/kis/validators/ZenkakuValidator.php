<?php
class ZenkakuValidator extends CValidator
{	
	/**
	 * http://blog.aidream.jp/codeigniter/codeigniter-form-validation-extend-class-1351.html
	 */
	 
	public $allowEmpty=true;
	
	private function double($str)
    {
        if ($str == '')
        {
            return TRUE;
        }
        $str = mb_convert_encoding($str, 'UTF-8');
        return (preg_match("/(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])|[\x20-\x7E]/", $str)) ? FALSE : TRUE;
    }
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;			
		
		if(!self::double($value)){
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be a zenkaku.');
			$this->addError($object,$attribute,$message);
		}
	}
}
?>