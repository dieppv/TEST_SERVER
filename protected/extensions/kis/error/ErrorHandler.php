<?php
class ErrorHandler extends CErrorHandler{	
	protected function handleException($exception){
		if(!empty($exception->statusCode) && 404 == $exception->statusCode && preg_match('/^\/(docomo|ezweb|softbank)/', Yii::app()->baseUrl, $match)){
			Yii::app()->request->redirect("/{$match[1]}");			
		}else{
			parent::handleException($exception);
		}
	}
	
	protected function handleError($event){
		if(!empty($exception->statusCode) && 404 == $exception->statusCode && preg_match('/^\/(docomo|ezweb|softbank)/', Yii::app()->baseUrl, $match)){
			Yii::app()->request->redirect("/{$match[1]}");
		}else{
			parent::handleError($event);
		}		
	}
}
?>