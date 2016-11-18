<?php

/**
 * @param \app\models\Request $request
 */

use yii\helpers\Html;
?>
<div id="popup-request-edit">
    <?= Html::beginForm('', 'post', ['class' => 'form-horizontal']); ?>
    <?= Html::hiddenInput('id', $request->id); ?>
    <h1><?= Yii::t('app', 'Редактирование вопроса'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Город*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[city]', $request->city, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Город*')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Страна*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[country]', $request->country, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Страна*')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Вопрос*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::textarea('model[text]', $request->text, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Вопрос*'), 'style' => 'height: 200px;']); ?>
            </div>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-offset-10 col-sm-2" style="padding-left: 0;">
                <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-info btn-block']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>