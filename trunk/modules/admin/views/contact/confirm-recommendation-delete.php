<?php

/**
 * @param \app\models\ContactRecommendation $recommendation
 */

use yii\helpers\Html;
?>
<div id="confirm-delete-recommendation">
    <?= Html::beginForm('', 'post'); ?>
    <?= Html::hiddenInput('id', $recommendation->id); ?>
    <h1><?= Yii::t('app', 'Удаление рекомендации'); ?><span class="close">&times;</span></h1>
    <div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <?= Yii::t('app', 'Вы уверены, что хотите удалить рекомендацию?'); ?>
            </div>
        </div>
        <div class="container-fluid form-group text-right">
            <div class="col-sm-12">
                <br />
                <?= Html::submitButton(Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger']); ?>
                <?= Html::button(Yii::t('app', 'Отмена'), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>