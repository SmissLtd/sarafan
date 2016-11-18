<?php

use \yii\helpers\Html;
use \yii\helpers\Url;

\app\assets\AuthAsset::register($this);
$this->title = Yii::t('app', 'Регистрация');
?>
<div id="form-register">
    <h1><?= Yii::t('app', 'Тебе поверят. Поэтому, добро пожаловать!'); ?></h1>
    <?= Html::beginForm(Url::to(['/auth/register-submit']), "POST", ['class' => 'form-horizontal']); ?>
    <!-- Code -->
    <div id="step-code">
        <div class="form-group">
            <div class="col-sm-10">
                <?= Html::input('text', 'model[code]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'КОД*'), 'autocomplete' => 'off']); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::button(Yii::t('app', 'ОК'), ['class' => 'btn btn-default btn-block']); ?>
            </div>
        </div>
    </div>
    
    <div id="step-social">
        <h1><?= Yii::t('app', 'Вход с помощью социальных сетей'); ?></h1>
        <p>
            <?= Yii::t('app', 'Авторизация через социальную сеть позволит вам сразу видеть рекомендации ваших друзей.'); ?>
            <br />
            <?= Yii::t('app', 'Мы гарантируем что никакая информация не будет публиковаться от вашего имени в соцсети.'); ?>
        </p>
        <?= \yii\authclient\widgets\AuthChoice::widget(['baseAuthUrl' => ['/auth/auth'], 'popupMode' => false]); ?>        
        <div class="container-fluid">
            <div class="col-sm-offset-8 col-sm-4">
                <?= Html::button(Yii::t('app', 'У меня нет аккаунта в соцсетях'), ['class' => 'btn btn-default btn-block']); ?>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-sm-offset-8 col-sm-4">
                <?= Html::button(Yii::t('app', 'Я не хочу использовать соцаккаунт'), ['class' => 'btn btn-default btn-block']); ?>
            </div>
        </div>
    </div>
    
    <div id="step-register">
        <h1><?= Yii::t('app', 'Регистрация аккаунта'); ?></h1>
        <!-- Name -->
        <div class="form-group">
            <?= Html::input('text', 'model[name]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Имя / Фамилия*'), 'autocomplete' => 'off', 'data-field' => 'name']); ?>
        </div>
        <!-- Email -->
        <div class="form-group">
            <?= Html::input('text', 'model[email]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'E-mail*'), 'autocomplete' => 'off', 'data-field' => 'email']); ?>
        </div>
        <!-- Login -->
        <div class="form-group">
            <?= Html::input('text', 'model[login]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Придумайте логин*'), 'autocomplete' => 'off', 'data-field' => 'login']); ?>
        </div>
        <!-- Password -->
        <div class="form-group">
            <?= Html::passwordInput('model[password]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Придумайте пароль*'), 'autocomplete' => 'off', 'data-field' => 'password']); ?>
        </div>
        <!-- Confirm password -->
        <div class="form-group">
            <?= Html::passwordInput('model[password2]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Повторите, чтобы не ошибиться*'), 'autocomplete' => 'off']); ?>
        </div>
        <br /><br />
        <!-- Phone -->
        <div class="form-group">
            <?= Html::input('text', 'model[phone]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Телефон'), 'autocomplete' => 'off', 'data-field' => 'phone']); ?>
        </div>
        <!-- City -->
        <div class="form-group">
            <?= \yii\jui\AutoComplete::widget([
                'name' => "model[city]",
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => Yii::t('app', 'Город'),
                    'autocomplete' => 'off',
                    'data-field' => 'city'],
                'clientOptions' => [
                    'minLength' => 1,
                    'source' => Url::to(['/auth/city-search'])
                ]]); ?>
        </div>
        <!-- Country -->
        <div class="form-group">
            <?= \yii\jui\AutoComplete::widget([
                'name' => "model[country]",
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => Yii::t('app', 'Страна'),
                    'autocomplete' => 'off',
                    'data-field' => 'country'],
                'clientOptions' => [
                    'minLength' => 1,
                    'source' => Url::to(['/auth/country-search'])
                ]]); ?>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-default']); ?>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>