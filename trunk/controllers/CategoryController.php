<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Contact;
use app\models\Request;
use yii\web\HttpException;

class CategoryController extends \app\components\Controller
{
    const CATEGORY_PAGE_SIZE = 12;
    const SUBCATEGORY_PAGE_SIZE = 20;
    const CATEGORY_COLUMNS = 3;
    const SUBCATEGORY_COLUMNS = 4;
    const CONTACTS_PAGE_SIZE = 3;
    const REQUESTS_PAGE_SIZE = 3;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'list-categories' => ['post'],
                    'list-subcategories' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionListCategories()
    {
        $filter = trim(Yii::$app->request->post('filter', ''));
        $page = intval(Yii::$app->request->post('page', 0));
        $categories = Category::find()->where(['and', ['is_deleted' => false, 'parent_id' => NULL], ['like', 'title', $filter]]);
        $pagination = self::buildPagination($categories->count(), self::CATEGORY_PAGE_SIZE, $page);
        $categories = $categories->offset($pagination['page'] * self::CATEGORY_PAGE_SIZE)->limit(self::CATEGORY_PAGE_SIZE)->orderBy('title')->all();
        return $this->renderPartial(
                'list-categories', [
                    'categories' => $categories, 
                    'pagination' => $pagination, 
                    'filter' => $filter, 
                    'title' => Yii::t('app', 'Надо выбрать категорию'),
                    'columns' => self::CATEGORY_COLUMNS]);
    }
    
    public function actionListSubcategories()
    {
        $filter = trim(Yii::$app->request->post('filter', ''));
        $page = intval(Yii::$app->request->post('page', 0));
        $category = Category::findOne(['_id' => Yii::$app->request->post('id', ''), 'is_deleted' => false, 'parent_id' => NULL]);
        $categories = [];
        if (!empty($category))
        {
            $categories = $category->getActiveCategories();
            if (!empty($filter))
                $categories->andWhere(['like', 'title', $filter]);
            $pagination = self::buildPagination($categories->count(), self::SUBCATEGORY_PAGE_SIZE, $page);
            $categories = $categories->offset($pagination['page'] * self::SUBCATEGORY_PAGE_SIZE)->limit(self::SUBCATEGORY_PAGE_SIZE)->orderBy('title')->all();
        }
        else
            $pagination = self::buildPagination(0, self::SUBCATEGORY_PAGE_SIZE, 0);
        return $this->renderPartial(
                'list-categories', [
                    'categories' => $categories, 
                    'pagination' => $pagination, 
                    'filter' => $filter, 
                    'title' => Yii::t('app', 'Придется немного уточнить'),
                    'columns' => self::SUBCATEGORY_COLUMNS]);
    }
    
    public function actionContacts($id)
    {
        $category = Category::find()->where(['and', ['_id' => (string)$id, 'is_deleted' => false], ['not in', 'parent_id', [null]]])->one();
        if (empty($category))
            throw new HttpException(500, Yii::t('app', 'Категория не найдена'));
        $contacts = Contact::find()->where(['category_id' => $category->_id, 'is_deleted' => false])->orderBy('date DESC');
        $pagination = self::buildPagination($contacts->count(), self::CONTACTS_PAGE_SIZE, 0);
        $contacts = $contacts->limit(self::CONTACTS_PAGE_SIZE)->all();
        return $this->render('contacts', ['category' => $category, 'contacts' => $contacts, 'pagination' => $pagination]);
    }
    
    public function actionRequests($id)
    {
        $category = Category::find()->where(['and', ['_id' => (string)$id, 'is_deleted' => false], ['not in', 'parent_id', [null]]])->one();
        if (empty($category))
            throw new HttpException(500, Yii::t('app', 'Категория не найдена'));
        $requests = Request::find()->where(['category_id' => $category->_id, 'is_deleted' => false])->orderBy('date DESC');
        $pagination = self::buildPagination($requests->count(), self::REQUESTS_PAGE_SIZE, 0);
        $requests = $requests->limit(self::REQUESTS_PAGE_SIZE)->all();
        return $this->render('requests', ['category' => $category, 'requests' => $requests, 'pagination' => $pagination]);
    }
}