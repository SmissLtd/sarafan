<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div id="popup-profile">
    <?= Html::beginForm('', 'POST', ['class' => 'form-horizontal']); ?>
    <h1><?= Yii::t('app', 'Редактирование профиля'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Имя / Фамилия*'), 'input-name', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::input('text', 'model[name]', Yii::$app->user->identity->name, ['class' => 'form-control', 'id' => 'input-name', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Имя / Фамилия*')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'E-mail*'), 'input-email', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::input('text', 'model[email]', Yii::$app->user->identity->email, ['class' => 'form-control', 'id' => 'input-email', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'E-mail*')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Логин*'), 'input-login', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::input('text', 'model[login]', Yii::$app->user->identity->login, ['class' => 'form-control', 'id' => 'input-login', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Логин*')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Телефон'), 'input-phone', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::input('text', 'model[phone]', Yii::$app->user->identity->phone, ['class' => 'form-control', 'id' => 'input-phone', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Телефон')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Город'), 'input-city', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::input('text', 'model[city]', Yii::$app->user->identity->city, ['class' => 'form-control', 'id' => 'input-city', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Город')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Страна'), 'input-country', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::input('text', 'model[country]', Yii::$app->user->identity->country, ['class' => 'form-control', 'id' => 'input-country', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Страна')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Пароль'), 'input-password', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::passwordInput('model[password]', '', ['class' => 'form-control', 'id' => 'input-password', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Оставьте пустым, если не нужно менять')]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Пароль (повтор)'), 'input-password2', ['class' => 'control-label col-sm-4']); ?>
            <div class="col-sm-8">
                <?= Html::passwordInput('model[password2]', '', ['class' => 'form-control', 'id' => 'input-password2', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Повтор пароля (если меняете)')]); ?>
            </div>
        </div>
        <div class="form-group text-right">
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>