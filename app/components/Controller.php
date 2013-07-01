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
     * Triggers a 404 (Page Not Found) error.
     * @throws CHttpException when invoked.
     */
    public function pageNotFound()
    {
        throw new CHttpException(404, t('error', 'Sivua ei löytynyt.'));
    }

    /**
     * Triggers a 403 (Access Denied) error.
     * @throws CHttpException when invoked.
     */
    public function accessDenied()
    {
        throw new CHttpException(403, t('error', 'Pääsy kielletty.'));
    }

    /**
     * Triggers a 400 (Bad Request) error.
     * @throws CHttpException when invoked.
     */
    public function badRequest()
    {
        throw new CHttpException(400, t('error', 'Virheellinen pyyntö.'));
    }
}