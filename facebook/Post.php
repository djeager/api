<?php

namespace djeager\api\facebook;


class Post extends Fb
{
    public function rules()
    {
        return [
            [$this->attributes(), 'safe'],
            ['attachments', 'validAttachments'],

        ];
    }

    public function attributes()
    {
        return $this->scenarios($this->getScenario());
    }

    public function scenarios($scenario = null)
    {
        $list = [
            'default' => ['id', 'fields'],
            'data' => ['id', 'source','name', 'caption', 'link', 'picture', 'message', 'description', 'attachments',],
        ];
        return $scenario ? $list[$scenario] : $list;
    }

    public function getAlias()
    {
        return [

        ];
    }

    public function getNode()
    {
        return $this->id;
    }

    public function afterValidate()
    {
        if ($this->getScenario()=='data' && $this->source) {
            $v = new Video(['scenario' => 'fields']);
            $v->setAttributes(['source' => $this->source, 'description' => $this->description]);
            $v->validate();

            $a = $this->attachments;
            $a[] = $v;
            $this->attachments = $a;
        }
        return parent::afterValidate();
    }
}