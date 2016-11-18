<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;

\app\assets\AuthAsset::register($this);
$this->title = Yii::t('app', 'Вход');
?>
<div id="form-login">
    <?= Html::beginForm(Url::to(['/auth/login-submit']), "POST", ['class' => 'form-horizontal']); ?>
    <!-- Login -->
    <div class="form-group">
        <?= Html::input('text', 'model[login]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Логин'), 'autocomplete' => 'off']); ?>
    </div>
    <!-- Password -->
    <div class="form-group">
        <?= Html::passwordInput('model[password]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Пароль'), 'autocomplete' => 'off']); ?>
    </div>
    <!-- Remember -->
    <div class="form-group">
        <div class="checkbox">
            <label>
                <?= Html::checkbox('model[remember]', true, ['autocomplete' => 'off']); ?>
                <?= Yii::t('app', 'Запомнить меня'); ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?= \yii\authclient\widgets\AuthChoice::widget(['baseAuthUrl' => ['/auth/auth'], 'popupMode' => false]); ?>
        </div>
        <div class="col-sm-8">
            <div class="form-group text-right">
                <a href="<?= Url::to(['/auth/register']); ?>" class="btn btn-danger" style="width: 200px;"><?= Yii::t('app', 'Регистрация'); ?></a>
                <?= Html::button(Yii::t('app', 'Вспомнить пароль'), ['class' => 'btn btn-warning', 'style' => 'width: 200px;']); ?>
                <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'btn btn-primary', 'style' => 'width: 200px;']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>

<div id="form-restore">
    <?= Html::beginForm(Url::to(['/auth/restore-submit']), "POST", ['class' => 'form-horizontal']); ?>
    <!-- Email -->
    <div class="form-group">
        <?= Html::input('text', 'model[email]', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'E-mail'), 'autocomplete' => 'off']); ?>
    </div>
    <div class="form-group text-right">
        <?= Html::button(Yii::t('app', '&lt;&lt; Обратно'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(Yii::t('app', 'Выслать новый пароль'), ['class' => 'btn btn-default']); ?>
    </div>
    <?= Html::endForm(); ?>
</div>