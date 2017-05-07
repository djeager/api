<?php

namespace djeager\api\facebook;

use backend\modules\post\extensions\BaseParser;

class Fb extends BaseParser
{
    public $id;

    public function attributes()
    {
        return [];
    }
    public function scenarios()
    {
        return [];
    }
    public function rules()
    {
        return [];
    }
    public function attributeLabels()
    {
        return [];
    }

    /** @return array */
    public function getData()
    {
        return json_decode(\vendor\djeager\parser\extensions\helpers\Html::getSslPage($this->queryUrl),true);
    }
    public function getHtml()
    {

    }
    public function getAccess_token()
    {
        return (new Auth)->accessToken;
    }
    public function getEdge()
    {
        return $this->getScenario() !== 'default' ? : '';
    }
    public function getQueryUrl()
    {
        $url = "https://graph.facebook.com/";
        $url .= mb_strtolower($this->formName());
        $url .= '/' . $this->edge;
        $url .= '?' . http_build_query(array_merge($this->getAttributes($this->activeAttributes()), ['access_token' => $this->access_token]));
        return $url;
    }
    public function getViewPath(){
        return __DIR__."/views";
    }
}
