<?php

namespace djeager\api\vkontakte;
use Yii;
class Error extends Vk
{
    public function attributes()
    {
        return [
            /** error default */
            'error_code','error_msg','request_params',
            /** error 14 */
            'captcha_sid','captcha_img','captcha_key',
            /** error 17 */
            'redirect_uri',
        ];
    }
    public function scenarios(){
    	return [
            'default'=>['error_code','error_msg','request_params','captcha_sid','captcha_img','redirect_uri',],
        ];
    }
    public function rules(){
    	return [
            [['error_code','captcha_sid'],'integer'],
            ['error_msg','string'],
            //['request_params','vArray'],
            ['redirect_uri','url'],
        ];
    }
    public function scenarioDefault($e=null)
    {
        if(method_exists($this,"getError{$this->error_code}")) return $this->{"getError".$this->error_code}();
        $v=new yii\base\View;
        return $v->render("@vendor/djeager/parser/module/views/vk/_error_{$this->error_code}",['model'=>$this]);
    }
    public function getError17(){
       //echo file_get_contents($this->redirect_uri);
    	echo "<pre>";
     print_r($this);
     echo "</pre>";
     exit();
    }
}

?>