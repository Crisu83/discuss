<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping user login form data.
 * It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('password', 'authenticate'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity = new UserIdentity($this->username,$this->password);
			if (!$this->_identity->authenticate())
				$this->addError('password', t('error', 'Väärä käyttäjänimi tai salasana.'));
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
            $user = user();
			$user->login($this->_identity);
            $user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, t('flash', 'Olet nyt kirjautunut {name} käyttäjänä.', array(
                '{name}' => '<b>' . $user->name . '</b>',
            )));
			return true;
		}
		else
			return false;
	}
}
