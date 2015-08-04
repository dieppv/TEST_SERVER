<?php
Yii::import('zii.widgets.grid.CCheckBoxColumn');
class IMSCheckBoxColumn extends CCheckBoxColumn
{
	public function init()
	{
		if(isset($this->checkBoxHtmlOptions['name']))
			$name=$this->checkBoxHtmlOptions['name'];
		else
		{
			$name=$this->id;
			if(substr($name,-2)!=='[]')
				$name.='[]';
			$this->checkBoxHtmlOptions['name']=$name;
		}
		$name=strtr($name,array('['=>"\\[",']'=>"\\]"));

		if($this->selectableRows===null)
		{
			if(isset($this->checkBoxHtmlOptions['class']))
				$this->checkBoxHtmlOptions['class'].=' select-on-check';
			else
				$this->checkBoxHtmlOptions['class']='select-on-check';
			return;
		}

		$cball=$cbcode='';
		if($this->selectableRows==0)
		{
			//.. read only
			$cbcode="return false;";
		}
		elseif($this->selectableRows==1)
		{
			//.. only one can be checked, uncheck all other
			$cbcode="$(\"input:not(#\"+$(this).attr('id')+\")[name='$name']\").attr('checked',false);";
		}
		else
		{
			//.. process check/uncheck all
			$cball=<<<CBALL
$('#{$this->id}_all').live('click',function() {
	var checked=this.checked;
	$("input[name='$name']").each(function() {this.checked=checked;});
});

CBALL;
			//$cbcode="$('#{$this->id}_all').attr('checked', $(\"input[name='$name']\").length==$(\"input[name='$name']:checked\").length);";
			$cbcode="return;";
		}

		$js=$cball;
		$js.=<<<EOD
$("input[name='$name']").live('click', function() {
	$cbcode
});
EOD;
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
	}
}
