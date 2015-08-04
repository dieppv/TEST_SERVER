<?php
Yii::import('zii.widgets.grid.CGridColumn');

class IMSIdColumn extends CGridColumn
{

  public function init()
  {
    if (empty ($this->header)) $this->header = 'ID';
  }

  protected function  renderDataCellContent($row, $data)
  {
    $pagination = $this->grid->dataProvider->pagination;
	  
    if($pagination)
      echo $pagination->currentPage * $pagination->pageSize + ($row+1);
	  else echo ($row+1);
  }

  public function renderHeaderCell()
  {
    $this->headerHtmlOptions['id']=$this->id;
    echo CHtml::openTag('th',$this->headerHtmlOptions);
    echo '<span class="nobr">';
    $this->renderHeaderCellContent();
    echo '</span>';
    echo "</th>";
  }
}