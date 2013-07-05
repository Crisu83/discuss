<?php

class BlogController extends Controller
{
    const FORM_ID = 'blogForm';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
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
            Blog::model()->updateWeights($_POST['data'], Blog::model()->findAll());
        Yii::app()->end();
    }

    /**
     * Displays a admin of blogs.
     */
    public function actionAdmin()
    {
        $criteria = new CDbCriteria();
        $criteria = Blog::model()->applyWeightCriteria($criteria);
        $blogs = new CActiveDataProvider('Blog', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $this->render('admin', array(
            'blogs' => $blogs,
        ));
    }

    /**
     * Creates a new model.
     */
    public function actionCreate()
    {
        $model = new Blog();
        $this->performAjaxValidation($model, self::FORM_ID);
        $request = request();
        if ($request->isPostRequest)
        {
            $model->attributes = $request->getPost('Blog');
            if ($model->save())
                $this->redirect(array('admin'));
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
            $model->attributes = $request->getPost('Blog');
            if ($model->save())
                $this->redirect(array('admin'));
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $id the ID of the model to be loaded
     * @return Blog the loaded model.
     * @throws CHttpException if the model is not found.
     */
    public function loadModel($id)
    {
        $model = Blog::model()->findByPk($id);
        if ($model === null)
            $this->pageNotFound();
        return $model;
    }
}