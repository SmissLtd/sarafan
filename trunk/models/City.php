<?php

namespace app\models;

/**
 * @property string $_id
 * @property string $title
 * @property boolean $is_deleted
 * 
 * @property-read string $id
 */
class City extends \yii\mongodb\ActiveRecord
{
    public function attributes()
    {
        return [
            '_id',
            'is_deleted',
            'title'
        ];
    }
    public function getId()
    {
        return (string)$this->_id;
    }
}