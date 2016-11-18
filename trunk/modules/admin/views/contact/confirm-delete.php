<?php

/**
 * @param \app\models\Contact $contact
 */

use yii\helpers\Html;
?>
<div id="confirm-delete-contact">
    <?= Html::beginForm('', 'post'); ?>
    <?= Html::hiddenInput('id', $contact->id); ?>
    <h1><?= Yii::t('app', 'Удаление контакта'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <?= Yii::t('app', 'Вы уверены, что хотите удалить контакт?'); ?>
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