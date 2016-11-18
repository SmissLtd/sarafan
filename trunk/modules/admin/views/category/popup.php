<?php

/**
 * @param \app\models\Category $category
 * @param \app\models\Category $parentCategory
 */

use yii\helpers\Html;
?>
<div id="popup-category">
    <?= Html::beginForm('', 'POST'); ?>
    <?= Html::hiddenInput('id', $category->id); ?>
    <?= Html::hiddenInput('parent', $parentCategory->id); ?>
    <h1><?= empty($category->id) ? Yii::t('app', 'Создание категории') : Yii::t('app', 'Редактирование категории'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="form-group">
                    <?= Html::input('text', 'model[title]', $category->title, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Название')]); ?>
                </div>
            </div>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>