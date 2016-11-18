<?php

/**
 * @param \app\models\ContactRecommendation $recommendation
 */

use yii\helpers\Html;
?>
<div id="popup-recommendation-edit">
    <?= Html::beginForm('', 'post', ['class' => 'form-horizontal']); ?>
    <?= Html::hiddenInput('id', $recommendation->id); ?>
    <h1><?= Yii::t('app', 'Редактирование рекомендации'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Коментарий*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::textarea('model[comment]', $recommendation->comment, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Коментарий*'), 'style' => 'height: 200px;']); ?>
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