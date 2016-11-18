<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $title
 * @property boolean $is_deleted
 * @property string $parent_id
 * 
 * @property-read string $id
 * @property-read \app\models\Category $parent Parent category
 * @property-read \app\models\Category[] $categories Subcategories
 * @property-read \app\models\Category[] $activeCategories Non-deleted subcategories
 */
class Category extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'is_deleted',
            'title',
            'parent_id'
        ];
    }
    
    public function rules()
    {
        return [
            [['title'], 'purify'],
            // Title
            [['title'], 'required', 'message' => Yii::t('app', 'Название обязательно')],
            [['title'], 'string', 'max' => 32, 'tooLong' => Yii::t('app', 'Слишком длинное название. Максимум 32 символа')],
            [['title'], 'unique', 'targetAttribute' => ['title', 'parent_id'], 'message' => 'Такая категория уже существует'],
            // Is deleted
            [['is_deleted'], 'boolean', 'skipOnEmpty' => false, 'message' => Yii::t('app', 'Признак удаленности должен быть булевым значением')],
        ];
    }
    
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->is_deleted = false;
        $this->parent_id = NULL;
    }
    
    public function purify($attribute, $params)
    {
        $this->$attribute = htmlspecialchars($this->$attribute, ENT_NOQUOTES, ini_get('default_charset'), false);
    }
    
    public function getId()
    {
        return (string)$this->_id;
    }
    
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['_id' => 'parent_id']);
    }
    
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => '_id']);
    }
    
    public function getActiveCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => '_id'])->where(['is_deleted' => false]);
    }
}