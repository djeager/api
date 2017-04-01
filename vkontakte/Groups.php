<?php

namespace backend\modules\post\extensions\api\vk;


class Groups extends Vk
{
    public function attributes(){
    	return[
            /** data    scenario https://vk.com/dev/objects/group*/
            /*v5.60     'id','name','screen_name','is_closed','deactivated','is_admin','admin_level','is_member','invited_by','type','has_photo','photo_50','photo_100','photo_200' */
            /*v4.104*/  'gid','name','screen_name','is_closed','type','photo','photo_medium','photo_big',
            /** search  search https://vk.com/dev/groups.search*/
                        'q','type','country_id','city_id','future','market','sort','offet','count',
        ];        
    }
    public function scenarios(){
    	return[
            /*v4.104*/  'data' => ['gid','name','screen_name','is_closed','type','photo','photo_medium','photo_big',],
                        'search'=>['q','type','country_id','city_id','future','market','sort','offet','count',],
        ];
    }
    public function rules(){
    	return [
            /** data scenario*/
            /*v4.104*/
                [['gid','is_closed'],'integer'],
                [['name','screen_name','type'],'string'],
                [['photo','photo_medium','photo_big'],'url'],                
        ];
    }
    public function afterValidate(){
    	\backend\modules\post\extensions\Vkparser::$temp['owners'][$this->id]=$this;
    }
    public function getAlias(){
    	return [
           
        ];
    }
    public function getId(){
    	return $this->gid?-$this->gid:null;
    }
    public function getUrl(){
    	return "https://vk.com/".$this->screen_name;
    }
}

?>