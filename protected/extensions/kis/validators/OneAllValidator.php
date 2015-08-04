<?php
class OneAllValidator extends CValidator
{	
	public $requiredValue;
	
	public $otherFields;
	
	public $strict=false;
	
	protected function validateAttribute($object,$attribute)
	{
		$value[]=$object->$attribute;
		
		$other=preg_split('/[\s,]+/',strtolower($this->otherFields),-1,PREG_SPLIT_NO_EMPTY);
		
		foreach($other as $field){
			$value[]=$object->$field;
		}
		
		if(!$this->required($value)){
			if($this->requiredValue!==null){
				$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be {value}.',array('{value}'=>$this->requiredValue));
				$this->addError($object,$attribute,$message);
			}else{
				$message=$this->message!==null?$this->message:Yii::t('msg','ms001-e', array('{0}'=>'{attribute}'));
				$this->addError($object,$attribute,$message);
			}
		}		
	}
	
	protected function required($data){
		foreach($data as $value){
			if($this->requiredValue!==null){
				if(!$this->strict && $value==$this->requiredValue || $this->strict && $value===$this->requiredValue){
					return true;
				}
			}else if(!$this->isEmpty($value,true)){
				return true;	
			}
		}
		return false;		
	}
}