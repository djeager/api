<?php

namespace backend\modules\post\extensions\api\vk;


class Video extends Vk
{
    protected $template='<!--Video-->{player}<!--/Video-->';
    public function attributes(){
    	return array_merge([
            /** data scenario */
            /*v5.60     'id','owner_id','title','description','duration','photo_130','photo_320','photo_640','photo_800','date','adding_date','views','comments','player','access_key',*/
            /*v4.104*/  'vid','owner_id','title','description','duration','link','image','image_medium','date','player','access_key','views',
            /** get scenario */
                        'videos','album_id','count','offset','extended',
        ],parent::attributes());
    }
    public function scenarios(){
    	$p=parent::scenarios();
        $s=[
            /*v5.60     'data' =>   ['id','owner_id','title','description','duration','photo_130','photo_320','photo_640','photo_800','date','adding_date','views','comments','player','access_key',], */
            /*v4.104*/  'data'=>    ['vid','owner_id','title','description','duration','link','image','image_medium','date','player','access_key','views'],
                        'get' =>    array_merge(['owner_id','videos','album_id','count','offset','extended',],$p['default']),
        ];
        return array_merge($p,$s);
    }
    public function rules(){
    	return array_merge(parent::rules(),[
            /** data scenario */
            /*v5.60
                [['id','owner_id','date','adding_date','views','comments',],'integer'],
                [['title','description','access_key'],'string'],
                [['photo_130','photo_320','photo_640','photo_800','player'],'url'],
                ['id','validId'],*/
            /*v4.104*/
                [['vid','owner_id','duration','link','date','views'],'integer'],
                [['title','description','access_key'],'string'],
                [['image','image_medium','player'],'url'],
                ['vid','validVid'],
            
            /** get scenario */
                [['album_id','count','offset','extended'],'integer'],
                [['videos'],'string'],
                //['v','default','value'=>'5.62']   
        ]);
    }
    /*v4.104*/
    public function validVid($a,$p){
    	if($this->scenario=='data') {
            if(isset(Vk::$lateLoad[$this->className()]['attributes']['videos'])) 
                $preVideos=Vk::$lateLoad[$this->className()]['attributes']['videos'];
            else $preVideos=null;
            $vname=$this->owner_id;
            $vname.='_'.$this->vid;
            if( isset($this->access_key))$fvid=$vname.'_'.$this->access_key;
            Vk::$lateLoad[$this->className()]['scenario']='get';
            Vk::$lateLoad[$this->className()]['attributes']['videos']=($fvid?:$vname).($preVideos?','.$preVideos:'');
            Vk::$lateLoad[$this->className()]['return']['templateId']=['owner_id','_','vid'];
            Vk::$lateLoad[$this->className()]['return']['to'][$vname]=$this;

    	}

        return $this->vid;
    }
    /*v5.60
    public function validId($a,$p){
    	if($this->scenario=='data') {
            if(isset(Vk::$lateLoad[$this->className()]['attributes']['videos'])) 
                $preVideos=Vk::$lateLoad[$this->className()]['attributes']['videos'];
            else $preVideos=null;
            $vname=$this->owner_id;
            $vname.='_'.$this->id;
            if( isset($this->access_key))$fvid=$vname.'_'.$this->access_key;
            Vk::$lateLoad[$this->className()]['scenario']='get';
            Vk::$lateLoad[$this->className()]['attributes']['videos']=($fvid?:$vname).($preVideos?','.$preVideos:'');
            Vk::$lateLoad[$this->className()]['return']['templateId']=['owner_id','_','id'];
            Vk::$lateLoad[$this->className()]['return']['to'][$vname]=$this;

    	}
        return $this->id;
    }*/
    
    public function scenarioData(Vk $obj=null)
    {
        if($this->scenario=='data') {
            return $this->attributes;
        }elseif($this->scenario=='default'){ 
            return $this->attributes;
        }else{
            $d=parent::getData($obj);
            return $this->data=$d['response'];
        }
    }
    public function scenarioGet(){
    	return $this->getData();
    }
    public function getHtml($data=null)
    {

        return parent::getHtml();
        
    }
    public function getPlayer($val=null){
    	if(!$val) return;
        return "<div class='embed-responsive embed-responsive-16by9'>
          <iframe class='embed-responsive-item' src='$val'></iframe>
        </div>
        ";
    }

}

?>