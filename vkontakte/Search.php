<?php

namespace backend\modules\post\extensions\api\vk;

/**
 * Метод позволяет получить результаты быстрого поиска по произвольной подстроке
 */
class Search extends Vk
{
    public function attributes(){
    	return array_merge([
            'q','limit','filters','search_global',
            
        ],parent::attributes());
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['getHints']=   array_merge(['q','limit','filters','search_global'],$scenarios['default']);
        return $scenarios;    	
    }
    public function rules(){
    	return array_merge(parent::rules(),[
            ['q','string'],
            ['limit','integer','max'=>200],
            ['filters','string'],
            ['search_global','in','range'=>[0,1]],
            
            
        ]);
    }
    
}

?>