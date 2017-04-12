<?php

namespace djeager\api\vkontakte;
class Likes extends Vk
{
    public function atrributes()
    {
        return [
            /** data scenario */
            'count','user_likes','can_like','can_publish',
        ];
    }

}

?>