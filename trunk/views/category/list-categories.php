<?php

/**
 * @param \app\models\Category[] $categories
 * @param string $filter
 * @param mixed $pagination
 * @param string $title
 * @param integer $columns
 */

use yii\helpers\Html;

$perColumn = (integer)(count($categories) / $columns);
if ($perColumn == 0)
    $perColumn = 1;
$maxLen = 18;
?>
<div class="container">
    <div class="col-sm-4">
        <b><?= $title; ?></b>
    </div>
    <div class="col-sm-4 text-right">
        <?= Html::input('text', 'filter', $filter, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'Искать')]); ?>
    </div>
    <div class="col-sm-2 text-right">
        <?= Html::button(Yii::t('app', 'Искать'), ['class' => 'btn btn-default btn-block', 'id'=> 'filter-apply']); ?>
    </div>
    <div class="col-sm-2 text-right">
        <?= Html::button(Yii::t('app', 'Очистить'), ['class' => 'btn btn-default btn-block', 'id' => 'filter-clear']); ?>
    </div>
</div>
<br />
<div class="container">
    <div class="col-sm-12">
        <?php if (empty($categories)): ?>
            <?= Yii::t('app', 'Извините, по вашему запросу ничего не найдено'); ?>
        <?php else: ?>
            <div class="container-list">
                <?= Html::hiddenInput('parent_id', $categories[0]->parent_id); ?>
                <div class="prev<?= $pagination['page'] == 0 ? ' disabled' : ''; ?>" data-page="<?= $pagination['page'] - 1; ?>">
                    <span>&Lt;</span>
                </div>
                <div class="list container">
                    <?php for ($column = 0; $column < $columns; $column++): ?>
                        <div class="col-lg-<?= 12 / $columns; ?>">
                            <?php for ($index = $column * $perColumn; $index < ($column + 1) * $perColumn; $index++): ?>
                                <?php if (isset($categories[$index])): ?>
                                    <div data-id="<?= $categories[$index]->id; ?>"<?= mb_strlen($categories[$index]->title, 'utf-8') > $maxLen ? (' title="' . $categories[$index]->title . '"') : ''; ?>>
                                        <span></span>
                                        <?= mb_strlen($categories[$index]->title, 'utf-8') > $maxLen ? (mb_substr($categories[$index]->title, 0, $maxLen, 'utf-8') . '...') : $categories[$index]->title; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="next<?= $pagination['page'] == $pagination['pages'] - 1 ? ' disabled' : ''; ?>" data-page="<?= $pagination['page'] + 1; ?>">
                    <span>&Gt;</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<br />