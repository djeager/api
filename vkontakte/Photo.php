<?php

namespace djeager\api\vkontakte;


class Photo extends Vk
{
   // protected $template='<!--Photo--><div>{photo_75}</div><!--/Photo-->';
    
     protected $template='
      <!--Photo--><div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          {thumbnail}
          <div class="caption">        
            <p>{text}</p>        
          </div>
        </div>
      </div><!--/Photo-->
    ';
    public function attributes(){
    	return array_merge([
            /** data scenario https://vk.com/dev/objects/photo */
            /*v5.60     'id','album_id','owner_id','user_id','text','date','size','photo_75','photo_130','photo_604','photo_807','photo_1280','photo_2560','width','height','access_key',
            /*v4.104*/  'pid','aid','owner_id','user_id','src','src_big','src_small','src_xbig','src_xxbig','src_xxxbig','width','height','text','created','post_id','access_key'
            ],parent::attributes());
    }
    public function scenarios(){
        $p = parent::scenarios();
        $s =[
            /*v5.60     'data' => ['id','album_id','owner_id','user_id','text','date','size','photo_75','photo_130','photo_604','photo_807','photo_1280','photo_2560','width','height','access_key',],
            /*v4.104*/  'data' => ['pid','aid','owner_id','user_id','src','src_big','src_small','src_xbig','src_xxbig','src_xxxbig','width','height','text','created','post_id','access_key'],
        ];
        //$scenarios['data']=array_merge(['pid','aid','src','src_big','src_small','width','height','text','created','post_id','access_key'],$scenarios['default']);

        return array_merge($p,$s);
    }
    public function rules(){
    	return array_merge(parent::rules(),[
            /** data scenario */
            /*v5.60
            [['id','album_id','owner_id','user_id','date','width','height'],'integer'],
            [['text',],'string'],
            [['photo_75','photo_130','photo_604','photo_807','photo_1280','photo_2560'],'url'],
            [['size'],'vArray'],*/
            
            /*v4.104*/
            [['pid','aid','owner_id','user_id','width','height','create'],'integer'],
            [['src','src_big','src_small','src_xbig','src_xxbig','src_xxxbig'],'url'],
            [['text'],'string']
        

        ]);
    }

    public function getAlias()
    {
        return[
            'preview'=>'thumbnail',
          'description'=>null,
        ];
    }
    public function scenarioData(Vk $obj=null){
        $this->parent->links=$this;
    	return $this->attributes;
    }
    public function getThumbnail(){
    	//$val=$this->src_xxxbig?:$this->src_xxbig?:$this->src_xbig?:$this->src_big?:$this->src;
        return "<img src='{$this->getUrl()}'>";
    }
    public function getPhoto_75($val){
        //$val=$this->photo_807 ?: $this->photo_604 ?: $this->photo_130 ?: $val;
    	return "<img src='{$this->getUrl()}'>";
    }
    public function generateData($data){
    	return $this;
    }

    public function getUrl()
    {
        return $this->src_xxxbig?:$this->src_xxbig?:$this->src_xbig?:$this->src_big?:$this->src;
    }
}

?>