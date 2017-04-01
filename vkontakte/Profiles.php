<?php

namespace backend\modules\post\extensions\api\vk;


class Profiles extends Vk{
    public function attributes(){
    	return [
            /** data scenario*/
            /*v4.104*/  'uid','first_name','last_name','sex','screen_name','photo','photo_medium_rec','online','online_app','online_mobile',
            
        ];
    }
    public function scenarios(){
    	return[
            /*v4.104*/ 'data'=> ['uid','first_name','last_name','sex','screen_name','photo','photo_medium_rec','online','online_app','online_mobile',],
        ];
    }
    public function rules(){
        return[
    	/** data scenario*/
        /*v4.104*/
            [['uid','sex','online'],'integer'],
            [['first_name','last_name','screen_name',],'string'],
            [['photo','photo_medium_rec'],'url'],
            
        ];
    }
    public function afterValidate(){
    	\backend\modules\post\extensions\Vkparser::$temp['owners'][$this->uid]=$this;
    }
    public function getId(){
    	return $this->uid;
    }
    public function getAlias(){
    	return[
            'name'=>'first_name',
        ];
    }
    public function getUrl(){
    	return "https://vk.com/".$this->screen_name;
    }
}

?>