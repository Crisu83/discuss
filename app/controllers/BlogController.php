<?php

class BlogController extends Controller
{
    const FORM_ID = 'blogForm';

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
            FeaturedBlog::model()->updateWeights($_POST['data'], FeaturedBlog::model()->findAll());
        Yii::app()->end();
    }

    /**
     * Displays a list of blogs.
     */
    public function actionList()
    {
        $criteria = new CDbCriteria();
        $criteria = FeaturedBlog::model()->applyWeightCriteria($criteria);
        $blogs = new CActiveDataProvider('FeaturedBlog', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $this->render('list', array(
            'blogs' => $blogs,
        ));
    }

    /**
     * Displays the description page for a blog.
     * @param integer $id the model id.
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        Statistic::create(array(
            'action' => Statistic::ACTION_VIEW,
            'model' => get_class($model),
            'modelId' => $model->id,
            'ipAddress' => request()->getUserHostAddress(),
            'userId' => user()->id,
        ));

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     */
    public function actionCreate()
    {
        $model = new FeaturedBlog();
        $this->performAjaxValidation($model, self::FORM_ID);
        $request = request();
        if ($request->isPostRequest)
        {
            $upload = CUploadedFile::getInstance($model, 'upload');
            iF ($upload !== null)
                $model->saveImage($upload, $model->name, 'featuredBlog');
            $model->attributes = $request->getPost('FeaturedBlog');
            if ($model->save())
                $this->redirect(array('list'));
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
            $upload = CUploadedFile::getInstance($model, 'upload');
            iF ($upload !== null)
                $model->saveImage($upload, $model->name, 'featuredBlog');
            $model->attributes = $request->getPost('FeaturedBlog');
            if ($model->save())
                $this->redirect(array('list'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via list grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('list'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $id the ID of the model to be loaded
     * @return FeaturedBlog the loaded model.
     * @throws CHttpException if the model is not found.
     */
    public function loadModel($id)
    {
        $model = FeaturedBlog::model()->findByPk($id);
        if ($model === null)
            $this->pageNotFound();
        return $model;
    }
}