<?php

namespace djeager\api\facebook;


class Post extends Fb
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
            'id', 'source', 'caption', 'link', 'picture', 'message', 'description', 'attachments',
        ];
    }

    public function rules()
    {
        return [
            [$this->attributes(), 'safe'],
            ['attachments', 'validAttachments'],

        ];
    }

    public function getAlias()
    {
        return [

        ];
    }

    public function afterValidate()
    {
        if ($this->source) {
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