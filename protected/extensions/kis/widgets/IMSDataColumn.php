<?php
Yii::import('zii.widgets.grid.CDataColumn');

class IMSDataColumn extends CDataColumn
{
  protected function renderHeaderCellContent()
  {
    echo '<span class="nobr">';
    
    if($this->grid->enableSorting && $this->sortable && $this->name!==null) //có sort
    {
      echo $this->exCSortLink($this->grid->dataProvider->getSort(), $this->name, $this->header);
    }
    else if($this->name!==null && $this->header===null) //không sort
    {
      if($this->grid->dataProvider instanceof CActiveDataProvider)
        echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
      else
        echo CHtml::encode($this->name);
    }
    else
      parent::renderHeaderCellContent();

    echo '</span>';
  }

  private function exCSortLink($csortObj, $attribute, $label)
  {
    $htmlOptions = $this->headerHtmlOptions;

    if($label===null)
        $label=$csortObj->resolveLabel($attribute);
    if(($definition=$csortObj->resolveAttribute($attribute))===false)
        return $label;
    $directions=$csortObj->getDirections();
    if(isset($directions[$attribute]))
    {
        $class=$directions[$attribute] ? 'sort-link sort-arrow-desc' : 'sort-link sort-arrow-asc';
		$htmlOptions['s'] = $class;
        if(isset($htmlOptions['class']))
            $htmlOptions['class'].=' '.$class;
        else
            $htmlOptions['class']=$class;
        $descending=!$directions[$attribute];
        unset($directions[$attribute]);
    } else if(is_array($definition) && isset($definition['default'])) {
        $descending=$definition['default']==='desc';
        $htmlOptions['class'] = 'sort-link';
    } else {
        $descending=false;
    }
        
    if($csortObj->multiSort)
        $directions=array_merge(array($attribute=>$descending),$directions);
    else
        $directions=array($attribute=>$descending);

    $url=$csortObj->createUrl(Yii::app()->getController(),$directions);

    $labelStr = '<span class="sort-title">'.$label.'</span>';
	
	
	$sorts=array();
    foreach($directions as $attribute=>$descending)
        $sorts[]=$descending ? $attribute.'.'.$csortObj->descTag : $attribute;
	$htmlOptions['s'] = implode($csortObj->separators[0],$sorts);
	
    echo CHtml::link($labelStr,$url,$htmlOptions);
  }
}
