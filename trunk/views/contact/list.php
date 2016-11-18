<?php

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @param \app\models\Contact[] $contacts
 */
?>
<?php foreach ($contacts as $contact): ?>
    <div class="container">
        <div class="col-sm-12">
            <h3 class="text-right"><?= Yii::t('app', 'Рекомендует: {name}', ['name' => $contact->getRecommendations()->one()->user->name]); ?></h3>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-2">
            <div><?= $contact->city; ?></div>
            <div><?= $contact->address; ?></div>
            <div><?= $contact->name; ?></div>
            <div>
                <?php foreach ($contact->phones as $phone): ?>
                    <div><?= $phone->phone; ?></div>
                <?php endforeach; ?>
            </div>
            <?php if (!empty($contact->company)): ?>
                <div>
                    <?php if (!empty($contact->site)): ?>
                        <a href="<?= $contact->site; ?>" target="_blank"><?= $contact->company; ?></a>
                    <?php else: ?>
                        <?= $contact->company; ?>
                    <?php endif; ?>
                </div>
            <?php elseif (!empty($contact->site)):
                $site = $contact->site;
                if (strlen($site) > 25)
                    $site = substr ($site, 0, 25) . '...'; ?>
                <a href="<?= $contact->site; ?>" target="_blank"><?= $site; ?></a>
            <?php endif; ?>
        </div>
        <div class="col-sm-10">
            <h4>
                <a href="<?= Url::to(['/contact/view', 'id' => $contact->id]); ?>">
                    <?= Yii::t('app', 'Коментарий 1 из {count}', ['count' => $contact->getRecommendations()->count()]); ?>
                </a>
            </h4>
            <a href="<?= Url::to(['/contact/view', 'id' => $contact->id]); ?>" class="comment">
                <?= $contact->getRecommendations()->orderBy('date DESC')->one()->comment; ?>
            </a>
        </div>
    </div>
    <?php if (Yii::$app->user->identity->role == \app\models\User::ROLE_ADMIN): ?>
        <br />
        <div class="container">
            <div class="col-sm-offset-8 col-sm-2">
                <?= Html::button(Yii::t('app', 'Редактировать'), ['class' => 'btn btn-info btn-block admin-contact-edit', 'data-id' => $contact->id]); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-warning btn-block admin-contact-delete', 'data-id' => $contact->id]); ?>
            </div>
        </div>
    <?php endif; ?>
    <div id="rating">
        <?= $this->render('rating', ['contact' => $contact]); ?>
    </div>
<?php endforeach; ?>