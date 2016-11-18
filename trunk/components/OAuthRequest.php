<?php

namespace app\components;

class OAuthRequest extends \yii\authclient\OAuth2
{
    private $_delay = 0;
    
    public function __construct($config = array(), $delay)
    {
        parent::__construct($config);
        $this->_delay = $delay;
    }
    
    public function send($method, $url, array $params = [], array $headers = [])
    {
        $result = $this->sendRequest($method, $url, $params, $headers);
        usleep($this->_delay);
        return $result;
    }
}