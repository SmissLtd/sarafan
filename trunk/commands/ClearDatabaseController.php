<?php

namespace app\commands;

class ClearDatabaseController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $m = new \MongoClient();
        $db = $m->selectDB('sarafan');
        $collections = $db->listCollections();
        foreach ($collections as $collection)
        {
            echo "Drop '$collection'...";
            $collection->drop();
            echo "OK\n";
        }
        \Yii::$app->runAction('mongodb-migrate', ['interactive' => '0']);
    }
}
