<?php

/**
 * @param \app\models\User[] $users
 * @param mixed $pagination
 * @param mixed $filter
 */

use yii\helpers\Html;

\app\modules\admin\assets\UserAsset::register($this);

$this->title = Yii::t('app', 'Управление пользователями');
?>

<div class="container-fluid">
    <h1 class="text-center"><?= Yii::t('app', 'Управление пользователями'); ?></h1>
</div>


<?= Html::beginForm('', 'post', ['class' => 'form-horizontal', 'id' => 'form-filter']); ?>
<div class="container-fluid">
    <div class="col-sm-6">
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Удаленные') . ':', 'inputShowDeleted', ['class' => 'control-label col-sm-6']); ?>
            <div class="col-sm-6">
                <?= Html::dropDownList('show-deleted', $filter['show-deleted'], ['all' => Yii::t('app', 'Показывать всё'), 'only' => Yii::t('app', 'Только удаленные'), 'exclude' => Yii::t('app', 'Скрыть удаленных')], ['class' => 'form-control', 'id' => 'inputShowDeleted']); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'ФИО') . ':', 'inputFilterName', ['class' => 'control-label col-sm-6']); ?>
            <div class="col-sm-6">
                <?= Html::input('text', 'filter[name]', empty($filter['fields']['name']) ? '' : $filter['fields']['name'], ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'inputFilterName']); ?>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="col-sm-6">
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Замороженные') . ':', 'inputShowBlocked', ['class' => 'control-label col-sm-6']); ?>
            <div class="col-sm-6">
                <?= Html::dropDownList('show-blocked', $filter['show-blocked'], ['all' => Yii::t('app', 'Показывать всё'), 'only' => Yii::t('app', 'Только замороженные'), 'exclude' => Yii::t('app', 'Скрыть замороженных')], ['class' => 'form-control', 'id' => 'inputShowBlocked']); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <?= Html::label(Yii::t('app', 'Код') . ':', 'inputFilterCode', ['class' => 'control-label col-sm-6']); ?>
            <div class="col-sm-6">
                <?= Html::input('text', 'filter[code]', empty($filter['fields']['code']) ? '' : $filter['fields']['code'], ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'inputFilterCode']); ?>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="col-sm-offset-6 col-sm-6">
        <div class="form-group">
            <div class="col-sm-6">
                <?= Html::button(Yii::t('app', 'Сбросить фильтр'), ['class' => 'btn btn-default btn-block', 'id' => 'reset']); ?>
            </div>
            <div class="col-sm-6">
                <?= Html::submitButton(Yii::t('app', 'Применить фильтр'), ['class' => 'btn btn-default btn-block']); ?>
            </div>
        </div>
    </div>
</div>
<?= Html::endForm(); ?>


<div id="users">
    <?= $this->render('list', ['users' => $users, 'pagination' => $pagination, 'filter' => $filter]); ?>
</div>

<div id="confirm-delete-user">
    <?= Html::hiddenInput('delete-id', ''); ?>
    <div>
        <h1><?= Yii::t('app', 'Удаление пользователя'); ?><span class="close">&times;</span></h1>
        <div>
            <div class="container-fluid">
                <div class="col-sm-12">
                    <?= Yii::t('app', 'Вы уверены, что хотите удалить пользователя <span id="title"></span>?'); ?>
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