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
        //return $this->posts->link ?: "http://facebook.com/" . $this->posts->id;;
        $id=explode('_',$this->posts->id);
        return isset($id[1])?"https://www.facebook.com/permalink.php?story_fbid=" . $id[1].'&id='.$id[0]:"https://www.facebook.com/".$id[0];
    }

    public function getDescription()
    {
        return $this->posts->message ?: $this->posts->description;
    }

    public function getImg()
    {

        return @$this->posts->links['Image']->src;
    }

    public function getTitle()
    {
        return $this->name?:$this->id;
    }
}