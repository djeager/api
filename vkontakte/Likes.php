<?php

namespace backend\modules\post\extensions\api\vk;
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