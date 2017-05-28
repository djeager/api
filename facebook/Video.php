<?php

namespace djeager\api\facebook;


class Video extends Fb
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
            'source', 'description'
        ];
    }

    public function rules()
    {
        return [
            [$this->attributes(), 'safe'],
            ['source', 'validSource'],
        ];
    }

    public function validSource($a, $p)
    {
        return $this->$a = str_replace('autoplay=1', '', $this->$a);
    }

    public function getPreview()
    {
        return $this->source ? "<iframe src='{$this->source}'></iframe>" : null;
    }
}