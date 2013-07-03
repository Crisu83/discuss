<?php

require_once(Yii::getPathOfAlias('vendor.facebook.php-sdk.src') . '/facebook.php');

class FacebookApi extends CApplicationComponent
{
    /**
     * @var string your Facebook application ID.
     */
    public $appId;
    /**
     * @var string your Facebook application secret.
     */
    public $appSecret;
    /**
     * @var string the name of your site.
     */
    public $siteName;
    /**
     * @var string your Facebook application namespace.
     */
    public $namespace;
    /**
     * @var boolean whether to enable Facebook file uploads.
     */
    public $fileUpload;
    /**
     * @var string the locale (default to 'en_US').
     */
    public $locale = 'en_US';
    /**
     * @var boolean whether to enable FBML.
     */
    public $xfbml = true;

    /** @var Facebook */
    protected $_facebook;
    /** @var integer */
    protected $_fbuid;
    /** @var boolean */
    protected $_registered = false;

    /**
     * Initializes the component.
     */
    public function init()
    {
        $config = array();
        $config['appId'] = $this->appId;
        $config['secret'] = $this->appSecret;
        if ($this->fileUpload !== null)
            $config['fileUpload'] = $this->fileUpload;
        $this->_facebook = new Facebook($config);
    }

    /**
     * Registers the JavaScript Facebook API.
     */
    public function register()
    {
        if (!$this->_registered)
        {
            ?>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/<?php echo $this->locale; ?>/all.js#xfbml=<?php echo $this->xfbml ? '1' : '0'; ?>1&appId=<?php echo $this->appId; ?>";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <?php
        }
    }

    /**
     * Registers an Open Graph action.
     * @param string $action name of the action.
     * @param array $params the query parameters.
     * @return array the result.
     */
    public function registerAction($action, $params=array())
    {
        if (!isset($params['access_token']))
            $params['access_token'] = $this->_facebook->getAccessToken();
        return $this->api('me/' . $this->namespace . ':' . $action, $params);
    }

    public function getFbuid()
    {
        if ($this->_fbuid !== null)
            return $this->_fbuid;
        else
        {
            $fbuid = 0;
            try {
                $fbuid = $this->_facebook->getUser();
            } catch (FacebookApiException $e) {
                $this->log('Failed to get Facebook user ID. Error: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
            }
            return $this->_fbuid = $fbuid;
        }
    }

    public function api($query, $params = array())
    {
        $data = array();
        if (!empty($params))
            $query .= '?' . http_build_query($params);
        try {
            $data = $this->_facebook->api('/' . $query);
        }
        catch (FacebookApiException $e) {
            $this->log('Failed to call the Facebook API. Error: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
        }
        return $data;
    }

    public function getFacebook()
    {
        return $this->_facebook;
    }

    protected function log($message, $level)
    {
        Yii::log($message, $level, 'facebook');
    }
}