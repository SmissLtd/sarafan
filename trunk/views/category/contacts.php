<?php

/**
 * @param \app\models\Category $category
 * @param \app\models\Contact[] $contacts
 * @param mixed $pagination
 */

use yii\helpers\Url;
use yii\helpers\Html;

\app\assets\CategoryAsset::register($this);
$this->title = Yii::t('app', 'Контакты');
?>
<div id="header-contacts">
    <div class="container">
        <div class="col-sm-12">
            <h1><?= $category->parent->title . ' / ' . $category->title; ?></h1>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-4">
            <?= Yii::t('app', 'Контакты'); ?>
            |
            <a href="<?= Url::to(['/category/requests', 'id' => $category->id]); ?>"><?= Yii::t('app', 'Вопросы'); ?></a>
        </div>
        <div class="col-sm-4">
            <?= Html::button(Yii::t('app', 'Добавить контакт'), array('class' => 'btn btn-default', 'id' => 'add-contact-button')); ?>
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
                                'date ASC' => Yii::t('app', '↑ По дате'),
                                'rating DESC' => Yii::t('app', '↓ По рейтингу'),
                                'rating ASC' => Yii::t('app', '↑ По рейтингу')],
                            ['class' => 'form-control', 'id' => 'select-sort', 'data-id' => $category->id]); ?>
                </div>
            </div>
            <?= Html::endForm(); ?>
        </div>
    </div>
</div>
<div id="list-contacts">
    <?php if (empty($contacts)): ?>
        <div class="container">
            <div class="col-sm-12">
                <?= Yii::t('app', 'В этой категории еще нет ни одного контакта'); ?>
            </div>
        </div>
    <?php else: ?>
        <?= $this->render('/contact/list', ['contacts' => $contacts]); ?>
    <?php endif; ?>
</div>
<?php if ($pagination['page'] < $pagination['pages'] - 1): ?>
    <div id="more-contacts" class="container">
        <div class="col-sm-offset-5 col-sm-2">
            <a href="#" data-page="<?= $pagination['page'] + 1; ?>" data-id="<?= $category->id; ?>" class="btn btn-default btn-block"><?= Yii::t('app', 'Еще'); ?></a>
        </div>
    </div>
<?php endif; ?>
<script>
    currentCategory = "<?= $category->id; ?>";
    currentCategoryTitle = "<?= $category->title; ?>";
</script>