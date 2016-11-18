<?php

/**
 * @param \app\models\Contact $contact
 */

use yii\helpers\Html;
?>
<div class="container">
    <div class="col-sm-2" id="vote">
        <?php $ratingsCount = $contact->getPositiveRatings()->count(); ?>
        <?= Html::button('+1(' . $ratingsCount . '):', ['class' => 'btn btn-success btn-block', 'data-id' => $contact->id, 'data-value' => 1]); ?>
    </div>
    <div class="col-sm-10" id="users">
        <?php 
            $ratings = $contact->getPositiveRatings()->limit(app\controllers\ContactController::RATING_LIMIT)->all();
            if (!empty($ratings))
            {
                $users = array();
                foreach ($ratings as $rating)
                    $users[] = $rating->user->name;
                if ($ratingsCount > app\controllers\ContactController::RATING_LIMIT)
                    $users[] = '...';
                echo '<span id="all" data-id="' . $contact->id . '" data-value="1">' . implode(', ', $users) . '</span>';
            }
        ?>
    </div>
</div>
<div class="container">
    <div class="col-sm-2" id="vote">
        <?php $ratingsCount = $contact->getNeutralRatings()->count(); ?>
        <?= Html::button('&plusmn;0(' . $ratingsCount . '):', ['class' => 'btn btn-default btn-block', 'data-id' => $contact->id, 'data-value' => 0]); ?>
    </div>
    <div class="col-sm-10" id="users">
        <?php
            $ratings = $contact->getNeutralRatings()->limit(app\controllers\ContactController::RATING_LIMIT)->all();
            if (!empty($ratings))
            {
                $users = array();
                foreach ($ratings as $rating)
                    $users[] = $rating->user->name;
                if ($ratingsCount > app\controllers\ContactController::RATING_LIMIT)
                    $users[] = '...';
                echo '<span id="all" data-id="' . $contact->id . '" data-value="0">' . implode(', ', $users) . '</span>';
            }
        ?>
    </div>
</div>
<div class="container">
    <div class="col-sm-2" id="vote">
        <?php $ratingsCount = $contact->getNegativeRatings()->count(); ?>
        <?= Html::button('-1(' . $ratingsCount . '):', ['class' => 'btn btn-danger btn-block', 'data-id' => $contact->id, 'data-value' => -1]); ?>
    </div>
    <div class="col-sm-10" id="users">
        <?php
            $ratings = $contact->getNegativeRatings()->limit(app\controllers\ContactController::RATING_LIMIT)->all();
            if (!empty($ratings))
            {
                $users = array();
                foreach ($ratings as $rating)
                    $users[] = $rating->user->name;
                if ($ratingsCount > app\controllers\ContactController::RATING_LIMIT)
                    $users[] = '...';
                echo '<span id="all" data-id="' . $contact->id . '" data-value="-1">' . implode(', ', $users) . '</span>';
            }
        ?>
    </div>
</div>