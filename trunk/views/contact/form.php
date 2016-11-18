<?php

/**
 * @param string $title
 * @param \app\models\Request $request
 * @param \app\models\Contact $contact
 */

use yii\helpers\Html;
?>
<?= Html::beginForm('', 'POST', ['class' => 'form-horizontal']); ?>
<?= Html::hiddenInput('model[category_id]', empty($request) ? 0 : (string)$request->category_id); ?>
<?= Html::hiddenInput('request_id', empty($request) ? 0 : $request->id); ?>
<?= Html::hiddenInput('contact_id', empty($contact) ? 0 : $contact->id); ?>
<?php 
    if (!empty($request))
        echo Html::hiddenInput('model[category_title]', $request->category->title);
?>
<h1><?= $title; ?><span class="close">&times;</span></h1>
<br />
<div>
    <div class="container-fluid form-group">
        <div class="col-sm-12">
            <?php if (empty($request)): ?>
                <?= Html::input('text', 'model[category_title]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Выберите категорию и подкатегорию*')]); ?>
            <?php else: ?>
                <?= Html::input('text', '', $request->category->title, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Выберите категорию и подкатегорию*'), 'disabled' => 'disabled']); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-sm-5">
            <div class="form-group">
                <?= Html::input('text', 'model[name]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Имя*')]); ?>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-5">
            <div class="form-group">
                <?= Html::input('text', 'model[city]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Город*')]); ?>
                
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="col-sm-5">
            <div class="form-group">
                <?= Html::input('text', 'model[address]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Адрес')]); ?>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-5">
            <div class="form-group">
                <?= Html::input('text', 'model[country]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Страна*')]); ?>
            </div>
        </div>
    </div>
    
    <div class="container-fluid" id="phones">
        <div class="col-sm-5">
            <div class="form-group">
                <div class="col-sm-11" style="padding: 0;">
                    <?= Html::input('text', 'model[phone_0]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Телефон*')]); ?>
                </div>
                <div class="col-sm-1" style="padding: 0;">
                    <?= Html::button('+', ['class' => 'btn btn-default btn-block', 'id' => 'add-phone']); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-5">
            <div class="form-group">
                <?= Html::input('text', 'model[company]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Компания')]); ?>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="col-sm-offset-7 col-sm-5">
            <div class="form-group">
                <?= Html::input('text', 'model[site]', '', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Сайт')]); ?>
            </div>
        </div>
    </div>

    
    <div class="container-fluid form-group">
        <div class="col-sm-12">
            <?= Html::textarea('model[comment]', Yii::t('app', 'Хочу порекомендовать данный контакт, так как...'), ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Напишите коментарий'), 'style' => 'height: 200px;']); ?>
        </div>
    </div>
    <div class="container-fluid form-group text-right">
        <div class="col-sm-12">
            <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-default']); ?>
        </div>
    </div>
</div>
<?= Html::endForm(); ?>
<script>
    var nextPhoneIndex = 1;
</script>