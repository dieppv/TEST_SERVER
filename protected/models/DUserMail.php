<?php

/**
 * DUserMail class.
 * DUserMail is the data structure for keeping
 * DUserMail form data. It is used by the 'contact' action of 'SiteController'.
 */
class DUserMail extends CFormModel
{
	public $domain;
	public $email;
	public $password;
        public $confirmpassword;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('domain, email, password, confirmpassword', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
		);
	}
        
    public function search() {
        // get criteria
       // $this->restoreSearchState();
        
        return false;
    }   
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'domain'=>'ドメイン名',
                        'email'=>'メール',
                        'password'=>'パスワード',
                        'confirmpassword'=>'パスワード確認',
		);
	}
}