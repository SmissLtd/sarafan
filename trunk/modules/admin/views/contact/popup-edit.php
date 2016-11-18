<?php

/**
 * @param \app\models\Contact $contact
 */

use yii\helpers\Html;
?>
<div id="popup-contact-edit">
    <?= Html::beginForm('', 'post', ['class' => 'form-horizontal']); ?>
    <?= Html::hiddenInput('id', $contact->id); ?>
    <h1><?= Yii::t('app', 'Редактирование контакта'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Город*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[city]', $contact->city, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Город*')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Страна*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[country]', $contact->country, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Страна*')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Компания'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[company]', $contact->company, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Компания')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Имя*'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[name]', $contact->name, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Имя*')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Сайт'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[site]', $contact->site, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Сайт')]); ?>
            </div>
        </div>
        <div class="container-fluid form-group">
            <?= Html::label(Yii::t('app', 'Адрес'), '', ['class' => 'control-label col-sm-2']); ?>
            <div class="col-sm-10">
                <?= Html::input('text', 'model[address]', $contact->address, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Адрес')]); ?>
            </div>
        </div>
        <div id="phones">
            <?php $phones = $contact->phones; ?>
            <?php for ($index = 0; $index < count($phones); $index++): ?>
                <?php if ($index == 0): ?>
                    <div class="container-fluid form-group">
                        <?= Html::label(Yii::t('app', 'Телефон*'), '', ['class' => 'control-label col-sm-2']); ?>
                        <div class="col-sm-8">
                            <?= Html::input('text', "model[phone_$index]", $phones[$index]->phone, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Телефон*')]); ?>
                        </div>
                        <div class="col-sm-1" style="padding-left: 0;">
                            <?= Html::button('+', ['class' => 'btn btn-default btn-block', 'id' => 'add-phone']); ?>
                        </div>
                        <div class="col-sm-1" style="padding-left: 0;"></div>
                    </div>
                <?php else: ?>
                    <div class="container-fluid form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <?= Html::input('text', "model[phone_$index]", $phones[$index]->phone, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Телефон*')]); ?>
                        </div>
                        <div class="col-sm-1" style="padding-left: 0;">
                            <?= Html::button('+', ['class' => 'btn btn-default btn-block', 'id' => 'add-phone']); ?>
                        </div>
                        <div class="col-sm-1" style="padding-left: 0;">
                            <?= Html::button('-', ['class' => 'btn btn-default btn-block', 'id' => 'delete-phone']); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-offset-10 col-sm-2" style="padding-left: 0;">
                <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-info btn-block']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>