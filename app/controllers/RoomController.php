<?php

class RoomController extends Controller
{
    const FORM_ID = 'roomForm';

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
            'accessControl', // perform access control for CRUD operations
            'postOnly + ajaxSort',
        );
    }

    /**
     * Action for updating item weights.
     */
    public function actionAjaxSort()
    {
        if (isset($_POST['data']))
            Room::model()->updateWeights($_POST['data'], Room::model()->findAll());
        Yii::app()->end();
    }

    /**
     * Displays the discussion main page.
     */
    public function actionList()
    {
        $criteria = new CDbCriteria();
        $criteria = Room::model()->applyWeightCriteria($criteria);
        $rooms = new CActiveDataProvider('Room', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $this->render('list', array(
            'rooms' => $rooms,
        ));
    }

    /**
     * Displays a single room.
     * @param integer $id the model id.
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->render('view', array(
            'model' => $model,
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
                $this->redirect(array('view', 'id' => $model->id));
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
                $this->redirect(array('view', 'id' => $model->id));
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('list'));
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