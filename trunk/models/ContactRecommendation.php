<?php

namespace app\models;

use Yii;

/**
 * @property string $request_id
 * @property string $contact_id
 * @property string $user_id
 * @property string $comment
 * @property string $date
 * @property boolean $is_deleted
 * 
 * @property-read string $id
 * @property-read \app\models\Request $request
 * @property-read \app\models\Contact $contact
 * @property-read \app\models\User $user
 */
class ContactRecommendation extends \yii\mongodb\ActiveRecord
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->is_deleted = false;
    }
    public function attributes()
    {
        return [
            '_id',
            'request_id',
            'contact_id',
            'user_id',
            'comment',
            'date',
            'is_deleted'
        ];
    }
    
    public function rules()
    {
        return [
            // Comment
            [['comment'], 'purify'],
            [['comment'], 'required', 'message' => Yii::t('app', 'Необходимо написать комментарий')],
            [['comment'], 'string', 'max' => 64000, 'tooLong' => Yii::t('app', 'Комментарий слишком длинный. Максимум 64000 символов')]
        ];
    }
    
    public function purify($attribute, $params)
    {
        $this->$attribute = htmlspecialchars($this->$attribute, ENT_NOQUOTES, ini_get('default_charset'), false);
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
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => 'user_id']);
    }
}