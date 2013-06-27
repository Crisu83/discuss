<?php

class ThreadController extends Controller
{
    const FORM_ID = 'threadForm';

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
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array('vendor.crisu83.yii-seo.filters.SeoFilter + view'),
			'accessControl',
		);
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
				'actions'=>array('view','create','captcha'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('update','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);

        Statistic::create(array(
            'action' => Statistic::ACTION_VIEW,
            'model' => get_class($model),
            'modelId' => $model->id,
            'creatorId' => user()->id,
        ));

		$this->render('view', array(
			'model' => $model,
		));
	}

	/**
	 * Creates a new model.
     * @property integer $roomId the id for the room that the thread belongs to.
	 */
	public function actionCreate($roomId)
	{
		$model = new Thread();
        $model->roomId = $roomId;
		$this->performAjaxValidation($model, self::FORM_ID);
        $request = request();
		if($request->isPostRequest)
		{
			$model->attributes = $request->getPost('Thread');
			$model->body = app()->bbcodeParser->parse($model->body);
			if ($model->save())
			{
                user()->setFlash(TbHtml::ALERT_COLOR_SUCCESS, t('roomFlash', 'Thread for {subject} created.', array(
                    '{subject}' => '<b>' . $model->subject . '</b>',
                )));
				$this->redirect($model->room->getUrl(array('id' => $model->roomId)));
			}
		}

		$this->render('create', array('model' => $model));
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
            $model->attributes = $request->getPost('Thread');
            $model->body = app()->bbcodeParser->parse($model->body);
			if ($model->save())
			{
                user()->setFlash(TbHtml::ALERT_COLOR_SUCCESS, t('roomFlash', 'Thread {subject} updated.', array(
                    '{subject}' => '<b>' . $model->subject . '</b>',
                )));
				$this->redirect($model->getUrl());
			}
		}
		$this->render('update', array('model'=>$model));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if (Yii::app()->user->isAdmin)
		{
			Yii::app()->user->setFlash(WebUser::FLASH_SUCCESS,t('flash','Topic deleted.'));
			$model->delete();
			$this->redirect(array('//discuss'));
		}
		else
			throw new CHttpException(404,t('core','Access denied.'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Thread
	 */
	public function loadModel($id)
	{
		$model = Thread::model()->findByPk($id);
		if ($model === null)
			$this->pageNotFound();
		return $model;
	}
}
