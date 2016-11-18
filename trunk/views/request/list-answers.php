<?php

/**
 * @param \app\models\RequestAnswer[] $answers
 * @param mixed $pagination
 */

use yii\helpers\Html;
?>
<?php foreach ($answers as $answer): ?>
    <div class="container">
        <div class="col-sm-offset-3 col-sm-9">
            <h3><?= Yii::t('app', 'Коментарий: {name}', ['name' => $answer->user->name]); ?></h3>
            <div><?= $answer->text; ?></div>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <div class="text-right">
                <a href="#" id="complain" data-answer="<?= $answer->id; ?>"><?= Yii::t('app', 'Пожаловаться'); ?></a> |
                <a href="#" id="create-contact" data-request="<?= (string)$answer->request_id; ?>"><?= Yii::t('app', 'Рекомендовать еще'); ?></a> |
                <a href="#" id="reply" data-answer=""><?= Yii::t('app', 'Ответить'); ?></a> |
                <a href="#" id="reply" data-answer="<?= $answer->id; ?>"><?= Yii::t('app', 'Цитировать'); ?></a>
            </div>
        </div>
    </div>
    <?php if (Yii::$app->user->identity->role == \app\models\User::ROLE_ADMIN): ?>
        <br />
        <div class="container">
            <div class="col-sm-offset-8 col-sm-2">
                <?= Html::button(Yii::t('app', 'Редактировать'), ['class' => 'btn btn-info btn-block admin-answer-edit', 'data-id' => $answer->id]); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-warning btn-block admin-answer-delete', 'data-id' => $answer->id]); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
<?php if ($pagination['pages'] > 1): ?>
    <div class="container">
        <div class="col-sm-offset-3 col-sm-9">
            <nav>
                <ul class="pagination">
                    <li class="<?= $pagination['page'] == 0 ? 'disabled' : ''; ?>">
                        <a href="#" aria-label="Previous" data-page="<?= $pagination['page'] - 1; ?>">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($index = 0; $index < $pagination['pages']; $index++): ?>
                        <li class="<?= $index == $pagination['page'] ? 'active' : ''; ?>">
                            <a href="#" data-page="<?= $index; ?>"><?= $index + 1; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="<?= $pagination['page'] == $pagination['pages'] - 1 ? 'disabled' : ''; ?>">
                        <a href="#" aria-label="Next" data-page="<?= $pagination['page'] + 1; ?>">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<?php endif; ?>