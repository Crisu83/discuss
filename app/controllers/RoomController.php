<?php

class RoomController extends Controller
{
    const FORM_ID = 'roomForm';

    public $defaultAction = 'list';

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
     * Displays a list of all discussion rooms.
     */
    public function actionList()
    {
        $rooms = new CActiveDataProvider('Room', array(
            // todo: add this.
        ));

        $this->render('list', array(
            'rooms' => $rooms,
        ));
    }

    /**
     * Creates a new model.
     */
    public function actionCreate()
    {
        $model = new Room();
        $this->performAjaxValidation($model, self::FORM_ID);
        $request = request();
        if ($request->isPostRequest)
        {
            $model->attributes = $request->getPost('Room');
            if ($model->save())
            {
                user()->setFlash(TbHtml::ALERT_COLOR_SUCCESS, t('roomFlash', 'Room {title} created.', array(
                    '{title}' => '<b>' . $model->title . '</b>',
                )));
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated.
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model, self::FORM_ID);
        $request = request();
        if ($request->isPostRequest)
        {
            $model->attributes = $request->getPost('Room');
            if ($model->save())
            {
                user()->setFlash(TbHtml::ALERT_COLOR_SUCCESS, t('roomFlash', 'Room {title} updated.', array(
                    '{title}' => '<b>' . $model->title . '</b>',
                )));
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $id the ID of the model to be loaded
     * @return Room the loaded model.
     * @throws CHttpException if the model is not found.
     */
    public function loadModel($id)
    {
        $model = Room::model()->findByPk($id);
        if ($model === null)
            $this->pageNotFound();
        return $model;
    }
}