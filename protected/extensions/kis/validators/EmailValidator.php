<?php

class EmailValidator extends CValidator
{

	public $allowEmpty = true;
	public $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

	protected function validateAttribute($object, $attribute)
	{
		$value = $object->$attribute;
		
		if($this->allowEmpty && $this->isEmpty($value))
			return;
		$error='';
		$array = array();
		if(!preg_match($this->pattern, $value))
		{
			$strlen = mb_strlen($value);
			while ($strlen) {
				$array[] = mb_substr($value,0,1,"UTF-8");
				$value = mb_substr($value,1,$strlen,"UTF-8");
				$strlen = mb_strlen($value);
			} 

			if(in_array('@', $array))
			{
				//check before @
				for($i=0;$i<array_search('@', $array);$i++)
				{
					$pattern1 = '/[a-zA-Z0-9!#$%\\\&.\'*+\\/=?^_`{|}~-]/';
					$ck1 = preg_match($pattern1, $array[$i]);
					if($ck1==0)
					{
						if($error=='')
							$error .= $array[$i];
						else
							$error = $error."、".$array[$i];
					}

				}
				//check after @
				for($i=array_search('@', $array)+1;$i<count($array);$i++)
				{
					$pattern2 = '/[a-zA-Z0-9\.-]/';
					$ck2 = preg_match($pattern2, $array[$i]);
					if($ck2==0)
					{
						if($error=='')
							$error .= $array[$i];
						else
							$error = $error."、".$array[$i];
					}
				}
			}
			if($error!='')
			{
				$error =  '。記号:'.$error.'は使用できません。';
				$message=$this->message!== null?$this->message:Yii::t('msg','input.wrong', array('{0}'=>'{attribute}','。'=>$error));
				$this->addError($object, $attribute, $message);
			}else
			{
				$message=$this->message!==null?$this->message:Yii::t('msg','input.wrong', array('{0}'=>'{attribute}'));
				$this->addError($object,$attribute,$message);
			}
			
		}
	}

}
