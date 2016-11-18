<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $answer_id
 * @property string $user_id
 * @property string $date
 * @property string $text
 * 
 * @property-read string $id
 * @property-read \app\models\RequestAnswer $answer
 * @property-read \app\models\User $user
 */
class Complain extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'answer_id',
            'user_id',
            'date',
            'text'
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
    
    public function getAnswer()
    {
        return $this->hasOne(RequestAnswer::className(), ['_id' => 'answer_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => 'user_id']);
    }
}