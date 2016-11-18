<?php

namespace app\models;

use Yii;

/**
 * @property boolean $is_deleted
 * @property string $category_id
 * @property string $user_id
 * @property string $city
 * @property string $country
 * @property string $text
 * @property string $date
 * @property integer $rating
 * 
 * @property-read string $id
 * @property-read \app\models\Category $category
 * @property-read \app\models\User $user
 * @property-read \app\models\RequestContact[] $contacts List of relations to contacts which was added in this request
 * @property-read \app\models\RequestAnswer[] $answers
 * 
 * @property-read string $address City, Country
 */
class Request extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'is_deleted',
            'category_id',
            'user_id',
            'city',
            'country',
            'text',
            'date',
            'rating'
        ];
    }
    
    public function rules()
    {
        return [
            [['city', 'text'], 'purify'],
            // Is deleted
            [['is_deleted'], 'boolean', 'skipOnEmpty' => false, 'message' => Yii::t('app', 'Признак удаленности должен быть булевым значением')],
            // Category ID
            [['category_id'], 'required', 'message' => Yii::t('app', 'Поле Выберите категорию и подкатегорию* обязательно для заполнения')],
            [['category_id'], 'exist', 'targetClass' => Category::className(), 'targetAttribute' => '_id', 'filter' => ['and', ['is_deleted' => false], ['parent_id' => ['$nin' => [null]]]], 'message' => Yii::t('app', 'Категория не найдена')],
            // City
            [['city'], 'required', 'message' => Yii::t('app', 'Город обязателен')],
            [['city'], 'string', 'max' => 32, 'tooLong' => Yii::t('app', 'Название города слишком длинное. Максимум 32 символа')],
            // Country
            [['country'], 'required', 'message' => Yii::t('app', 'Страна обязательна')],
            [['country'], 'string', 'max' => 32, 'tooLong' => Yii::t('app', 'Название страны слишком длинное. Максимум 32 символа')],
            // Text
            [['text'], 'required', 'message' => Yii::t('app', 'Необходимо написать вопрос')],
            [['text'], 'string', 'max' => 64000, 'tooLong' => Yii::t('app', 'Вопрос слишком длинный. Максимум 64000 символов')],
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
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['_id' => 'category_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => 'user_id']);
    }
    
    public function getContacts()
    {
        return $this->hasMany(RequestContact::className(), ['request_id' => '_id']);
    }
    
    public function getAnswers()
    {
        return $this
                ->hasMany(RequestAnswer::className(), ['request_id' => '_id'])
                ->where(['answer_id' => NULL, 'is_deleted' => false])
                ->orderBy('date DESC');
    }
    
    public function getAddress()
    {
        $parts = [];
        if (!empty($this->city))
            $parts[] = $this->city;
        if (!empty($this->country))
            $parts[] = $this->country;
        return implode(', ', $parts);
    }
}