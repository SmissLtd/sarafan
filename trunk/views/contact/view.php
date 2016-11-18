<?php

/**
 * @param \app\models\Contact $contact
 */

use yii\helpers\Url;
use yii\helpers\Html;

\app\assets\ContactAsset::register($this);
$this->title = Yii::t('app', 'Контакт "{name}"', ['name' => $contact->name]);
?>
<div class="container">
    <div class="col-sm-12">
        <h1><?= $contact->category->parent->title . ' / ' . $contact->category->title; ?></h1>
    </div>
</div>

<div class="container">
    <div class="col-sm-12">
        <a href="<?= Url::to(['/category/contacts', 'id' => $contact->category->id]); ?>"><?= Yii::t('app', 'Контакты'); ?></a>
        |
        <a href="<?= Url::to(['/category/requests', 'id' => $contact->category->id]); ?>"><?= Yii::t('app', 'Вопросы'); ?></a>
    </div>
</div>

<div id="list-contacts">
    <div class="container" id="rating">
        <?= $this->render('rating', ['contact' => $contact]); ?>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <h3 class="text-right"><?= Yii::t('app', 'Рекомендует: {name}', ['name' => $contact->getRecommendations()->one()->user->name]); ?></h3>
        </div>
    </div>
    <?php $first = true; $counter = 1; $count = $contact->getRecommendations()->count(); ?>
    <?php foreach ($contact->recommendations as $recommendation): ?>
        <div class="container">
            <div class="col-sm-2">
                <?php if ($first): ?>
                    <div><?= $contact->city; ?></div>
                    <div><?= $contact->address; ?></div>
                    <div><?= $contact->name; ?></div>
                    <div>
                        <?php foreach ($contact->phones as $phone): ?>
                            <div><?= $phone->phone; ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($contact->company)): ?>
                        <div>
                            <?php if (!empty($contact->site)): ?>
                                <a href="<?= $contact->site; ?>" target="_blank"><?= $contact->company; ?></a>
                            <?php else: ?>
                                <?= $contact->company; ?>
                            <?php endif; ?>
                        </div>
                    <?php elseif (!empty($contact->site)): ?>
                        <a href="<?= $contact->site; ?>" target="_blank"><?= Yii::t('app', 'Сайт'); ?></a>
                    <?php endif; ?>
                    <?php $first = false; ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-10">
                <h4><?= Yii::t('app', 'Коментарий {counter} из {count}', ['counter' => $counter, 'count' => $count]); ?></h4>
                <div class="comment">
                    <?= $recommendation->comment; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-sm-offset-2 col-sm-10">
                <?php if (!empty($recommendation->request_id)): ?>
                    <div>
                        <a href="<?= Url::to(['/request/view', 'id' => (string)$recommendation->request_id]); ?>">
                            <?= Yii::t('app', 'Перейти к вопросу &gt;&gt;'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (Yii::$app->user->identity->role == \app\models\User::ROLE_ADMIN): ?>
            <br />
            <div class="container">
                <div class="col-sm-offset-8 col-sm-2">
                    <?= Html::button(Yii::t('app', 'Редактировать'), ['class' => 'btn btn-info btn-block admin-recommendation-edit', 'data-id' => $recommendation->id]); ?>
                </div>
                <div class="col-sm-2">
                    <?php if ($count > 1): ?>
                        <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-warning btn-block admin-recommendation-delete', 'data-id' => $recommendation->id]); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $counter++; ?>
    <?php endforeach; ?>
</div>
<script>
    currentCategory = "<?= $contact->category->id; ?>";
    currentCategoryTitle = "<?= $contact->category->title; ?>";
</script>