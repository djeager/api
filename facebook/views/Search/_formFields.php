<?php
use yii\helpers\Html;

$objs[]=$model;
$obj=reset($objs);
for($m=current($objs)->formName();$o=next($objs);) $m.=is_object($o)?"[{$o->formName()}]":"[$o]";
        
    $attr='q';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');
    
    $attr='type';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::dropDownList(
            $name,
            $model->type,
            $model->getTypes(),
            ['class'=>'form-control']          
        );
        echo Html::error($model,$attr);
    echo Html::endTag('div');
    
    $attr='fields';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::label($model->getAttributeLabel($attr),null,['class'=>'control-label']);
        echo Html::input('text',$name,$model->getAttribute($attr),['class'=>'form-control']);
        echo Html::error($model,$attr);
    echo Html::endTag('div');
?>