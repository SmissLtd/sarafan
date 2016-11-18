<?php

/**
 * @param \app\models\Category[] $categories
 * @param mixed $pagination
 */

use yii\helpers\Html;

\app\modules\admin\assets\CategoryAsset::register($this);

$this->title = Yii::t('app', 'Управление категориями');
?>
<div class="container-fluid">
    <h1 class="text-center"><?= Yii::t('app', 'Управление категориями'); ?></h1>
</div>
<div id="categories">
    <?= $this->render('list', ['categories' => $categories, 'pagination' => $pagination, 'isSubcategories' => false]); ?>
</div>
<div id="confirm-delete-category">
    <?= Html::hiddenInput('delete-id', ''); ?>
    <div>
        <h1><?= Yii::t('app', 'Удаление категории'); ?><span class="close">&times;</span></h1>
        <div>
            <div class="container-fluid">
                <div class="col-sm-12">
                    <?= Yii::t('app', 'Вы уверены, что хотите удалить категорию <span id="title"></span>?'); ?>
                </div>
            </div>
            <div class="container-fluid form-group text-right">
                <div class="col-sm-12">
                    <br />
                    <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger']); ?>
                    <?= Html::button(Yii::t('app', 'Отмена'), ['class' => 'btn btn-default']); ?>
                </div>
            </div>
        </div>
    </div>
</div>