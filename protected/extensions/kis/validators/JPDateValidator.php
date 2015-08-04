<?php
Yii::import('ext.kis.date.Date_Japanese_Era');
class JPDateValidator extends CValidator
{		 
	public $era_name_field;
	public $year_field;
	public $month_field;
	public $day_field;
	public $integerPattern='/^\d+$/';
	public $monthPattern;
	public $dayPattern;
	public $isCheckDay = true;
	public $isCompare = false;
	public $operator='=';
	public $compareValue;//string date YYYYY-MM-DD
	public $message_compare;
	
	protected function validateAttribute($object,$attribute){
		$value=$object->$attribute;
		$bIsOk = true;	
		try {
			$era_name = $this->era_name_field;
			$year = $this->year_field;
			$month = $this->month_field;
			$day = $this->day_field;

			//check year
			if(!$this->isEmpty($object->$era_name) && !$this->isEmpty($object->$year) && !$this->isEmpty($object->$month)) {
				if(!preg_match($this->integerPattern,$object->$year) && $object->$year<1) $bIsOk = false;
				$era = new Date_Japanese_Era(array($object->$era_name, $object->$year));
				
				//check mont and day
				if(!$this->isEmpty($this->monthPattern)) $month = preg_replace($this->monthPattern, '', $object->$month);
				else $month = $object->$month;
				if(!preg_match($this->integerPattern,$month)) $bIsOk = false;
				else {
					if($this->isCheckDay && !$this->isEmpty($object->$day)){
						if(!$this->isEmpty($this->dayPattern)) $day = preg_replace($this->dayPattern, '', $object->$day);
						else $day = $object->$day;
						if(!preg_match($this->integerPattern,$month)) $bIsOk = false;
						else{
							$era_ = new Date_Japanese_Era(array($era->__get('gregorianYear'), $month, $day));
							if($era->__get('nameAscii') != $era_->__get('nameAscii')) $bIsOk = false;
							else { 
								if($this->isCompare) {
									$input_date = $era_->__get('gregorianYear').'-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-'.str_pad($day, 2, "0", STR_PAD_LEFT);
									$this->compareValue($input_date,$object,$attribute);
								}
							}
						}
					}
					else {$this->checkYm($object->$era_name, array($era->__get('gregorianYear'), $month)); }
				}
				
				
			}

		} catch (Exception $e) {
			$bIsOk = false;
		}
		if(!$bIsOk){
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} is invalid.');
			$this->addError($object,$attribute,$message);
		}		
	}
	
    private function checkYm($era_name, array $args)
    {
        list($year, $month) = $args;
        if (checkdate($month, 1, $year) === false) {
            throw new Date_Japanese_Era_Exception('Invalid.');
        }
        $era   = Date_Japanese_Era_Table::$ERA_TABLE;
        $table = array_reverse($era);
		if(isset($table[$era_name])){
			$val = $table[$era_name];
            $start = sprintf('%02d%02d', $val[1], $val[2]);
            $end   = sprintf('%02d%02d', $val[4], $val[5]);
            $ym   = sprintf('%02d%02d', $year, $month);
            if ($ym >= $start && $ym <= $end) {
                return;
            }
        }
        throw new Date_Japanese_Era_Exception(
            'Unsupported: ' . implode('-', $args)
        );
    }
	
	private function compareValue($input_date,$object,$attribute){
		$compareValue = $this->compareValue;
		switch($this->operator)
		{
			case '=':
				if($input_date!=$compareValue)
				{
					$message=$this->message_compare!==null?$this->message_compare:Yii::t('yii','{attribute} must be repeated exactly.');
					$this->addError($object,$attribute,$message);
				}
				break;
			case '>':
				if($input_date<=$compareValue)
				{
					$message=$this->message_compare!==null?$this->message_compare:Yii::t('yii','{attribute} must be greater than "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareValue}'=>$compareValue));
				}
				break;
			case '>=':
				if($input_date<$compareValue)
				{
					$message=$this->message_compare!==null?$this->message_compare:Yii::t('yii','{attribute} must be greater than or equal to "{compareValue}".');
					
					//echo $message;
					$this->addError($object,$attribute,$message,array('{compareValue}'=>$compareValue));
				}
				break;
			case '<':
				if($input_date>=$compareValue)
				{
					$message=$this->message_compare!==null?$this->message_compare:Yii::t('yii','{attribute} must be less than "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareValue}'=>$compareValue));
				}
				break;
			case '<=':
				if($input_date>$compareValue)
				{
					$message=$this->message_compare!==null?$this->message_compare:Yii::t('yii','{attribute} must be less than or equal to "{compareValue}".');
					$this->addError($object,$attribute,$message,array('{compareValue}'=>$compareValue));
				}
				break;
			default:
				throw new CException(Yii::t('yii','Invalid operator "{operator}".',array('{operator}'=>$this->operator)));
		}	
	}
	
}
?>