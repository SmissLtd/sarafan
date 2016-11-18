<?php

/**
 * @param string $title
 */

use yii\helpers\Html;
?>
<?= Html::beginForm('', 'POST', ['class' => 'form-horizontal']); ?>
<?= Html::hiddenInput('model[category_id]'); ?>
<h1><?= $title; ?><span class="close">&times;</span></h1>
<br />
<div>
    <div class="container-fluid form-group">
        <div class="col-sm-6">
            <?= Html::input('text', 'model[category_title]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Выберите категорию и подкатегорию*')]); ?>
        </div>
    </div>
    <div class="container-fluid form-group">
        <div class="col-sm-6">
            <?= Html::input('text', 'model[city]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Город*')]); ?>
        </div>
    </div>
    <div class="container-fluid form-group">
        <div class="col-sm-6">
            <?= Html::input('text', 'model[country]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Страна*')]); ?>
        </div>
    </div>
    <div class="container-fluid form-group">
        <div class="col-sm-12">
            <?= Html::textarea('model[text]', Yii::t('app', 'Посоветуйте хорошего...'), ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Введите вопрос'), 'style' => 'height: 200px;']); ?>
        </div>
    </div>
    <div class="container-fluid form-group text-right">
        <div class="col-sm-12">
            <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-default']); ?>
        </div>
    </div>
</div>
<?= Html::endForm(); ?>