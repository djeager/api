<?php

namespace djeager\api\facebook;
//use yii\authclient\OAuth2;

class Auth extends \yii\authclient\clients\Facebook
{
    public $apiBaseUrl;

    /**
     * @var string protocol version.
     */
    public $version = '2.8';

    public function __construct($cofig = [])
    {
        $conf = \Yii::$app->getComponents('authClientCollection')['authClientCollection']['clients']['facebook'];
        $this->clientId = $conf['clientId'];
        $this->clientSecret = $conf['clientSecret'];
        return parent::__construct($cofig);
    }

    public function setAccessToken($token)
    {
        $f = fopen(__dir__ . '/auth.json', 'w+');
        if (!fwrite($f, json_encode($token))) throw new ErrorException('Не могу записать файл auth.json');
        fclose($f);
    }

    public function getAccessToken()
    {
        return json_decode(file_get_contents(__dir__ . '/auth.json'))->access_token;
    }
}

?>