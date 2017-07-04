<?php
namespace djeager\api\facebook;


class Url extends Fb
{
    public function rules()
    {
        return [
            [$this->attributes(), 'safe']
        ];
    }

    public function attributes()
    {
        return $this->scenarios($this->getScenario());
    }

    public function scenarios($scenario = null)
    {
        $list = [
            'default' => ['ids'],
            'data' => ['id', 'created_time', 'message', 'story'],
        ];
        return $scenario ? $list[$scenario] : $list;
    }

    public function getNode()
    {
        return '';
    }
}