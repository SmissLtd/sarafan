<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use app\models\Category;
use app\models\Request;
use app\models\RequestRating;
use app\models\RequestAnswer;
use yii\web\HttpException;

class RequestController extends \app\components\Controller
{
    const LIST_PAGE_SIZE = 3;
    const CONTACT_PAGE_SIZE = 3;
    const ANSWER_PAGE_SIZE = 3;
    
    private static $LIST_ORDERS = ['date ASC', 'date DESC'];
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'popup-create-show' => ['post'],
                    'popup-create-submit' => ['post'],
                    'list' => ['post'],
                    'list-contacts' => ['post'],
                    'list-answers' => ['post'],
                    'popup-create-answer-show' => ['post'],
                    'popup-create-answer-submit' => ['post'],
                    'popup-create-complain-show' => ['post'],
                    'popup-create-complain-submit' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionPopupCreateShow()
    {
        return $this->renderPartial('popup-create');
    }
    
    public function actionPopupCreateSubmit()
    {
        $data = Yii::$app->request->post('model');
        // Invalidate category id if category title was changed
        $category = Category::findOne(['_id' => $data['category_id'], 'is_deleted' => false]);
        if (empty($category) || empty($category->parent_id) || $category->title != $data['category_title'])
            $data['category_id'] = '';
        $request = new Request();
        $request->attributes = $data;
        $request->user_id = Yii::$app->user->identity->_id;
        $request->is_deleted = false;
        $request->date = date('Y-m-d H:i:s');
        if (!$request->validate())
        {
            $errors = $request->errors;
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
        $request->category_id = new \MongoId($request->category_id);
        $request->rating = 0;
        $request->save(false);
        return $request->id;
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
        $requests = Request::find()->where(['category_id' => $category->_id, 'is_deleted' => false])->orderBy($order);
        $pagination = $this->buildPagination($requests->count(), self::LIST_PAGE_SIZE, $page);
        $requests->offset($page * self::LIST_PAGE_SIZE)->limit(self::LIST_PAGE_SIZE);
        return Json::encode(['data' => $this->renderPartial('list', ['requests' => $requests->all()]), 'is_last' => $pagination['page'] >= $pagination['pages'] - 1]);
    }
    
    public function actionView($id)
    {
        $request = Request::findOne(['_id' => $id, 'is_deleted' => false]);
        if (empty($request))
            throw new HttpException(500, Yii::t('app', 'Вопрос не найден'));
        return $this->render('view', ['request' => $request]);
    }
    
    public function actionListContacts()
    {
        $request = Request::findOne(['_id' => Yii::$app->request->post('id'), 'is_deleted' => false]);
        if (empty($request))
            throw new HttpException(500, Yii::t('app', 'Вопрос не найден'));
        $page = intval(Yii::$app->request->post('page', 0));        
        return $this->renderPartial('list-contacts', ['contacts' => $request->getAnswers()->offset(self::CONTACT_PAGE_SIZE * $page)->limit(self::CONTACT_PAGE_SIZE)->all()]);
    }
    
    public function actionListAnswers()
    {
        $root = RequestAnswer::findOne(['_id' => Yii::$app->request->post('id')]);
        if (empty($root))
            throw new HttpException(500, Yii::t('app', 'Коментарий не найден'));
        $page = intval(Yii::$app->request->post('page', 0));
        $pagination = self::buildPagination($root->getAnswers()->count(), self::ANSWER_PAGE_SIZE, $page);
        $answers = $root->getAnswers()->offset(self::ANSWER_PAGE_SIZE * $pagination['page'])->limit(self::ANSWER_PAGE_SIZE)->all();
        return $this->renderPartial('list-answers', ['answers' => $answers, 'pagination' => $pagination]);
    }
    
    public function actionPopupCreateAnswerShow()
    {
        $root = RequestAnswer::findOne(['_id' => Yii::$app->request->post('root_id', '')]);
        if (empty($root))
            throw new HttpException(500, Yii::t('app', 'Комментарий не найден'));
        $answer = RequestAnswer::findOne(['_id' => Yii::$app->request->post('answer_id', '')]);
        return $this->renderPartial('popup-answer', ['root' => $root, 'answer' => $answer]);
    }
    
    public function actionPopupCreateAnswerSubmit()
    {
        $data = Yii::$app->request->post('model');
        $rootId = isset($data['root_id']) ? $data['root_id'] : '';
        $answerId = isset($data['answer_id']) ? $data['answer_id'] : '';
        $root = RequestAnswer::findOne(['_id' => $rootId]);
        if (empty($root))
            throw new HttpException(500, Yii::t('app', 'Комментарий не найден'));
        $answer = RequestAnswer::findOne(['_id' => $answerId]);
        $model = new RequestAnswer();
        $model->attributes = $data;
        if (!$model->validate())
            return Json::encode(['error' => 1, 'data' => $model->errors]);
        if (!empty($answer))
            $model->text = 
                '<div class="quote">' .
                    '<h3>' . Yii::t('app', 'Цитата: {name}', ['name' => $answer->user->name]) . '</h3>' .
                    '<div>' . $answer->text . '</div>' .
                '</div>' .
                $model->text;
        $model->request_id = $root->request_id;
        $model->user_id = Yii::$app->user->identity->_id;
        $model->answer_id = $root->_id;
        $model->date = date('Y-m-d H:i:s');
        $model->save(false);
    }
}