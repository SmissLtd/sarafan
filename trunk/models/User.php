<?php

namespace app\models;

use \Yii;

/**
 * @property string $_id
 * @property string $auth_key Authorization key for autologin
 * @property boolean $is_deleted User deleted or not
 * @property string $code Registration code
 * @property string $name User name
 * @property string $email User e-mail
 * @property string $login User login
 * @property string $password Password hash
 * @property string $phone
 * @property string $city
 * @property string $country
 * @property string $role User role(user, admin)
 * @property boolean $is_blocked
 * @property string $blocker_id
 * @property string $block_date
 * @property string $block_till If empty - forever
 * @property string $block_reason
 * @property string $phone_clear
 * @property string $last_fb_update Last time facebook friends was updated
 * @property string $date_register
 * @property string $date_action
 * @property integer $complain_count How many complains was sent on all user answers
 * 
 * @property-read string $id
 * @property-read string $address City, Country
 * @property-read string $nameOrLogin Return user name if not empty, login otherwise
 * @property-read \app\models\User $blocker
 * @property-read \app\models\Account[] $accounts External(SN) accounts
 * @property-read \app\models\User $referrer User who gives code
 * @property-read \app\models\RequestAnswer[] $answers User answers on requests
 * @property-read \app\models\UserCode[] $codes
 * @property-read \app\models\UserCode[] $usedCodes
 */
class User extends \yii\mongodb\ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    
    const ACCOUNT_RESULT_REGISTER = 'register';
    const ACCOUNT_RESULT_LOGIN = 'login';
    const ACCOUNT_RESULT_ASSIGN = 'assign';
    const ACCOUNT_RESULT_REMOVE = 'remove';
    
    /**
     * @var string
     */
    public $password2;
    
    private $_referrer = false;
    
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->is_deleted = false;
        $this->is_blocked = false;
    }
    
    public function attributes()
    {
        return [
            '_id',
            'auth_key',
            'is_deleted',
            'code',
            'name',
            'email',
            'login',
            'password',
            'phone',
            'city',
            'country',
            'role',
            'is_blocked',
            'blocker_id',
            'block_date',
            'blocked_till',
            'block_reason',
            'phone_clear',
            'last_fb_update',
            'date_register',
            'date_action',
            'complain_count'
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'login', 'city', 'country'], 'purify'],
            [['code'], 'trim'],
            // Auth key
            [['auth_key'], 'required', 'except' => ['register'], 'message' => Yii::t('app', 'Аутентификационный ключ обязателен')],
            [['auth_key'], 'string', 'length' => 64, 'message' => Yii::t('app', 'Слишком длинный аутентификационный ключ. Максимум 64 символа')],
            // Is deleted
            [['is_deleted'], 'boolean', 'message' => Yii::t('app', 'Неверное значение для признака удаленности')],
            // Code
            [['code'], 'required', 'message' => Yii::t('app', 'Код обязателен')],
            [['code'], 'exist', 'targetClass' => \app\models\UserCode::className(), 'targetAttribute' => 'code', 'filter' => ['is_used' => false, 'is_deleted' => false], 'message' => Yii::t('app', 'Код не найден или уже использовался')],
            // Name
            [['name'], 'required', 'message' => Yii::t('app', 'Поле Имя / Фамилия* обязательно нужно заполнить')],
            [['name'], 'string', 'max' => 50, 'tooLong' => Yii::t('app', 'Слишком длинное Имя. Максимум 50 символов')],
            [['name'], 'match', 'skipOnError' => false, 'pattern' => '/^[A-Za-zА-Яа-я \-.]*$/iu', 'message' => Yii::t('app', 'Для имени можно использовать только следующие символы: "A-Z", "a-z", "А-Я", "а-я", "-", ".", пробел')],
            // E-mail
            [['email'], 'required', 'message' => Yii::t('app', 'Поле E-mail* обязателено нужно заполнить')],
            [['email'], 'email', 'message' => Yii::t('app', 'Неверный формат. Введите E-mail в формате xxx@xxx.xx')],
            [['email'], 'unique', 'filter' => ['is_deleted' => false], 'message' => Yii::t('app', 'Пользователь с таким Email уже существует')],
            // Login
            [['login'], 'required', 'message' => Yii::t('app', 'Поле Придумайте логин* обязательно нужно заполнить')],
            [['login'], 'string', 'max' => 50, 'tooLong' => Yii::t('app', 'Логин слишком длинный. Максимум 50 символа')],
            [['login'], 'match', 'skipOnError' => false, 'pattern' => '/^[A-Za-z0-9]*$/i', 'message' => Yii::t('app', 'Для логина можно использовать только следующие символы: "A-Z", "a-z", "0-9"')],
            [['login'], 'unique', 'filter' => ['is_deleted' => false], 'message' => Yii::t('app', 'Пользователь с таким логином уже существует')],
            // Password
            [['password'], 'required', 'on' => ['register'], 'message' => Yii::t('app', 'Пароль обязателен')],
            [['password'], 'string', 'on' => ['register'], 'min' => 6, 'tooShort' => Yii::t('app', 'Минимальная длина пароля - 6 символов')],
            [['password'], 'safe', 'except' => ['register']],
            // Password2
            [['password2'], 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'when' => function($model) {return !empty($model->password);}, 'message' => Yii::t('app', 'Пароли не совпадают')],
            // Phone
            [['phone'], 'match', 'pattern' => '/^\(?([0-9]{3})\)?([ .-]?)([0-9]{3})([ .-]?)([0-9]{2})([ .-]?)([0-9]{2})$/i', 'message' => Yii::t('app', 'Неверный формат. Введите телефон в формате (xxx) xxx-xx-xx')],
            // Phone clear
            [['phone_clear'], 'unique', 'filter' => ['is_deleted' => false], 'message' => Yii::t('app', 'Этот номер телефона уже используется другим пользователем. Пожалуйста, введите другой номер.')],
            // City
            [['city'], 'string', 'max' => 128, 'tooLong' => Yii::t('app', 'Слишком длинное название города. Максимум 128 символов')],
            // Country
            [['country'], 'string', 'max' => 128, 'tooLong' => Yii::t('app', 'Слишком длинное название страны. Максимум 128 символов')],
            // Role
            [['role'], 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_USER], 'message' => Yii::t('app', 'Недопустимая роль')]
        ];
    }
    
    public function purify($attribute, $params)
    {
        $this->$attribute = htmlspecialchars($this->$attribute, ENT_NOQUOTES, ini_get('default_charset'), false);
    }
    
    public static function findIdentity($id)
    {
        return static::findOne(['_id' => $id, 'is_deleted' => false]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new \yii\base\NotSupportedException(Yii::t('app', 'Метод "findIdentityByAccessToken()" не реализован'));
    }

    public static function findByLogin($login)
    {
        $users = static::find()->where(['and', ['like', 'login', strtolower($login)], ['is_deleted' => false]])->all();
        foreach ($users as $user)
            if (strtolower($user->login) == strtolower($login))
                return $user;
        return null;
    }

    public function getId()
    {
        return (string)$this->_id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function getAddress()
    {
        $parts = [];
        if (!empty($this->city))
            $parts[] = $this->city;
        if (!empty($this->country))
            $parts[] = $this->country;
        return implode(', ', $parts);
    }
    
    public function getNameOrLogin()
    {
        if (!empty($this->name))
            return $this->name;
        return $this->login;
    }
    
    public function getBlocker()
    {
        return $this->hasOne(User::className(), ['_id' => 'blocker_id']);
    }
    
    public function getAccounts()
    {
        return $this->hasMany(UserAccount::className(), ['user_id' => '_id']);
    }
    
    public static function AccountRegister($client, $attributes)
    {
        // Check email exists and it is already exists in db
        if (!empty($attributes['email']))
        {
            if (User::findOne(['email' => $attributes['email'], 'is_deleted' => false]))
                return Yii::t('app', 'Пользователь с таким E-mail уже существует в системе');
        }
        $code = UserCode::findOne(['code' => Yii::$app->session->get('code', ''), 'is_deleted' => false]);
        if (empty($code))
            return Yii::t('app', 'Регистрационный код не найден');
        // Register new user
        $user = new User();
        $user->auth_key = Yii::$app->security->generateRandomString(64);
        $user->is_deleted = false;
        $user->code = $code->code;
        $user->name = trim((empty($attributes['last_name']) ? '' : $attributes['last_name']) . ' ' . (empty($attributes['first_name']) ? '' : $attributes['first_name']));
        $user->email = empty($attributes['email']) ? '' : $attributes['email'];
        $user->login = '';
        $user->password = '';
        $user->phone = '';
        $user->city = '';
        $user->country = '';
        $user->role = User::ROLE_USER;
        $user->is_blocked = false;
        $user->blocker_id = null;
        $user->block_date = null;
        $user->blocked_till = null;
        $user->block_reason = '';
        $user->phone_clear = '';
        $user->save(false);
        $user->login = $user->id;
        $user->setPassword($user->id);
        $user->date_register = date('Y-m-d H:i:s');
        $user->date_action = date('Y-m-d H:i:s');
        $user->save(false);
        // Set code as used
        $code->is_used = true;
        $code->used_user_id = $user->_id;
        $code->date_used = date('Y-m-d H:i:s');
        $code->save(false);
        // Create friends
        $friend = new UserFriend();
        $friend->user_id = $user->_id;
        $friend->friend_id = $code->user_id;
        $friend->is_code = true;
        $friend->save(false);
        $friend = new UserFriend();
        $friend->user_id = $code->user_id;
        $friend->friend_id = $user->_id;
        $friend->is_code = true;
        $friend->save(false);
        // Create account
        $account = new UserAccount();
        $account->user_id = $user->_id;
        $account->source = $client->getId();
        $account->source_id = $attributes['id'];
        $account->save(false);
        // Create queue
        $queue = new UserAccountQueue();
        $queue->user_id = $user->_id;
        $queue->source = $client->getId();
        $queue->date = date('Y-m-d H:i:s');
        $queue->save(false);
        // Update facebook friends
        if ($client->getId() == 'facebook')
            self::UpdateFacebookFriends($client, $user);
        // Login user
        Yii::$app->user->login($user, 0);
        return self::ACCOUNT_RESULT_REGISTER;
    }
    
    public static function AccountLogin($client, UserAccount $account)
    {
        if ($account->user->is_blocked && (empty($account->user->block_till) || strtotime($account->user->block_till) >= time()))
            return Yii::t('app', 'Ваш аккаунт заблокирован {date} за "{reason}". Заблокировал {blocker}. Окончание блокировки {till}', [
                'date' => Yii::$app->formatter->asDatetime($account->user->block_date, 'medium'),
                'reason' => empty($account->user->reason) ? Yii::t('app', 'неизвестно') : $account->user->block_reason,
                'blocker' => $account->user->blocker ? $account->user->blocker->name : Yii::t('app', 'неизвестный администратор'),
                'till' => empty($account->user->block_till) ? Yii::t('app', 'никогда') : Yii::$app->formatter->asDatetime($account->user->block_till, 'medium')
            ]);
        // Update facebook friends
        if ($client->getId() == 'facebook')
            self::UpdateFacebookFriends($client, $account->user);
        Yii::$app->user->login($account->user, 0);
        return self::ACCOUNT_RESULT_LOGIN;
    }
    
    public static function AccountAssign($client, $attributes)
    {
        $account = UserAccount::findOne(['source' => $client->getId(), 'source_id' => $attributes['id']]);
        if (!empty($account))
        {
            switch ($client->getId())
            {
                case 'facebook':
                    $client->api('/me/permissions', 'DELETE');
                    break;
                case 'vkontakte':
                    // Do nothing right now. It looks like VK API have no method for App uninstall
                    break;
            }
            $account->delete();
            return self::ACCOUNT_RESULT_REMOVE;
        }
        // Create account
        $account = new UserAccount();
        $account->user_id = Yii::$app->user->identity->_id;
        $account->source = $client->getId();
        $account->source_id = $attributes['id'];
        $account->save(false);
        // Create queue
        $queue = new UserAccountQueue();
        $queue->user_id = Yii::$app->user->identity->_id;
        $queue->source = $client->getId();
        $queue->date = date('Y-m-d H:i:s');
        $queue->save(false);
        // Update facebook friends
        if ($client->getId() == 'facebook')
            self::UpdateFacebookFriends($client, $account->user);
        return self::ACCOUNT_RESULT_ASSIGN;
    }
    
    public function isAccountExists($clientId)
    {
        foreach ($this->accounts as $account)
            if ($account->source == $clientId)
                return true;
        return false;
    }
    
    private static function UpdateFacebookFriends(\yii\authclient\clients\Facebook $client, User $user)
    {
        // Check last update time
        if (!empty($user->last_fb_update) && strtotime($user->last_fb_update) < time() - Yii::$app->params['friendUpdateInterval'])
            return;
        // Update list
        $info = $client->api('/me/friends');
        $friendIds = [];
        if (!empty($info['data']))
            foreach ($info['data'] as $infoUser)
                $friendIds[] = $infoUser['id'];
        // Find user ids who must be friends
        $accounts = UserAccount::find()
                ->where(['source' => 'facebook'])
                ->andWhere(['in', 'source_id', $friendIds])
                ->all();
        $ids = [];
        foreach ($accounts as $item)
            $ids = (string)$item->user_id;
        // Remove deleted friends
        $processed = [];
        $friends = UserFriend::findAll(['user_id' => $user->_id, 'is_fb' => true]);
        foreach ($friends as $friend)
            if (!in_array((string)$friend->friend_id, $ids))
            {
                // Remove friend from list
                if ($friend->is_fb)
                {
                    $friend->is_fb = false;
                    $friend->save(false);
                    $other = UserFriend::findOne(['user_id' => $friend->friend_id, 'friend_id' => $user->_id]);
                    $other->is_fb = false;
                    $other->save(false);
                }
            }
            else
                $processed[] = (string)$friend->friend_id;
        // Add missing friends
        $toAdd = array_diff($ids, $processed);
        foreach ($toAdd as $id)
        {
            $friend = UserFriend::findOne(['user_id' => $user->_id, 'friend_id' => new \MongoId($id)]);
            if (empty($friend))
            {
                $friend = new UserFriend();
                $friend->user_id = $user->_id;
                $friend->friend_id = new \MongoId($id);
                $friend->is_fb = true;
                $friend->save(false);
                $other = new UserFriend();
                $other->user_id = $friend->friend_id;
                $other->friend_id = $user->_id;
                $other->is_fb = true;
                $other->save(false);
            }
            elseif (!$friend->is_fb)
            {
                $friend->is_fb = true;
                $friend->save(false);
                $other = UserFriend::findOne(['user_id' => $friend->friend_id, 'friend_id' => $user->_id]);
                $other->is_fb = true;
                $other->save(false);
            }
        }
        // Set update time
        $user->last_fb_update = date('Y-m-d H:i:s');
        $user->save(false);
    }
    
    public function getReferrer()
    {
        if ($this->_referrer === false)
        {
            if (empty($this->code))
                $this->_referrer = null;
            else
            {
                $code = UserCode::findOne(['used_user_id' => $this->_id]);
                if (empty($code))
                    $this->_referrer = null;
                else
                    $this->_referrer = $code->user;
            }
        }
        return $this->_referrer;
    }
    
    public function getAnswers()
    {
        return $this->hasMany(RequestAnswer::className(), ['user_id' => '_id']);
    }
    
    public static function translate($const)
    {
        switch ($const)
        {
            case self::ROLE_ADMIN:
                return Yii::t('app', 'Администратор');
            case self::ROLE_USER:
                return Yii::t('app', 'Пользователь');
        }
        return $const;
    }
    
    public function getCodes()
    {
        return $this
                ->hasMany(UserCode::className(), ['user_id' => '_id'])
                ->where(['is_deleted' => false]);
    }
    
    public function getUsedCodes()
    {
        return $this
                ->hasMany(UserCode::className(), ['user_id' => '_id'])
            ->where(['is_deleted' => false, 'is_used' => true]);
    }
}