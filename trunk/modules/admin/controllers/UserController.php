<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Json;
use app\models\User;

class UserController extends \app\modules\admin\components\Controller
{
    const USER_PAGE_SIZE = 10;
    const REFERRERS_PAGE_SIZE = 10;
    
    private static $allowedOrders = ['date_register' => 'DESC', 'name' => 'ASC', 'date_action' => 'DESC', 'complain_count' => 'DESC', 'is_blocked' => 'DESC'];
    private static $allowedFilters = ['name', 'code'];
    private static $allowedDisplay = ['all', 'only', 'exclude'];
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'sort' => ['post'],
                    'filter' => ['post'],
                    'list' => ['post'],
                    'delete' => ['post'],
                    'restore' => ['post'],
                    'page' => ['post'],
                    'show-block' => ['post'],
                    'submit-block' => ['post'],
                    'unblock' => ['post'],
                    'show-edit' => ['post'],
                    'submit-edit' => ['post'],
                    'referrers-list' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    private function getFilter()
    {
        $filter = Yii::$app->session['filter-users'];
        if (empty($filter))
            $filter = [];
        if (empty($filter['orderBy']))
        {
            $filter['orderBy'] = 'date_register';
            $filter['order'] = 'DESC';
        }
        if (empty($filter['page']))
            $filter['page'] = 0;
        if (empty($filter['fields']))
            $filter['fields'] = [];
        if (empty($filter['show-deleted']))
            $filter['show-deleted'] = 'exclude';
        if (empty($filter['show-blocked']))
            $filter['show-blocked'] = 'all';
        return $filter;
    }
    
    private function setFilter($filter)
    {
        Yii::$app->session['filter-users'] = $filter;
    }
    
    private function filterUsers($users, $filter)
    {
        foreach ($filter['fields'] as $field => $value)
            if (strlen($value) > 0)
                $users->andWhere(['like', $field, $value]);
        switch ($filter['show-deleted'])
        {
            case 'only':
                $users->andWhere(['is_deleted' => true]);
                break;
            case 'exclude':
                $users->andWhere(['is_deleted' => false]);
                break;
        }
        switch ($filter['show-blocked'])
        {
            case 'only':
                $users->andWhere(['is_blocked' => true]);
                break;
            case 'exclude':
                $users->andWhere(['is_blocked' => false]);
                break;
        }
    }
    
    private function orderUsers($users, $filter)
    {
        $users->orderBy($filter['orderBy'] . ' ' . $filter['order']);
    }
    
    private function listUsers($view, $method)
    {
        $id = Yii::$app->request->get('id', '');
        $filter = $this->getFilter();
        $users = User::find();
        if (!empty($id))
        {
            $filter['fields'] = [];
            $filter['show-deleted'] = 'all';
            $filter['show-blocked'] = 'all';
            $users->andWhere(['_id' => $id]);
        }
        $this->filterUsers($users, $filter);
        $pagination = \app\components\Controller::buildPagination($users->count(), self::USER_PAGE_SIZE, $filter['page']);
        $filter['page'] = $pagination['page'];
        $this->orderUsers($users, $filter);
        return $this->$method(
                $view, [
                    'users' => $users->offset($pagination['page'] * self::USER_PAGE_SIZE)->limit(self::USER_PAGE_SIZE)->all(), 
                    'pagination' => $pagination,
                    'filter' => $filter
                ]);
    }
    
    public function actionIndex()
    {
        return $this->listUsers('index', 'render');
    }
    
    public function actionSort()
    {
        $field = Yii::$app->request->post('field', '');
        $filter = $this->getFilter();
        if (in_array($field, array_keys(self::$allowedOrders)))
        {
            if ($filter['orderBy'] == $field)
                $filter['order'] = $filter['order'] == 'DESC' ? 'ASC' : 'DESC';
            else
            {
                $filter['orderBy'] = $field;
                $filter['order'] = self::$allowedOrders[$field];
            }
            $this->setFilter($filter);
        }
        return $this->listUsers('list', 'renderPartial');
    }
    
    public function actionFilter()
    {
        $values = Yii::$app->request->post('filter', []);
        $filter = $this->getFilter();
        $filter['fields'] = [];
        if (is_array($values))
            foreach ($values as $field => $value)
                if (in_array($field, array_keys(self::$allowedFilters)))
                    $filter['fields'][$field] = $value;
        $showDeleted = Yii::$app->request->post('show-deleted', '');
        if (in_array($showDeleted, self::$allowedDisplay))
            $filter['show-deleted'] = $showDeleted;
        else
            $filter['show-deleted'] = 'exclude';
        $showBlocked = Yii::$app->request->post('show-blocked', '');
        if (in_array($showBlocked, self::$allowedDisplay))
            $filter['show-blocked'] = $showBlocked;
        else
            $filter['show-blocked'] = 'all';
        $this->setFilter($filter);
        return $this->listUsers('list', 'renderPartial');
    }
    
    public function actionList()
    {
        return $this->listUsers('list', 'renderPartial');
    }
    
    public function actionDelete()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        if ($user->id == Yii::$app->user->identity->id)
            throw new HttpException(500, Yii::t('app', 'Нельзя удалить себя'));
        $user->is_deleted = true;
        $user->save(false);
    }
    
    public function actionRestore()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        $user->is_deleted = false;
        $user->save(false);
    }
    
    public function actionPage()
    {
        $filter = $this->getFilter();
        $filter['page'] = intval(Yii::$app->request->post('page', ''));
        $this->setFilter($filter);
        return $this->listUsers('list', 'renderPartial');
    }
    
    public function actionShowBlock()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        if ($user->id == Yii::$app->user->identity->id)
            throw new HttpException(500, Yii::t('app', 'Нельзя удалить себя'));
        return $this->renderPartial('popup-block', ['user' => $user]);
    }
    
    public function actionSubmitBlock()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        if ($user->id == Yii::$app->user->identity->id)
            throw new HttpException(500, Yii::t('app', 'Нельзя удалить себя'));
        $model = new \app\models\forms\Block();
        $model->attributes = Yii::$app->request->post('model');
        if (!$model->validate())
            return Json::encode(['error' => 1, 'data' => $model->errors]);
        $user->block_date = date('Y-m-d H:i:s');
        $user->block_reason = $model->reason;
        $user->blocked_till = date('Y-m-d', strtotime($model->till));
        $user->blocker_id = Yii::$app->user->identity->_id;
        $user->is_blocked = true;
        $user->save(false);
    }
    
    public function actionUnblock()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        $user->is_blocked = false;
        $user->save(false);
    }
    
    public function actionShowEdit()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            $user = new User();
        if ($user->id == Yii::$app->user->identity->id)
            throw new HttpException(500, Yii::t('app', 'Этого пользователя нельзя редактировать'));
        return $this->renderPartial('popup-edit', ['user' => $user]);
    }
    
    public function actionSubmitEdit()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('id', '')]);
        if (empty($user))
            $user = new User(['scenario' => 'register']);
        $data = Yii::$app->request->post('model');
        if (empty($data['password']))
        {
            unset($data['password']);
            unset($data['password2']);
        }
        $user->attributes = $data;
        $keys = ['name', 'email', 'login', 'phone', 'city', 'country', 'role'];
        if ($user->isNewRecord || (!$user->isNewRecord && !empty($data['password'])))
            $keys = array_merge($keys, ['password', 'password2']);
        if (!$user->validate($keys))
            return Json::encode(['error' => 1, 'data' => $user->errors]);
        if (!empty($data['password']))
            $user->setPassword($data['password']);
        $user->date_register = date('Y-m-d H:i:s');
        $user->save(false);
    }
    
    public function actionReferrers($id)
    {
        $user = User::findOne(['_id' => $id]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        $codes = $user->getUsedCodes();
        $pagination = \app\components\Controller::buildPagination($codes->count(), self::REFERRERS_PAGE_SIZE, 0);
        return $this->render('referrers', ['user' => $user, 'codes' => $codes->orderBy('date_used DESC')->limit(self::REFERRERS_PAGE_SIZE)->all(), 'pagination' => $pagination]);
    }
    
    public function actionReferrersList()
    {
        $user = User::findOne(['_id' => Yii::$app->request->post('user_id', '')]);
        if (empty($user))
            throw new HttpException(500, Yii::t('app', 'Пользователь не найден'));
        $page = intval(Yii::$app->request->post('page', 0));
        $codes = $user->getUsedCodes();
        $pagination = \app\components\Controller::buildPagination($codes->count(), self::REFERRERS_PAGE_SIZE, $page);
        return $this->renderPartial(
                'referrers-list', [
                    'codes' => $codes->orderBy('date_used DESC')->offset($pagination['page'] * self::REFERRERS_PAGE_SIZE)->limit(self::REFERRERS_PAGE_SIZE)->all(),
                    'pagination' => $pagination]);
    }
}