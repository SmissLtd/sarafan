<?php

namespace app\commands;

use Yii;
use app\models\UserFriend;
use app\models\Job;
use app\models\UserAccount;
use app\models\UserAccountQueue;
use app\components\OAuthRequest;

class FriendVkController extends \yii\console\Controller
{
    const DELAY = 250000; // Microseconds(VK limit: 5 requests per second)
    const JOB_NAME = 'vkontakte';
    const JOB_ACTIVITY = 60;
    const PAGE_SIZE = 5000;
    
    private $_job;
    
    public function actionIndex()
    {
        if ($this->checkJobExists())
        {
            echo "Job is already in progress\n";
            return;
        }
        $client = new OAuthRequest([], self::DELAY);
        $queues = UserAccountQueue::find()
                ->where(['between', 'date', '0000-00-00 00:00:00', date('Y-m-d H:i:s')])
                ->andWhere(['source' => 'vkontakte'])
                ->orderBy('date')
                ->all();
        foreach ($queues as $queue)
            foreach ($queue->user->accounts as $account)
                if ($account->source == 'vkontakte')
                {
                    $this->updateFriends($queue, $account, $client);
                    break;
                }
        $this->_job->is_active = false;
        $this->_job->save(false);
    }
    
    private function checkJobExists()
    {
        $this->_job = Job::find()
                ->where(['name' => self::JOB_NAME, 'is_active' => true])
                ->one();
        if (empty($this->_job))
        {
            $this->_job = new Job();
            $this->_job->name = self::JOB_NAME;
            $this->_job->is_active = true;
            $this->_job->date = date('Y-m-d H:i:s');
            $this->_job->save(false);
            return false;
        }
        if (strtotime($this->_job->date) < time() - self::JOB_ACTIVITY)
            return false;
        return true;
    }
    
    private function updateFriends(UserAccountQueue $queue, UserAccount $account, OAuthRequest $client)
    {
        echo "Updating friends of {$queue->user->name}({$queue->user->id})...";
        // Load VK user ids
        $friendIds = [];
        $page = 0;
        while (true)
        {
            $info = $client->send('GET', 'https://api.vk.com/method/friends.get', ['user_id' => $account->source_id, 'count' => self::PAGE_SIZE, 'offset' => self::PAGE_SIZE * $page]);
            if (empty($info['response']))
                break;
            $friendIds = array_merge($friendIds, $info['response']);
            $page++;
        }
        echo count($friendIds) . " loaded...";
        // Find user ids who must be friends
        $accounts = UserAccount::find()
                ->where(['source' => 'vkontakte'])
                ->andWhere(['in', 'source_id', $friendIds])
                ->all();
        $ids = [];
        foreach ($accounts as $item)
            $ids = (string)$item->user_id;
        // Remove deleted friends
        $processed = [];
        $friends = UserFriend::findAll(['user_id' => $account->user->_id, 'is_vk' => true]);
        foreach ($friends as $friend)
            if (!in_array((string)$friend->friend_id, $ids))
            {
                // Remove friend from list
                if ($friend->is_vk)
                {
                    $friend->is_vk = false;
                    $friend->save(false);
                    $other = UserFriend::findOne(['user_id' => $friend->friend_id, 'friend_id' => $account->user_id]);
                    $other->is_vk = false;
                    $other->save(false);
                }
            }
            else
                $processed[] = (string)$friend->friend_id;
        // Add missing friends
        $toAdd = array_diff($ids, $processed);
        foreach ($toAdd as $id)
        {
            $friend = UserFriend::findOne(['user_id' => $account->user_id, 'friend_id' => new \MongoId($id)]);
            if (empty($friend))
            {
                $friend = new UserFriend();
                $friend->user_id = $account->user_id;
                $friend->friend_id = new \MongoId($id);
                $friend->is_vk = true;
                $friend->save(false);
                $other = new UserFriend();
                $other->user_id = $friend->friend_id;
                $other->friend_id = $account->user_id;
                $other->is_vk = true;
                $other->save(false);
            }
            elseif (!$friend->is_vk)
            {
                $friend->is_vk = true;
                $friend->save(false);
                $other = UserFriend::findOne(['user_id' => $friend->friend_id, 'friend_id' => $account->user_id]);
                $other->is_vk = true;
                $other->save(false);
            }
        }
        // Set next update time
        $queue->date = date('Y-m-d H:i:s', time() + Yii::$app->params['friendUpdateInterval']);
        $queue->save(false);
        echo "OK\n";
    }
}
