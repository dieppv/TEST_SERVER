<?php
/**
 * CJuiDatePicker class file.
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

//Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * CDatePicker displays a datepicker.
 *
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('ext.kis.zii.widgets.CDatePickerJP', array(
 *     'name'=>'publishDate',
 *     // additional javascript options for the date picker plugin
 *     'options'=>array(
 *         'showAnim'=>'fold',
 *     ),
 *     'htmlOptions'=>array(
 *         'style'=>'height:20px;'
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI datepicker plugin. Please refer to
 * the {@link http://jqueryui.com/demos/datepicker/ JUI datepicker} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id: CJuiDatePicker.php 3302 2011-06-23 14:36:00Z qiang.xue $
 * @package zii.widgets.jui
 * @since 1.1
 */
class CDatePickerJP extends CInputWidget
{
	/**
	 * @var array The default options called just one time per request. This options will alter every other CJuiDatePicker instance in the page.
	 * It has to be set at the first call of CJuiDatePicker widget in the request.
	 */
	public $defaultOptions = array('altFormat'=>"D", 
            'syncAltField'=>true,
            'region'=>'ja',
            'language'=>'Japanese',
			'showAltFieldOnly'=>true,
			'highlightWeekends'=>true,
			'highlightPublicHolidays'=>true,			
			'changeMonth'=>true, 
			'changeYear'=>true);

	/**
	 * @var boolean If true, shows the widget as an inline calendar and the input as a hidden field. Use the onSelect event to update the hidden field
	 */
	public $options;
	public $link_input_blank = false;
	public $link_text = 'empty';
	public $link_separator = '';
	public $script;
	public $option_script;

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{

		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;
			
		$id_view = $id.'_view';
		foreach($this->defaultOptions as $key=>$val) 
			$this->options[$key] = $val;
		//$this->options = $this->defaultOptions;
		$this->options['altField'] = $id_view;
		$this->options['altField'] = $id_view;
		
		$this->htmlOptions['style']='display:none;';
		
		$view_htmlOptions['id'] = $id_view;
		$view_htmlOptions['type'] = 'text';
		$view_htmlOptions['readonly'] = 'readonly';

		if($this->hasModel()){	
			$attribute = $this->attribute;
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
			echo CHtml::tag('input', $view_htmlOptions);
		}
		else {
			echo CHtml::textField($name,$this->value,$this->htmlOptions);
			echo CHtml::tag('input', $view_htmlOptions);
		}
		
		$jsClick = "$('#{$id}').val(''); $('#{$id_view}').val('')";
		if($this->link_input_blank) echo $this->link_separator.CHtml::link(CHtml::encode($this->link_text), 'javascript: void(0)', array('onclick'=>$jsClick));

		$options=CJavaScript::encode($this->options);
		$js = "jQuery('#{$id}').datepicker($options);";
		$this->script = $js;

		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.global.js');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.glob.ja.custom.js');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.public-holiday.ja.js');	
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.ui.core.js');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.ui.datepicker.js');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.ui.global-datepicker.js');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.ui.datepicker-ja.js');

		$cs = Yii::app()->getClientScript();
		$cs->registerScript(__CLASS__.'#'.$id, $js);

	}
}