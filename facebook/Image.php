<?php

namespace djeager\api\facebook;


class Image extends Fb
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
            'height', 'width', 'src',
        ];
    }

    public function rules()
    {
        return [
            [$this->attributes(), 'safe'],
            ['src', 'validSrc'],
        ];
    }

    public function validSrc()
    {
        $this->parent->links = $this;
    }

    public function getAlias()
    {
        return [
            'preview' => 'thumbnail',
            'description' => null,
        ];
    }

    public function getThumbnail()
    {
        return "<img src='{$this->src}'>";
    }
}