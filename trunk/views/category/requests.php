<?php

/**
 * @param \app\models\Category $category
 * @param \app\models\Request[] $requests
 * @param mixed $pagination
 */

use yii\helpers\Url;
use yii\helpers\Html;

\app\assets\CategoryAsset::register($this);
$this->title = Yii::t('app', 'Вопросы');
?>
<div id="header-requests">
    <div class="container">
        <div class="col-sm-12">
            <h1><?= $category->parent->title . ' / ' . $category->title; ?></h1>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-4">
            <a href="<?= Url::to(['/category/contacts', 'id' => $category->id]); ?>"><?= Yii::t('app', 'Контакты'); ?></a>
            |
            <?= Yii::t('app', 'Вопросы'); ?>
        </div>
        <div class="col-sm-4">
            <?= Html::button(Yii::t('app', 'Добавить вопрос'), array('class' => 'btn btn-default', 'id' => 'add-request-button')); ?>
        </div>
        <div class="col-sm-4">
            <?= Html::beginForm('', 'post', ['class' => 'form-horizontal']); ?>
            <div class="form-group">
                <?= Html::label(Yii::t('app', 'Сортировать:'), 'select-sort', ['class' => 'control-label col-sm-6']); ?>
                <div class="col-sm-6">
                    <?= Html::dropDownList(
                            'sortby',
                            [],
                            [
                                'date DESC' => Yii::t('app', '↓ По дате'),
                                'date ASC' => Yii::t('app', '↑ По дате')],
                            ['class' => 'form-control', 'id' => 'select-sort', 'data-id' => $category->id]); ?>
                </div>
            </div>
            <?= Html::endForm(); ?>
        </div>
    </div>
</div>
<div id="list-requests">
    <div class="container">
        <?php if (empty($requests)): ?>
            <div class="col-sm-12">
                <?= Yii::t('app', 'В этой категории еще нет ни одного вопроса'); ?>
            </div>
        <?php else: ?>
            <?= $this->render('/request/list', ['requests' => $requests]); ?>
        <?php endif; ?>
    </div>
</div>
<?php if ($pagination['page'] < $pagination['pages'] - 1): ?>
    <div id="more-requests" class="container">
        <div class="col-sm-offset-5 col-sm-2">
            <a href="#" data-page="<?= $pagination['page'] + 1; ?>" data-id="<?= $category->id; ?>" class="btn btn-default btn-block"><?= Yii::t('app', 'Еще'); ?></a>
        </div>
    </div>
<?php endif; ?>
<script>
    currentCategory = "<?= $category->id; ?>";
    currentCategoryTitle = "<?= $category->title; ?>";
</script>