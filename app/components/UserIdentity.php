<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * @var integer the ID of the logged in user.
     */
    protected $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        /* @var APasswordBehavior $user */
        $user = User::model()->find(array(
            'condition' => 'name=LOWER(:name)', 'params' => array(':name' => strtolower($this->username)),
        ));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$user->verifyPassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
        }

        if ($this->errorCode === self::ERROR_NONE) {
            $this->_id = $user->id;
            $this->username = $user->name;
        }

        return !$this->errorCode;
	}

    /**
    * Returns the ID of the logged in user.
    * @return integer the id.
    */
    public function getId()
    {
        return $this->_id;
    }
}