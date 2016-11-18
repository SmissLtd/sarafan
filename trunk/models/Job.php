<?php

namespace app\models;

use Yii;

/**
 * @property string $_id
 * @property string $name
 * @property boolean $is_active
 * @property string $date Last activity date
 * 
 * @property-read string $id
 */
class Job extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'name',
            'is_active',
            'date'
        ];
    }
    
    public function getId()
    {
        return (string)$this->_id;
    }
}