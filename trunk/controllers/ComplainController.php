<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\HttpException;
use app\models\RequestAnswer;
use app\models\Complain;

class ComplainController extends \app\components\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'popup-create-show' => ['post'],
                    'popup-create-submit' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionPopupCreateShow()
    {
        $answer = RequestAnswer::findOne(['_id' => Yii::$app->request->post('id', '')]);
        return $this->renderPartial('popup-create', ['answer' => $answer]);
    }
    
    public function actionPopupCreateSubmit()
    {
        $data = Yii::$app->request->post('model');
        $answer = RequestAnswer::findOne(['_id' => isset($data['answer_id']) ? $data['answer_id'] : '']);
        if (empty($answer))
            throw new HttpException(500, Yii::t('app', 'Комментарий не найден'));
        $model = new Complain();
        $model->attributes = $data;
        if (!$model->validate())
            return Json::encode(['error' => 1, 'data' => $model->errors]);
        $model->answer_id = $answer->_id;
        $model->user_id = Yii::$app->user->identity->_id;
        $model->date = date('Y-m-d H:i:s');
        $model->save(false);
        $answer->user->complain_count = intval($answer->user->complain_count) + 1;
        $answer->user->save(false);
    }
}