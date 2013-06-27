<?php

use crisu83\yii_extension\behaviors\ComponentBehavior;

\Yii::import('vendor.crisu83.yii-extension.behaviors.ComponentBehavior', true);

require_once(__DIR__ . '/../lib/sbbcodeparser/SBBCodeParser.php');

class BBCodeParser extends CApplicationComponent
{
    /**
     * @var array additional emoticons (shorthand=>url).
     */
    public $emoticons;

    /**
     * @var array the default emoticons.
     */
    protected $_defaultEmoticons = array(
        ':)'            => 'emoticons/smile.png',
        ':angel:'       => 'emoticons/angel.png',
        ':angry:'       => 'emoticons/angry.png',
        '8-)'           => 'emoticons/cool.png',
        ':\'('          => 'emoticons/cwy.png',
        ':ermm:'        => 'emoticons/ermm.png',
        ':D'            => 'emoticons/grin.png',
        '<3'            => 'emoticons/heart.png',
        ':('            => 'emoticons/sad.png',
        ':O'            => 'emoticons/shocked.png',
        ':P'            => 'emoticons/tongue.png',
        ';)'            => 'emoticons/wink.png',
        ':alien:'       => 'emoticons/alien.png',
        ':blink:'       => 'emoticons/blink.png',
        ':blush:'       => 'emoticons/blush.png',
        ':cheerful:'    => 'emoticons/cheerful.png',
        ':devil:'       => 'emoticons/devil.png',
        ':dizzy:'       => 'emoticons/dizzy.png',
        ':getlost:'     => 'emoticons/getlost.png',
        ':happy:'       => 'emoticons/happy.png',
        ':kissing:'     => 'emoticons/kissing.png',
        ':ninja:'       => 'emoticons/ninja.png',
        ':pinch:'       => 'emoticons/pinch.png',
        ':pouty:'       => 'emoticons/pouty.png',
        ':sick:'        => 'emoticons/sick.png',
        ':sideways:'    => 'emoticons/sideways.png',
        ':silly:'       => 'emoticons/silly.png',
        ':sleeping:'    => 'emoticons/sleeping.png',
        ':unsure:'      => 'emoticons/unsure.png',
        ':woot:'        => 'emoticons/w00t.png',
        ':wassat:'      => 'emoticons/wassat.png',
    );

    /** @var SBBCodeParser_Document */
    private $_parser;

    /**
     * Initializes the component.
     */
    public function init()
    {
        $this->attachBehavior('ext', new ComponentBehavior);
        $this->_parser = new SBBCodeParser_Document(true, false);
        $assetsUrl = $this->publishAssets(__DIR__ . '/../assets');
        $this->_parser->set_base_uri($assetsUrl . '/');
        $this->_parser->add_emoticons(array_merge($this->_defaultEmoticons, $this->_defaultEmoticons));
    }

    /**
     * Parses the given BBCode and returns it as HTML.
     * @param string $bbCode the BBCode.
     * @return string the HTML.
     */
    public function parse($bbCode)
    {
        return $this->_parser->parse($bbCode)
            ->detect_links()
            ->detect_emails()
            ->detect_emoticons()
            ->get_html();
    }
}