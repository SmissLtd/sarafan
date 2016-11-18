<?php
/**
 * @param \app\models\Contact $contact
 */

use yii\helpers\Html;

?>
<div id="popup-confirm-contact-edit">
    <div>
        <h1><?= Yii::t('app', 'Предупреждение'); ?><span class="close">&times;</span></h1>
        <p>
            <?= Yii::t('app', 'Контакт c таким номером телефона уже существует в системе.'); ?>
            <br />
            <?= Yii::t('app', 'Хотите использовать существующие контактные данные?'); ?>
        </p>
        <div>
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
            <?php elseif (!empty($contact->site)): ?>
                <a href="<?= $contact->site; ?>" target="_blank"><?= Yii::t('app', 'Сайт'); ?></a>
            <?php endif; ?>
        </div>
        <div class="text-right">
            <?= Html::button(Yii::t('app', 'Да'), ['class' => 'btn btn-success']); ?>
            <?= Html::button(Yii::t('app', 'Нет'), ['class' => 'btn btn-danger']); ?>
        </div>
    </div>
</div>