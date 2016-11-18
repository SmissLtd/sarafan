<?php

/**
 * @param \app\models\Category[] $categories
 * @param mixed $pagination
 * @param \app\models\Category $parent
 */

use yii\helpers\Html;

\app\modules\admin\assets\CategoryAsset::register($this);

$this->title = Yii::t('app', 'Управление подкатегориями категории "{title}"', ['title' => $parent->title]);
?>
<?= Html::hiddenInput('parent', $parent->id); ?>
<div class="container">
    <h1 class="text-center"><?= Yii::t('app', 'Управление подкатегориями категории "{title}"', ['title' => $parent->title]); ?></h1>
</div>
<div id="subcategories">
    <?= $this->render('list', ['categories' => $categories, 'pagination' => $pagination, 'isSubcategories' => true]); ?>
</div>
<div id="confirm-delete-subcategory">
    <?= Html::hiddenInput('delete-id', ''); ?>
    <div>
        <h1><?= Yii::t('app', 'Удаление подкатегории'); ?><span class="close">&times;</span></h1>
        <div>
            <div class="container-fluid">
                <div class="col-sm-12">
                    <?= Yii::t('app', 'Вы уверены, что хотите удалить подкатегорию <span id="title"></span>?'); ?>
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