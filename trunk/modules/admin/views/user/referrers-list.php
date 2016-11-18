<?php

/**
 * @param \app\models\UserCode[] $codes
 * @param mixed $pagination
 */

use yii\helpers\Html;
?>
<div class="container-fluid">
    <?php if (empty($codes)): ?>
        <?= Yii::t('app', 'Ничего не найдено'); ?>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Код'); ?></th>
                    <th><?= Yii::t('app', 'Создан'); ?></th>
                    <th><?= Yii::t('app', 'Дата регистрации'); ?></th>
                    <th><?= Yii::t('app', 'Пользователь'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $code): ?>
                    <tr>
                        <td><?= $code->code; ?></td>
                        <td><?= $code->created; ?></td>
                        <td><?= $code->date_used; ?></td>
                        <td><?= $code->usedUser->name; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div class="container-fluid">
        <div class="col-sm-12">
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
    </div>
</div>