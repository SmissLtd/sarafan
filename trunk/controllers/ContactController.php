<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use app\models\Category;
use app\models\Contact;
use app\models\ContactRecommendation;
use app\models\ContactRating;
use app\models\ContactPhone;
use app\models\Request;
use app\models\RequestContact;
use app\models\RequestAnswer;
use yii\web\HttpException;

class ContactController extends \app\components\Controller
{
    const LIST_PAGE_SIZE = 3;
    const RATING_LIMIT = 10;
    
    private static $LIST_ORDERS = ['date ASC', 'date DESC', 'rating ASC', 'rating DESC'];
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'popup-create-show' => ['post'],
                    'popup-create-submit' => ['post'],
                    'list' => ['post'],
                    'rate' => ['post'],
                    'popup-ratings' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionPopupCreateShow()
    {
        $request = Request::findOne(['_id' => Yii::$app->request->post('request_id', ''), 'is_deleted' => false]);
        $contact = Contact::findOne(['_id' => Yii::$app->request->post('contact_id', ''), 'is_deleted' => false]);
        return $this->renderPartial('popup-create', ['request' => $request, 'contact' => $contact]);
    }
    
    public function actionPopupCreateSubmit()
    {
        $data = Yii::$app->request->post('model');
        // Invalidate category id if category title was changed
        $category = Category::findOne(['_id' => $data['category_id'], 'is_deleted' => false]);
        if (empty($category) || empty($category->parent_id) || $category->title != $data['category_title'])
            $data['category_id'] = '';
        // Validate contact and recommendations
        $contact = Contact::findOne(['_id' => Yii::$app->request->post('contact_id', ''), 'is_deleted' => false]);
        if (empty($contact))
        {
            $contact = new Contact();
            $contact->attributes = $data;
            $contact->is_deleted = false;
        }
        $recommendation = new ContactRecommendation();
        $recommendation->attributes = $data;
        $result = $contact->isNewRecord ? $contact->validate() : true;
        if (!$recommendation->validate())
            $result = false;
        // Validate phones
        $phoneErrors = $phones = [];
        if ($contact->isNewRecord)
        {
            foreach ($data as $key => $value)
                if (strpos($key, 'phone_') === 0)
                {
                    $value = trim($value);
                    if (empty($value))
                        continue;
                    $phone = new ContactPhone();
                    $phone->attributes = ['phone' => $value, 'clear' => ContactPhone::clearPhone($value)];
                    if (!$phone->validate())
                    {
                        // Check
                        if (isset($phone->errors['clear']))
                        {
                            $existing = ContactPhone::findOne(['clear' => $phone->clear, 'is_deleted' => false]);
                            return Json::encode([
                                'error' => 1,
                                'data' => [
                                    'exists' => 1,
                                    'popup' => $this->renderPartial('popup-confirm-edit', ['contact' => $existing->contact]),
                                    'contact' => [
                                        'id' => (string)$existing->contact_id,
                                        'category_title' => $existing->contact->category->title,
                                        'city' => $existing->contact->city,
                                        'company' => $existing->contact->company,
                                        'name' => $existing->contact->name,
                                        'site' => $existing->contact->site,
                                        'phone' => $value,
                                        'address' => $existing->contact->address,
                                    ]]]);
                        }
                        $phoneErrors[$key] = [];
                        foreach ($phone->errors as $errors)
                            foreach ($errors as $error)
                                $phoneErrors[$key][] = $error;
                    }
                    else
                        $phones[$phone->clear] = $phone;
                }
            if (empty($phoneErrors) && empty($phones))
                $phoneErrors['phone_0'] = [Yii::t('app', 'Номер телефона обязателен')];
        }
        if (!empty($phoneErrors))
            $result = false;
        if (!$result)
        {
            // Merge errors
            $errors = array_merge($contact->errors, $recommendation->errors, $phoneErrors);
            if (isset($errors['category_id']))
            {
                if (!empty($data['category_title']) && empty($data['category_id']))
                    $errors['category_title'] = [Yii::t('app', 'Категория / подкатегория не найдена')];
                else
                    $errors['category_title'] = $errors['category_id'];
                unset($errors['category_id']);
            }
            return Json::encode(['error' => 1, 'data' => $errors]);
        }
        // Save contact
        if ($contact->isNewRecord)
        {
            $contact->date = date('Y-m-d H:i:s');
            $contact->category_id = new \MongoId($contact->category_id);
            $contact->save(false);
        }
        $request = Request::findOne(['_id' => Yii::$app->request->post('request_id', ''), 'is_deleted' => false]);
        if (!empty($request))
        {
            $recommendation->request_id = $request->_id;
            // Save Request-Contact relation
            $relation = new RequestContact();
            $relation->request_id = $request->_id;
            $relation->contact_id = $contact->_id;
            $relation->save(false);
            // Save request answer
            $answer = new RequestAnswer();
            $answer->request_id = $request->_id;
            $answer->contact_id = $contact->_id;
            $answer->user_id = Yii::$app->user->identity->_id;
            $answer->date = date('Y-m-d H:i:s');
            $answer->text = $recommendation->comment;
            $answer->save(false);
        }
        // Save recommendation
        $recommendation->contact_id = $contact->_id;
        $recommendation->user_id = Yii::$app->user->identity->_id;
        $recommendation->date = date('Y-m-d H:i:s');
        $recommendation->save(false);
        // Save phones
        foreach ($phones as $phone)
        {
            $phone->contact_id = $contact->_id;
            $phone->is_deleted = false;
            $phone->save(false);
        }
        return $contact->id;
    }
    
    public function actionSearchCategory($term)
    {
        $result = [];
        $term = trim($term);
        if (!empty($term))
        {
            $list = Category::find()
                    ->where(['and', ['and', ['is_deleted' => false], ['parent_id' => ['$nin' => [null]]]], ['like', 'title', $term]])
                    ->all();
            $cats = [];
            foreach ($list as $category)
            {
                if (!isset($cats[(string)$category->parent_id]))
                {
                    $cats[(string)$category->parent_id]['category'] = $category->parent;
                    $cats[(string)$category->parent_id]['items'] = [];
                }
                if (!isset($cats[(string)$category->parent_id]['items'][(string)$category->id]))
                    $cats[(string)$category->parent_id]['items'][(string)$category->id] = $category;
            }
            foreach ($cats as $cat)
            {
                $result[] = ['label' => $cat['category']->title, 'value' => $cat['category']->id, 'disabled' => true];
                foreach ($cat['items'] as $item)
                    $result[] = ['label' => $item->title, 'value' => $item->title, 'id' => (string)$item->id, 'disabled' => false];
            }
        }
        return Json::encode($result);
    }
    
    public function actionList()
    {
        $page = intval(Yii::$app->request->post('page', 0));
        $id = Yii::$app->request->post('id', '');
        $category = Category::find()->where(['and', ['_id' => (string)$id, 'is_deleted' => false], ['not in', 'parent_id', [null]]])->one();
        if (empty($category))
            throw new HttpException(500, Yii::t('app', 'Категория не найдена'));
        $order = Yii::$app->request->post('order', '');
        if (!in_array($order, self::$LIST_ORDERS))
            $order = self::$LIST_ORDERS[0];
        $contacts = Contact::find()->where(['category_id' => $category->_id, 'is_deleted' => false])->orderBy($order);
        $pagination = $this->buildPagination($contacts->count(), self::LIST_PAGE_SIZE, $page);
        $contacts->offset($page * self::LIST_PAGE_SIZE)->limit(self::LIST_PAGE_SIZE);
        return Json::encode(['data' => $this->renderPartial('list', ['contacts' => $contacts->all()]), 'is_last' => $pagination['page'] >= $pagination['pages'] - 1]);
    }
    
    public function actionRate()
    {
        $contact = Contact::findOne(['_id' => Yii::$app->request->post('id'), 'is_deleted' => false]);
        if (empty($contact))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        $value = intval(Yii::$app->request->post('value', 0));
        if (!in_array($value, [1, 0, -1]))
            throw new HttpException(500, Yii::t('app', 'Некорректное значение рейтинга'));
        $add = true;
        if ($rate = ContactRating::findOne(['contact_id' => $contact->_id, 'user_id' => Yii::$app->user->identity->_id]))
        {
            $contact->rating = intval($contact->rating) - ($rate->value == 0 ? 1 : ($rate->value == 1 ? 2 : -1));
            $contact->save(false);
            if ($rate->value == $value)
                $add = false;
            $rate->delete();
        }
        if ($add)
        {
            $contact->rating = intval($contact->rating) + ($value == 0 ? 1 : ($value == 1 ? 2 : -1));
            $contact->save(false);
            $rate = new ContactRating();
            $rate->contact_id = $contact->_id;
            $rate->user_id = Yii::$app->user->identity->_id;
            $rate->date = date('Y-m-d H:i:s');
            $rate->value = $value;
            $rate->save(false);
        }
        return $this->renderPartial('rating', ['contact' => $contact]);
    }
    
    public function actionPopupRatings()
    {
        $contact = Contact::findOne(['_id' => Yii::$app->request->post('id'), 'is_deleted' => false]);
        if (empty($contact))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        $value = intval(Yii::$app->request->post('value', 0));
        if (!in_array($value, [1, 0, -1]))
            throw new HttpException(500, Yii::t('app', 'Некорректное значение рейтинга'));
        return $this->renderPartial('ratings', ['ratings' => $contact->getRatings()->where(['value' => $value])->all()]);
    }
    
    public function actionView($id)
    {
        $contact = Contact::findOne(['_id' => $id, 'is_deleted' => false]);
        if (empty($contact))
            throw new HttpException(500, Yii::t('app', 'Контакт не найден'));
        return $this->render('view', ['contact' => $contact]);
    }
}