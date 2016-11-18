<?php

/**
 * @param \app\models\RequestAnswer[] $contacts
 */

use yii\helpers\Html;

?>
<?php foreach ($contacts as $answer): ?>
    <div class="contact" data-id="<?= $answer->id; ?>">
        <div class="container">
            <div class="col-sm-12">
                <h3 class="text-right"><?= Yii::t('app', 'Рекомендует: {name}', ['name' => $answer->user->name]); ?></h3>
            </div>
        </div>
        <div class="container">
            <div class="col-sm-2">
                <div><?= $answer->contact->city; ?></div>
                <div><?= $answer->contact->address; ?></div>
                <div><?= $answer->contact->name; ?></div>
                <div>
                    <?php foreach ($answer->contact->phones as $phone): ?>
                        <div><?= $phone->phone; ?></div>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($answer->contact->company)): ?>
                    <div>
                        <?php if (!empty($answer->contact->site)): ?>
                            <a href="<?= $answer->contact->site; ?>" target="_blank"><?= $answer->contact->company; ?></a>
                        <?php else: ?>
                            <?= $answer->contact->company; ?>
                        <?php endif; ?>
                    </div>
                <?php elseif (!empty($answer->contact->site)):
                    $site = $answer->contact->site;
                    if (strlen($site) > 25)
                        $site = substr ($site, 0, 25) . '...'; ?>
                    <a href="<?= $answer->contact->site; ?>" target="_blank"><?= $site; ?></a>
                <?php endif; ?>
            </div>
            <div class="col-sm-10">
                <h4><?= Yii::t('app', 'Описание'); ?></h4>
                <div class="comment">
                    <?= $answer->text; ?>
                </div>
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
                <div class="col-sm-offset-10 col-sm-2">
                    <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-warning btn-block admin-answer-delete', 'data-id' => $answer->id]); ?>
                </div>
            </div>
        <?php endif; ?>
        <div id="rating">
            <?= $this->render('/contact/rating', ['contact' => $answer->contact]); ?>
        </div>
        <div id="answers">
            <?php $pagination = \app\components\Controller::buildPagination($answer->getAnswers()->count(), \app\controllers\RequestController::ANSWER_PAGE_SIZE, 1000000000); ?>
            <?= $this->render('list-answers', [
                'answers' => $answer
                    ->getAnswers()
                    ->offset(\app\controllers\RequestController::ANSWER_PAGE_SIZE * $pagination['page'])
                    ->limit(\app\controllers\RequestController::ANSWER_PAGE_SIZE)
                    ->all(),
                'pagination' => $pagination]); ?>
        </div>
    </div>
<?php endforeach; ?>