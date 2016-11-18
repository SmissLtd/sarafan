<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 */
class Block extends \yii\base\Model
{
    public $till;
    public $reason;

    public function rules()
    {
        return [
            [['reason'], 'trim'],
            // Till
            [['till'], 'date', 'format' => 'm/d/Y', 'message' => Yii::t('app', 'Неверный формат даты')],
            // Reason
            [['reason'], 'required', 'message' => Yii::t('app', 'Причина бана обязательна')],
        ];
    }
}