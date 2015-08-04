<?php
Yii::import('zii.widgets.grid.CGridView');
Yii::import('ext.kis.widgets.IMSDataColumn');

class IMSGridView extends CGridView
{
  public $template = '<div class="box-table" style="height:850px;overflow:scroll;overflow-x:hidden;">{items}</div>
      <div class="fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">{summary}{pager}</div>';
  public $columnGroup;
  public $htmlOptions = array('class'=>'box-table');
  public $itemsCssClass = 'tablebox';
  public $cssFile = false;
  public $summaryText = '{count}件中　{start}~{end}件を表示';
  public $summaryCssClass = 'dataTables_info';
  public $pager = array('class'=>'ext.kis.widgets.IMSLinkPager');
  public $pagerCssClass = 'dataTables_paginate fg-buttonset fg-buttonset-multi paging_full_numbers';
  public $myPageSize;

  public function init()
	{
    if(!empty($this->dataProvider->pagination)){
		//set pagesize
		if (empty($this->myPageSize))
		  $this->myPageSize = Yii::app()->params['page_size'];
		$this->dataProvider->pagination->pageSize = $this->myPageSize;
	}

    parent::init();
  }

  /**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns()
	{
		if($this->columns===array())
		{
			if($this->dataProvider instanceof CActiveDataProvider)
				$this->columns=$this->dataProvider->model->attributeNames();
			else if($this->dataProvider instanceof IDataProvider)
			{
				// use the keys of the first row of data as the default columns
				$data=$this->dataProvider->getData();
				if(isset($data[0]) && is_array($data[0]))
					$this->columns=array_keys($data[0]);
			}
		}
		$id=$this->getId();
		foreach($this->columns as $i=>$column)
		{
			if(is_string($column))
				$column=$this->createDataColumn($column);
			else
			{
				if(!isset($column['class']))
					$column['class']='IMSDataColumn';
				$column=Yii::createComponent($column, $this);
			}
			if(!$column->visible)
			{
				unset($this->columns[$i]);
				continue;
			}
			if($column->id===null)
				$column->id=$id.'_c'.$i;
			$this->columns[$i]=$column;
		}

		foreach($this->columns as $column)
			$column->init();
	}
  
  /**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
    $this->renderColumnGroup();

		if(!$this->hideHeader)
		{
			echo '<thead class="table-header">'."\n";

			if($this->filterPosition===self::FILTER_POS_HEADER)
				$this->renderFilter();

			echo "<tr class=\"headings\">\n";
			foreach($this->columns as $column)
        $column->renderHeaderCell();
			echo "</tr>\n";

			if($this->filterPosition===self::FILTER_POS_BODY)
				$this->renderFilter();

			echo "</thead>\n";
		}
		else if($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY))
		{
			echo "<thead>\n";
			$this->renderFilter();
			echo "</thead>\n";
		}
	}
  
  protected function renderColumnGroup()
  {
    if (is_array($this->columnGroup) && (count($this->columnGroup) == count($this->columns)))
    {
      foreach ($this->columnGroup as $col)
        if ($col !== 0)
          echo '<col width="'.$col.'" />';
        else
          echo '<col />';
    }
  }

 	/**
	 * Creates a {@link CDataColumn} based on a shortcut column specification string.
	 * @param string $text the column specification string
	 * @return CDataColumn the column instance
	 */
	protected function createDataColumn($text)
	{
		if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/',$text,$matches))
			throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
		$column=new IMSDataColumn($this);
		$column->name=$matches[1];
		if(isset($matches[3]) && $matches[3]!=='')
			$column->type=$matches[3];
		if(isset($matches[5]))
			$column->header=$matches[5];
		return $column;
	}

  public function renderSummary()
  {
    if(($count=$this->dataProvider->getItemCount())<=0)
        return;

    echo '<div id="tabledata_info" class="'.$this->summaryCssClass.'">';
    if($this->enablePagination)
    {
        if(($summaryText=$this->summaryText)===null)
            $summaryText=Yii::t('zii','Displaying {start}-{end} of {count} result(s).');
        $pagination=$this->dataProvider->getPagination();
        $total=$this->dataProvider->getTotalItemCount();
        $start=$pagination->currentPage*$pagination->pageSize+1;
        $end=$start+$count-1;
        if($end>$total)
        {
            $end=$total;
            $start=$end-$count+1;
        }
        echo strtr($summaryText,array(
            '{start}'=>$start,
            '{end}'=>$end,
            '{count}'=>$total,
            '{page}'=>$pagination->currentPage+1,
            '{pages}'=>$pagination->pageCount,
        ));
    }
    else
    {
        if(($summaryText=$this->summaryText)===null)
            $summaryText=Yii::t('zii','Total {count} result(s).');
        echo strtr($summaryText,array(
            '{count}'=>$count,
            '{start}'=>1,
            '{end}'=>$count,
            '{page}'=>1,
            '{pages}'=>1,
        ));
    }
    echo '</div>';
  }

  
}