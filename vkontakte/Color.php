<?php
/**
 * Created by PhpStorm.
 * User: Я
 * Date: 08.04.2017
 * Time: 11:16
 */

namespace djeager\api\vkontakte;


class Color
{
    protected $color='#507299';

    public function __toString()
    {
        return $this->color;
    }
}