<?php

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @param \app\models\Request[] $requests
 */
?>
<?php foreach ($requests as $request): ?>
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
            <a href="<?= Url::to(['/request/view', 'id' => $request->id]); ?>" class="comment">
                <?= $request->text; ?>
            </a>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <?= Yii::t('app', 'Рекомендаций: {count}', ['count' => $request->getContacts()->count()]); ?>
        </div>
    </div>
    <?php if (Yii::$app->user->identity->role == \app\models\User::ROLE_ADMIN): ?>
        <br />
        <div class="container">
            <div class="col-sm-offset-8 col-sm-2">
                <?= Html::button(Yii::t('app', 'Редактировать'), ['class' => 'btn btn-info btn-block admin-request-edit', 'data-id' => $request->id]); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-warning btn-block admin-request-delete', 'data-id' => $request->id]); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>