<?php

/**
 * @param \app\models\ContactRating[] $ratings
 */
?>
<div id="ratings">
    <div>
        <h1><?= Yii::t('app', 'Проголосовавшие'); ?><span class="close">&times;</span></h1>
        <?php if (empty($ratings)): ?>
            <?= Yii::t('app', 'Еще никто не голосовал'); ?>
        <?php else: ?>
            <div class="row">
                <?php foreach ($ratings as $rating): ?>
                    <div>
                        <?= $rating->user->name; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>