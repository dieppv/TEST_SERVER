<?php
class ArrayCompareValidator extends CValidator
{		
	public $compareAttribute;
	
	public $compareAttributeTo;
	
	public $compareValue;
	
	public $strict=false;
	
	public $allowEmpty=false;
	
	public $operator='=';
	
	public $level = 1;
	
	public $error = false;
	
	protected function validateAttribute($object,$attribute)
	{
		$data=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($data) && !is_array($data))
			return;
		
		if($this->compareAttribute===null && ($this->compareAttributeTo === null || $this->compareValue === null) )
			return;	
		
		$this->recursive($object, $data, $attribute, 1);
	}

	private function recursive($object, $data, $attribute, $level){
		if($this->error)
			return;
			
		foreach($data as $i=>$row){
			if($this->level != $level){
				$this->recursive($object, $row, $attribute, ($level+1));
			}else{
				if(!isset($row[$this->compareAttribute]))
					return;
					
				$value=$row[$this->compareAttribute];
				
				if($this->compareValue!==null)
					$compareValue=$this->compareValue;
				else{				
					if(!isset($row[$this->compareAttributeTo]))
						return;
						
					$compareValue=$row[$this->compareAttributeTo];
				}				
				
				$this->compare($object, $value, $compareValue, $attribute, $compareValue);
			}			
		}		
	}	
	
	private function compare($object, $value, $compareValue, $attribute, $compareTo){
		switch($this->operator)
		{
			case '=':
			case '==':
				if(($this->strict && $value!==$compareValue) || (!$this->strict && $value!=$compareValue))
				{
					$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be repeated exactly.');
					$this->addError($object,$attribute,$message,array('{compareAttribute}'=>$compareTo));
					$this->error = true;
				}
				break;
			case '!=':
				if(($this->strict && $value===$compareValue) || (!$this->strict && $value==$compareValue))
				{
					$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must not be equal to "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareAttribute}'=>$compareTo,'{compareValue}'=>$compareValue));
					$this->error = true;
				}
				break;
			case '>':
				if($value<=$compareValue)
				{
					$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be greater than "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareAttribute}'=>$compareTo,'{compareValue}'=>$compareValue));
					$this->error = true;
				}
				break;
			case '>=':
				if($value<$compareValue)
				{
					$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be greater than or equal to "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareAttribute}'=>$compareTo,'{compareValue}'=>$compareValue));
					$this->error = true;
				}
				break;
			case '<':
				if($value>=$compareValue)
				{
					$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be less than "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareAttribute}'=>$compareTo,'{compareValue}'=>$compareValue));
					$this->error = true;
				}
				break;
			case '<=':
				if($value>$compareValue)
				{
					$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be less than or equal to "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareAttribute}'=>$compareTo,'{compareValue}'=>$compareValue));
					$this->error = true;
				}				
				break;
			default:
				throw new CException(Yii::t('yii','Invalid operator "{operator}".',array('{operator}'=>$this->operator)));
		}
	}
}
