<?php

class FbLike extends CWidget
{
    // Like button layouts.
    const LAYOUT_STANDARD = '';
    const LAYOUT_BUTTON_COUNT = 'button_count';
    const LAYOUT_BOX_COUNT = 'box_count';

    // Like button fonts.
    const FONT_ARIAL = '';
    const FONT_LUCIAGRANDE = 'lucia grande';
    const FONT_SEGOEUI = 'segoe ui';
    const FONT_TAHOMA = 'tahoma';
    const FONT_TREBUCHETMS = 'trebuchet ms';
    const FONT_VERDANA = 'verdana';

    // Like button colorschemes.
    const COLORSCHEME_LIGHT = '';
    const COLORSCHEME_DARK = 'dark';

    // Like button actions.
    const ACTION_LIKE = '';
    const ACTION_RECOMMEND = 'recommend';

    /**
     * @var string the URL to like.
     */
    public $url;
    /**
     * @var boolean whether to show the 'send button'.
     */
    public $send = false;
    /**
     * @var string the layout.
     */
    public $layout = self::LAYOUT_STANDARD;
    /**
     * @var integer the iframe widget.
     */
    public $width = 450;
    /**
     * @var boolean whether to show faces.
     */
    public $showFaces = false;
    /**
     * @var string the font to use.
     */
    public $font = self::FONT_ARIAL;
    /**
     * @var string the colorsheme to use.
     */
    public $colorscheme = self::COLORSCHEME_LIGHT;
    /**
     * @var string the action.
     */
    public $action = self::ACTION_LIKE;
    /**
     * @var array additional HTML attributes.
     */
    public $htmlOptions = array();

    /**
     * Initializes the widget.
     */
    public function init()
    {
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] .= ' fb-like';
        else
            $this->htmlOptions['class'] = ' fb-like';
        $this->htmlOptions['url'] = $this->url;
        if ($this->send)
            $this->htmlOptions['data-send'] = 'true';
        if (!empty($this->layout))
            $this->htmlOptions['data-layout'] = $this->layout;
        $this->htmlOptions['data-width'] = $this->width;
        $this->htmlOptions['data-show-faces'] = $this->showFaces ? 'true' : 'false';
        if (!empty($this->font))
            $this->htmlOptions['data-font'] = $this->font;
        if (!empty($this->colorscheme))
            $this->htmlOptions['data-colorscheme'] = $this->colorscheme;
        if (!empty($this->action))
            $this->htmlOptions['data-action'] = $this->action;
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        echo CHtml::tag('div', $this->htmlOptions);
    }
}