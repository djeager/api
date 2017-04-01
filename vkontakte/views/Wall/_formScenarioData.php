<?php
use yii\helpers\Html;
    
    
    $attr='template';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::textarea($name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr);
    echo Html::endTag('div');
?>