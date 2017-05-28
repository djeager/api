<?php
namespace djeager\api\facebook;


class Page extends Fb
{
    public function scenarios()
    {
        return [
            'fields' => $this->attributes(),
        ];
    }

    public function attributes()
    {
        return [
            'id', 'name', 'posts',
        ];
    }

    public function rules()
    {
        return [
            //   ['posts','\djeager\validatots\CreateObject','object'=>['fullname'=>'\\djeager\\api\\facebook\\Post'],'isArray'=>true]
        ];
    }

    public function scenarioFields()
    {

    }

    public function getAlias()
    {
        return [
            'title' => 'name',
        ];
    }

    public function getOrigin()
    {
        return $this->posts->link ?: "http://facebook.com/" . $this->posts->id;;
    }

    public function getDescription()
    {
        return $this->posts->message ?: $this->posts->description;
    }

    public function getImg()
    {

        return @$this->posts->links['Image']->src;
    }
}