<?php

class IMSLinkPager extends CLinkPager
{
  public $callback='';
  public $header = '';
  public $cssFile = false;
	const CSS_FIRST_PAGE='first ui-corner-tl ui-corner-bl fg-button ui-state-default';
	const CSS_LAST_PAGE='last ui-corner-tr ui-corner-br fg-button ui-state-default';
	const CSS_PREVIOUS_PAGE='previous fg-button ui-state-default';
	const CSS_NEXT_PAGE='next fg-button ui-state-default';
	const CSS_INTERNAL_PAGE='fg-button ui-state-default';
	const CSS_HIDDEN_PAGE='fg-button ui-state-default ui-state-disabled';
	const CSS_SELECTED_PAGE='fg-button ui-state-default ui-state-disabled';

  public function run()
	{
		$this->registerClientScript();
		$buttons=$this->createPageButtons();
		if(empty($buttons))
			return;
		echo $this->header;
		echo implode($buttons);
		echo $this->footer;
	}

	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected)
			$class.=' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
		
    if(empty($this->callback))
    {
      if ($hidden || $selected)
        return '<span class="'.$class.'">'.CHtml::link($label, 'javascript:void(0)').'</span>';
      else
        return '<span class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page)).'</span>';
    }
		else
			return '<span class="'.$class.'">'.CHtml::link($label,'javascript:void(0)',array('p'=>$page,'onclick'=>$this->callback)).'</span>';
	}

  /**
	 * Creates the page buttons.
	 * @return array a list of page buttons (in HTML code).
	 */
	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons=array();

		// first page
		$buttons[]=$this->createPageButton($this->firstPageLabel,0,self::CSS_FIRST_PAGE,$currentPage<=0,false);

		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
		$buttons[]=$this->createPageButton($this->prevPageLabel,$page,self::CSS_PREVIOUS_PAGE,$currentPage<=0,false);

		// internal pages
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,self::CSS_INTERNAL_PAGE,false,$i==$currentPage);

		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
		$buttons[]=$this->createPageButton($this->nextPageLabel,$page,self::CSS_NEXT_PAGE,$currentPage>=$pageCount-1,false);

		// last page
		$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,self::CSS_LAST_PAGE,$currentPage>=$pageCount-1,false);

		return $buttons;
	}
}