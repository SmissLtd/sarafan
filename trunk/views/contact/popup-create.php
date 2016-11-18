<?php
/**
 * @param \app\models\Request $request
 * @param \app\models\Contact $contact
 */
?>
<div id="popup-create-contact">
    <?= $this->render('form', ['title' => Yii::t('app', 'Рекомендовать контакт'), 'request' => $request, 'contact' => $contact]); ?>
</div>