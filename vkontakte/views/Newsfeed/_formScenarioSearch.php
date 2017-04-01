<?php
use yii\helpers\Html;

    
    $attr='q';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr);
    echo Html::endTag('div');


    $attr='extended';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group col-lg-2 col-md-2 '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr,['class'=>'form-control']);
    echo Html::endTag('div');
    
    $attr='count';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group  col-lg-2 col-md-2 '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::input('number',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr,['class'=>'form-control']);
    echo Html::endTag('div');
    
    $attr='latitude';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group col-lg-2 col-md-2 '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr,['class'=>'form-control']);
    echo Html::endTag('div');
    
    $attr='longitude';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group col-lg-2 col-md-2 '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr,['class'=>'form-control']);
    echo Html::endTag('div');
    
    $attr='start_time';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group col-lg-2 col-md-2 '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->attributeLabels()[$attr],null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr,['class'=>'form-control']);
    echo Html::endTag('div');
?>