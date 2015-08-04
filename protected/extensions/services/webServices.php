<?php

class webServices {

    public $client = "", $errors = array();

    function __construct($url) {
        $this->client = new nusoap_client($url);
        $this->client->response_timeout = Yii::app()->params['nusoap']['response_timeout'];
    }

    /**
     * @param array $myData
     * @return array
     */
    private function convertLowerCase($myData) {
        $result = array();
        
        foreach ($myData as $key => $value) {
            $newKey = strtolower($key);
            $result[$newKey] = $value;
        }
        
        return $result;
    }
    
    /**
     * @param array $myData
     * @return array the filtered data
     */
    public function filterKey($aData, $aExpects) {
        $result = array();
        foreach ($aExpects as $key) {
            if (isset($aData[$key])) {
                $result[$key] = $aData[$key];
            } else {
                $result[$key] = '';
            }
        }
        
        return $result;
    }
  
    public function emailAliasReadFiles() {
		if(!empty(Yii::app()->params['proxy_server'])){
			$this->client->setHTTPProxy(Yii::app()->params['proxy_server']['address'], Yii::app()->params['proxy_server']['port']);	
		}		
        $result = $this->client->call("read_files", array(
            'params' => array(
                'key' => Yii::app()->params['email_alias']['auth_key'],
            ),
        ));

        $errorStr = $this->client->getError();
        if ( ! $errorStr ) {
            return $result;
        } else {
            array_push( $this->errors, $errorStr );
            return false;
        }
    }

    public function emailAliasWriteAliasAdd( $email, $alias ) {
		if(!empty(Yii::app()->params['proxy_server'])){
			$this->client->setHTTPProxy(Yii::app()->params['proxy_server']['address'], Yii::app()->params['proxy_server']['port']);	
		}
        $result = $this->client->call("write_file", array(
            'params' => array(
                'key' => Yii::app()->params['email_alias']['auth_key'],
                'email' => $email,
                'alias' => $alias,
            ),
        ));

        $errorStr = $this->client->getError();
        if ( ! $errorStr ) {
            return $result;
        } else {
            array_push( $this->errors, $errorStr );
            return false;
        }
    }

    /**
     * @param  string $email is prefix of email address
     * @return boolean true if duplicate
     */
    public function emailAliasHaveDupEmail( $email ) {
		if(!empty(Yii::app()->params['proxy_server'])){
			$this->client->setHTTPProxy(Yii::app()->params['proxy_server']['address'], Yii::app()->params['proxy_server']['port']);	
		}
        $result = $this->client->call("have_duplicate_email", array(
            'params' => array(
                'key'   => Yii::app()->params['email_alias']['auth_key'],
                'email' => $email,
            ),
        ));

        $errorStr = $this->client->getError();
        if ( ! $errorStr ) {
            return $result;
        } else {
            array_push( $this->errors, $errorStr );
            return false;
        }
    }

}

?>