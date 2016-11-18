<?php

/**
 * @param \app\models\RequestAnswer $root Root answer(contact)
 * @param \app\models\RequestAnswer $answer Answer for quoting
 */

use yii\helpers\Html;
?>

<div id="popup-create-answer">
    <?= Html::beginForm('', 'POST', ['class' => 'form-horizontal']); ?>
    <?= Html::hiddenInput('model[root_id]', $root->id); ?>
    <?= Html::hiddenInput('model[answer_id]', empty($answer) ? '' : $answer->id); ?>
    <h1><?= Yii::t('app', 'Ответить'); ?><span class="close">&times;</span></h1>
    <div>
        <?php if (!empty($answer)): ?>
            <div class="container-fluid form-group">
                <div class="col-sm-12">
                    <?= Yii::t('app', '{name} писал:', ['name' => $answer->user->name]); ?>
                </div>
            </div>
            <div class="container-fluid form-group">
                <div class="col-sm-12">
                    <?= $answer->text; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="container-fluid form-group">
            <div class="col-sm-12">
                <?= Html::textarea('model[text]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Ваш ответ')]); ?>
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