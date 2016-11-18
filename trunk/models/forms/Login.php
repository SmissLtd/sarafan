<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Login is the model behind the login form.
 * 
 * @property-read \app\models\User $user
 */
class Login extends \yii\base\Model
{
    public $login;
    public $password;
    public $remember = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // Login
            [['login'], 'required', 'message' => Yii::t('app', 'Нужно ввести логин')],
            [['login'], 'validateLogin'],
            // Password
            [['password'], 'required', 'message' => Yii::t('app', 'Нужно ввести пароль')],
            [['password'], 'validatePassword'],
            // Remember
            [['remember'], 'boolean', 'message' => Yii::t('app', '"Запомнить меня" должно быть будевым значением')],
        ];
    }
    
    public function validateLogin($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();
            if (!$user)
                $this->addError('login', Yii::t('app', 'Пользователь с таким логином не найден'));
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            if (!$this->user->validatePassword($this->password))
                $this->addError($attribute, Yii::t('app', 'Неверный пароль'));
        }
    }

    /**
     * Finds user by login
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false)
            $this->_user = \app\models\User::findByLogin($this->login);
        return $this->_user;
    }
}
