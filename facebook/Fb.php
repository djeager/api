<?php

namespace djeager\api\facebook;

use backend\modules\post\extensions\BaseParser;

class Fb extends BaseParser
{
    public $parent;
    private $_links = [];


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

    public function validAttachments($a, $p)
    {
        $aa = [];
        foreach ($this->attachments['data'] as $k => $v) {
            foreach ($v['media'] as $obj => $data) {
                if (is_object($obj)) {
                    $aa[$k] = $v;
                    continue;
                }
                $name = ucfirst($obj);
                $name = "\\djeager\\api\\facebook\\" . $name;
                //            $objname =
                if (!class_exists($name))
                    continue;
                $o = new $name();
                if ($o instanceof Fb) $o->parent = $this;
                $o->setScenario('fields');
                $o->setAttributes($data);
                $o->validate();

                $aa[] = $o;
            }
        }
        $this->attachments = $aa;
    }

    /** @return array */
    public function getData()
    {
        return json_decode(\backend\modules\post\extensions\helpers\Html::getSslPage($this->queryUrl), true);
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
        return $this->getScenario() !== 'default' ?: '';
    }

    public function getQueryUrl()
    {
        $url = "https://graph.facebook.com/v2.8/";
        $url .= mb_strtolower($this->formName());
        $url .= '/' . $this->edge;
        $url .= '?' . http_build_query(array_merge($this->getAttributes($this->activeAttributes()), ['access_token' => $this->access_token]));
        return $url;
    }

    public function getViewPath()
    {
        return __DIR__ . "/views";
    }

    public function getLinks($name = null)
    {
        if ($name) return key_exists($name, $this->_links) ? $this->_links[$name] : false;
        else return $this->_links;
    }

    public function setLinks(\yii\base\Model $v)
    {
        return key_exists($v->formName(), $this->_links) ? false : $this->_links[$v->formName()] = $v;
    }
}
