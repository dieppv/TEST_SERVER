<?php
Yii::import('zii.widgets.jui.CJuiDatePicker');
class DatePicker extends CJuiDatePicker
{
	public $defaultOptions = array(
			'clearText'=>"クリア",
			'currentText'=>'選択日に移動',			
			'clearClass'=>'ui-datepicker-close ui-state-default ui-priority-secondary ui-corner-all',
            'dateFormat'=>"yy/dd/mm",
            'id'=>'datepicker',
            'name'=>'datepicker',
            'region'=>'ja',
            'language'=>'Japanese',
			'showMonthAfterYear'=>true,
			'showButtonPanel'=>true,
			'gotoCurrent'=> true,
			'beforeShow'=> 'js:function(input, inst) { 
				setTimeout(function() {
				  var buttonPane = $( input )
					.datepicker( "widget" )
					.find(".ui-datepicker-close").click(function () {
											$.datepicker._clearDate(input);
										  });
				  $( input )
					.datepicker( "widget" )
					.find(".ui-datepicker-close").html(this.$.datepicker._defaults.clearText)
					.attr("class", this.$.datepicker._defaults.clearClass);
				  $( input )
					.datepicker( "widget" )
					.find(".ui-datepicker-current").html(this.$.datepicker._defaults.currentText);
				}, 1 );
			}',
			'onChangeMonthYear'=> 'js:function(year, month, input) { 
			    var id= "#"+$(input).attr("id");
				setTimeout(function() {
				  var buttonPane = $( input )
					.datepicker( "widget" )
					.find(".ui-datepicker-close").click(function () {
											$.datepicker._clearDate(id);
										  });
				  $( input )
					.datepicker( "widget" )
					.find(".ui-datepicker-close").html(this.$.datepicker._defaults.clearText)
					.attr("class", this.$.datepicker._defaults.clearClass);
				  $( input )
					.datepicker( "widget" )
					.find(".ui-datepicker-current").html(this.$.datepicker._defaults.currentText);
				}, 1 );
			}',			
	);
}