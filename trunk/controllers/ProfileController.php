<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserCode;
use yii\web\HttpException;
use yii\helpers\Json;

class ProfileController extends \app\components\Controller
{
    const PAGE_SIZE_CODES = 10;
    const GENERATE_CODE_ATTEMPTS = 10;
    const CODE_LENGTH = 8;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'list-codes' => ['post'],
                    'generate-code' => ['post'],
                    'delete-code' => ['post'],
                    'popup-profile-show' => ['post'],
                    'popup-profile-submit' => ['post'],
                    '*' => ['get']
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index-profile');
    }
    
    public function actionCodes()
    {
        $codes = UserCode::find()->where(['user_id' => Yii::$app->user->identity->_id, 'is_deleted' => false]);
        $pagination = self::buildPagination($codes->count(), self::PAGE_SIZE_CODES, 0);
        $codes->limit(self::PAGE_SIZE_CODES);
        $codes->offset($pagination['page'] * self::PAGE_SIZE_CODES);
        return $this->render('index-codes', [
            'codes' => $codes->all(),
            'pagination' => $pagination,
            'pageSize' => self::PAGE_SIZE_CODES]);
    }
    
    public function actionListCodes()
    {
        $page = Yii::$app->request->post('page', 0);
        $codes = UserCode::find()->where(['user_id' => Yii::$app->user->identity->_id, 'is_deleted' => false]);
        $pagination = self::buildPagination($codes->count(), self::PAGE_SIZE_CODES, $page);
        $codes->limit(self::PAGE_SIZE_CODES);
        $codes->offset($pagination['page'] * self::PAGE_SIZE_CODES);
        return $this->renderPartial('codes', [
            'codes' => $codes->all(),
            'pagination' => $pagination,
            'pageSize' => self::PAGE_SIZE_CODES]);
    }
    
    public function actionGenerateCode()
    {
        $code = new UserCode();
        $code->user_id = Yii::$app->user->identity->_id;
        $code->created = date('Y-m-d H:i:s');
        $code->is_used = false;
        $code->date_used = '';
        $code->used_user_id = '';
        $code->is_deleted = false;
        for ($index = 0; $index < self::GENERATE_CODE_ATTEMPTS; $index++)
        {
            $code->code = Yii::$app->security->generateRandomString(self::CODE_LENGTH);
            if (!UserCode::findOne(['is_deleted' => false, 'code' => $code->code]))
                break;
        }
        if ($index >= self::GENERATE_CODE_ATTEMPTS)
            throw new HttpException(500, Yii::t('app', 'Не удалось сгенерировать уникальный код. Пожалуйста повторите попытку позже.'));
        $code->save(false);
        return Yii::t('app', 'Новый код {code} был успешно сгенерирован.', ['code' => $code->code]);
    }
    
    public function actionDeleteCode()
    {
        $id = Yii::$app->request->post('id');
        UserCode::deleteAll(['user_id' => Yii::$app->user->identity->_id, '_id' => $id]);
        return $this->actionListCodes();
    }
    
    public function actionPopupProfileShow()
    {
        return $this->renderPartial('popup-profile');
    }
    
    public function actionPopupProfileSubmit()
    {
        $model = Yii::$app->user->identity;
        $data = Yii::$app->request->post('model');
        if (empty($data['password']))
        {
            unset($data['password']);
            unset($data['password2']);
        }
        $model->attributes = $data;
        $keys = ['name', 'email', 'login', 'phone', 'city', 'country'];
        if (!empty($data['password']))
            $keys = array_merge($keys, ['password', 'password2']);
        if (!$model->validate($keys))
            return Json::encode(['error' => 1, 'data' => $model->errors]);
        if (!empty($data['password']))
            $model->setPassword($data['password']);
        $model->save(false);
    }
}