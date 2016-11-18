<?php

/**
 * @param \app\models\User[] $users
 * @param mixed $pagination
 * @param mixed $filter
 */

use yii\helpers\Html;
?>
<div class="container-fluid">
    <?php if (empty($users)): ?>
        <?= Yii::t('app', 'Ничего не найдено'); ?>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="sortable <?= $filter['orderBy'] == 'date_register' ? ($filter['order'] == 'DESC' ? 'desc' : 'asc') : ''; ?>" data-field="date_register">
                        <?= Yii::t('app', 'Регистрация'); ?><span></span>
                    </th>
                    <th class="sortable <?= $filter['orderBy'] == 'name' ? ($filter['order'] == 'DESC' ? 'desc' : 'asc') : ''; ?>" data-field="name">
                        <?= Yii::t('app', 'ФИО'); ?><span></span>
                    </th>
                    <th><?= Yii::t('app', 'Код'); ?></th>
                    <th><?= Yii::t('app', 'Пригласил'); ?></th>
                    <th class="sortable <?= $filter['orderBy'] == 'date_action' ? ($filter['order'] == 'DESC' ? 'desc' : 'asc') : ''; ?>" data-field="date_action">
                        <?= Yii::t('app', 'Активность'); ?><span></span>
                    </th>
                    <th class="sortable <?= $filter['orderBy'] == 'complain_count' ? ($filter['order'] == 'DESC' ? 'desc' : 'asc') : ''; ?>" data-field="complain_count">
                        <?= Yii::t('app', 'Жалобы'); ?><span></span>
                    </th>
                    <th class="sortable <?= $filter['orderBy'] == 'is_blocked' ? ($filter['order'] == 'DESC' ? 'desc' : 'asc') : ''; ?>" data-field="is_blocked">
                        <?= Yii::t('app', 'Статус'); ?><span></span>
                    </th>
                    <th><?= Yii::t('app', 'Роль'); ?></th>
                    <th><?= Yii::t('app', 'Рефералы'); ?></th>
                    <th><?= Yii::t('app', 'Удаление'); ?></th>
                    <th><?= Yii::t('app', 'Заморозка'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr<?= $user->is_deleted ? ' class="deleted"' : ''; ?> data-id="<?= $user->id; ?>">
                        <td class="edit"><?= $user->date_register; ?></td>
                        <td class="edit name"><?= $user->nameOrLogin; ?></td>
                        <td class="edit"><?= $user->code; ?></td>
                        <td class="edit"><?= $user->referrer ? $user->referrer->name : ''; ?></td>
                        <td class="edit"><?= $user->date_action; ?></td>
                        <td class="edit"><?= $user->complain_count; ?></td>
                        <td class="edit"><?= $user->is_blocked ? Yii::t('app', 'Заморожен') : ''; ?></td>
                        <td class="edit"><?= \app\models\User::translate($user->role); ?></td>
                        <td class="referrers" title="<?= Yii::t('app', 'Использовано кодов / Всего сгенерировано кодов(удаленные не в счет)'); ?>"><?= $user->getUsedCodes()->count() . ' / ' . $user->getCodes()->count(); ?></td>
                        <td>
                            <?php if ($user->id != Yii::$app->user->identity->id): ?>
                                <?php if ($user->is_deleted): ?>
                                    <?= Html::button(Yii::t('app', 'Восстановить'), ['class' => 'btn btn-primary btn-block', 'id' => 'restore']); ?>
                                <?php else: ?>
                                    <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger btn-block', 'id' => 'delete']); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user->id != Yii::$app->user->identity->id): ?>
                                <?php if ($user->is_blocked): ?>
                                    <?= Html::button(Yii::t('app', 'Разморозить'), ['class' => 'btn btn-primary btn-block', 'id' => 'unblock']); ?>
                                <?php else: ?>
                                    <?= Html::button(Yii::t('app', 'Заморозить'), ['class' => 'btn btn-danger btn-block', 'id' => 'block']); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
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