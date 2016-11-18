<?php

namespace app\modules\admin\components;

use Yii;

class Controller extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role != \app\models\User::ROLE_ADMIN)
        {
            $this->redirect(Yii::$app->homeUrl);
            Yii::$app->end();
        }
    }
}