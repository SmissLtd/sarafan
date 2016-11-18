<?php
use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;
?>
<form>
    <fieldset>
        <legend><?= Yii::t('app', 'Личный кабинет'); ?></legend>
        <div class="container-fluid" style="padding-left: 0; padding-right: 0;">
            <div class="col-sm-8" style="padding-left: 0;">
                <div class="name"><?= Yii::$app->user->identity->name; ?></div>
                <div class="address"><?= Yii::$app->user->identity->address; ?></div>
                <div class="phone"><?= Yii::$app->user->identity->phone; ?></div>
            </div>
            <div class="col-sm-4" style="padding-right: 0;">
                <div class="text-right">
                    <?= Html::button(Yii::t('app', 'Редактировать'), ['class' => 'btn btn-default', 'id' => 'show-edit-profile']); ?>
                </div>
            </div>
        </div>
        <div class="auth-clients">
            <br/><br />
            <?php 
                $authAuthChoice = AuthChoice::begin(['baseAuthUrl' => ['/auth/auth'], 'popupMode' => false]);
                $authAuthChoice->autoRender = false;
            ?>
            <ul class="auth-clients clear">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <li class="auth-client<?= Yii::$app->user->identity->isAccountExists($client->getId()) ? ' exists' : ''; ?>">
                        <?php $authAuthChoice->clientLink($client) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php AuthChoice::end(); ?>
        </div>
        <fieldset>
            <legend><?= Yii::t('app', 'Важно!'); ?></legend>
            <div class="container-fluid" style="padding-left: 0; padding-right: 0;">
                <div class="col-sm-12 notice" style="padding-left: 0;">
                    <?= Yii::t('app', 'Авторизация через социальную сеть позволит вам сразу видеть рекомендации ваших друзей.'); ?>
                    <br />
                    <?= Yii::t('app', 'Мы гарантируем что никакая информация не будет публиковаться от вашего имени в соцсети.'); ?>
                </div>
            </div>
        </fieldset>
    </fieldset>
</form>