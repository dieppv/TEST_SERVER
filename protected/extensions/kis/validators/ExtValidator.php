<?php
class ExtValidator extends CValidator
{	
	public $allowEmpty=true;
	
	public $types;
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;			
		
		if($this->types!==null){
			if(is_string($this->types))
				$types=preg_split('/[\s,]+/',strtolower($this->types),-1,PREG_SPLIT_NO_EMPTY);
			else
				$types=$this->types;
				
			if(!in_array(strtolower($this->getExtensionName($value)), $types)){				
				$message=Yii::t('yii','The file "{file}" cannot be uploaded. Only files with these extensions are allowed: {extensions}.');
				$this->addError($object,$attribute,$message,array('{file}'=>$value, '{extensions}'=>implode(', ',$types)));
			}
		}		
	}
	
	public function getExtensionName($name){
		if(($pos=strrpos($name,'.'))!==false)
			return (string)substr($name,$pos+1);
		else
			return '';
	}
}
?>