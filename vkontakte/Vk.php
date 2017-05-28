<?php

namespace djeager\api\vkontakte;

use Yii;
use yii\base\ErrorException;

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/djeager/yii2-api/vkontakte/vkPhpSdk" . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'VkPhpSdk.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/djeager/yii2-api/vkontakte/vkPhpSdk" . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Oauth2Proxy.php';

class Vk extends \backend\modules\post\extensions\BaseParser //implements \Serializable
{

    private $_clientId = '';                             // client id
    private $_clientSecret = '';                // client secret
    private $_accessTokenUrl = 'https://oauth.vk.com/access_token';   // access token url
    private $_dialogUrl = 'https://oauth.vk.com/authorize';      // dialog url
    private $_responseType = 'code';                                // response type
    private $_redirectUri = 'http://za-repost.dev/admin/post/vk/newtoken';      // redirect url
    private $_scope = 'offline,notify,friends,photos,audio,video,wall';  // scope
    private $_accessParams;
    private $_authJson;

    public static $auth;
    public static $sdk;

    public $parent;
    private $_links = [];

    public function __construct(array $config = null, $formName = null)
    {
        $conf = \Yii::$app->getComponents('authClientCollection')['authClientCollection']['clients']['vkontakte'];
        $this->_clientId = $conf['clientId'];
        $this->_clientSecret = $conf['clientSecret'];
        parent::__construct($config, $formName);
    }

    //protected $data;
    protected $template = '<div class="box">{text}<div>{attachments}</div></div>';
    /**
     * @param список
     * [className=[
     *      scenario    =>...   указать сценария для обработки
     *      attributes  =>...   заполнить атрибутами, если нужно
     *      return      =>      используется для возврата
     *      [               индефикатор соответствия для сопоставления скачанных данных
     *          to[$id]=>object..        object обьект куда вернуть
     *          templateId =>[] шаброн для формирования id для сопоставления с object
     *      ]
     *  ]
     * ]
     */
    public static $lateLoad;

//    public function serialize(){
//    	return serialize($this->attributes);
//    }
//    public function unserialize($data){
//        $this->setScenario('data');
//  	     $this->attributes=unserialize($data);
//    }
    public function attributes()
    {
        return [
            'user_id', 'id', 'owner_id', 'from_id', 'v',  //default scenario
            'attachments',  //data scenario
            'code', 'state', 'captcha_sid', 'captcha_img', 'captcha_key', 'error_code', 'error_msg' //auth scenario
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['id', 'owner_id', 'from_id', 'user_id', 'v', 'captcha_key', 'captcha_sid',],
            'auth' => ['code', 'state', 'captcha_sid', 'captcha_img', 'captcha_key', 'error_code', 'error_msg', 'access_token'],
            'data' => ['attachments'],
        ];
    }

    public function rules()
    {
        return [
            /** default scenario */
            [['user_id', 'id', 'owner_id', 'from_id'], 'integer'],
            ['v', 'default', 'value' => '4.104'],

            /** data scenario */
            ['attachments', 'validAttachments'],

            /** auth scenario */
            [['code', 'state', 'captcha_sid', 'captcha_img', 'captcha_key', 'error_code', 'error_msg'], 'safe'],
            //['access_token','validToken'],
            //['access_token','default','value'=>function() {return $this->validToken('access_token',null);}],
        ];
    }

    public function authorize()
    {

//		if(!isset ($_SESSION))
//			session_start();

        $result = false;
        if ($this->getAuthJson()) return true;
        else {
            $scenario = (string)$this->scenario;
            $this->setScenario('auth');
            $this->attributes = $_REQUEST;
            if (!$this->validate()) throw new ErrorException('Ошибка в валидацыи auth');

            if (!$this->code) return $this->getAuthCode();
            else //if($this->state === $_SESSION['vkPhpSdk']['state'])
            {
                $this->_authJson = file_get_contents(
                    $this->_accessTokenUrl
                    . '?client_id=' . $this->_clientId
                    . '&client_secret=' . $this->_clientSecret
                    . '&code=' . $this->code
                    . '&redirect_uri=' . $this->_redirectUri
                );
                if ($this->getAuthErrors($this->_authJson)) return 'error';
                $this->_authJson = $this->setAuthJson($this->_authJson);

                if ($this->_authJson !== false) {
                    //	$_SESSION['vkPhpSdk']['authJson'. $this->_clientId] = $this->_authJson;
                    $result = true;
                } else
                    $result = false;
            }
        }
        $this->setScenario($scenario);
        return $result;
    }

    protected function getAuthCode()
    {
        $conf = \Yii::$app->getComponents('authClientCollection')['authClientCollection']['clients']['vkontakte'];
        $_SESSION['vkPhpSdk']['state'] = md5(uniqid(rand(), true)); // CSRF protection

        $this->_dialogUrl .= '?client_id=' . $this->_clientId;
        $this->_dialogUrl .= '&redirect_uri=' . $this->_redirectUri;
        $this->_dialogUrl .= '&scope=' . $this->_scope;
        $this->_dialogUrl .= '&response_type=' . $this->_responseType;
        $this->_dialogUrl .= '&state=' . $_SESSION['vkPhpSdk']['state'];

        echo("<script>top.location.href='" . $this->_dialogUrl . "'</script>");
        exit();
    }

    /**
     * Get errors.
     *
     * @param null $e
     * @return string
     */
    public function getAuthErrors($e = null)
    {
        if (is_array($e) && isset($e['error'])) {
            $err = new Error;
            $err->attributes = (array)$e['error'];
            $err->validate();
            return $err->getHtml();
        }
        return false;
    }

    /**
     * Get access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        if ($this->_accessParams === null)
            $this->_accessParams = json_decode($this->getAuthJson(), true);
        return $this->_accessParams['access_token'];
    }

    public function getAccessParams()
    {
        return $this->_accessParams;
    }

    /**
     * Get expires time.
     *
     * @return string
     */
    public function getExpiresIn()
    {
        if ($this->_accessParams === null)
            $this->_accessParams = json_decode($this->getAuthJson(), true);
        return $this->_accessParams['expires_in'];
    }

    /**
     * Get user id.
     *
     * @return string
     */
    public function getUserId()
    {
        if ($this->_accessParams === null)
            $this->_accessParams = json_decode($this->getAuthJson(), true);
        return $this->_accessParams['user_id'];
    }

    /**
     * Get authorization JSON string.
     * @return string
     * @throws ErrorException
     */
    protected function getAuthJson()
    {

        if ($this->_authJson) return $this->_authJson;
        else {
            $auth = @file_get_contents(__dir__ . '/auth.json', 'r');
            if (!$auth) return false;
            if (!@json_decode($auth, true)) throw new ErrorException('Не могу декодировать auth.json ' . json_last_error());
            return $auth;
        }
    }

    /**
     * @param $p
     * @return var
     * @throws ErrorException
     */
    protected function setAuthJson($p)
    {
        if (!@json_decode($p)) throw new ErrorException('При записи auth.json строка не в формате JSON ' . json_last_error());
        $f = fopen(__dir__ . '/auth.json', 'w+');
        if (!fwrite($f, $p)) throw new ErrorException('Не могу записать файл auth.json');
        fclose($f);
        return $p;
    }

    //    public function validToken($attribute,$params)
    //    {
    //        if($token=json_decode(file_get_contents(__dir__.'/token.json','r'),true)) return $this->$attribute=$token['access_token'];
    //        else return $this->$attribute=false;
    //    }
    public function validAttachments($a, $p)
    {
        $aa = [];
        foreach ($this->attachments as $k => $v) {
            if (is_object($v)) {
                $aa[$k] = $v;
                continue;
            }
            $name = ucfirst($v['type']);
            $name = "\\djeager\\api\\vkontakte\\" . $name;
            //            $objname =
            if (!class_exists($name))
                continue;
            $o = new $name();
            if ($o instanceof Vk) $o->parent = $this;
            $o->setScenario('data');
            $o->attributes = (array )$v[$v['type']];
            $o->validate();

            $o->runScenario();


            $aa[$k] = $o;
        }
        $this->attachments = $aa;
    }

    /**
     * @return \Oauth2Proxy
     */
    protected function auth()
    {
        if (self::$auth) return self::$auth;
        if ($this->authorize() == true) return self::$auth = $this;
        else \Yii::warning("Ошибка");
    }

    /**
     * @return \VkPhpSdk
     */
    public function getSdk()
    {
        if (self::$sdk)
            return self::$sdk;
        $oauth2Proxy = $this->auth();
        $vkPhpSdk = new \VkPhpSdk();

        $vkPhpSdk->setAccessToken($oauth2Proxy->getAccessToken());
        $vkPhpSdk->setUserId($oauth2Proxy->getUserId());
        return self::$sdk = $vkPhpSdk;
    }

    /**
     * @param Vk|null $obj
     * @return array
     */
    public function getData(Vk $obj = null)
    {
        $obj = $obj ?: $this;
        //$obj->data=  /* $obj->data ? :*/ $obj->data = $obj->getSdk()->api(strtolower($obj->modelname) . "." . $obj->scenario, $obj->getAttributes());
        $data = $obj->getSdk()->api(strtolower($obj->modelname) . "." . $obj->scenario, $obj->getAttributes());

        //return $this->getAuthErrors($data)?:$data;
        return $data;
//        if( $this->getAuthErrors($obj->data)) {
//            $c=new yii\base\View;
//           // $c->viewPath='/';
//            return $c->render('@vendor/djeager/parser/module/views/vk/_formCaptcha',['model'=>$this]);
//        }
//        return $obj->data ; 
    }

    public function getHtml()
    {
//        if (!$data)
//            $data = $this->getData();
//        if (!is_array($data) && !is_object($data))
//            return;
        //$this->trigger(self::EVENT_BEFORE_HTML_REPLACE);
        $data = $this->attributes;
        $r = preg_replace_callback("~{([^}]+)}~", function ($m) use ($data) {
            //if (!isset($data[$m[1]])) return ''; 
            $v = isset($data[$m[1]]) ? $data[$m[1]] : null;
            if (method_exists($this, "get" . $kname = ucfirst($m[1]))) return $this->{"get$kname"}($v);
            elseif (!is_array($v) && !is_object($v) && $v) return $v;
            else return '';
        }, $this->template);
        //$this->trigger(self::EVENT_AFTER_HTML_REPLACE);
        return $r;
    }

    public function getAttachment($val)
    {
        //    	$objname="\\vendor\\djeager\\parser\\extensions\api\\vk\\".ucfirst($val['type']);
        //
        //        if(!class_exists($objname))return;
        //echo "<pre>";
        //print_r($val->className());
        //echo "</pre>";
        //        $obj=new $objname;
        //        $obj->setScenario('data');
        //        $obj->load([ucfirst($val['type'])=>$val[$val['type']]]);
        //        $obj->validate();
        //
        //        $this->setEvents($obj->getEvents());
        return $val->getHtml();
    }

    public function getAttachments(array $val = null)
    {
        $val = $val ?: $this->attachments;
        // if (count($val) <= 1)
        //    return;
        $html = null;
        // array_shift($val);
        foreach ((array)$val as $v) {
            $html .= $this->getAttachment($v);

        }

        return $html;
    }

    public function getViewPath()
    {
        return __DIR__ . "/views";
    }

    public function getSource_name()
    {
        return 'vkontakte';
    }

    public function setLinks(\yii\base\Model $v)
    {
        return key_exists($v->formName(), $this->_links) ? false : $this->_links[$v->formName()] = $v;
    }


    public function getLinks($name = null)
    {
        if ($name) return key_exists($name, $this->_links) ? $this->_links[$name] : false;
        else return $this->_links;
    }
    public function getColor()
    {
        return new Color();
    }
}

?>