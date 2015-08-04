<?php
class Sort extends CSort
{	
	public $value;
	
	private $_directions;
	
	public function __construct($modelClass=null)
	{
		$this->modelClass=$modelClass;
	}
	
	public function link($attribute,$label=null,$htmlOptions=array())
	{
		if($label===null)
			$label=$this->resolveLabel($attribute);
		if(($definition=$this->resolveAttribute($attribute))===false)
			return $label;
		$directions=$this->getDirections();
		if(isset($directions[$attribute]))
		{
			$class=$directions[$attribute] ? 'desc' : 'asc';
			if(isset($htmlOptions['class']))
				$htmlOptions['class'].=' '.$class;
			else
				$htmlOptions['class']=$class;
			$descending=!$directions[$attribute];
			unset($directions[$attribute]);
		}
		else if(is_array($definition) && isset($definition['default']))
			$descending=$definition['default']==='desc';
		else
			$descending=false;

		if($this->multiSort)
			$directions=array_merge(array($attribute=>$descending),$directions);
		else
			$directions=array($attribute=>$descending);
		
		$sorts=array();
		foreach($directions as $attribute=>$descending)
			$sorts[]=$descending ? $attribute.$this->separators[1].$this->descTag : $attribute;		
		
		$htmlOptions['sort'] = implode($this->separators[0], $sorts);		
		
		if(isset($htmlOptions['class']))
			$htmlOptions['class'].=' sort';
		else
			$htmlOptions['class']=' sort';
				
		return CHtml::link($label, 'javascript:void(0)', $htmlOptions);
	}
	
	public function getDirections()
	{
		if($this->_directions===null)
		{
			$this->_directions=array();
			if(!empty($this->value))
			{
				$attributes=explode($this->separators[0], $this->value);
				foreach($attributes as $attribute)
				{
					if(($pos=strrpos($attribute,$this->separators[1]))!==false)
					{
						$descending=substr($attribute,$pos+1)===$this->descTag;
						if($descending)
							$attribute=substr($attribute,0,$pos);
					}
					else
						$descending=false;

					if(($this->resolveAttribute($attribute))!==false)
					{
						$this->_directions[$attribute]=$descending;
						if(!$this->multiSort)
							return $this->_directions;
					}
				}
			}
			if($this->_directions===array() && is_array($this->defaultOrder))
				$this->_directions=$this->defaultOrder;
		}
		return $this->_directions;
	}
}
?>