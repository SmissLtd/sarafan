<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $request_id
 * @property string $contact_id
 * 
 * @property-read string $id
 * @property-read \app\models\Request $request
 * @property-read \app\models\Contact $contact
 */
class RequestContact extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'request_id',
            'contact_id'
        ];
    }
    
    public function getId()
    {
        return (string)$this->_id;
    }
    
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['_id' => 'request_id']);
    }
    
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['_id' => 'contact_id']);
    }
}