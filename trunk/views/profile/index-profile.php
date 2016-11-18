<?php
use yii\helpers\Url;

\app\assets\ProfileAsset::register($this);
$this->title = Yii::t('app', 'Мой профиль');
?>
<div class="btn-group tabs">
    <a href="<?= Url::to(['/profile/index']); ?>" class="btn btn-success"><?= Yii::t('app', 'Профиль'); ?></a>
    <a href="<?= Url::to(['/profile/codes']); ?>" class="btn btn-default"><?= Yii::t('app', 'Коды'); ?></a>
</div>
<?= $this->render('profile'); ?>