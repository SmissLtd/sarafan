<?php

/**
 * @param \app\models\Request $request
 */

use yii\helpers\Html;
?>
<div id="confirm-delete-request">
    <?= Html::beginForm('', 'post'); ?>
    <?= Html::hiddenInput('id', $request->id); ?>
    <h1><?= Yii::t('app', 'Удаление вопроса'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <?= Yii::t('app', 'Вы уверены, что хотите удалить вопрос?'); ?>
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