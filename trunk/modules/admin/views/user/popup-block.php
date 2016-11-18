<?php

/**
 * @param \app\models\User $user
 */

use yii\helpers\Html;
?>
<div id="popup-user-block">
    <?= Html::beginForm('', 'POST'); ?>
    <?= Html::hiddenInput('id', $user->id); ?>
    <h1><?= Yii::t('app', 'Заморозить'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="form-group">
                    <?= Html::input('text', 'model[till]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Навсегда')]); ?>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <?= Html::textarea('model[reason]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Напишите причину заморозки пользователя'), 'style' => 'height: 250px;']); ?>
                </div>
            </div>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'Заморозить'), ['class' => 'btn btn-danger']); ?>
                <?= Html::button(Yii::t('app', 'Отмена'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>