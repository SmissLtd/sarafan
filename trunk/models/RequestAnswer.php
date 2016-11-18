<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $request_id
 * @property string $contact_id
 * @property string $user_id
 * @property string $answer_id If empty - the model is contact, if not empty - the model is answer on contact
 * @property string $date
 * @property string $text
 * @property boolean $is_deleted
 * 
 * @property-read string $id
 * @property-read \app\models\Request $request
 * @property-read \app\models\Contact $contact
 * @property-read \app\models\User $user
 * @property-read \app\models\RequestAnswer $answer Answer to which this answer belongs
 * @property-read \app\models\RequestAnswer[] $answers Answers on this answer
 * @property-read \app\models\Complain[] $complains
 */
class RequestAnswer extends \yii\mongodb\ActiveRecord
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
            'answer_id',
            'date',
            'text',
            'is_deleted'
        ];
    }
    
    public function rules()
    {
        return [
            // Text
            [['text'], 'trim'],
            [['text'], 'purify'],
            [['text'], 'required', 'message' => Yii::t('app', 'Нужно написать сообщение')],
            [['text'], 'string', 'max' => 64000, 'tooLong' => Yii::t('app', 'Слишком длинное сообщение. Максимум 64000 символов')]
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
    
    public function getAnswer()
    {
        return $this->hasOne(RequestAnswer::className(), ['_id' => 'answer_id']);
    }
    
    public function getAnswers()
    {
        return $this
                ->hasMany(RequestAnswer::className(), ['answer_id' => '_id'])
                ->where(['is_deleted' => false]);
    }
    
    public function getComplains()
    {
        return $this->hasMany(Complain::className(), ['answer_id' => '_id']);
    }
}