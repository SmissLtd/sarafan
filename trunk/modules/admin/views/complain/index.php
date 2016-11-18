<?php

/**
 * @param \app\models\Complain[] $list
 * @param mixed $pagination
 */

use yii\helpers\Html;

\app\modules\admin\assets\ComplainAsset::register($this);

$this->title = Yii::t('app', 'Управление жалобами');
?>
<div class="container-fluid">
    <h1 class="text-center"><?= Yii::t('app', 'Управление жалобами'); ?></h1>
</div>
<div id="complains">
    <?= $this->render('list', ['list' => $list, 'pagination' => $pagination]); ?>
</div>