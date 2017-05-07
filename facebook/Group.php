<?php

namespace djeager\api\facebook;


class Group extends Fb
{
    public function attributes()
    {
        return [
        
        ];
    }
    public function scenarios()
    {
        return [
//            'default'=>['id','cover','description','email','icon','member_request_count','name','owner','parent','privacy','updated_time'],
//            'feed' => [],
        ];
    }
    public function rules()
    {
        return [];
    }
    public function attributeLabels()
    {
        return [];
    }
    
    public function getData(){
        $url="https://graph.facebook.com/{$this->id}/feed?".http_build_query(['access_token'=>(new Auth)->accessToken]);
    $data=	\vendor\djeager\parser\extensions\helpers\Html::getSslPage($url);
    echo "<pre>";
    echo '<p style="color:red;font-size:1.2em">'.__FILE__.':'.__LINE__.'<p />';
    print_r(json_decode($data,true));
    echo "</pre>";
    exit();
    }
    public function getHtml(){
    	
    }
}