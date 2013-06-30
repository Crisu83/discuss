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
        return array_merge(parent::filters(), array(
            array('vendor.crisu83.yii-seo.filters.SeoFilter + view'),
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
        $reply = new Reply;
        $reply->threadId = $model->id;

        $this->performAjaxValidation($reply, ReplyController::FORM_ID);

        $request = request();
        if ($request->isPostRequest)
        {
            $reply->attributes = $request->getPost('Reply');
            if ($reply->save())
                $this->redirect($model->getUrl());
        }

        $replies = new CActiveDataProvider('Reply', array(
            'criteria' => $model->createRepliesCriteria(),
        ));

        Statistic::create(array(
            'action' => Statistic::ACTION_VIEW,
            'model' => get_class($model),
            'modelId' => $model->id,
            'ipAddress' => request()->getUserHostAddress(),
            'userId' => user()->id,
        ));

		$this->render('view', array(
			'model' => $model,
            'reply' => $reply,
            'replies' => $replies,
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
			if ($model->save())
				$this->redirect($model->room->getUrl(array('id' => $model->roomId)));
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
		$model = Thread::model()->findByPk($id);
		if ($model === null)
			$this->pageNotFound();
		return $model;
	}
}
