<?php

/**
 * @param \app\models\RequestAnswer $answer
 */

use yii\helpers\Html;
?>

<div id="popup-create-complain">
    <?= Html::beginForm('', 'POST', ['class' => 'form-horizontal']); ?>
    <?= Html::hiddenInput('model[answer_id]', empty($answer) ? '' : $answer->id); ?>
    <h1><?= Yii::t('app', 'Написать жалобу'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid form-group">
            <div class="col-sm-12">
                <?= Html::textarea('model[text]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Описание')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>