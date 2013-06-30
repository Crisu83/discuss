<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class Controller extends CController
{
	/**
	 * @var array context menu items.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs for the current page.
	 */
	public $breadcrumbs = array();
    /**
     * @var string the back button.
     */
    public $backButton;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $id the ID of the model to be loaded
     * @return CActiveRecord the loaded model.
     */
    abstract public function loadModel($id);

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Performs ajax validation on the given model.
     * @param CModel $model the model to validate.
     * @param string $formId the form id.
     */
    public function performAjaxValidation($model, $formId)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $formId)
        {
            if (!is_array($model))
                $model = array($model);
            foreach ($model as $m)
                echo CActiveForm::validate($m);
            app()->end();
        }
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
     * Triggers a 404 (Page Not Found) error.
     * @throws CHttpException when invoked.
     */
    public function pageNotFound()
    {
        throw new CHttpException(404, t('error', 'Page not found.'));
    }

    /**
     * Triggers a 403 (Access Denied) error.
     * @throws CHttpException when invoked.
     */
    public function accessDenied()
    {
        throw new CHttpException(403, t('error', 'Access denied.'));
    }

    /**
     * Triggers a 400 (Bad Request) error.
     * @throws CHttpException when invoked.
     */
    public function badRequest()
    {
        throw new CHttpException(400, t('error', 'Bad request.'));
    }
}