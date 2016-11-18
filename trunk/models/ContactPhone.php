<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $contact_id
 * @property string $phone
 * @property string $clear Clear phone value(digits only)
 * @property boolean $is_deleted Should be set to true when parent contact is deleted
 * 
 * @property-read string $id
 * @property-read \app\models\Contact $contact
 */
class ContactPhone extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'contact_id',
            'phone',
            'clear',
            'is_deleted'
        ];
    }
    
    public function rules()
    {
        return [
            // Phone
            [['phone'], 'required', 'message' => Yii::t('app', 'Номер телефона обязателен')],
            [['phone'], 'match', 'pattern' => '/^\(?([0-9]{3})\)?([ .-]?)([0-9]{3})([ .-]?)([0-9]{2})([ .-]?)([0-9]{2})$/i', 'message' => Yii::t('app', 'Неверный формат. Введите телефон в формате (xxx) xxx-xx-xx')],
            // Clear
            [['clear'], 'unique', 'filter' => ['is_deleted' => false], 'message' => Yii::t('app', 'Контакт с таким номером телефона уже существует')]
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
    
    public static function clearPhone($value)
    {
        return str_replace(['+', '(', ')', '-', '.', ' '], ['', '', '', '', '', ''], $value);
    }
}