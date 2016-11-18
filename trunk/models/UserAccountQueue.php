<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $user_id
 * @property string $source
 * @property string $date
 * 
 * @property-read string $id
 * @property-read \app\models\User $user
 */
class UserAccountQueue extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'source',
            'date'
        ];
    }
    
    public function getId()
    {
        return (string)$this->_id;
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => 'user_id']);
    }
}