<?php

namespace app\commands;

use app\models\User;

class ResetAdminPasswordController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $users = User::findAll(['role' => 'admin']);
        foreach ($users as $user)
        {
            
            $user->setPassword('admin');
            $user->save(false);
        }
    }
}
