<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $contact_id
 * @property string $user_id
 * @property string $date
 * @property integer $value 1|0|-1
 * 
 * @property-read string $id
 * @property-read \app\models\Contact $contact
 * @property-read \app\models\User $user
 */
class ContactRating extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'contact_id',
            'user_id',
            'date',
            'value'
        ];
    }
    
    public function getId()
    {
        return (string)$this->_id;
    }
    
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['_id' => 'contact_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => 'user_id']);
    }
}