<?php

/**
 * @param \app\models\Category[] $categories
 * @param string $filter
 * @param mixed $pagination
 * @param integer $columns
 */

use yii\helpers\Html;

\app\assets\HomeAsset::register($this);
$this->title = Yii::t('app', 'Главная');
?>
<!-- Buttons section -->
<div id="options" class="container text-uppercase">
    <div class="col-sm-5 text-right">
        <?= Yii::t('app', 'Я хочу'); ?>
    </div>
    <div class="col-sm-2">
        <a href="#categories" class="btn btn-default btn-block"><?= Yii::t('app', 'Искать'); ?></a>
        <a href="#request" class="btn btn-default btn-block"><?= Yii::t('app', 'Спросить'); ?></a>
        <a href="#contact" class="btn btn-default btn-block"><?= Yii::t('app', 'Шарить'); ?></a>
    </div>
    <div class="col-sm-5">
        <?= Yii::t('app', 'контакты'); ?>
    </div>
</div>
<div class="container mb150">
    <div class="col-sm-12">
        <br />
        <p><?= Yii::t('app', 'Нам просто надоели мутки, откаты, накрутки, нереальное количество времени, денег и сил потраченных на поиск порядочных исполнителей.'); ?></p>
        <p><?= Yii::t('app', 'Поэтому мы создали закрытый ресурс, куда смогут попасть только те люди, которым вы доверяете и только они смогут делится проверенной информацией, помогая друг другу.'); ?></p>
    </div>
</div>

<!-- Categories/Subcategories -->
<a name="categories"></a>
<div id="categories">
    <?= $this->render(
            '/category/list-categories', [
                'categories' => $categories, 
                'filter' => '', 
                'pagination' => $pagination, 
                'title' => Yii::t('app', 'Надо выбрать категорию'),
                'columns' => $columns]); ?>
</div>
<a name="subcategories"></a>
<div id="subcategories"></div>
<div class="container">
    <div class="col-sm-12">
        <div class="text-right">
            <?= Yii::t('app', 'Не могу ничего найти, хочу'); ?>
            <a href="#request" class="btn btn-default" id="goto-create-request"><?= Yii::t('app', 'Спросить'); ?></a>
        </div>
    </div>
</div>

<!-- Create request form -->
<a name="request"></a>
<div id="form-create-request">
    <br />
    <?= $this->render('/request/form', ['title' => Yii::t('app', 'Да, я хочу задать вопрос')]); ?>
</div>

<!-- Create contact form -->
<a name="contact"></a>
<div id="form-create-contact">
    <br />
    <?= $this->render('/contact/form', ['title' => Yii::t('app', 'Да, у меня есть проверенный контакт')]); ?>
</div>