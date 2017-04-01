<?php
use yii\helpers\Html;
    
    $attr='owner_id';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('number',$name,$model->$attr,['class'=>'form-control']);
        echo Html::error($model,$attr);
        echo Html::activeHint($model,$attr);
    echo Html::endTag('div');
    
    $attr='domain';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');
    
    
    $attr='count';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('number',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');
    
    $attr='offset';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('number',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');
    
    $attr='extended';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('number',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');
    

    
    /*
    $attr='fields';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');*/
?>