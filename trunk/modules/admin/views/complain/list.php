<?php

/**
 * @param \app\models\Complain[] $list
 * @param mixed $pagination
 */

use yii\helpers\Html;
?>
<div class="container-fluid">
    <?php if (empty($list)): ?>
        <?= Yii::t('app', 'Ничего не найдено'); ?>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Дата'); ?></th>
                    <th><?= Yii::t('app', 'Пользователь'); ?></th>
                    <th><?= Yii::t('app', 'Жалоба'); ?></th>
                    <th><?= Yii::t('app', 'На сообщение...'); ?></th>
                    <th><?= Yii::t('app', '...которое отправил'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $item): ?>
                    <tr data-id="<?= $item->id; ?>">
                        <td><?= $item->date; ?></td>
                        <td class="user" data-id="<?= $item->user->id; ?>"><?= $item->user->name; ?></td>
                        <td><?= $item->text; ?></td>
                        <td><?= $item->answer->text; ?></td>
                        <td class="user" data-id="<?= $item->answer->user->id; ?>"><?= $item->answer->user->name; ?></td>
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