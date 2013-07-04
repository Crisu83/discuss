<?php

// todo: comment
class FbLikeBox extends CWidget
{
    const COLORSCHEME_LIGHT = '';
    const COLORSCHEME_DARK = '';

    public $url;
    public $width = 292;
    public $height;
    public $showFaces = true;
    public $colorscheme;
    public $showStream = true;
    public $showBorder = true;
    public $showHeader = true;
    public $htmlOptions = array();

    public function init()
    {
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] .= ' fb-like-box';
        else
            $this->htmlOptions['class'] = ' fb-like-box';
        $this->htmlOptions['href'] = $this->url;
        $this->htmlOptions['data-width'] = $this->width;
        if (!empty($this->height))
        if (!empty($this->height))
            $this->htmlOptions['data-height'] = $this->height;
        $this->htmlOptions['data-show-faces'] = $this->showFaces ? 'true' : 'false';
        if (!empty($this->colorscheme))
            $this->htmlOptions['data-colorscheme'] = $this->colorscheme;
        $this->htmlOptions['data-show-stream'] = $this->showStream ? 'true' : 'false';
        $this->htmlOptions['data-show-border'] = $this->showBorder ? 'true' : 'false';
        $this->htmlOptions['data-show-header'] = $this->showHeader ? 'true' : 'false';
    }

    public function run()
    {
        echo CHtml::openTag('div', $this->htmlOptions) . '</div>';
    }
}