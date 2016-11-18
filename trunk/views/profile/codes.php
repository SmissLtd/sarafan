<?php
/**
 * @param \app\models\UserCode[] $codes
 * @param mixed $pagination
 * @param integer $pageSize
 */
use yii\helpers\Html;
?>
<div id="codes">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th><?= Yii::t('app', 'Код'); ?></th>
                <th><?= Yii::t('app', 'Создан'); ?></th>
                <th><?= Yii::t('app', 'Пользователь'); ?></th>
                <th><?= Yii::t('app', 'Дата регистрации'); ?></th>
                <th><?= Yii::t('app', 'Удалить код'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $index = $pagination['page'] * $pageSize + 1; ?>
            <?php foreach ($codes as $code): ?>
                <tr>
                    <td><?= $index++; ?></td>
                    <td><?= $code->code; ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($code->created); ?></td>
                    <td>
                        <?php if ($code->is_used): ?>
                            <?= $code->usedUser->name; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($code->is_used): ?>
                            <?= Yii::$app->formatter->asDate($code->date_used); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-default delete-code', 'data-id' => $code->id]); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($codes)): ?>
                <tr>
                    <td colspan="6" align="center"><i><?= Yii::t('app', 'Нет кодов'); ?></i></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
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
            <?= Html::button(Yii::t('app', 'Создать код'), ['class' => 'btn btn-default', 'id' => 'create-code']); ?>
        </div>
    </div>
</div>