<?php

namespace app\models;

use Yii;

/**
 * @property boolean $is_deleted
 * @property string $category_id
 * @property string $city
 * @property string $country
 * @property string $name
 * @property string $company
 * @property string $site
 * @property string $address
 * @property string $date
 * 
 * @property-read string $id
 * @property-read \app\models\Category $category
 * @property-read \app\models\ContactRecommendation[] $recommendations
 * @property-read \app\models\ContactRating[] $ratings
 * @property-read \app\models\ContactRating[] $positiveRatings
 * @property-read \app\models\ContactRating[] $neutralRatings
 * @property-read \app\models\ContactRating[] $negativeRatings
 * @property-read \app\models\RequestContact[] $requests List of relations to requests where contact was added
 * @property-read \app\models\ContactPhone[] $phones
 */
class Contact extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'is_deleted',
            'category_id',
            'city',
            'country',
            'name',
            'company',
            'site',
            'address',
            'date',
            'rating'
        ];
    }
    
    public function rules()
    {
        return [
            [['city', 'name', 'company', 'address'], 'purify'],
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
            // Name
            [['name'], 'required', 'message' => Yii::t('app', 'Имя обязательно')],
            [['name'], 'string', 'max' => 128, 'tooLong' => Yii::t('app', 'Имя слишком длинное. Максимум 128 символов')],
            // Company
            [['company'], 'string', 'max' => 128, 'tooLong' => Yii::t('app', 'Название компании слишком длинное. Максимум 128 символа')],
            // Site
            [['site'], 'url', 'defaultScheme' => 'http', 'pattern' => '/^{schemes}:\/\/(([A-ZА-Я0-9][A-ZА-Я0-9_-]*)(\.[A-ZА-Я0-9][A-ZА-Я0-9_-]*)+)/iu', 'message' => Yii::t('app', 'Ссылка имеет неверный формат')],
            // Address
            [['address'], 'string', 'max' => 255, 'tooLong' => Yii::t('app', 'Адрес слишком длинный. Максимум 255 символов')],
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
    
    public function getRecommendations()
    {
        return $this
                ->hasMany(ContactRecommendation::className(), ['contact_id' => '_id'])
                ->where(['is_deleted' => false]);
    }
    
    public function getRatings()
    {
        return $this
                ->hasMany(ContactRating::className(), ['contact_id' => '_id'])
                ->orderBy('date DESC');
    }
    
    public function getPositiveRatings()
    {
        return $this
                ->hasMany(ContactRating::className(), ['contact_id' => '_id'])
                ->where(['value' => 1])
                ->orderBy('date DESC');
    }
    
    public function getNeutralRatings()
    {
        return $this
                ->hasMany(ContactRating::className(), ['contact_id' => '_id'])
                ->where(['value' => 0])
                ->orderBy('date DESC');
    }
    
    public function getNegativeRatings()
    {
        return $this
                ->hasMany(ContactRating::className(), ['contact_id' => '_id'])
                ->where(['value' => -1])
                ->orderBy('date DESC');
    }
    
    public function getRequests()
    {
        return $this->hasMany(RequestContact::className(), ['contact_id' => '_id']);
    }
    
    public function getPhones()
    {
        return $this
                ->hasMany(ContactPhone::className(), ['contact_id' => '_id'])
                ->where(['is_deleted' => false]);
    }
}