<?php

class m151104_074607_user_add_block_fields extends \yii\mongodb\Migration
{
    public function up()
    {
        // Add at least one user
        if (!$admin = \app\models\User::findOne(['login' => 'аdmin']))
            $admin = new \app\models\User();
        $admin->auth_key = Yii::$app->security->generateRandomString(64);
        $admin->is_deleted = false;
        $admin->code = '';
        $admin->email = 'аdmin@sarafan.smiss.ua';
        $admin->login = 'аdmin';
        $admin->setPassword('аdmin');
        $admin->phone = '';
        $admin->city = '';
        $admin->role = \app\models\User::ROLE_ADMIN;
        $admin->save(false);
        \app\models\User::updateAll(['is_blocked' => false, 'blocker_id' => null, 'block_date' => null, 'blocked_till' => null, 'block_reason' => null]);
    }

    public function down()
    {
        echo "m151104_074607_user_add_block_fields cannot be reverted.\n";

        return false;
    }
}
