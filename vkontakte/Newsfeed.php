<?php
namespace backend\modules\post\extensions\api\vk;
use yii\helpers\Html;

class Newsfeed extends Vk
{
    
    protected $template = '
        <div class="box">{text}<div>
        <!--Attachments-->{attachments}<!--/Attachments--></div>
        </div>';

    public function attributes()
    {
        return array_merge([
            /** data scenario */
            'from_id', 'date', 'text', 'comments', 'likes','reposts',
            /** search scenario */
            'q','extended','count','offset','latitude','longitude','start_time','end_time','start_from','fields','next_from',
           // 'filter',

        ], parent::attributes());
    }

    public function scenarios()
    {
        $s = parent::scenarios();
        //$s['default']=[];
        $s['search'] = array_merge(['q','extended','count','offset','latitude','longitude','start_time','end_time','start_from','fields','next_from',], $s['default']);
        $s['get'] = array_merge(['domain', 'count', 'offset', 'extended', 'filter', 'fields'], $s['default']);
        //$s['data'] = array_merge(['from_id', 'date', 'text', 'comments', 'likes','reposts','next_from'], $s['data'], $s['default']);
        $s['data']=$this->attributes();
        return $s;
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            /** search scenario */
            ['q',           'string'],
            ['extended',    'default','value'=>0],
            ['extended',    'in','range'=>[0,1],'message'=>'флаг, может принимать значения 1 или 0'],
            ['count',       'integer','min'=>1, 'max'=>200 ,'message'=>'положительное число, по умолчанию 30, максимальное значение 200'],
            ['offset',      'integer','min'=>1, 'max'=>1000],
            ['latitude',    'double','min'=>-90,'max'=>90],
            ['longitude',   'double','min'=>-180,'max'=>180],
            [['start_time', 'end_time'],'integer'],
            ['start_from',  'string'],
            ['next_from',   'validNexfrom'],
            
            /** data scenario */
            [['from_id', 'date', 'text', 'comments', 'likes','reposts','next_from'], 'safe'],
            
            
        ]);
    }
    public function attributeLabels(){
        return [
                    'q' => 'Поисковой запрос',
             'extended' => 'Владелец',
                'count' => 'Количество записей',
             'latitude' => 'Широта',
            'longitude' => 'Долгота',
           'start_time' => 'Начальное время',
           
        ];
    	
    }
    public function scenarioLabels(){
    	return[
               'search' => 'Поиск по новостям',
                  'get' => 'Данные текущего пользователя',
        ];
    }
    public function attributeHint(){
    	return [
            /** search scenario */
                    'q' => 'например, "New Year"',
             'extended' => 'указывается 1, если необходимо получить информацию о пользователе или группе, разместившей запись. По умолчанию 0.',
                'count' => 'указывает, какое максимальное число записей следует возвращать',
             'latitude' => 'географическая широта точки, в радиусе от которой необходимо производить поиск, заданная в градусах (от -90 до 90).',
            'longitude' => 'географическая долгота точки, в радиусе от которой необходимо производить поиск, заданная в градусах (от -180 до 180).',
           'start_time' => 'время в формате unixtime, начиная с которого следует получить новости для текущего пользователя. Если параметр не задан, то он считается равным значению времени, которое было сутки назад. ',
        ];
    }    
    public function validNexfrom($a,$p){if($this->$a==false)$this->addError($a,'Следующей страницы нет');}
    public function scenarioSearch(Vk $obj = null){
      $d = $this->getData($obj);
        if(is_string($d)) return $d;
        $this->next_from=@$d['response']['next_from']?:false;
        return $d;
    }
    public function getAlias(){
    	return ['query'=>'q','title'=>'','slug'=>'',];
    } 
    public function getHtml($data = null){
        return parent::getHtml($data ?: $this->attributes);
    }
    public function getUrl()
    {
        return "https://vk.com/feed?w=wall{$this->owner_id}-{$this->id}";	
    }
    
    

}

?>