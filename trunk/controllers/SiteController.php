<?php

namespace app\controllers;

use Yii;
use app\models\Category;

class SiteController extends \app\components\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        $categories = Category::find()->where(['is_deleted' => false, 'parent_id' => null]);
        $pagination = self::buildPagination($categories->count(), CategoryController::CATEGORY_PAGE_SIZE, 0);
        $categories = $categories->limit(CategoryController::CATEGORY_PAGE_SIZE)->orderBy('title')->all();
        return $this->render('home', ['categories' => $categories, 'pagination' => $pagination, 'columns' => CategoryController::CATEGORY_COLUMNS]);
    }
}