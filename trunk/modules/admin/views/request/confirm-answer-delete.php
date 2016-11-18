<?php

/**
 * @param \app\models\RequestAnswer $answer
 */

use yii\helpers\Html;
?>
<div id="confirm-answer-delete">
    <?= Html::beginForm('', 'post'); ?>
    <?= Html::hiddenInput('id', $answer->id); ?>
    <h1><?= Yii::t('app', 'Удаление ответа'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <?= Yii::t('app', 'Вы уверены, что хотите удалить ответ?'); ?>
            </div>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-12">
                <br />
                <?= Html::submitButton(Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger']); ?>
                <?= Html::button(Yii::t('app', 'Отмена'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>