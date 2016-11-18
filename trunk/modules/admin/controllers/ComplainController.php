<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Json;
use app\models\Complain;

class ComplainController extends \app\modules\admin\components\Controller
{
    const COMPLAIN_PAGE_SIZE = 10;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'list' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        $list = Complain::find();
        $pagination = \app\components\Controller::buildPagination($list->count(), self::COMPLAIN_PAGE_SIZE, 0);
        return $this->render('index', ['list' => $list->orderBy('date DESC')->limit(self::COMPLAIN_PAGE_SIZE)->all(), 'pagination' => $pagination]);
    }
    
    public function actionList()
    {
        $page = intval(Yii::$app->request->post('page', 0));
        $list = Complain::find();
        $pagination = \app\components\Controller::buildPagination($list->count(), self::COMPLAIN_PAGE_SIZE, $page);
        return $this->renderPartial(
                'list', [
                    'list' => $list->orderBy('date DESC')->offset($pagination['page'] * self::COMPLAIN_PAGE_SIZE)->limit(self::COMPLAIN_PAGE_SIZE)->all(),
                    'pagination' => $pagination]);
    }
}