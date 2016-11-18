<?php

/**
 * @param \app\models\Request $request
 */

use yii\helpers\Html;
use yii\helpers\Url;

\app\assets\RequestAsset::register($this);
$this->title = Yii::t('app', 'Вопрос');
?>
<div class="container">
    <div class="col-sm-12">
        <h1><?= $request->category->parent->title . ' / ' . $request->category->title; ?></h1>
    </div>
</div>

<div class="container">
    <div class="col-sm-12">
        <a href="<?= Url::to(['/category/contacts', 'id' => $request->category->id]); ?>"><?= Yii::t('app', 'Контакты'); ?></a>
        |
        <a href="<?= Url::to(['/category/requests', 'id' => $request->category->id]); ?>"><?= Yii::t('app', 'Вопросы'); ?></a>
    </div>
</div>

<div id="list-requests">
    <div class="container h3">
        <div class="col-sm-4">
            <?= Yii::$app->formatter->asDatetime($request->date, 'medium'); ?>
        </div>
        <div class="col-sm-8">
            <div class="text-right">
                <?= Yii::t('app', 'Спрашивает: {name}', ['name' => $request->user->name]); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <div><?= $request->address; ?></div>
            <div class="comment"><?= $request->text; ?></div>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-offset-4 col-sm-4">
            <br />
            <?= Html::button(Yii::t('app', 'Рекомендовать'), ['class' => 'btn btn-default btn-block', 'id' => 'create-contact', 'data-request' => $request->id, 'data-category' => $request->category->id]); ?>
        </div>
    </div>
</div>

<div id="contacts">
    <?= $this->render('list-contacts', ['contacts' => $request->getAnswers()->limit(\app\controllers\RequestController::CONTACT_PAGE_SIZE)->all()]); ?>
</div>
<?php if ($request->getAnswers()->count() > \app\controllers\RequestController::CONTACT_PAGE_SIZE): ?>
    <br />
    <a name="more-contacts"></a>
    <div class="container">
        <div class="col-sm-offset-4 col-sm-4">
            <?= Html::button(Yii::t('app', 'Еще'), ['class' => 'btn btn-default btn-block', 'id' => 'more-contacts', 'data-id' => $request->id, 'data-page' => 1]); ?>
        </div>
    </div>
<?php endif; ?>
<script>
    currentCategory = "<?= $request->category->id; ?>";
    currentCategoryTitle = "<?= $request->category->title; ?>";
</script>