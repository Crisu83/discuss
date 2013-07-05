<?php

class SiteController extends Controller
{
    const FORM_ID_LOGIN = 'loginForm';

    /**
     * @return array attached behaviors.
     */
    public function behaviors()
    {
        return array(
            'seo' => array(
                'class' => 'vendor.crisu83.yii-seo.behaviors.SeoBehavior',
            ),
        );
    }

    /**
     * Displays the front page.
     */
    public function actionIndex()
    {
        $blogCriteria = new CDbCriteria();
        $blogCriteria = Blog::model()->applyWeightCriteria($blogCriteria);
        $blogCriteria->limit = 6;
        $blogs = new CActiveDataProvider('Blog', array(
            'criteria' => $blogCriteria,
            'pagination' => array(
                'pageSize' => 6,
            ),
        ));

        $threadCriteria = new CDbCriteria();
        $threadCriteria->order = 'lastActivityAt DESC';
        $threads = new CActiveDataProvider('Thread', array(
            'criteria' => $threadCriteria,
            'pagination' => array(
                'pageSize' => 5,
            ),
        ));

        $this->render('index', array(
            'blogs' => $blogs,
            'threads' => $threads,
        ));
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = app()->errorHandler->error)
		{
			if (request()->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page.
	 */
	public function actionLogin()
	{
		$this->layout = 'minimal';
		$model = new LoginForm;
        $this->performAjaxValidation($model, self::FORM_ID_LOGIN);
        $request = request();
		if ($request->isPostRequest)
		{
			$model->attributes = $request->getPost('LoginForm');
			if ($model->validate() && $model->login())
            {
                /* @var $user WebUser */
                $user = user();
                $user->updateLastLoginAt();
   				$this->redirect($user->returnUrl);
            }
		}
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		user()->logout();
		$this->redirect(app()->homeUrl);
	}
}