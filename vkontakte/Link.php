<?php

namespace backend\modules\post\extensions\api\vk;


class Link extends Vk
{
     protected $template='
      <!--link-->
          <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
              {thumbnail}
              <div class="caption">
                <p>{title}</p>
                <p>{decription}</p>
                <p><a href="{url}">Подробнее</a></p>        
              </div>
            </div>
          </div>
          <!--/link-->
    ';
    public function attributes(){
    	return array_merge([
            /** data scenario https://vk.com/dev/objects/link  */
            /*v5.60     'url','title','caption','description','photo','is_external','product','button','preview_page','preview_url',*/
            /*v4.104*/  'url','title','description','image_src','preview_page','preview_url',
        
        ],parent::attributes());
    }
    public function scenarios(){
    	return[
            /*v5.60     'data' =>['url','title','caption','description','photo','is_external','product','button','preview_page','preview_url',],*/
            /*v4.104*/  'data' => ['url','title','description','image_src','preview_page','preview_url',],
        ];
    }
    public function rules(){
    	return [
        /** data scenario */
        /*v5.60
            [['url','preview_url'],'url'],
            [[],'integer'],
            [['title','caption','description'],'string'],
            [['photo','product','button'],'vObject','params'=>['namespace'=>'\backend\modules\post\extensions\api\vk','config'=>['scenario'=>'data']]],
            [['is_external'],'boolean'],*/
        /*v4.104*/
            [['url','image_src'],'url'],
            [['title','description','preview_page','preview_url'],'string'],
        ];
    }
    public function scenarioData(){
    	return $this->attributes;
    }
    public function getPhoto($val=null){
        return is_object($val)? $val->getHtml():'';
    }
    public function getThumbnail($v){
    	$val=$v?:$this->image_src;
        return "<img src='$val'>";
    }
}

?>