<?php

namespace djeager\api\facebook;


class Search  extends Fb
{
    public function attributes()
    {
        return [
            'q','type','fields'
        ];
    }
    public function scenarios()
    {
        return [
            'default' => ['q','type','fields'],
        ];
    }
    public function rules()
    {
        return [
            [['q','type'],'string'],
            //['type','in','range'=>array_keys($this->getTypes())],
            //[['q','type'],'required'],
            ['fields','safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'q'=>"Поисковый запрос",
            'type' => 'Тип поиска',
        ];
    }
    public function getTypes(){
    	return [
            'user'=>'Пользователь',
            'page'=>'Страницы',
            'event'=>'Мероприятия',
            'group'=>'Группы',
            'place'=>'Места',
            'placetopic'=>'Список тем'
        ];
    }
}