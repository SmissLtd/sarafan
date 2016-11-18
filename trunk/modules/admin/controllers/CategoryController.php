<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Json;
use app\models\Category;

class CategoryController extends \app\modules\admin\components\Controller
{
    const CATEGORY_PAGE_SIZE = 10;
    const SUBCATEGORY_PAGE_SIZE = 10;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'list-categories' => ['post'],
                    'list-subcategories' => ['post'],
                    'show-edit' => ['post'],
                    'submit-edit' => ['post'],
                    'delete' => ['post'],
                    'restore' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        $categories = Category::find()->where(['parent_id' => null]);
        $pagination = \app\components\Controller::buildPagination($categories->count(), self::CATEGORY_PAGE_SIZE, 0);
        return $this->render('index', ['categories' => $categories->orderBy('title')->limit(self::CATEGORY_PAGE_SIZE)->all(), 'pagination' => $pagination]);
    }
    
    public function actionListCategories()
    {
        $page = intval(Yii::$app->request->post('page', 0));
        $categories = Category::find()->where(['parent_id' => null]);
        $pagination = \app\components\Controller::buildPagination($categories->count(), self::CATEGORY_PAGE_SIZE, $page);
        return $this->renderPartial(
                'list', [
                    'categories' => $categories->orderBy('title')->offset($pagination['page'] * self::CATEGORY_PAGE_SIZE)->limit(self::CATEGORY_PAGE_SIZE)->all(),
                    'pagination' => $pagination,
                    'isSubcategories' => false]);
    }
    
    public function actionListSubcategories()
    {
        $parent = Category::findOne(['_id' => Yii::$app->request->post('parent', '')]);
        if (empty($parent))
            throw new HttpException(500, Yii::t('app', 'Категря не найдена'));
        $page = intval(Yii::$app->request->post('page', 0));
        $categories = Category::find()->where(['parent_id' => $parent->_id]);
        $pagination = \app\components\Controller::buildPagination($categories->count(), self::SUBCATEGORY_PAGE_SIZE, $page);
        return $this->renderPartial(
                'list', [
                    'categories' => $categories->orderBy('title')->offset($pagination['page'] * self::SUBCATEGORY_PAGE_SIZE)->limit(self::SUBCATEGORY_PAGE_SIZE)->all(),
                    'pagination' => $pagination,
                    'isSubcategories' => true]);
    }
    
    public function actionShowEdit()
    {
        $category = Category::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($category))
            $category = new Category();
        $parentCategory = Category::findOne(['_id' => Yii::$app->request->post('parent', '')]);
        if (empty($parentCategory))
            $parentCategory = new Category();
        return $this->renderPartial('popup', ['category' => $category, 'parentCategory' => $parentCategory]);
    }
    
    public function actionSubmitEdit()
    {
        $id = Yii::$app->request->post('id', '');
        $category = Category::findOne(['_id' => $id]);
        if (empty($category))
            $category = new Category();
        $parentCategory = Category::findOne(['_id' => Yii::$app->request->post('parent', '')]);
        $category->attributes = Yii::$app->request->post('model');
        if (!empty($parentCategory))
            $category->parent_id = $parentCategory->_id;
        if (!$category->validate())
            return Json::encode(['error' => 1, 'data' => $category->errors]);
        $category->save(false);
    }
    
    public function actionView($id)
    {
        $parent = Category::findOne(['_id' => $id]);
        if (empty($parent))
            throw new HttpException(500, Yii::t('app', 'Категория не найдена'));
        $categories = Category::find()->where(['parent_id' => $parent->_id]);
        $pagination = \app\components\Controller::buildPagination($categories->count(), self::SUBCATEGORY_PAGE_SIZE, 0);
        return $this->render('view', ['categories' => $categories->orderBy('title')->limit(self::SUBCATEGORY_PAGE_SIZE)->all(), 'pagination' => $pagination, 'parent' => $parent]);
    }
    
    public function actionDelete()
    {
        $category = Category::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($category))
            throw new HttpException(500, Yii::t('app', 'Категория не найдена'));
        $category->is_deleted = true;
        $category->save(false);
    }
    
    public function actionRestore()
    {
        $category = Category::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($category))
            throw new HttpException(500, Yii::t('app', 'Категория не найдена'));
        $category->is_deleted = false;
        $category->save(false);
    }
}