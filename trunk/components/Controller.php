<?php

namespace app\components;

use Yii;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
        if ($this->id != 'auth')
        {
            if (
                    Yii::$app->user->isGuest || // User is guest
                    !Yii::$app->user->identity || // User is not found in DB
                    Yii::$app->user->identity->is_deleted || // User is deleted
                    (
                            Yii::$app->user->identity->is_blocked && // User is blocked
                            (
                                    empty(Yii::$app->user->identity->block_till) || // Block till is empty
                                    strtotime(Yii::$app->user->identity->block_till) >= time() // Block block till is greater than now
                            )
                    )
                )
            {
                Yii::$app->user->logout();
                $this->redirect(Url::to(['/auth/login']));
                Yii::$app->end();
            }
            if (!Yii::$app->user->isGuest)
            {
                // Set SN friends update
                $list = \app\models\UserAccountQueue::find()
                        ->where(['user_id' => Yii::$app->user->identity->_id])
                        ->andWhere(['between', 'date', date('Y-m-d H:i:s', time() + Yii::$app->params['friendUpdateIntervalShort']), date('Y-m-d H:i:s', time() + Yii::$app->params['friendUpdateInterval'])])
                        ->all();
                foreach ($list as $item)
                {
                    $item->date = date('Y-m-d H:i:s', time() + Yii::$app->params['friendUpdateIntervalShort']);
                    $item->save(false);
                }
                // Update last action time
                Yii::$app->user->identity->date_action = date('Y-m-d H:i:s');
                Yii::$app->user->identity->save(false);
            }
        }
    }
    
    public static function buildPagination($count, $size, $page)
    {
        $result = [];
        $result['pages'] = (integer)($count / $size);
        if ($count % $size > 0)
            $result['pages']++;
        $result['page'] = max(0, min($result['pages'] - 1, $page));
        return $result;
    }
}