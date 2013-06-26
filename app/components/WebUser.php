<?php

class WebUser extends CWebUser
{
    protected $_model;

    public function init()
    {
        parent::init();
        $this->attachBehavior('auditChanger', 'AuditChanger');
        $this->updateLastActiveAt();
    }

    /**
     * Loads the user model for the logged in user.
     * @return User the model.
     */
    public function loadModel()
    {
        if (isset($this->_model))
            return $this->_model;
        else
        {
            if ($this->isGuest)
                return null;
            return $this->_model = User::model()->findByPk($this->id);
        }
    }

    /**
     * Updates the users last active at field.
     * @return boolean whether the update was successful.
     */
    public function updateLastActiveAt()
    {
        if (!$this->isGuest)
        {
            $model = $this->loadModel();
            $model->lastActiveAt = sqlDateTime();
            return $model->save(true, array('lastActiveAt'));
        }
        return false;
    }

    /**
     * Updates the users last login at field.
     * @return boolean whether the update was successful.
     */
    public function updateLastLoginAt()
    {
        if (!$this->isGuest)
        {
            $model = $this->loadModel();
            $model->lastLoginAt = sqlDateTime();
            return $model->save(true, array('lastLoginAt'));
        }
        return false;
    }
}