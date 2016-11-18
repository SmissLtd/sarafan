<?php

/**
 * @param \app\models\User $user
 */

use yii\helpers\Html;
use app\models\User;
?>
<div id="popup-user-edit">
    <?= Html::beginForm('', 'POST'); ?>
    <?= Html::hiddenInput('id', $user->id); ?>
    <h1><?= $user->isNewRecord ? Yii::t('app', 'Создать пользователя') : Yii::t('app', 'Редактировать пользователя'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Имя / Фамилия*'), 'input-name', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::input('text', 'model[name]', $user->name, ['class' => 'form-control', 'id' => 'input-name', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Имя / Фамилия*')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'E-mail*'), 'input-email', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::input('text', 'model[email]', $user->email, ['class' => 'form-control', 'id' => 'input-email', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'E-mail*')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Логин*'), 'input-login', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::input('text', 'model[login]', $user->login, ['class' => 'form-control', 'id' => 'input-login', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Логин*')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Телефон'), 'input-phone', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::input('text', 'model[phone]', $user->phone, ['class' => 'form-control', 'id' => 'input-phone', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Телефон')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Город'), 'input-city', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::input('text', 'model[city]', $user->city, ['class' => 'form-control', 'id' => 'input-city', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Город')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Страна'), 'input-country', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::input('text', 'model[country]', $user->country, ['class' => 'form-control', 'id' => 'input-country', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Страна')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Пароль'), 'input-password', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::passwordInput('model[password]', '', ['class' => 'form-control', 'id' => 'input-password', 'autocomplete' => 'off', 'placeholder' => $user->isNewRecord ? Yii::t('app', 'Пароль') : Yii::t('app', 'Оставьте пустым, если не нужно менять')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Пароль (повтор)'), 'input-password2', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::passwordInput('model[password2]', '', ['class' => 'form-control', 'id' => 'input-password2', 'autocomplete' => 'off', 'placeholder' => $user->isNewRecord ? Yii::t('app', 'Повтор пароля') : Yii::t('app', 'Повтор пароля (если меняете)')]); ?>
                </div>
            </div>
            <br /><br />
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Роль'), 'input-admin', ['class' => 'control-label col-sm-4']); ?>
                <div class="col-sm-8">
                    <?= Html::dropDownList('model[role]', $user->role, [User::ROLE_USER => User::translate(User::ROLE_USER), User::ROLE_ADMIN => User::translate(User::ROLE_ADMIN)], ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                </div>
            </div>
            <br /><br />
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>