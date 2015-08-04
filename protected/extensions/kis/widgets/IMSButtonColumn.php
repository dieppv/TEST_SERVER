<?php
Yii::import('zii.widgets.grid.CButtonColumn');

class IMSButtonColumn extends CButtonColumn
{
  public $htmlOptions=array();
  public $template = '{update} {delete}';
  public $updateButtonImageUrl = false;
  public $deleteButtonImageUrl = false;
  public $updateButtonLabel = '<span class="icon_single edit"></span>';
  public $deleteButtonLabel = '<span class="icon_single delete"></span>';
  public $deleteButtonOptions = array('class'=>'button white delete', 'title'=>'');
  public $updateButtonOptions = array('class'=>'button white', 'title'=>'');

  public function init()
  {
    $this->initDefaultButtons();

    foreach($this->buttons as $id=>$button)
    {
        if(strpos($this->template,'{'.$id.'}')===false)
            unset($this->buttons[$id]);
        else if(isset($button['click']))
        {
            if(!isset($button['options']['class']))
                $this->buttons[$id]['options']['class']=$id;
            if(strpos($button['click'],'js:')!==0)
                $this->buttons[$id]['click']='js:'.$button['click'];
        }
    }

    $this->registerClientScript();
  }
}
