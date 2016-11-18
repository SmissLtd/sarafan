<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Json;
use app\models\Request;
use app\models\RequestAnswer;

class RequestController extends \app\modules\admin\components\Controller
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
                    'show-confirm-answer-delete' => ['post'],
                    'answer-delete' => ['post'],
                    'show-answer-edit' => ['post'],
                    'submit-answer-edit' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionShowConfirmDelete()
    {
        if (!$request = Request::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Вопрос не найден'));
        return $this->renderPartial('confirm-delete', ['request' => $request]);
    }
    
    public function actionDelete()
    {
        if (!$request = Request::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Вопрос не найден'));
        $request->is_deleted = true;
        $request->save(false);
    }
    
    public function actionShowEdit()
    {
        if (!$request = Request::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Вопрос не найден'));
        return $this->renderPartial('popup-edit', ['request' => $request]);
    }
    
    public function actionSubmitEdit()
    {
        if (!$request = Request::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Вопрос не найден'));
        $data = Yii::$app->request->post('model');
        $request->attributes = $data;
        if (!$request->validate())
            return Json::encode(['error' => 1, 'data' => $request->errors]);
        $request->save(false);
    }
    
    public function actionShowConfirmAnswerDelete()
    {
        if (!$answer = RequestAnswer::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Ответ не найден'));
        return $this->renderPartial('confirm-answer-delete', ['answer' => $answer]);
    }
    
    public function actionAnswerDelete()
    {
        if (!$answer = RequestAnswer::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Ответ не найден'));
        $answer->is_deleted = true;
        $answer->save(false);
    }
    
    public function actionShowAnswerEdit()
    {
        if (!$answer = RequestAnswer::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Ответ не найден'));
        return $this->renderPartial('popup-answer-edit', ['answer' => $answer]);
    }
    
    public function actionSubmitAnswerEdit()
    {
        if (!$answer = RequestAnswer::findOne(['_id' => Yii::$app->request->post('id', '')]))
            throw new HttpException(500, Yii::t('app', 'Ответ не найден'));
        $data = Yii::$app->request->post('model');
        $answer->attributes = $data;
        if (!$answer->validate())
            return Json::encode(['error' => 1, 'data' => $answer->errors]);
        $answer->save(false);
    }
}