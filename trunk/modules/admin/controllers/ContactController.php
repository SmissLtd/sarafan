<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Json;
use app\models\Contact;
use app\models\ContactPhone;
use app\models\ContactRecommendation;

class ContactController extends \app\modules\admin\components\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'show-confirm-delete' => ['post'],
                    'delete' => ['post'],
                    'show-edit' => ['post'],
                    'submit-edit' => ['post'],
                    'show-confirm-recommendation-delete' => ['post'],
                    'recommendation-delete' => ['post'],
                    'show-recommendation-edit' => ['post'],
                    'submit-recommendation-edit' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionShowConfirmDelete()
    {
        if (!$contact = Contact::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        return $this->renderPartial('confirm-delete', ['contact' => $contact]);
    }
    
    public function actionDelete()
    {
        if (!$contact = Contact::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        $contact->is_deleted = true;
        $contact->save(false);
    }
    
    public function actionShowEdit()
    {
        if (!$contact = Contact::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        return $this->renderPartial('popup-edit', ['contact' => $contact]);
    }
    
    public function actionSubmitEdit()
    {
        if (!$contact = Contact::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        $data = Yii::$app->request->post('model');
        $contact->attributes = $data;
        $result = $contact->validate();
        // Validate phones
        $phones = $contact->phones;
        $phoneErrors = $processedPhones = [];
        foreach ($data as $key => $value)
            if (strpos($key, 'phone_') === 0)
            {
                $value = trim($value);
                if (empty($value))
                    continue;
                $phone = null;
                foreach ($phones as $item)
                    if ($item->clear == ContactPhone::clearPhone($value))
                    {
                        $phone = $item;
                        break;
                    }
                if (!empty($phone))
                {
                    $processedPhones[] = $phone;
                    continue;
                }
                $phone = new ContactPhone();
                $phone->attributes = ['phone' => $value, 'clear' => ContactPhone::clearPhone($value)];
                if (!$phone->validate())
                {
                    $phoneErrors[$key] = [];
                    foreach ($phone->errors as $errors)
                        foreach ($errors as $error)
                            $phoneErrors[$key][] = $error;
                }
                else
                    $processedPhones[] = $phone;
            }
        if (empty($phoneErrors) && empty($phones))
            $phoneErrors['phone_0'] = [Yii::t('app', 'Номер телефона обязателен')];
        if (!empty($phoneErrors))
            $result = false;
        if (!$result)
        {
            // Merge errors
            $errors = array_merge($contact->errors, $phoneErrors);
            return Json::encode(['error' => 1, 'data' => $errors]);
        }
        // Save contact
        $contact->save(false);
        // Save phones
        foreach ($processedPhones as $phone)
            if ($phone->isNewRecord)
            {
                $phone->contact_id = $contact->_id;
                $phone->is_deleted = false;
                $phone->save(false);
            }
        foreach ($phones as $phone)
        {
            $found = false;
            foreach ($processedPhones as $item)
                if ($item->id == $phone->id)
                {
                    $found = true;
                    break;
                }
            if (!$found)
            {
                $phone->is_deleted = true;
                $phone->save(false);
            }
        }
    }
    
    public function actionShowConfirmRecommendationDelete()
    {
        if (!$recommendation = ContactRecommendation::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Рекомендация не найдена'));
        return $this->renderPartial('confirm-recommendation-delete', ['recommendation' => $recommendation]);
    }
    
    public function actionRecommendationDelete()
    {
        if (!$recommendation = ContactRecommendation::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Рекомендация не найдена'));
        if (!$recommendation->is_deleted)
        {
            if ($recommendation->contact->getRecommendations()->count() <= 1)
                throw new HttpException(500, Yii::t('app', 'Нельзя удалить последнюю рекомендацию'));
            $recommendation->is_deleted = true;
            $recommendation->save(false);
        }
    }
    
    public function actionShowRecommendationEdit()
    {
        if (!$recommendation = ContactRecommendation::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Рекомендация не найдена'));
        return $this->renderPartial('popup-recommendation-edit', ['recommendation' => $recommendation]);
    }
    
    public function actionSubmitRecommendationEdit()
    {
        if (!$recommendation = ContactRecommendation::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Рекомендация не найдена'));
        $data = Yii::$app->request->post('model');
        $recommendation->attributes = $data;
        if (!$recommendation->validate())
            return Json::encode(['error' => 1, 'data' => $recommendation->errors]);
        $recommendation->save(false);
    }
}