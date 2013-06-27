<?php

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
    public $debug = YII_DEBUG;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->attachBehavior('widget', array(
            'class' => 'WidgetBehavior',
            'forceCopyAssets' => $this->forceCopyAssets,
            'debug' => $this->debug,
        ));
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

        $assetsUrl = $this->getAssetsUrl(__DIR__ . '/../assets');

        /* @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        if (isset($this->theme))
            $cs->registerCssFile($assetsUrl . '/css/themes/' . $this->resolveScriptVersion($this->theme . '.css'));
        $cs->registerScriptFile($assetsUrl . '/js/' . $this->resolveScriptVersion('jquery.sceditor.js'), CClientScript::POS_END);
        $cs->registerScriptFile($assetsUrl . '/js/plugins/' . $this->resolveScriptVersion($this->plugin . '.js'), CClientScript::POS_END);
        $cs->registerScriptFile($assetsUrl . '/js/' . $this->resolveScriptVersion('jquery.sceditor.bbcode.js'), CClientScript::POS_END);
        if (isset($this->language))
        {
            $cs->registerScriptFile($assetsUrl . '/js/languages/' . $this->resolveScriptVersion($this->language . '.js'), CClientScript::POS_END);
            $this->options['locale'] = $this->language;
        }
        if (isset($this->cssFile))
            $this->options['style'] = $this->cssFile;
        $this->options['plugins'] = $this->plugin;
        $this->options['width'] = '100%';
        $this->options['height'] = '200px';
        $this->options['emoticonsCompat'] = true;
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
        $cs->registerScript(__CLASS__ . '#' . $id, "(function($) {
            var textarea = $('#{$id}');
            textarea.sceditor($options);
            var instance = textarea.sceditor('instance');
            var html = textarea.text().replace(/<img alt=\"(.*?)\".*?>/, \"$1\");
            instance.val(instance.toBBCode(html));
        })(jQuery);");
    }
}