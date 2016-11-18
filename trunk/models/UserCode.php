<?php

namespace app\models;

/**
 * @property string $user_id
 * @property string $code
 * @property string $created
 * @property boolean $is_used
 * @property string $date_used
 * @property string $used_user_id
 * @property boolean $is_deleted
 * 
 * @property-read string $id
 * @property-read \app\models\User $user
 * @property-read \app\models\User $usedUser
 */
class UserCode extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'code',
            'created',
            'is_used',
            'date_used',
            'used_user_id',
            'is_deleted'
        ];
    }
    
    public function getId()
    {
        return (string)$this->_id;
    }
    
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['_id' => 'user_id']);
    }
    
    public function getUsedUser()
    {
        return $this->hasOne(\app\models\User::className(), ['_id' => 'used_user_id']);
    }
}