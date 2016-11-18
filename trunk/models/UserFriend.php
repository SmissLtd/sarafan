<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $user_id
 * @property string $friend_id
 * @property boolean $is_code
 * @property boolean $is_fb
 * @property boolean $is_tw
 * 
 * @property-read string $id
 * @property-read \app\models\User $user
 * @property-read \app\models\User $friend
 */
class UserFriend extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'friend_id',
            'is_code',
            'is_fb',
            'is_vk',
            'is_tw'
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
    
    public function getFriend()
    {
        return $this->hasOne(User::className(), ['_id' => 'friend_id']);
    }
}