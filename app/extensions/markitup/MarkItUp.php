<?php
/**
 * MarkItUp class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * MarkItUp widget wraps the textarea jQuery widget.
 */
class MarkItUp extends CInputWidget
{
	/**
	 * @property string the mark it up set.
	 */
	public $set='default';
	/**
	 * @property string the mark it up skin.
	 */
	public $skin='simple';
	/**
	 * @property array mark it up settings.
	 */
	public $settings=array();

	/**
	* Initialization.
	*/
	public function init()
	{
		$app = Yii::app();

		$identifiers=$this->resolveNameID();

		if ($this->name===null)
			$this->name=$identifiers[0];
		
		$this->setId($identifiers[1]);

		// Publish the extension's assets folder
		$assetPath=$app->assetManager->publish(dirname(__FILE__).'/assets', false, -1, true);

		// Register the necessary scripts
		$app->clientScript->registerCoreScript('jquery');
		$app->clientScript->registerScriptFile($assetPath.'/markitup/jquery.markitup.js');
		//$app->clientScript->registerScriptFile($assetPath.'/markitup/sets/'.$this->set.'/set.js');
		$app->clientScript->registerCssFile($assetPath.'/markitup/sets/'.$this->set.'/style.css');
		$app->clientScript->registerCssFile($assetPath.'/markitup/skins/'.$this->skin.'/style.css');

		if (!isset($this->settings['nameSpace']))
			$this->settings['nameSpace'] = 'markitup';

		// Build the script for registering the bbcode plugin
		$script = "jQuery('#".$this->id."').markItUp(".CJSON::encode($this->settings).");";

		// Place the editor on the page with the given config
		$app->clientScript->registerScript('MarkItUp#'.$this->id,$script,CClientScript::POS_READY);
	}

	/**
	* Runs the widget.
	*/
	public function run()
	{
		echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
	}
}