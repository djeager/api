<?php

namespace djeager\api\facebook;


class Group extends Fb
{
    public $id;
    public $node = null;

    public function attributes()
    {
        return [
            // 'id', 'cover', 'description', 'email', 'icon', 'member_request_count', 'name', 'owner', 'parent', 'privacy', 'updated_time'
            'name', 'fields',
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['id', 'name', 'fields'],
            'feed' => ['id', 'fields'],
        ];
    }

    public function getEdge()
    {
        return $this->id . '/' . $this->getScenario() == !'default' ? $this->getScenario() : '';
    }

    public function rules()
    {
        return [
            ['fields', 'default', 'value' => implode(',', (new Post(['scenario' => 'data']))->attributes())],
            ['id','required'],
            ['id', 'validId'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function validId($a, $p)
    {
        if ((new \yii\validators\UrlValidator)->validate($this->$a)) {
            $params = parse_url($this->$a, PHP_URL_PATH);
            $params = explode('/', $params . '/');
            $params = array_values(array_diff($params, ['', null]));
            $url = new \djeager\api\facebook\Url();
            $ids = "https://www.facebook.com/" . $params[0];
            $url->setAttributes(['ids' => $ids]);
            $data = $url->getData();

            return $this->$a = $data[$ids]['id'];
        }
    }

//    public function getData(){
//        $url="https://graph.facebook.com/{$this->id}/feed?".http_build_query(['access_token'=>(new Auth)->accessToken]);
//    $data=	\vendor\djeager\parser\extensions\helpers\Html::getSslPage($url);
//    echo "<pre>";
//    echo '<p style="color:red;font-size:1.2em">'.__FILE__.':'.__LINE__.'<p />';
//    print_r(json_decode($data,true));
//    echo "</pre>";
//    exit();
//    }
//    public function getHtml()
//    {
//
//    }
}