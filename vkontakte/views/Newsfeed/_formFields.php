<?php
use yii\helpers\Html;

$objs[]=$model;
$obj=reset($objs);
for($m=current($objs)->formName();$o=next($objs);) $m.=is_object($o)?"[{$o->formName()}]":"[$o]";
 
    //echo Html::errorSummary($model);
    
    $attr='scenario';
    $name=$m."[$attr]";
    echo Html::beginTag('div',['class'=>'from-group '.($model->hasErrors($attr)?'has-error':'')]);
        echo Html::radioList($name,$model->getScenario(),$model->scenarioLabels()) ;
    echo Html::endTag('div');
 
   echo $this->render("_formScenario".ucfirst($model->getScenario()),['model'=>$model,'m'=>$m,'objs'=>$objs])
?>