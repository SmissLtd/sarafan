<?php

/**
 * @param \app\models\User $user
 * @param \app\models\UserCode[] $codes
 * @param mixed $pagination
 */

use yii\helpers\Html;

\app\modules\admin\assets\UserAsset::register($this);

$this->title = Yii::t('app', 'Рефералы');
?>
<?= Html::hiddenInput('referrers-owner-id', $user->id); ?>
<div class="container-fluid">
    <h1 class="text-center"><?= Yii::t('app', 'Рефералы пользователя "{name}"', ['name' => $user->name]); ?></h1>
</div>

<div id="referrers">
    <?= $this->render('referrers-list', ['codes' => $codes, 'pagination' => $pagination]); ?>
</div>