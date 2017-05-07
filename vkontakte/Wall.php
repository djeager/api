<?php
namespace djeager\api\vkontakte;
use yii\helpers\Html;

class Wall extends Vk
{
    public $template = '
        <div class="box">{text}
            <div>
                <!--Attachments--><div class="row">{attachments}</div><!--/Attachments-->
        </div>
    </div>';
    public function attributes()
    {
        return array_merge([
            /** data scenario https://vk.com/dev/objects/post */
            'id','owner_id','to_id','from_id','date','text','reply_owner_id','reply_post_id','friends_only','comments','likes','reposts','post_type','attachments','geo','owner',
            'copy_history',


            /*'owner_id',*/ 'domain', 'query', 'owners_only', 'count', 'offset', 'extended', 'fields',
            'filter',

            /** getById scenario */
            'posts', /* 'extended'*/
            ], parent::attributes());
    }

    public function scenarios()
    {
        $p = parent::scenarios();
        $s=[
                 'data' => array_merge($this->attributes(),['template']),
               'search' => array_merge(['owner_id', 'domain', 'query', 'owners_only', 'count', 'offset', 'extended', 'fields'], $p['default']),
                  'get' => array_merge(['owner_id', 'domain', 'offset', 'count', 'extended', 'filter', 'fields'], $p['default']),
              'getById' => array_merge(['posts','extended'], $p['default']),
        ];
        return array_merge($p,$s);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            /** data scenario */
            [['id','owner_id','from_id','date','reply_owner_id','reply_post_id','friends_only'],'integer'],
            [['text','post_type','template'],'string'],
            //[['comments','likes','reposts','geo'],'vArray'],
            //[['attachments'],'validAttachments'],
            ['copy_history','djeager\validators\CreateObject','isArray'=>true,'object'=>['fullName'=>$this->className()],'construct'=>['scenario'=>'data']],

            /** search scenario */
//            ['owner_id', 'integer'],
            [['owners_only','count','offset','extended'],'integer'],
            [['domain','query','fileds'],'string'],
            ['owners_only','default','value'=>'0'],
            ['count','integer','min'=>0,'max'=>100],
            ['offset','default','value'=>0],
            ['extended','default','value'=>1],
            ['query','required','on'=>['search']],

            /** get scenario */
//            ['domain', 'string'],
//            ['query', 'string'],
//            [['owners_only','default','value'=>1], 'in', 'range' => [0, 1]],
//            ['offset', 'integer'],
//            ['count', 'integer'],
//            ['extended', 'integer'],
//            ['fields', 'string'],
//
//            [['filter', 'default', 'value' => 'owner'], 'in', 'range' => ['suggests', 'postponed', 'owner', 'others', 'all']],

            /** getById scenario */
            ['posts','string'],
        ]);
    }

    public function attributeLabels(){
    	return[
             'template' => 'Шаблон подстановки',

            /** serach scenario */
            'owner_id'  => 'Индефикатор владельца пользователя или сообщества (owner_id)',
               'domain' => 'Короткий адрес пользователя или сообщества (domain)',
                'query' => 'Поисковой запрос (query)',
          'owners_only' => 'Записи только владельца? (owners_only)',
                'count' => 'Количество записей, которые необходимо вернуть[20] (count)',
               'offset' => 'Смещение (offset)',
             'extended' => 'Дополнительные поля [0] (extended)',
               'fields' => 'Список дополнительных полей для профилей и групп (fields)',
        ];
    }
    public function scenarioLabels(){
    	return[
                 'data' => 'Сохранить данные (data)',
               'search' => 'Искать на стене (search)',
                  'get' => 'Записи со стены (get)',
        ];
    }
    public function attributeHint(){
    	return [

            ''
        ];
    }
    public function afterValidate(){
        if($this->scenario=='data' && isset(\backend\modules\post\extensions\Vkparser::$temp['owners'][$this->owner_id?:$this->from_id]))
            $this->owner=\backend\modules\post\extensions\Vkparser::$temp['owners'][$this->owner_id?:$this->from_id];
    }
    public function getData(Vk $obj = null)
    {
        return parent::getData($obj);
        if(is_string($d)) return $d;
        return isset($d['response'])?$this->data = $d:false;
    }

    public function getHtml($data = null)
    {
        return parent::getHtml($data ?: $this->attributes).$this->getCopy_historys();
    }
    public function getAlias()
    {
        return[
            'title'=>null,
            'type'=>'post_type',
            'item_id' => 'id',
        ];
    }


    public function scenarioSearch(Vk $obj = null){
        return $this->getData($obj);
    }
    public function scenarioGet(Vk $obj = null){
        return $this->getData($obj);
    }

    public function scenarioGetById(Vk $obj = null){
        return $this->getData($obj);
    }
    public function getUrl()
    {
        return "https://vk.com/wall".($this->owner_id?:$this->to_id?:$this->from_id)."_{$this->id}";
    }
    public function getLikes(){
    	return isset($this->likes)?$this->likes['count']:'';
    }
    public function getComments(){
    	return isset($this->comments)?$this->comments['count']:'';
    }
    public function getReposts(){
    	return isset($this->reposts)?$this->reposts['count']:'';
    }
    public function getOrigin(){
    	return Html::a('https://vk.com',$this->url,['target'=>'_blank']);
    }
    public function getCopy_historys(){
        $a=null;
    	foreach((array)$this->copy_history as $obj) if(is_object($obj)) $a.=$obj->getHtml();
        return $a;
    }

    public function getImg()
    {
        if($p=$this->getLinks('Photo')) return $p->url;
        elseif($p=$this->getLinks('Video')) return $p->image;
        elseif($p=$this->getLinks('Link')) return $p->img;
        else return null;
    }

}

?>