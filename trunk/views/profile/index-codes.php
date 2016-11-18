<?php
/**
 * @param \app\models\UserCode[] $codes
 * @param mixed $pagination
 * @param integer $pageSize
 */
use yii\helpers\Url;

\app\assets\ProfileAsset::register($this);
$this->title = Yii::t('app', 'Мой коды');
?>
<div class="btn-group tabs">
    <a href="<?= Url::to(['/profile/index']); ?>" class="btn btn-default"><?= Yii::t('app', 'Профиль'); ?></a>
    <a href="<?= Url::to(['/profile/codes']); ?>" class="btn btn-success"><?= Yii::t('app', 'Коды'); ?></a>
</div>
<?= $this->render('codes', ['codes' => $codes, 'pagination' => $pagination, 'pageSize' => $pageSize]); ?>