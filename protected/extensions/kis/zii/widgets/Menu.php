<?php
Yii::import('zii.widgets.CMenu');
class Menu extends CMenu
{	
	/**
	 * Calls {@link renderMenu} to render the menu.
	 */
	public function run(){
		$this->renderMenu($this->items);
	}
	
	protected function renderMenu($items)
	{
		if(count($items))
		{
			echo CHtml::openTag('ul',$this->htmlOptions)."\n";
			$this->renderMenuRecursive($items, true);
			echo CHtml::closeTag('ul');
		}
	}
	
	/**
	 * Recursively renders the menu items.
	 * @param array $items the menu items to be rendered recursively
	 */
	protected function renderMenuRecursive($items, $root = false)
	{
		$count=0;
		$n=count($items);
		foreach($items as $item)
		{
			$count++;
			$options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
			$class=array();
			if($item['active'] && $this->activeCssClass!='')
				$class[]=$this->activeCssClass;
			if($count===1 && $this->firstItemCssClass!='')
				$class[]=$this->firstItemCssClass;
			if($count===$n && $this->lastItemCssClass!='')
				$class[]=$this->lastItemCssClass;
			if($class!==array())
			{
				if(empty($options['class']))
					$options['class']=implode(' ',$class);
				else
					$options['class'].=' '.implode(' ',$class);
			}
			
			if($root){
				echo CHtml::openTag('li');
				echo CHtml::image(Yii::app()->request->baseUrl."/images/header_navi_sepline.gif", '|');
				echo CHtml::closeTag('li');				
			}
			
			echo CHtml::openTag('li', $options);

			$menu=$this->renderMenuItem($item);
			if(isset($this->itemTemplate) || isset($item['template']))
			{
				$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
				echo strtr($template,array('{menu}'=>$menu));
			}
			else
				echo $menu;

			if(isset($item['items']) && count($item['items']))
			{
				echo "\n<div class=\"sub\">\n";
				echo "\n".CHtml::openTag('ul',isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions)."\n";
				$this->renderMenuRecursive($item['items']);
				echo CHtml::closeTag('ul')."\n";
				echo "</div>\n";
			}

			echo CHtml::closeTag('li')."\n";
		}
	}
}