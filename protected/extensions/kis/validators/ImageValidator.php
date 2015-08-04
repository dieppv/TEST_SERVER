<?php

/**
 * EPhotoValidator class file.
 *
 * @author emix
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2009 emix
 * @license
 *
 * Copyright © 2008 by emix
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * - Neither the name of emix nor the names of its contributors may
 *   be used to endorse or promote products derived from this software without
 *   specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

class ImageValidator extends CFileValidator {
	
	/**
	 * @var string Allowed mime type of the image, ie: image/jpeg
	 */
	public $mimeType;
	
	/**
	 * @var string Mime type error message
	 */
	public $mimeTypeError;
	
	/**
	 * @var int Minimum width of the image required.
	 */
	public $minWidth;
	/**
	 * @var string Mime type error message
	 */
	public $minWidthError;
		
	/**
	 * @var int Maximum width of the image allowed
	 */
	public $maxWidth;
	/**
	 * @var string Mime type error message
	 */
	public $maxWidthError;
		
	/**
	 * @var int Minimum height of the image required.
	 */
	public $minHeight;
	/**
	 * @var string Mime type error message
	 */
	public $minHeightError;
		
	/**
	 * @var int Maximum height of the image allowed.
	 */
	public $maxHeight;
	/**
	 * @var string Mime type error message
	 */
	public $maxHeightError;
		
	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel the object being validated
	 * @param string the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		parent::validateAttribute($object, $attribute);
		$info=getimagesize($_FILES[$attribute]['tmp_name']);
		
		if (isset($this->minWidth)) {
			if ($info[0] < $this->minWidth)
			{
				$message=$this->minWidthError ? $this->minWidthError : Yii::t('yii','Photo should be at least {width}px in width');
				$this->addError($object,$attribute,$message,array('{width}'=>$this->minWidth));
			}
		}
		if (isset($this->maxWidth)) {
			if ($info[0] > $this->maxWidth)
			{
				$message=$this->maxWidthError ? $this->maxWidthError : Yii::t('yii','Photo should be at max {width}px in width');
				$this->addError($object,$attribute,$message,array('{width}'=>$this->maxWidth));
			}
		}
		if (isset($this->minHeight)) {
			if ($info[0] < $this->minHeight)
			{
				$message=$this->minHeightError ? $this->minHeightError : Yii::t('yii','Photo should be at least {height}px in height');
				$this->addError($object,$attribute,$message,array('{height}'=>$this->minHeight));
			}
		}
		if (isset($this->maxHeight)) {
			if ($info[0] > $this->maxHeight)
			{
				$message=$this->maxHeightError ? $this->maxHeightError : Yii::t('yii','Photo should be at max {height}px in height');
				$this->addError($object,$attribute,$message,array('{height}'=>$this->maxHeight));
			}
		}
		if (isset($this->mimeType))
		{
			$file=$object->$attribute;
			$info=@getimagesize($file->getTempName());
			
			$mime=is_scalar($this->mimeType) ? array($this->mimeType) : $this->mimeType;
			if (!in_array($info['mime'], $mime))
			{
				$message=$this->mimeTypeError ? $this->mimeTypeError : Yii::t('yii','This mime type of the photo is not allowed');
				$this->addError($object,$attribute,$message);
			}
		}
	}
}

?>