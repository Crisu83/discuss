<?php

Yii::import('vendor.crisu83.yii-extension.behaviors.WidgetBehavior');

class SCEditor extends CInputWidget
{
    /**
     * @var string the active theme.
     */
    public $theme = 'default';
    /**
     * @var string the enabled plugins.
     */
    public $plugin = 'bbcode';
    /**
     * @var string the active language.
     */
    public $language;
    /**
     * @var string the url to the CSS file to include when editing.
     */
    public $cssFile;
    /**
     * @var array the initial JavaScript options that should be passed to the plugin.
     */
    public $options = array();
    /**
     * @var boolean indicates whether to republish the widget assets every time.
     */
    public $forceCopyAssets = true;
    /**
     * @var boolean indicates whether the include the minified js and css files.
     */
    public $debug = true; // don't change before we have minified the script

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->attachBehavior('widget', new WidgetBehavior);
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        list($name, $id) = $this->resolveNameID();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->hasModel())
            echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        else
            echo CHtml::textArea($name, $this->value, $this->htmlOptions);

        $assetsUrl = $this->publishAssets(__DIR__ . '/../assets', $this->forceCopyAssets);

        /* @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        if (isset($this->theme))
            $cs->registerCssFile($assetsUrl . '/css/themes/' . $this->_resolveScriptVersion($this->theme . '.css'));
        $cs->registerScriptFile($assetsUrl . '/js/' . $this->_resolveScriptVersion('jquery.sceditor.bbcode.js'), CClientScript::POS_END);
        if (isset($this->language))
        {
            $cs->registerScriptFile($assetsUrl . '/js/languages/' . $this->language . '.js', CClientScript::POS_END);
            $this->options['locale'] = $this->language;
        }
        if (isset($this->cssFile))
            $this->options['style'] = $this->cssFile;
        $this->options['plugins'] = $this->plugin;
        $this->options['width'] = '100%';
        $this->options['height'] = '200px';
        $this->options['emoticonsRoot'] = $assetsUrl . '/';
        $this->options['commands'] = array(
            'bold' => array('icon' => 'bold'),
            'italic' => array('icon' => 'italic'),
            'underline' => array('icon' => 'underline'),
            'link' => array('icon' => 'link'),
            'quote' => array('icon' => 'quote-right'),
            'emoticon' => array('icon' => 'smile'),
            'source' => array('icon' => 'code'),
        );
        $options = !empty($this->options) ? CJavaScript::encode($this->options) : '';
        $cs->registerScript(__CLASS__ . '#' . $id, "$('#{$id}').sceditor($options);");
    }

    protected function _resolveScriptVersion($filename)
    {
        return $this->resolveScriptVersion($filename, !$this->debug);
    }
}