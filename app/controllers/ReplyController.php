<?php

class ReplyController extends Controller
{
    const FORM_ID = 'replyForm';

	/**
	 * @return array attached behaviors.
	 */
	public function behaviors()
	{
		return array(
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
        return array_merge(parent::filters(), array(
        ));
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
            array('allow',
                'actions' => array('view', 'create'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('update', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
        $this->performAjaxValidation($model, self::FORM_ID);
        $request = request();
        if($request->isPostRequest)
        {
            $model->attributes = $request->getPost('Reply');
			if ($model->save())
				$this->redirect($model->getUrl());
		}
		$this->render('update', array('model'=>$model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Thread
	 */
	public function loadModel($id)
	{
		$model = Reply::model()->findByPk($id);
		if ($model === null)
			$this->pageNotFound();
		return $model;
	}
}
