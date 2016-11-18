<?php

/**
 * @param \app\models\Category[] $categories
 * @param mixed $pagination
 * @param boolean $isSubcategories
 */

use yii\helpers\Html;
?>
<div class="container-fluid">
    <?php if (empty($categories)): ?>
        <?= Yii::t('app', 'Ничего не найдено'); ?>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Название'); ?></th>
                    <th><?= Yii::t('app', 'Действия'); ?></th>
                    <?php if (!$isSubcategories): ?>
                        <th><?= Yii::t('app', 'Подкатегории'); ?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr<?= $category->is_deleted ? ' class="deleted"' : ''; ?> data-id="<?= $category->id; ?>">
                        <td class="title"><?= $category->title; ?></td>
                        <td>
                            <?php if ($category->is_deleted): ?>
                                <?= Html::button(Yii::t('app', 'Восстановить'), ['class' => 'btn btn-primary btn-block', 'id' => 'restore']); ?>
                            <?php else: ?>
                                <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger btn-block', 'id' => 'delete']); ?>
                            <?php endif; ?>
                        </td>
                        <?php if (!$isSubcategories): ?>
                            <td class="subcategories">
                                <?= Yii::t('app', 'Управление({active}/{count})', ['active' => $category->getActiveCategories()->count(), 'count' => $category->getCategories()->count()]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div class="container-fluid">
        <div class="col-sm-10">
            <?php if ($pagination['pages'] > 1): ?>
                <nav>
                    <ul class="pagination">
                        <li class="<?= $pagination['page'] == 0 ? 'disabled' : ''; ?>">
                            <a href="#" aria-label="First" data-page="0">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($index = 0; $index < $pagination['pages']; $index++): ?>
                            <li class="<?= $index == $pagination['page'] ? 'active' : ''; ?>">
                                <a href="#" data-page="<?= $index; ?>"><?= $index + 1; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="<?= $pagination['page'] == $pagination['pages'] - 1 ? 'disabled' : ''; ?>">
                            <a href="#" aria-label="Last" data-page="<?= $pagination['pages'] - 1; ?>">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
        <div class="col-sm-2 text-right">
            <?= Html::button(Yii::t('app', 'Создать'), ['class' => 'btn btn-default', 'id' => 'create']); ?>
        </div>
    </div>
</div>