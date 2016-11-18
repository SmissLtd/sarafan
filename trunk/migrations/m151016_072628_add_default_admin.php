<?php

class m151016_072628_add_default_admin extends \yii\mongodb\Migration
{
    public function up()
    {
        if (!$admin = \app\models\User::findOne(['login' => 'admin']))
            $admin = new \app\models\User();
        $admin->auth_key = Yii::$app->security->generateRandomString(64);
        $admin->is_deleted = false;
        $admin->code = '';
        $admin->email = 'admin@sarafan.smiss.ua';
        $admin->login = 'admin';
        $admin->setPassword('admin');
        $admin->phone = '';
        $admin->city = '';
        $admin->role = \app\models\User::ROLE_ADMIN;
        $admin->save(false);
        echo "Admin ID: " . $admin->_id . "\n";
    }

    public function down()
    {
        echo "m151016_072628_add_default_admin cannot be reverted.\n";

        return false;
    }
}
